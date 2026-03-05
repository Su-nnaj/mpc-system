<div class="page-content">

<!-- HERO -->
<section style="background:linear-gradient(160deg,#0d1117 0%,#0d1f17 50%,#0a0c0f 100%);border-bottom:1px solid var(--border);padding:80px 0;position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 30% 50%,rgba(0,212,170,0.07) 0%,transparent 60%);pointer-events:none"></div>
    <div class="container" style="position:relative">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center">
            <div>
                <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(0,212,170,0.1);border:1px solid rgba(0,212,170,0.3);color:var(--accent);padding:4px 14px;border-radius:99px;font-size:12px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;margin-bottom:20px">
                    🖥 About MPC Trading
                </div>
                <h1 style="font-family:var(--font-display);font-size:clamp(34px,5vw,54px);font-weight:700;line-height:1.1;margin-bottom:20px">
                    Dasmariñas' Most Trusted
                    <span style="color:var(--accent);display:block">PC Shop Since Day One.</span>
                </h1>
                <p style="color:var(--text-secondary);font-size:17px;line-height:1.7;margin-bottom:28px">
                    MPC Trading started as a small computer repair shop in the heart of Dasmariñas, Cavite. 
                    Today, we've grown into a full PC component retailer serving hundreds of happy builders — from first-timers to seasoned enthusiasts.
                </p>
                <div style="display:flex;gap:16px;flex-wrap:wrap">
                    <a href="<?=APP_URL?>/products" class="btn btn-primary btn-lg">Browse Our Products</a>
                    <a href="<?=APP_URL?>/contact" class="btn btn-outline btn-lg">Get in Touch</a>
                </div>
            </div>
            <!-- Stats Panel -->
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <?php
                $aboutStats = [
                    ['500+', 'Components In Stock', '📦'],
                    ['1,200+', 'Happy Customers', '😊'],
                    ['5+ Yrs', 'In Business', '🏆'],
                    ['COD', 'Cash on Delivery', '🚚'],
                ];
                foreach ($aboutStats as [$val, $label, $icon]): ?>
                <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;text-align:center;transition:var(--transition)" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                    <div style="font-size:28px;margin-bottom:8px"><?=$icon?></div>
                    <div style="font-family:var(--font-display);font-size:28px;font-weight:700;color:var(--accent)"><?=$val?></div>
                    <div style="font-size:13px;color:var(--text-secondary);margin-top:4px"><?=$label?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- OUR STORY -->
<section style="padding:72px 0;border-bottom:1px solid var(--border)">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center">
            <div>
                <div class="section-heading">
                    <h2>Our Story</h2>
                    <div class="section-line"></div>
                </div>
                <p style="color:var(--text-secondary);font-size:15px;line-height:1.8;margin-bottom:16px">
                    MPC Trading was founded with one simple goal: to make quality PC components accessible and affordable to every Filipino builder. 
                    We understand the Filipino budget — that's why we carry both brand-new and quality second-hand parts, so every peso of your build counts.
                </p>
                <p style="color:var(--text-secondary);font-size:15px;line-height:1.8;margin-bottom:16px">
                    We're based in Dasmariñas, Cavite — one of the fastest-growing cities in CALABARZON. Our physical store allows customers to inspect components in person, while our website makes shopping possible from anywhere with Cash on Delivery.
                </p>
                <p style="color:var(--text-secondary);font-size:15px;line-height:1.8">
                    Our team is made up of passionate PC enthusiasts who use the same parts they sell. When you ask us a question, you get a real answer — not a script.
                </p>
            </div>
            <!-- Timeline -->
            <div>
                <?php
                $timeline = [
                    ['Founded', 'Started as a small computer repair and parts shop in Dasmariñas, Cavite.'],
                    ['Expanded', 'Grew product catalog to include full PC component lines: CPUs, GPUs, RAM, and more.'],
                    ['Online Shop', 'Launched our online store with Cash on Delivery, serving all of Dasmariñas.'],
                    ['AI Recommender', 'Introduced the intelligent PC Build Recommender with automatic compatibility checking.'],
                ];
                foreach ($timeline as $i => [$title, $desc]): ?>
                <div style="display:flex;gap:20px;margin-bottom:28px">
                    <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0">
                        <div style="width:40px;height:40px;background:linear-gradient(135deg,var(--accent),#00a88a);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-family:var(--font-mono);font-size:14px;color:var(--bg-base)"><?=$i+1?></div>
                        <?php if($i < count($timeline)-1): ?><div style="width:2px;height:100%;background:var(--border);margin-top:8px;min-height:20px"></div><?php endif; ?>
                    </div>
                    <div style="padding-bottom:20px">
                        <div style="font-family:var(--font-display);font-size:16px;font-weight:700;margin-bottom:6px"><?=$title?></div>
                        <div style="color:var(--text-secondary);font-size:14px;line-height:1.7"><?=$desc?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- WHAT WE OFFER -->
