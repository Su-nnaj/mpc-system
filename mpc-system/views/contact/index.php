<div class="page-content">
<div class="container">

    <div class="breadcrumb">
        <a href="<?=APP_URL?>/">Home</a><span class="sep">/</span><span>Contact Us</span>
    </div>

    <div class="section-heading" style="margin-bottom:40px">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you. Reach out for inquiries, orders, or just to ask a question.</p>
        <div class="section-line"></div>
    </div>

    <!-- Flash messages from controller -->
    <?php if(!empty($flash)): ?>
        <div class="alert alert-<?=$flash['type']==='success'?'success':'error'?> alert-auto" style="margin-bottom:24px">
            <?=htmlspecialchars($flash['message'])?>
        </div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start">

        <!-- CONTACT FORM -->
        <div class="card card-body">
            <h3 style="font-family:var(--font-display);font-size:20px;font-weight:700;margin-bottom:20px">💬 Send Us a Message</h3>
            <form action="<?=APP_URL?>/contact/send" method="POST">
                <div class="form-group">
                    <label class="form-label">Your Name</label>
                    <input type="text" name="name" class="form-control <?=!empty($errors['name'])?'is-invalid':''?>"
                           value="<?=htmlspecialchars($old['name']??$_SESSION['full_name']??'')?>"
                           placeholder="Juan Dela Cruz" required>
                    <?php if(!empty($errors['name'])): ?><div class="invalid-feedback"><?=htmlspecialchars($errors['name'])?></div><?php endif; ?>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control <?=!empty($errors['email'])?'is-invalid':''?>"
                               value="<?=htmlspecialchars($old['email']??$_SESSION['email']??'')?>"
                               placeholder="you@example.com" required>
                        <?php if(!empty($errors['email'])): ?><div class="invalid-feedback"><?=htmlspecialchars($errors['email'])?></div><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone (Optional)</label>
                        <input type="tel" name="phone" class="form-control"
                               value="<?=htmlspecialchars($old['phone']??'')?>"
                               placeholder="09XX-XXX-XXXX">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Subject</label>
                    <select name="subject" class="form-control">
                        <option value="general" <?=($old['subject']??'')==='general'?'selected':''?>>General Inquiry</option>
                        <option value="product" <?=($old['subject']??'')==='product'?'selected':''?>>Product Availability</option>
                        <option value="order" <?=($old['subject']??'')==='order'?'selected':''?>>Order / Delivery</option>
                        <option value="installment" <?=($old['subject']??'')==='installment'?'selected':''?>>Installment Inquiry</option>
                        <option value="technical" <?=($old['subject']??'')==='technical'?'selected':''?>>Technical Support</option>
                        <option value="bulk" <?=($old['subject']??'')==='bulk'?'selected':''?>>Bulk / Reseller Order</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-control <?=!empty($errors['message'])?'is-invalid':''?>"
                              rows="5" placeholder="Tell us what you need — product availability, pricing, delivery time, etc."
                              required><?=htmlspecialchars($old['message']??'')?></textarea>
                    <?php if(!empty($errors['message'])): ?><div class="invalid-feedback"><?=htmlspecialchars($errors['message'])?></div><?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Send Message →</button>
            </form>
            <div class="installment-notice" style="margin-top:16px">
                <span class="notice-icon">ℹ</span>
                <span>We reply within 24 hours on weekdays. For urgent orders, call or message us on Facebook directly.</span>
            </div>
        </div>

        <!-- CONTACT INFO -->
        <div>
            <!-- Store Details -->
            <div class="card card-body" style="margin-bottom:20px">
                <h3 style="font-family:var(--font-display);font-size:20px;font-weight:700;margin-bottom:20px">📍 Find Us</h3>
                <?php
                $contactDetails = [
                    ['📍', 'Address', 'Dasmariñas, Cavite, Philippines'],
                    ['📞', 'Phone / Viber', '0917-123-4567'],
                    ['💬', 'Facebook', 'facebook.com/MPCTrading'],
                    ['📧', 'Email', 'mpctrading@gmail.com'],
                ];
                foreach ($contactDetails as [$icon, $label, $value]): ?>
                <div style="display:flex;gap:16px;align-items:flex-start;padding:14px 0;border-bottom:1px solid var(--border)">
                    <span style="font-size:22px;flex-shrink:0;margin-top:2px"><?=$icon?></span>
                    <div>
                        <div style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:2px"><?=$label?></div>
                        <div style="font-weight:600;font-size:14px"><?=$value?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Hours -->
            <div class="card card-body" style="margin-bottom:20px">
                <h3 style="font-family:var(--font-display);font-size:18px;font-weight:700;margin-bottom:16px">🕐 Store Hours</h3>
                <?php
                $hours = [
                    ['Monday – Friday', '8:00 AM – 7:00 PM', true],
                    ['Saturday', '8:00 AM – 7:00 PM', true],
                    ['Sunday', '9:00 AM – 5:00 PM', true],
                    ['Holidays', 'Closed / Limited Hours', false],
                ];
                foreach ($hours as [$day, $time, $open]): ?>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--border);font-size:14px">
                    <span style="color:var(--text-secondary)"><?=$day?></span>
                    <span style="font-weight:600;color:<?=$open?'var(--accent)':'var(--red)'?>"><?=$time?></span>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Installment Notice -->
            <div class="installment-notice">
                <span class="notice-icon">⚠</span>
                <div>
                    <strong style="display:block;margin-bottom:4px">Installment Arrangements</strong>
                    <span>Installment and financing are available <strong>in-store only</strong>. Visit our physical store in Dasmariñas to discuss options with our team. Online orders are Cash on Delivery only.</span>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card card-body" style="margin-top:20px">
                <h4 style="font-family:var(--font-display);font-size:16px;font-weight:700;margin-bottom:14px">Quick Actions</h4>
                <a href="<?=APP_URL?>/recommend" class="btn btn-primary btn-block" style="margin-bottom:10px">🤖 Get a PC Recommendation</a>
                <a href="<?=APP_URL?>/products" class="btn btn-outline btn-block" style="margin-bottom:10px">📦 Browse Products</a>
                <a href="<?=APP_URL?>/orders" class="btn btn-outline btn-block">📋 Track My Order</a>
            </div>
        </div>

    </div>

    <!-- FAQ SECTION -->
    <div style="margin-top:60px">
        <div class="section-heading text-center" style="margin-bottom:36px">
            <h2>Frequently Asked Questions</h2>
            <p>Quick answers to common questions about ordering and our services.</p>
            <div class="section-line" style="margin:12px auto 0"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <?php
            $faqs = [
                ['Do you deliver outside Dasmariñas?', 'Currently we primarily serve Dasmariñas and nearby areas of Cavite. Contact us to check if we can arrange delivery to your location.'],
                ['Can I pay in installments?', 'Yes — but installment arrangements are in-store only. You need to visit our physical store to arrange financing. Online orders are Cash on Delivery only.'],
                ['Are used components tested?', 'Yes! Every used component is tested and graded by our team. Condition is clearly listed on each product page.'],
                ['What is your return policy?', 'We offer a 7-day return or replacement for defective items. Items must be in original condition. Contact us within 7 days of delivery.'],
                ['How long does delivery take?', 'Typically 1–3 days within Dasmariñas. We will contact you to confirm delivery schedule after your order is placed.'],
                ['Can I visit the store to pick up my order?', 'Absolutely! You can place an order online and pick it up in-store, or simply walk in and shop directly.'],
                ['Do you do PC assembly?', 'Yes, we offer PC assembly services in-store. Contact us for pricing depending on build complexity.'],
                ['Do you accept GCash or card payments?', 'In-store we accept Cash and GCash. Online orders are Cash on Delivery only.'],
            ];
            foreach ($faqs as [$q, $a]): ?>
            <div class="card card-body" style="transition:var(--transition)" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                <div style="font-weight:700;font-size:14px;margin-bottom:8px;color:var(--text-primary)"><?=$q?></div>
                <div style="font-size:13px;color:var(--text-secondary);line-height:1.7"><?=$a?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>
</div>
