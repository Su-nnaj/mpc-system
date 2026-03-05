// ============================================================
// MPC Trading — Main JavaScript
// ============================================================

const APP_URL = document.querySelector('meta[name="app-url"]')?.content || '';

// ============================================================
// DOM Ready
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    initDropdowns();
    initCartBadge();
    initAddToCart();
    initQuantityControls();
    initAlertDismiss();
    initModalTriggers();
    initSearchSuggestions();
});

// ============================================================
// Dropdown Menus
// ============================================================
function initDropdowns() {
    document.querySelectorAll('[data-toggle="dropdown"]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const target = document.getElementById(btn.dataset.target);
            if (target) {
                document.querySelectorAll('.dropdown.show').forEach(d => {
                    if (d !== target) d.classList.remove('show');
                });
                target.classList.toggle('show');
            }
        });
    });
    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown.show').forEach(d => d.classList.remove('show'));
    });
}

// ============================================================
// Cart Badge
// ============================================================
function initCartBadge() {
    const badge = document.getElementById('cart-badge');
    if (!badge) return;
    fetch(APP_URL + '/cart/count')
        .then(r => r.json())
        .then(data => {
            badge.textContent = data.count || 0;
            badge.style.display = data.count > 0 ? 'flex' : 'none';
        })
        .catch(() => {});
}

// ============================================================
// Add to Cart
// ============================================================
function initAddToCart() {
    document.querySelectorAll('.btn-add-cart').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const productId = btn.dataset.id;
            const qty = document.getElementById('qty-' + productId)?.value || 1;
            addToCart(productId, parseInt(qty), btn);
        });
    });
}

function addToCart(productId, qty = 1, btn = null) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', qty);

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner" style="width:16px;height:16px;border-width:2px"></span>';
    }

    fetch(APP_URL + '/cart/add', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast('Added to cart!', 'success');
                const badge = document.getElementById('cart-badge');
                if (badge) {
                    badge.textContent = data.cart_count;
                    badge.style.display = 'flex';
                }
                if (btn) {
                    btn.innerHTML = '✓ Added';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline');
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = '🛒 Add to Cart';
                        btn.classList.add('btn-primary');
                        btn.classList.remove('btn-outline');
                    }, 2000);
                }
            } else {
                showToast(data.error || 'Failed to add', 'error');
                if (btn) { btn.disabled = false; btn.innerHTML = '🛒 Add to Cart'; }
            }
        })
        .catch(() => {
            showToast('Network error', 'error');
            if (btn) { btn.disabled = false; btn.innerHTML = '🛒 Add to Cart'; }
        });
}

// ============================================================
// Quantity Controls
// ============================================================
function initQuantityControls() {
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.closest('.cart-qty')?.querySelector('.qty-input');
            if (!input) return;
            let val = parseInt(input.value) || 1;
            if (btn.dataset.action === 'plus') val++;
            else val = Math.max(1, val - 1);
            input.value = val;
            input.dispatchEvent(new Event('change'));
        });
    });
}

// ============================================================
// Toast Notifications
// ============================================================
let toastContainer = null;
function showToast(message, type = 'success', duration = 3000) {
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:8px;';
        document.body.appendChild(toastContainer);
    }
    const toast = document.createElement('div');
    const colors = { success: '#00d4aa', error: '#f85149', warning: '#d29922', info: '#388bfd' };
    toast.style.cssText = `
        background: #161b22; border: 1px solid ${colors[type] || colors.info};
        color: #e6edf3; padding: 12px 18px; border-radius: 8px;
        font-size: 14px; font-family: 'Inter',sans-serif;
        box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        transform: translateX(120%); transition: transform 0.3s ease;
        display: flex; align-items: center; gap: 10px; min-width: 240px;
    `;
    const icons = { success: '✓', error: '✕', warning: '⚠', info: 'ℹ' };
    toast.innerHTML = `<span style="color:${colors[type]};font-weight:700">${icons[type]}</span> ${message}`;
    toastContainer.appendChild(toast);
    requestAnimationFrame(() => {
        setTimeout(() => toast.style.transform = 'translateX(0)', 10);
    });
    setTimeout(() => {
        toast.style.transform = 'translateX(120%)';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// ============================================================
// Alert Dismiss
// ============================================================
function initAlertDismiss() {
    document.querySelectorAll('.alert [data-dismiss]').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('.alert')?.remove());
    });
    // Auto-dismiss flash alerts
    document.querySelectorAll('.alert-auto').forEach(el => {
        setTimeout(() => {
            el.style.opacity = '0';
            el.style.transition = 'opacity 0.4s';
            setTimeout(() => el.remove(), 400);
        }, 4000);
    });
}

