/**
 * Post Image Carousel Handler
 * Instagram-style image slider with keyboard support
 * Version: 1.0
 */

class PostCarousel {
    constructor() {
        this.carousels = new Map(); // postId -> currentIndex
        this.initKeyboardNavigation();
    }

    /**
     * Initialize carousel for a post
     */
    init(postId) {
        if (!this.carousels.has(postId)) {
            this.carousels.set(postId, 0);
        }
    }

    /**
     * Go to next image
     */
    next(postId) {
        this.init(postId);
        const container = document.querySelector(`.post-carousel-container[data-post-id="${postId}"]`);
        if (!container) return;
        
        const slides = container.querySelectorAll('.carousel-slide');
        if (slides.length <= 1) return;
        
        let currentIndex = this.carousels.get(postId);
        const newIndex = (currentIndex + 1) % slides.length;
        
        this.goTo(postId, newIndex);
    }

    /**
     * Go to previous image
     */
    prev(postId) {
        this.init(postId);
        const container = document.querySelector(`.post-carousel-container[data-post-id="${postId}"]`);
        if (!container) return;
        
        const slides = container.querySelectorAll('.carousel-slide');
        if (slides.length <= 1) return;
        
        let currentIndex = this.carousels.get(postId);
        const newIndex = (currentIndex - 1 + slides.length) % slides.length;
        
        this.goTo(postId, newIndex);
    }

    /**
     * Go to specific image
     */
    goTo(postId, index) {
        this.init(postId);
        const container = document.querySelector(`.post-carousel-container[data-post-id="${postId}"]`);
        if (!container) return;
        
        const slides = container.querySelectorAll('.carousel-slide');
        const dots = container.querySelectorAll('.dot');
        
        if (index < 0 || index >= slides.length) return;
        
        // Remove active class from all
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Add active class to current
        slides[index].classList.add('active');
        if (dots[index]) {
            dots[index].classList.add('active');
        }
        
        // Update state
        this.carousels.set(postId, index);
    }

    /**
     * Keyboard navigation (Arrow keys)
     */
    initKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            // Get focused carousel (mouse hover or last interacted)
            const hoveredCarousel = document.querySelector('.post-carousel-container:hover');
            if (!hoveredCarousel) return;
            
            // Support both numeric and string postId (e.g., "modal-16")
            const postId = hoveredCarousel.dataset.postId;
            if (!postId) return;
            
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                this.prev(postId);
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                this.next(postId);
            }
        });
    }

    /**
     * Touch/Swipe support for mobile
     */
    initTouchSupport() {
        document.querySelectorAll('.post-carousel-container').forEach(container => {
            let touchStartX = 0;
            let touchEndX = 0;
            
            container.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });
            
            container.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                this.handleSwipe(container, touchStartX, touchEndX);
            }, { passive: true });
        });
    }

    /**
     * Handle swipe gesture
     */
    handleSwipe(container, startX, endX) {
        // Support both numeric and string postId
        const postId = container.dataset.postId;
        if (!postId) return;
        
        const swipeThreshold = 50;
        
        if (startX - endX > swipeThreshold) {
            // Swipe left -> next
            this.next(postId);
        } else if (endX - startX > swipeThreshold) {
            // Swipe right -> prev
            this.prev(postId);
        }
    }
}

// Initialize global instance
const postCarousel = new PostCarousel();

// Init touch support when DOM ready
document.addEventListener('DOMContentLoaded', () => {
    postCarousel.initTouchSupport();
    console.log('✅ Post Carousel initialized');
});

// Export for use in other files
window.postCarousel = postCarousel;
