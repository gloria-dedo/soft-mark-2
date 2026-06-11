// ======================================
// SoftMark ERP — Main JavaScript
// ======================================

document.addEventListener('DOMContentLoaded', () => {

    // ---- Navbar: scroll border ----
    const navbar = document.getElementById('main-navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 10);
        });
    }

    // ---- Store header: scroll border + hamburger + search toggle ----
    const storeHeader    = document.getElementById('store-header');
    const storeHamburger = document.getElementById('store-hamburger');
    const storeMobileNav = document.getElementById('store-mobile-nav');
    const searchToggle   = document.getElementById('store-search-toggle');
    const searchClose    = document.getElementById('store-search-close');
    const searchInput    = document.getElementById('store-search-input');

    // Scroll border
    if (storeHeader) {
        window.addEventListener('scroll', () => {
            storeHeader.classList.toggle('scrolled', window.scrollY > 10);
        });
    }

    // Hamburger opens/closes mobile drawer
    const mobileNavClose = document.getElementById('store-mobile-nav-close');
    const mobileTabs = document.querySelectorAll('[data-mobile-tab]');
    const mobilePanels = document.querySelectorAll('[data-mobile-panel]');

    function setMobileNavTab(tabName) {
        mobileTabs.forEach(tab => {
            const isActive = tab.dataset.mobileTab === tabName;
            tab.classList.toggle('is-active', isActive);
            tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });

        mobilePanels.forEach(panel => {
            const isActive = panel.dataset.mobilePanel === tabName;
            panel.classList.toggle('is-active', isActive);
            panel.hidden = !isActive;
        });
    }

    function openMobileNav() {
        if (!storeMobileNav || !storeHamburger) return;
        storeMobileNav.classList.add('open');
        storeMobileNav.setAttribute('aria-hidden', 'false');
        storeHamburger.setAttribute('aria-expanded', 'true');
        document.body.classList.add('mobile-nav-open');
        setMobileNavTab('categories');
    }

    function closeMobileNav() {
        if (!storeMobileNav || !storeHamburger) return;
        storeMobileNav.classList.remove('open');
        storeMobileNav.setAttribute('aria-hidden', 'true');
        storeHamburger.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('mobile-nav-open');
    }

    if (storeHamburger && storeMobileNav) {
        storeHamburger.addEventListener('click', () => {
            if (storeMobileNav.classList.contains('open')) {
                closeMobileNav();
            } else {
                openMobileNav();
            }
        });
    }

    mobileNavClose?.addEventListener('click', closeMobileNav);

    mobileTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            setMobileNavTab(tab.dataset.mobileTab || 'categories');
        });
    });

    storeMobileNav?.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', closeMobileNav);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && storeMobileNav?.classList.contains('open')) {
            closeMobileNav();
        }
    });

    // Search icon opens full-width search bar on mobile
    if (searchToggle && storeHeader) {
        searchToggle.addEventListener('click', () => {
            storeHeader.classList.add('search-open');
            if (searchInput) searchInput.focus();
            closeMobileNav();
        });
    }

    // Close button collapses search bar
    if (searchClose && storeHeader) {
        searchClose.addEventListener('click', () => {
            storeHeader.classList.remove('search-open');
            closeSearchDropdown();
        });
    }

    // ---- Categories dropdown ----
    const categoryWrap = document.getElementById('store-category-wrap');
    const categoryBtn = document.getElementById('store-category-btn');
    const categoryDropdown = document.getElementById('store-category-dropdown');

    function closeCategoryDropdown() {
        if (!categoryBtn || !categoryDropdown) return;
        categoryBtn.setAttribute('aria-expanded', 'false');
        categoryDropdown.hidden = true;
    }

    function openCategoryDropdown() {
        if (!categoryBtn || !categoryDropdown) return;
        closeSearchDropdown();
        categoryBtn.setAttribute('aria-expanded', 'true');
        categoryDropdown.hidden = false;
    }

    if (categoryBtn && categoryDropdown) {
        categoryBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = categoryBtn.getAttribute('aria-expanded') === 'true';
            if (isOpen) {
                closeCategoryDropdown();
            } else {
                openCategoryDropdown();
            }
        });
    }

    // ---- Live search dropdown ----
    const searchForm = document.getElementById('store-search-form');
    const searchWrap = document.getElementById('store-search-wrap');
    const searchDropdown = document.getElementById('store-search-dropdown');
    let searchDebounceTimer = null;
    let searchAbortController = null;
    let activeResultIndex = -1;

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function highlightMatch(text, query) {
        const safeText = escapeHtml(text);
        const trimmed = query.trim();
        if (!trimmed) return safeText;
        const escaped = trimmed.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        return safeText.replace(new RegExp(`(${escaped})`, 'gi'), '<mark class="search-highlight">$1</mark>');
    }

    function formatPrice(price) {
        return '$' + Number(price).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    }

    function closeSearchDropdown() {
        if (!searchDropdown || !searchInput) return;
        searchDropdown.hidden = true;
        searchDropdown.innerHTML = '';
        searchInput.setAttribute('aria-expanded', 'false');
        searchForm?.classList.remove('is-focused');
        activeResultIndex = -1;
    }

    function openSearchDropdown() {
        if (!searchDropdown || !searchInput) return;
        closeCategoryDropdown();
        searchDropdown.hidden = false;
        searchInput.setAttribute('aria-expanded', 'true');
        searchForm?.classList.add('is-focused');
    }

    function renderSearchResults(data) {
        if (!searchDropdown) return;

        const query = data.query || '';
        const total = data.total || 0;
        const results = data.results || [];

        if (total === 0) {
            searchDropdown.innerHTML = `
                <div class="store-search-empty">
                    No results for &ldquo;${escapeHtml(query)}&rdquo;
                </div>
            `;
            openSearchDropdown();
            return;
        }

        const header = `<div class="store-search-dropdown-header">${total} result${total === 1 ? '' : 's'}</div>`;
        const items = results.map((item, index) => `
            <a
                href="product-detail.php?id=${item.id}"
                class="store-search-result"
                data-index="${index}"
                role="option"
            >
                <span class="store-search-result-img">
                    <img src="${escapeHtml(item.image_url)}" alt="" loading="lazy" onerror="this.onerror=null;this.src='assets/images/placeholder.jpg';">
                </span>
                <span class="store-search-result-body">
                    <span class="store-search-result-name">${highlightMatch(item.name, query)}</span>
                    <span class="store-search-result-price">${formatPrice(item.price)}</span>
                </span>
            </a>
        `).join('');

        const viewAll = total > results.length
            ? `<a href="products.php?search=${encodeURIComponent(query)}" class="store-search-view-all">View all ${total} results</a>`
            : '';

        searchDropdown.innerHTML = header + items + viewAll;
        openSearchDropdown();
        activeResultIndex = -1;
    }

    function performLiveSearch(query) {
        if (searchAbortController) {
            searchAbortController.abort();
        }

        if (query.trim().length < 2) {
            closeSearchDropdown();
            return;
        }

        searchDropdown.innerHTML = '<div class="store-search-loading">Searching&hellip;</div>';
        openSearchDropdown();

        searchAbortController = new AbortController();

        fetch(`api_search.php?q=${encodeURIComponent(query)}`, { signal: searchAbortController.signal })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    renderSearchResults(data);
                } else {
                    closeSearchDropdown();
                }
            })
            .catch(err => {
                if (err.name !== 'AbortError') {
                    closeSearchDropdown();
                }
            });
    }

    if (searchInput && searchDropdown) {
        searchInput.addEventListener('focus', () => {
            searchForm?.classList.add('is-focused');
            closeCategoryDropdown();
            const query = searchInput.value.trim();
            if (query.length >= 2) {
                performLiveSearch(query);
            }
        });

        searchInput.addEventListener('input', () => {
            clearTimeout(searchDebounceTimer);
            const query = searchInput.value;
            searchDebounceTimer = setTimeout(() => performLiveSearch(query), 280);
        });

        searchInput.addEventListener('keydown', (e) => {
            const results = searchDropdown.querySelectorAll('.store-search-result');

            if (e.key === 'Escape') {
                closeSearchDropdown();
                searchInput.blur();
                return;
            }

            if (searchDropdown.hidden || !results.length) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                activeResultIndex = Math.min(activeResultIndex + 1, results.length - 1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeResultIndex = Math.max(activeResultIndex - 1, 0);
            } else if (e.key === 'Enter' && activeResultIndex >= 0) {
                e.preventDefault();
                results[activeResultIndex].click();
                return;
            } else {
                return;
            }

            results.forEach((el, i) => el.classList.toggle('is-active', i === activeResultIndex));
            results[activeResultIndex]?.scrollIntoView({ block: 'nearest' });
        });

        searchDropdown.addEventListener('mousedown', (e) => {
            e.preventDefault();
        });
    }

    document.addEventListener('click', (e) => {
        if (categoryWrap && !categoryWrap.contains(e.target)) {
            closeCategoryDropdown();
        }
        if (searchWrap && !searchWrap.contains(e.target)) {
            closeSearchDropdown();
            searchForm?.classList.remove('is-focused');
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeCategoryDropdown();
        }
    });

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
    const homeSystemSelect = document.getElementById('home-system-filter');
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
            if (homeSystemSelect) homeSystemSelect.value = activeHomeFilter;
            filterHomeProducts();
        });
    });

    if (homeSystemSelect) {
        homeSystemSelect.addEventListener('change', () => {
            activeHomeFilter = homeSystemSelect.value || 'all';
            homeFilterButtons.forEach(btn => btn.classList.toggle('active', (btn.dataset.filter || 'all') === activeHomeFilter));
            filterHomeProducts();
        });
    }

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
                        updateStoreBadge('store-cart', 1);
                        if (btn.classList.contains('product-detail-cart-btn')) {
                            btn.disabled = true;
                        }
                        showToast('Added to cart successfully!');
                    } else {
                        showToast('Could not add to cart. Try again.', 'error');
                    }
                })
                .catch(() => {
                    updateStoreBadge('store-cart', 1);
                    if (btn.classList.contains('product-detail-cart-btn')) {
                        btn.disabled = true;
                    }
                    showToast('Added to cart!');
                });
        });
    });

    // ---- Add to Wishlist (toggle) ----
    document.querySelectorAll('.add-to-wishlist-overlay, .add-to-wishlist').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const id = btn.dataset.id;
            if (!id) return;

            fetch(`api_wishlist.php?action=toggle&id=${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        syncWishlistButtons(id);
                        const isActive = document.querySelector(`.add-to-wishlist-overlay[data-id="${id}"]`)?.classList.contains('heart-active');
                        updateStoreBadge('store-wishlist', isActive ? 1 : -1);
                        showToast(isActive ? 'Added to wishlist!' : 'Removed from wishlist.');
                    } else {
                        showToast('Could not update wishlist. Try again.', 'error');
                    }
                })
                .catch(() => {
                    btn.classList.toggle('heart-active');
                    syncWishlistHeartIcon(btn);
                    const isActive = btn.classList.contains('heart-active');
                    updateStoreBadge('store-wishlist', isActive ? 1 : -1);
                    showToast(isActive ? 'Added to wishlist!' : 'Removed from wishlist.');
                });
        });
    });

    function syncWishlistHeartIcon(btn) {
        const icon = btn.querySelector('i');
        if (!icon) return;
        const active = btn.classList.contains('heart-active');
        icon.classList.toggle('fas', active);
        icon.classList.toggle('far', !active);
        btn.setAttribute('aria-pressed', active ? 'true' : 'false');
        btn.setAttribute('aria-label', active ? 'Remove from wishlist' : 'Add to wishlist');
    }

    function syncWishlistButtons(productId) {
        const buttons = document.querySelectorAll(`.add-to-wishlist-overlay[data-id="${productId}"], .add-to-wishlist[data-id="${productId}"]`);
        if (!buttons.length) return;

        const active = !buttons[0].classList.contains('heart-active');
        buttons.forEach(b => {
            b.classList.toggle('heart-active', active);
            syncWishlistHeartIcon(b);
        });
    }

    function updateStoreBadge(linkId, delta) {
        const link = document.getElementById(linkId);
        if (!link) return;
        let badge = link.querySelector('.store-badge');
        const current = badge ? parseInt(badge.textContent, 10) || 0 : 0;
        const next = Math.max(0, current + delta);
        if (next > 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'store-badge';
                link.appendChild(badge);
            }
            badge.textContent = next;
        } else if (badge) {
            badge.remove();
        }
    }

    // ---- Legacy nav badge helper (cart/wishlist pages with old navbar) ----
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