// ============================================================
// Modal Triggers
// ============================================================
function initModalTriggers() {
    document.querySelectorAll('[data-modal]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = document.getElementById(btn.dataset.modal);
            if (modal) modal.classList.add('show');
        });
    });
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.remove('show');
        });
    });
    document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('.modal-overlay')?.classList.remove('show'));
    });
}

// ============================================================
// Recommendation Engine
// ============================================================
function initRecommendation() {
    const form   = document.getElementById('rec-form');
    const result = document.getElementById('rec-result');
    const loading= document.getElementById('rec-loading');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(form);
        loading.style.display = 'flex';
        result.style.display  = 'none';

        try {
            const res  = await fetch(APP_URL + '/recommend/generate', { method: 'POST', body: fd });
            const data = await res.json();
            if (data.error) { showToast(data.error, 'error'); return; }
            renderRecommendation(data);
            result.style.display = 'block';
            result.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch { showToast('Failed to generate recommendation', 'error'); }
        finally { loading.style.display = 'none'; }
    });
}

function renderRecommendation(data) {
    const container = document.getElementById('components-list');
    const totalEl   = document.getElementById('rec-total');
    const remainEl  = document.getElementById('rec-remain');
    const compatEl  = document.getElementById('compat-status');
    const productIds= [];

    if (!container) return;
    container.innerHTML = '';

    const labels = { cpu:'Processor', motherboard:'Motherboard', ram:'Memory', gpu:'Graphics Card', storage:'Storage', psu:'Power Supply', case:'Case', cooling:'Cooling' };
    const icons  = { cpu:'🔧', motherboard:'🖥', ram:'💾', gpu:'🎮', storage:'💿', psu:'⚡', case:'📦', cooling:'❄️' };

    for (const [type, product] of Object.entries(data.components)) {
        if (!product) continue;
        const price = parseFloat(product.sale_price || product.price);
        productIds.push(product.id);
        container.innerHTML += `
            <div class="component-row fade-in">
                <div>
                    <div class="comp-type">${icons[type] || '🔩'} ${labels[type] || type}</div>
                    <div class="comp-name">${escHtml(product.name)}</div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:2px">${product.condition_type === 'used' ? '⚠ Used' : '✓ Brand New'}</div>
                </div>
                <div style="font-size:13px;color:var(--text-secondary)">${escHtml(product.brand || '')}</div>
                <div class="comp-price">₱${price.toLocaleString('en-PH', {minimumFractionDigits:2})}</div>
            </div>`;
    }

    // Store product IDs for add to cart
    document.getElementById('rec-product-ids').value = JSON.stringify(productIds);

    // Totals
    if (totalEl) totalEl.textContent = '₱' + data.total_price.toLocaleString('en-PH', {minimumFractionDigits:2});
    if (remainEl) {
        const rem = data.remaining;
        remainEl.textContent = (rem >= 0 ? '₱' : '-₱') + Math.abs(rem).toLocaleString('en-PH', {minimumFractionDigits:2});
        remainEl.style.color = rem >= 0 ? 'var(--accent)' : 'var(--red)';
    }

    // Compatibility
    if (compatEl) {
        const { status, issues, warnings } = data.compatibility;
        const map = { compatible: ['compat-ok','✓ All components are compatible!'], warning: ['compat-warn','⚠ Compatibility warnings detected'], incompatible: ['compat-err','✕ Compatibility issues found'] };
        const [cls, msg] = map[status] || map.warning;
        let html = `<div class="compatibility-bar ${cls}"><span style="font-size:18px">${msg.charAt(0)}</span><div><strong>${msg.slice(2)}</strong>`;
        [...(issues||[]), ...(warnings||[])].forEach(m => { html += `<div style="font-size:12px;margin-top:4px;opacity:0.8">${escHtml(m)}</div>`; });
        html += '</div></div>';
        compatEl.innerHTML = html;
    }

    // Score bars
    if (data.score) {
        ['performance','value','compatibility'].forEach(k => {
            const bar = document.getElementById('score-' + k);
            const lbl = document.getElementById('score-' + k + '-val');
            const val = data.score[k] || 0;
            if (bar) { bar.style.width = '0%'; setTimeout(() => bar.style.width = val + '%', 100); }
            if (lbl) lbl.textContent = val + '/100';
        });
    }
}

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ============================================================
// Product Detail - Image Gallery
// ============================================================
function initGallery() {
    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
        thumb.addEventListener('click', () => {
            const main = document.getElementById('main-img');
            if (main) main.src = thumb.src;
            document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        });
    });
}

