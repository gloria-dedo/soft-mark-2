// ======================================
// SoftMark ERP — Main JavaScript
// ======================================

document.addEventListener('DOMContentLoaded', () => {

    // ---- Navbar: scroll shadow ----
    const navbar = document.getElementById('main-navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 10);
        });
    }

    // ---- Hamburger menu ----
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const mobileNav = document.getElementById('mobile-nav');
    if (hamburgerBtn && mobileNav) {
        hamburgerBtn.addEventListener('click', () => {
            mobileNav.classList.toggle('open');
            // Animate hamburger spans
            const spans = hamburgerBtn.querySelectorAll('span');
            hamburgerBtn.classList.toggle('active');
        });
    }

    // ---- Home product search + system filters ----
    const homeSearch = document.getElementById('home-product-search');
    const homeCards = Array.from(document.querySelectorAll('#home-product-grid .product-card'));
    const homeFilterButtons = Array.from(document.querySelectorAll('.home-filter-chip'));
    const homeCount = document.getElementById('home-product-count');
    const homeEmpty = document.getElementById('home-empty-state');
    let activeHomeFilter = 'all';

    function filterHomeProducts() {
        if (!homeCards.length) return;

        const query = homeSearch ? homeSearch.value.trim().toLowerCase() : '';
        let visibleCount = 0;

        homeCards.forEach(card => {
            const type = card.dataset.systemType || '';
            const text = card.dataset.search || card.textContent.toLowerCase();
            const matchesType = activeHomeFilter === 'all' || type === activeHomeFilter;
            const matchesSearch = !query || text.includes(query);
            const shouldShow = matchesType && matchesSearch;

            card.classList.toggle('is-hidden', !shouldShow);
            if (shouldShow) visibleCount += 1;
        });

        if (homeCount) {
            homeCount.textContent = `${visibleCount} product${visibleCount === 1 ? '' : 's'} available`;
        }
        if (homeEmpty) {
            homeEmpty.hidden = visibleCount !== 0;
        }
    }

    homeFilterButtons.forEach(button => {
        button.addEventListener('click', () => {
            activeHomeFilter = button.dataset.filter || 'all';
            homeFilterButtons.forEach(btn => btn.classList.toggle('active', btn === button));
            filterHomeProducts();
        });
    });

    if (homeSearch) {
        homeSearch.addEventListener('input', filterHomeProducts);
    }

    // ---- Toast notification helper ----
    function showToast(message, type = 'success') {
        const existing = document.getElementById('sm-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.id = 'sm-toast';
        toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'}"></i> ${message}`;
        toast.style.cssText = `
            position: fixed; bottom: 28px; right: 28px; z-index: 9999;
            background: ${type === 'success' ? '#2563eb' : '#ef4444'};
            color: #fff; padding: 14px 22px; font-size: .875rem; font-weight: 600;
            display: flex; align-items: center; gap: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,.2);
            animation: toastIn .3s ease forwards;
            font-family: 'Inter', sans-serif;
            letter-spacing: .03em;
        `;
        document.head.insertAdjacentHTML('beforeend', `
            <style>
                @keyframes toastIn { from { transform: translateY(20px); opacity: 0; } to { transform: none; opacity: 1; } }
                @keyframes toastOut { to { transform: translateY(20px); opacity: 0; } }
            </style>
        `);
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = 'toastOut .3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // ---- Add to Cart ----
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            if (!id || btn.disabled) return;

            fetch(`api_cart.php?action=add&id=${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Update all cart buttons for this product
                        document.querySelectorAll(`.add-to-cart[data-id="${id}"]`).forEach(b => {
                            b.innerHTML = '<i class="fas fa-check"></i> In Cart';
                            b.classList.add('btn-incart');
                            b.disabled = true;
                        });
                        // Update in-cart badge on image
                        const card = document.getElementById(`product-card-${id}`);
                        if (card) {
                            const wrapper = card.querySelector('.product-img-wrapper');
                            if (wrapper && !wrapper.querySelector('.in-cart-badge')) {
                                const badge = document.createElement('span');
                                badge.className = 'in-cart-badge';
                                badge.innerHTML = '<i class="fas fa-check"></i> In Cart';
                                wrapper.appendChild(badge);
                            }
                        }
                        // Update navbar cart count
                        updateNavCount('nav-cart', '.badge-blue', 1);
                        showToast('Added to cart successfully!');
                    } else {
                        showToast('Could not add to cart. Try again.', 'error');
                    }
                })
                .catch(() => {
                    // Optimistic fallback for dev environments
                    btn.innerHTML = '<i class="fas fa-check"></i> In Cart';
                    btn.classList.add('btn-incart');
                    btn.disabled = true;
                    showToast('Added to cart!');
                });
        });
    });

    // ---- Add to Wishlist (toggle) ----
    document.querySelectorAll('.add-to-wishlist-overlay, .add-to-wishlist').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            if (!id) return;

            fetch(`api_wishlist.php?action=toggle&id=${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Toggle all heart buttons for this product
                        document.querySelectorAll(`.add-to-wishlist-overlay[data-id="${id}"], .add-to-wishlist[data-id="${id}"]`).forEach(b => {
                            b.classList.toggle('heart-active');
                        });
                        const isActive = btn.classList.contains('heart-active');
                        updateNavCount('nav-wishlist', '.badge-red', isActive ? 1 : -1);
                        showToast(isActive ? 'Added to wishlist!' : 'Removed from wishlist.');
                    } else {
                        showToast('Could not update wishlist. Try again.', 'error');
                    }
                })
                .catch(() => {
                    // Optimistic fallback
                    btn.classList.toggle('heart-active');
                    const isActive = btn.classList.contains('heart-active');
                    showToast(isActive ? 'Added to wishlist!' : 'Removed from wishlist.');
                });
        });
    });

    // ---- Helper: update nav badge count ----
    function updateNavCount(navLinkId, badgeClass, delta) {
        const link = document.getElementById(navLinkId);
        if (!link) return;
        let badge = link.querySelector(badgeClass);
        let current = badge ? parseInt(badge.textContent) || 0 : 0;
        const next = Math.max(0, current + delta);
        if (next > 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'badge ' + badgeClass.replace('.', '');
                link.appendChild(badge);
            }
            badge.textContent = next;
        } else if (badge) {
            badge.remove();
        }
    }

});
