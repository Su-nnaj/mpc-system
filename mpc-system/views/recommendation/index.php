<div class="page-content"><div class="container">
<div style="max-width:900px;margin:0 auto">
    <div class="section-heading text-center">
        <h2 style="font-family:var(--font-display);font-size:36px;font-weight:700">Intelligent PC Build Recommender</h2>
        <p style="color:var(--text-secondary);margin-top:10px">Enter your budget and usage type. Our algorithm recommends optimal components with automatic compatibility checking.</p>
        <div class="section-line" style="margin:12px auto 0"></div>
    </div>
    <div class="rec-builder mt-4">
        <form id="rec-form">
            <div class="form-group">
                <label class="form-label" style="font-size:15px">Usage Type</label>
                <div class="usage-grid">
                    <?php foreach([["gaming","Gaming"],["office","Office"],["workstation","Workstation"],["streaming","Streaming"],["general","General"]] as [$v,$lbl]): ?>
                    <div>
                        <input type="radio" name="usage_type" id="usage_<?=$v?>" value="<?=$v?>" class="usage-option" <?=$v==="gaming"?"checked":"" ?>>
                        <label for="usage_<?=$v?>" class="usage-label">
                            <span class="usage-name"><?=$lbl?></span>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Your Budget (PHP)</label>
                    <input type="number" name="budget" class="form-control" placeholder="e.g. 25000" min="5000" style="font-size:18px;font-family:var(--font-mono)" required>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:6px">Minimum: PHP 5,000</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Condition</label>
                    <div style="display:flex;flex-direction:column;gap:12px;margin-top:8px">
                        <label class="form-check"><input type="radio" name="include_used" value="0" checked style="accent-color:var(--accent)"> Brand New Only</label>
                        <label class="form-check"><input type="radio" name="include_used" value="1" style="accent-color:var(--accent)"> Include Used (better value)</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg">Generate Recommendation</button>
        </form>

        <div id="rec-loading" style="display:none;flex-direction:column;align-items:center;gap:16px;padding:48px">
            <div class="spinner" style="width:48px;height:48px;border-width:4px"></div>
            <p style="color:var(--text-secondary)">Analyzing budget and matching optimal components...</p>
        </div>

        <div id="rec-result" style="display:none">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;padding-top:28px;border-top:1px solid var(--border)">
                <h3 style="font-family:var(--font-display);font-size:22px;font-weight:700">Recommended Build</h3>
                <div><span style="font-size:14px;color:var(--text-secondary)">Build Total: </span><span class="font-mono" style="color:var(--accent);font-weight:700;font-size:18px" id="rec-total">PHP 0</span></div>
            </div>
            <div id="compat-status"></div>
            <div id="components-list" style="margin:16px 0"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:20px">
                <div class="card card-body">
                    <div class="form-label">Budget Summary</div>
                    <div class="price-current" style="font-size:24px" id="rec-total-2">PHP 0</div>
                    <div style="font-size:13px;color:var(--text-secondary);margin-top:4px">Remaining: <span id="rec-remain" class="font-mono" style="font-weight:700">PHP 0</span></div>
                </div>
                <div class="card card-body">
                    <div class="form-label">Build Scores</div>
                    <?php foreach(["performance"=>"Performance","value"=>"Value","compatibility"=>"Compatibility"] as $k=>$lbl): ?>
                    <div style="display:flex;align-items:center;gap:10px;font-size:13px;margin-top:8px">
                        <span style="width:90px;color:var(--text-secondary)"><?=$lbl?></span>
                        <div class="score-bar-wrap" style="flex:1"><div class="score-bar" id="score-<?=$k?>" style="width:0%"></div></div>
                        <span id="score-<?=$k?>-val" class="font-mono" style="font-size:12px;width:50px;text-align:right">0/100</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <input type="hidden" id="rec-product-ids" value="[]">
            <div style="display:flex;gap:12px;margin-top:20px">
                <?php if(isset($_SESSION["user_id"])): ?>
                    <button onclick="addBuildToCart()" class="btn btn-primary btn-lg" style="flex:1">Add Entire Build to Cart</button>
                <?php else: ?>
                    <a href="<?=APP_URL?>/auth/login" class="btn btn-primary btn-lg" style="flex:1">Login to Add to Cart</a>
                <?php endif; ?>
                <button onclick="document.getElementById('rec-result').style.display='none'" class="btn btn-outline btn-lg">Try Again</button>
            </div>
            <div class="installment-notice" style="margin-top:12px">
                <span class="notice-icon">i</span>
                <span>Only Cash on Delivery available online. For installment, visit our store in Dasmari&ntilde;as.</span>
            </div>
        </div>
    </div>
    <div style="margin-top:48px">
        <h3 style="font-family:var(--font-display);font-size:20px;font-weight:700;margin-bottom:20px;text-align:center">How It Works</h3>
        <div class="grid grid-3">
            <?php foreach([["Budget Analysis","We allocate your budget across components based on your intended use case."],["Smart Matching","Best-value parts are picked from live inventory within each budget tier."],["Auto Compatibility","Sockets, RAM types, PSU power — all verified automatically."]] as [$t,$d]): ?>
                <div class="card card-body" style="text-align:center"><h4 style="font-family:var(--font-display);font-size:16px;margin-bottom:8px"><?=$t?></h4><p style="font-size:13px;color:var(--text-secondary);line-height:1.6"><?=$d?></p></div>
            <?php endforeach; ?>
        </div>
    </div>
</div></div></div>
<script>
function addBuildToCart(){
    const ids=JSON.parse(document.getElementById("rec-product-ids").value||"[]");
    if(!ids.length)return MPC.showToast("No components to add","error");
    const fd=new FormData();ids.forEach(id=>fd.append("product_ids[]",id));
    fetch("<?=APP_URL?>/recommend/add-to-cart",{method:"POST",body:fd}).then(r=>r.json()).then(d=>{
        if(d.success){MPC.showToast("Build added to cart!","success");setTimeout(()=>window.location.href=d.redirect,1200);}
        else MPC.showToast(d.error||"Failed","error");
    });
}
document.addEventListener("DOMContentLoaded",()=>{
    if(window.MPC&&MPC.initRecommendation)MPC.initRecommendation();
    const obs=new MutationObserver(()=>{
        const t=document.getElementById("rec-total"),b=document.getElementById("rec-total-2");
        if(t&&b)b.textContent=t.textContent;
    });
    const tot=document.getElementById("rec-total");
    if(tot)obs.observe(tot,{childList:true,characterData:true,subtree:true});
});
</script>