// ============================================================
// Admin: Product Form
// ============================================================
function openProductModal(data = null) {
    const modal = document.getElementById('product-modal');
    if (!modal) return;
    if (data) {
        modal.querySelector('[name="id"]').value = data.id;
        modal.querySelector('[name="name"]').value = data.name;
        modal.querySelector('[name="price"]').value = data.price;
        modal.querySelector('[name="sale_price"]').value = data.sale_price || '';
        modal.querySelector('[name="stock_quantity"]').value = data.stock_quantity;
        modal.querySelector('[name="condition_type"]').value = data.condition_type;
        modal.querySelector('[name="is_featured"]').checked = data.is_featured == 1;
        modal.querySelector('[name="description"]').value = data.description || '';
        modal.querySelector('.modal-title').textContent = 'Edit Product';
    } else {
        modal.querySelector('form')?.reset();
        modal.querySelector('[name="id"]').value = '';
        modal.querySelector('.modal-title').textContent = 'Add Product';
    }
    modal.classList.add('show');
}

// ============================================================
// Search Suggestions
// ============================================================
function initSearchSuggestions() {
    const input = document.getElementById('search-input');
    const box   = document.getElementById('search-suggestions');
    if (!input || !box) return;
    let timer;
    input.addEventListener('input', () => {
        clearTimeout(timer);
        const q = input.value.trim();
        if (q.length < 2) { box.style.display = 'none'; return; }
        timer = setTimeout(() => {
            fetch(`${APP_URL}/products?q=${encodeURIComponent(q)}&ajax=1`)
                .then(r => r.json())
                .then(products => {
                    if (!products.length) { box.style.display = 'none'; return; }
                    box.innerHTML = products.slice(0,5).map(p =>
                        `<a href="${APP_URL}/products/${p.slug}" class="suggestion-item">
                            <span>${escHtml(p.name)}</span>
                            <span class="font-mono" style="color:var(--accent);font-size:13px">₱${parseFloat(p.sale_price||p.price).toLocaleString()}</span>
                         </a>`
                    ).join('');
                    box.style.display = 'block';
                })
                .catch(() => {});
        }, 300);
    });
    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !box.contains(e.target)) box.style.display = 'none';
    });
}

// ============================================================
// Cart page quantity form auto-submit
// ============================================================
document.querySelectorAll('.qty-auto-form input')?.forEach(input => {
    input.addEventListener('change', () => input.closest('form')?.submit());
});

// ============================================================
// Expose globals
// ============================================================
window.MPC = { addToCart, showToast, openProductModal, initRecommendation, initGallery };

// Init recommendation on page load if form exists
if (document.getElementById('rec-form')) {
    initRecommendation();
}
// Init gallery on detail page
if (document.querySelector('.gallery-thumb')) {
    initGallery();
}
