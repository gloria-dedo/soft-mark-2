document.addEventListener('DOMContentLoaded', () => {
    initDeploymentChips();
    initReviewModal();
    initReviewCarousel();
});

function initDeploymentChips() {
    const chips = document.querySelectorAll('.pd-deployment-chip');
    if (!chips.length) return;

    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            chips.forEach(c => c.classList.remove('is-active'));
            chip.classList.add('is-active');
        });
    });
}

function initReviewModal() {
    const modal = document.getElementById('review-modal');
    const openBtn = document.getElementById('open-review-modal');
    const closeBtn = document.getElementById('close-review-modal');
    const backdrop = document.getElementById('review-modal-backdrop');
    const form = document.getElementById('review-form');
    const starInput = document.getElementById('review-star-input');
    const ratingValue = document.getElementById('review-rating-value');
    const errorEl = document.getElementById('review-form-error');
    const submitBtn = document.getElementById('review-form-submit');

    if (!modal || !openBtn || !form) return;

    let selectedRating = 0;

    function openModal() {
        modal.hidden = false;
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('pd-modal-open');
        document.getElementById('reviewer-name')?.focus();
    }

    function closeModal() {
        modal.hidden = true;
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('pd-modal-open');
    }

    function setRating(value) {
        selectedRating = value;
        ratingValue.value = String(value);

        starInput?.querySelectorAll('.pd-star-input-btn').forEach(btn => {
            const starValue = parseInt(btn.dataset.value, 10);
            const icon = btn.querySelector('i');
            const active = starValue <= value;
            btn.classList.toggle('is-active', active);
            if (icon) {
                icon.classList.toggle('fas', active);
                icon.classList.toggle('far', !active);
            }
        });
    }

    openBtn.addEventListener('click', openModal);
    closeBtn?.addEventListener('click', closeModal);
    backdrop?.addEventListener('click', closeModal);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.hidden) {
            closeModal();
        }
    });

    starInput?.querySelectorAll('.pd-star-input-btn').forEach(btn => {
        btn.addEventListener('mouseenter', () => {
            setRating(parseInt(btn.dataset.value, 10));
        });

        btn.addEventListener('focus', () => {
            setRating(parseInt(btn.dataset.value, 10));
        });

        btn.addEventListener('click', () => {
            setRating(parseInt(btn.dataset.value, 10));
        });
    });

    starInput?.addEventListener('mouseleave', () => {
        setRating(selectedRating);
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (errorEl) errorEl.hidden = true;

        if (!selectedRating) {
            showReviewError('Please select a star rating.');
            return;
        }

        const payload = {
            product_id: form.querySelector('[name="product_id"]')?.value,
            reviewer_name: form.querySelector('[name="reviewer_name"]')?.value.trim(),
            rating: selectedRating,
            comment: form.querySelector('[name="comment"]')?.value.trim(),
        };

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
        }

        try {
            const res = await fetch('api_reviews.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });
            const data = await res.json();

            if (data.success) {
                window.location.reload();
                return;
            }

            showReviewError(data.error || 'Could not submit review.');
        } catch {
            showReviewError('Could not submit review. Please try again.');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Review';
            }
        }
    });

    function showReviewError(message) {
        if (!errorEl) return;
        errorEl.textContent = message;
        errorEl.hidden = false;
    }
}

function initReviewCarousel() {
    const cards = Array.from(document.querySelectorAll('.pd-review-card'));
    const dots = Array.from(document.querySelectorAll('.pd-reviews-dot'));
    const nextBtn = document.getElementById('pd-reviews-next');

    if (!cards.length) return;

    let current = 0;

    function showReview(index) {
        current = (index + cards.length) % cards.length;

        cards.forEach((card, i) => card.classList.toggle('is-active', i === current));
        dots.forEach((dot, i) => dot.classList.toggle('is-active', i === current));
    }

    nextBtn?.addEventListener('click', () => showReview(current + 1));

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            showReview(parseInt(dot.dataset.index, 10));
        });
    });
}
