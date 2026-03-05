<?php
class RecommendationModel extends Model {
    protected string $table = 'recommendation_logs';
    private ProductModel $productModel;

    // Budget allocation percentages per usage type
    private array $budgetAllocation = [
        'gaming' => [
            'cpu' => 0.20, 'motherboard' => 0.12, 'ram' => 0.10,
            'gpu' => 0.35, 'storage' => 0.08, 'psu' => 0.07, 'case' => 0.05, 'cooling' => 0.03
        ],
        'office' => [
            'cpu' => 0.28, 'motherboard' => 0.18, 'ram' => 0.15,
            'gpu' => 0.00, 'storage' => 0.20, 'psu' => 0.10, 'case' => 0.06, 'cooling' => 0.03
        ],
        'workstation' => [
            'cpu' => 0.25, 'motherboard' => 0.15, 'ram' => 0.20,
            'gpu' => 0.25, 'storage' => 0.08, 'psu' => 0.04, 'case' => 0.02, 'cooling' => 0.01
        ],
        'streaming' => [
            'cpu' => 0.22, 'motherboard' => 0.13, 'ram' => 0.12,
            'gpu' => 0.30, 'storage' => 0.10, 'psu' => 0.07, 'case' => 0.04, 'cooling' => 0.02
        ],
        'general' => [
            'cpu' => 0.25, 'motherboard' => 0.15, 'ram' => 0.12,
            'gpu' => 0.20, 'storage' => 0.12, 'psu' => 0.09, 'case' => 0.05, 'cooling' => 0.02
        ],
    ];

    public function __construct() {
        parent::__construct();
        $this->productModel = new ProductModel();
    }

    public function generateRecommendation(float $budget, string $usageType, bool $includeUsed = false): array {
        $allocation = $this->budgetAllocation[$usageType] ?? $this->budgetAllocation['general'];
        $recommended = [];
        $usedBudget = 0;
        $components = ['cpu', 'motherboard', 'ram', 'storage', 'psu', 'case', 'cooling'];
        if ($usageType !== 'office') $components[] = 'gpu';

        // First pass: pick best within budget per component
        foreach ($components as $component) {
            $alloc = $allocation[$component] ?? 0;
            if ($alloc <= 0) continue;
            $componentBudget = $budget * $alloc * 1.15; // 15% flexibility
            $products = $this->productModel->getByCategoryType($component, $componentBudget);
            if (empty($products)) continue;
            // Score: maximize price/value ratio within budget
            $best = null;
            $bestScore = -1;
            foreach ($products as $p) {
                if (!$includeUsed && $p['condition_type'] !== 'brand_new') continue;
                $price = (float)($p['sale_price'] ?? $p['price']);
                $targetBudget = $budget * $alloc;
                // Score based on how close to target budget (higher price = better within budget)
                $score = $price / max($targetBudget, 1);
                if ($price <= $componentBudget && $score > $bestScore) {
                    $bestScore = $score;
                    $best = $p;
                }
            }
            // Fallback to cheapest if none found
            if (!$best && !empty($products)) {
                $best = $products[0];
            }
            if ($best) {
                $recommended[$component] = $best;
                $usedBudget += (float)($best['sale_price'] ?? $best['price']);
            }
        }

        // Check compatibility and adjust if needed
        $compatibility = $this->checkBuildCompatibility($recommended);

        return [
            'components'     => $recommended,
            'total_price'    => $usedBudget,
            'budget'         => $budget,
            'remaining'      => $budget - $usedBudget,
            'usage_type'     => $usageType,
            'compatibility'  => $compatibility,
            'score'          => $this->calculateBuildScore($recommended, $usageType),
        ];
    }