<section style="padding:72px 0;background:var(--bg-surface);border-bottom:1px solid var(--border)">
    <div class="container">
        <div class="section-heading text-center" style="margin-bottom:40px">
            <h2>What We Offer</h2>
            <p>Everything you need to build your perfect PC — in one place.</p>
            <div class="section-line" style="margin:12px auto 0"></div>
        </div>
        <div class="grid grid-3" style="gap:20px">
            <?php
            $offerings = [
                ['🔧', 'Full Component Catalog', 'CPUs, GPUs, RAM, Motherboards, Storage, PSUs, Cases, Cooling — brand new and quality used.'],
                ['🤖', 'AI PC Build Recommender', 'Enter your budget and usage type. Our algorithm picks the best compatible components automatically.'],
                ['✅', 'Compatibility Checking', 'Socket, RAM type, and power requirements are verified automatically — no guesswork needed.'],
                ['🚚', 'Cash on Delivery', 'Order online and pay when your parts arrive. Available throughout Dasmariñas.'],
                ['🏷', 'Best Value Parts', 'We stock quality used and refurbished components so your peso stretches further.'],
                ['🏪', 'Physical Store', 'Visit us in Dasmariñas. See parts before you buy, and get real advice from our team.'],
            ];
            foreach ($offerings as [$icon, $title, $desc]): ?>
            <div class="card" style="padding:28px 24px;transition:var(--transition)" onmouseover="this.style.borderColor='var(--accent)';this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='var(--border)';this.style.transform='translateY(0)'">
                <div style="font-size:36px;margin-bottom:14px"><?=$icon?></div>
                <h3 style="font-family:var(--font-display);font-size:18px;font-weight:700;margin-bottom:10px"><?=$title?></h3>
                <p style="font-size:14px;color:var(--text-secondary);line-height:1.7"><?=$desc?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- MEET THE TEAM -->
<section style="padding:72px 0;border-bottom:1px solid var(--border)">
    <div class="container">
        <div class="section-heading text-center" style="margin-bottom:40px">
            <h2>Meet the Team</h2>
            <p>Real people who love PCs, helping real people build them.</p>
            <div class="section-line" style="margin:12px auto 0"></div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px">
            <?php
            $team = [
                ['🧑‍💻', 'Marco P.', 'Owner & Founder', 'PC builder since 2010. Knows every socket type and GPU generation by heart.'],
                ['👩‍🔧', 'Carla R.', 'Hardware Specialist', 'Expert in compatibility and component sourcing. She\'s built over 300 rigs.'],
                ['👨‍💼', 'Jomar S.', 'Sales & Support', 'Your go-to for delivery questions, COD arrangements, and product inquiries.'],
                ['👩‍💻', 'Lea T.', 'Inventory Manager', 'Keeps the shelves stocked and prices competitive. She hunts great deals daily.'],
            ];
            foreach ($team as [$avatar, $name, $role, $bio]): ?>
            <div class="card" style="padding:28px 20px;text-align:center;transition:var(--transition)" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--accent),#00a88a);display:flex;align-items:center;justify-content:center;font-size:34px;margin:0 auto 16px"><?=$avatar?></div>
                <div style="font-family:var(--font-display);font-size:17px;font-weight:700"><?=$name?></div>
                <div style="color:var(--accent);font-size:12px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;margin-bottom:10px"><?=$role?></div>
                <p style="font-size:13px;color:var(--text-secondary);line-height:1.6"><?=$bio?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- STORE INFO + CTA -->
<section style="padding:72px 0">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start">
            <!-- Store Info -->
            <div class="card card-body">
                <h3 style="font-family:var(--font-display);font-size:22px;font-weight:700;margin-bottom:20px">📍 Visit Our Store</h3>
                <?php
                $storeInfo = [
                    ['📍 Address', 'Dasmariñas, Cavite, Philippines'],
                    ['📞 Phone', '0917-123-4567'],
                    ['💬 Facebook', 'facebook.com/MPCTrading'],
                    ['📧 Email', 'mpctrading@gmail.com'],
                    ['🕐 Mon–Sat', '8:00 AM – 7:00 PM'],
                    ['🕐 Sunday', '9:00 AM – 5:00 PM'],
                ];
                foreach ($storeInfo as [$label, $value]): ?>
                <div style="display:flex;gap:16px;padding:12px 0;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted);font-size:13px;width:100px;flex-shrink:0"><?=$label?></span>
                    <span style="font-weight:600;font-size:14px"><?=$value?></span>
                </div>
                <?php endforeach; ?>
                <div class="installment-notice" style="margin-top:20px">
                    <span class="notice-icon">⚠</span>
                    <span>Installment and financing arrangements are <strong>in-store only</strong>. Visit us in Dasmariñas to discuss payment plans.</span>
                </div>
            </div>
            <!-- CTA -->
            <div>
                <div class="cod-banner" style="flex-direction:column;align-items:flex-start;gap:16px;padding:28px">
                    <div style="font-size:40px">🤖</div>
                    <div>
                        <strong style="font-family:var(--font-display);font-size:22px;display:block;margin-bottom:8px">Try Our PC Build Recommender</strong>
                        <p style="color:var(--text-secondary);font-size:14px;line-height:1.7;margin-bottom:20px">Not sure what to buy? Enter your budget and usage type — our AI picks the best components and checks compatibility automatically.</p>
                        <a href="<?=APP_URL?>/recommend" class="btn btn-primary btn-lg">🚀 Start Building</a>
                    </div>
                </div>
                <div class="card card-body" style="margin-top:20px">
                    <h4 style="font-family:var(--font-display);font-size:16px;font-weight:700;margin-bottom:12px">Why customers choose MPC Trading</h4>
                    <?php foreach(['No hidden charges — price is price','Knowledgeable staff, real PC enthusiasts','Quality used parts with honest condition ratings','Fast Cash on Delivery in Dasmariñas','Both online and in-store shopping available'] as $reason): ?>
                    <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);font-size:14px;color:var(--text-secondary)">
                        <span style="color:var(--accent);font-weight:700;flex-shrink:0">✓</span><?=$reason?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

</div>