    public function checkBuildCompatibility(array $build): array {
        $issues = [];
        $warnings = [];
        $status = 'compatible';

        // CPU + Motherboard socket check
        if (isset($build['cpu']) && isset($build['motherboard'])) {
            $cpuSpecs = json_decode($build['cpu']['specifications'] ?? '{}', true);
            $mbSpecs  = json_decode($build['motherboard']['specifications'] ?? '{}', true);
            $cpuSocket = $cpuSpecs['socket'] ?? $build['cpu']['socket_type'] ?? '';
            $mbSocket  = $mbSpecs['socket'] ?? $build['motherboard']['socket_type'] ?? '';
            if ($cpuSocket && $mbSocket && $cpuSocket !== $mbSocket) {
                $issues[] = "CPU socket ($cpuSocket) is incompatible with motherboard socket ($mbSocket).";
                $status = 'incompatible';
            }
        }

        // Power check: PSU wattage vs total TDP
        if (isset($build['psu'])) {
            $psuSpecs = json_decode($build['psu']['specifications'] ?? '{}', true);
            $psuWatts = (int)($psuSpecs['wattage'] ?? 0);
            $totalTdp = 0;
            foreach ($build as $comp) {
                $totalTdp += (int)($comp['tdp_watts'] ?? 0);
                $totalTdp += (int)($comp['power_required'] ?? 0);
            }
            if ($psuWatts > 0 && $totalTdp > 0 && $totalTdp > $psuWatts * 0.8) {
                $warnings[] = "PSU ({$psuWatts}W) may be insufficient for estimated system power ({$totalTdp}W). Consider a higher wattage PSU.";
                if ($status === 'compatible') $status = 'warning';
            }
        }

        // RAM type check with motherboard
        if (isset($build['ram']) && isset($build['motherboard'])) {
            $mbSpecs  = json_decode($build['motherboard']['specifications'] ?? '{}', true);
            $ramSpecs = json_decode($build['ram']['specifications'] ?? '{}', true);
            $mbDdr    = $mbSpecs['ddr_support'] ?? '';
            $ramType  = $ramSpecs['type'] ?? '';
            if ($mbDdr && $ramType && $mbDdr !== $ramType) {
                $issues[] = "RAM type ($ramType) is incompatible with motherboard ($mbDdr).";
                if ($status !== 'incompatible') $status = 'incompatible';
            }
        }

        return ['status' => $status, 'issues' => $issues, 'warnings' => $warnings];
    }

    private function calculateBuildScore(array $build, string $usageType): array {
        $scores = ['performance' => 0, 'value' => 0, 'compatibility' => 100];
        $weights = [
            'gaming'     => ['cpu' => 25, 'gpu' => 45, 'ram' => 20, 'storage' => 10],
            'office'     => ['cpu' => 40, 'ram' => 30, 'storage' => 20, 'gpu' => 10],
            'workstation'=> ['cpu' => 30, 'gpu' => 35, 'ram' => 25, 'storage' => 10],
            'general'    => ['cpu' => 30, 'gpu' => 30, 'ram' => 25, 'storage' => 15],
        ][$usageType] ?? ['cpu' => 30, 'gpu' => 30, 'ram' => 25, 'storage' => 15];

        // Simple scoring based on relative price within category
        foreach ($build as $type => $product) {
            $price = (float)($product['sale_price'] ?? $product['price']);
            $weight = $weights[$type] ?? 10;
            // Normalize: 10k PHP = 100 score
            $scores['performance'] += min(100, ($price / 10000) * 100) * ($weight / 100);
        }
        $scores['performance'] = min(100, round($scores['performance']));
        $scores['value'] = min(100, max(0, 100 - abs($scores['performance'] - 70)));
        return $scores;
    }

    public function logRecommendation(array $data): void {
        $this->create([
            'user_id'                => $data['user_id'] ?? null,
            'session_id'             => $data['session_id'] ?? '',
            'budget'                 => $data['budget'],
            'usage_type'             => $data['usage_type'],
            'recommended_products'   => json_encode(array_keys($data['components'] ?? [])),
        ]);
    }
}
