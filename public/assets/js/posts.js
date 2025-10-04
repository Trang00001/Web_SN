/**
 * Modern Posts JavaScript - TechConnect
 * Clean and maintainable code for social media interactions
 */

class SocialApp {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.initAnimations();
        console.log('SocialApp initialized');
    }

    bindEvents() {
        console.log('Binding events...');
        
        // Sử dụng cách tiếp cận đơn giản như trong test file
        document.addEventListener('click', (e) => {
            console.log('Click detected on:', e.target.tagName, e.target.className);
            
            // Like button
            if (e.target.closest('.like-btn')) {
                e.preventDefault();
                console.log('Like button clicked!');
                this.toggleLike(e.target.closest('.like-btn'));
                return;
            }
            
            // Comment button  
            if (e.target.closest('.comment-btn')) {
                e.preventDefault();
                console.log('Comment button clicked!');
                this.toggleComments(e.target.closest('.comment-btn'));
                return;
            }
            
            // Share button
            if (e.target.closest('.share-btn')) {
                e.preventDefault();
                console.log('Share button clicked!');
                this.sharePost(e.target.closest('.share-btn'));
                return;
            }
        });

        // Comment submission với Enter
        document.addEventListener('keypress', (e) => {
            if (e.target.classList.contains('comment-input') && e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                console.log('Enter pressed in comment input');
                this.submitComment(e.target);
            }
        });
        
        console.log('Events bound successfully');
    }

    toggleLike(button) {
        if (!button) return;
        
        console.log('Toggle like called');
        const isLiked = button.classList.contains('liked');
        const icon = button.querySelector('i');
        const text = button.querySelector('span');
        const postCard = button.closest('.post-card');
        const likeCount = postCard.querySelector('.like-count');
        
        let count = parseInt(likeCount.textContent.match(/\d+/)[0]) || 0;
        
        if (isLiked) {
            button.classList.remove('liked');
            icon.className = 'far fa-heart';
            text.textContent = 'Thích';
            count = Math.max(0, count - 1);
            this.showToast('Đã bỏ thích', 'info');
        } else {
            button.classList.add('liked');
            icon.className = 'fas fa-heart';
            text.textContent = 'Đã thích';
            count++;
            this.showToast('Đã thích bài viết', 'success');
        }
        
        likeCount.textContent = count + ' lượt thích';
        this.animateButton(button, 'like');
        console.log('Like toggled, new count:', count);
    }

    toggleComments(button) {
        if (!button) return;
        
        console.log('Toggle comments called');
        const postCard = button.closest('.post-card');
        const commentsSection = postCard.querySelector('.comments-section');
        
        if (commentsSection) {
            const isVisible = commentsSection.classList.contains('show');
            if (isVisible) {
                commentsSection.classList.remove('show');
                this.showToast('Đã ẩn bình luận', 'info');
            } else {
                commentsSection.classList.add('show');
                this.showToast('Hiển thị bình luận', 'info');
                // Focus vào comment input
                const commentInput = commentsSection.querySelector('.comment-input');
                if (commentInput) {
                    setTimeout(() => commentInput.focus(), 300);
                }
            }
            console.log('Comments toggled:', { isVisible: !isVisible });
        }
    }

    sharePost(button) {
        console.log('Share post called');
        const postCard = button.closest('.post-card');
        const postContent = postCard.querySelector('.post-content').textContent.substring(0, 100) + '...';
        
        if (navigator.share) {
            navigator.share({
                title: 'TechConnect - Chia sẻ bài viết',
                text: postContent,
                url: window.location.href
            });
        } else {
            navigator.clipboard.writeText(window.location.href);
            this.showToast('Đã sao chép link bài viết', 'success');
        }
        
        this.animateButton(button, 'share');
    }

    submitComment(input) {
        if (!input) return;
        
        console.log('Submit comment called');
        const text = input.value.trim();
        
        if (!text) {
            this.showToast('Vui lòng nhập bình luận!', 'warning');
            return;
        }

        const commentsSection = input.closest('.comments-section');
        const commentsList = commentsSection.querySelector('.comments-list');
        const postCard = input.closest('.post-card');
        
        // Create new comment
        const newComment = document.createElement('div');
        newComment.className = 'comment-item';
        newComment.innerHTML = `
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                 style="width: 32px; height: 32px;">
                <span class="text-white small fw-bold">U</span>
            </div>
            <div class="comment-content">
                <div class="bg-light rounded p-2">
                    <small class="fw-bold text-primary">Demo User</small>
                    <div>${text}</div>
                </div>
                <small class="text-muted">Vừa xong</small>
            </div>
        `;
        
        commentsList.appendChild(newComment);
        input.value = '';
        
        // Update comment count
        const commentCount = postCard.querySelector('.comment-count');
        let count = parseInt(commentCount.textContent.match(/\d+/)[0]) || 0;
        commentCount.textContent = (count + 1) + ' bình luận';
        
        this.showToast('Đã thêm bình luận', 'success');
        console.log('Comment added:', text);
    }

    animateButton(button, type) {
        button.style.transform = 'scale(1.2)';
        
        if (type === 'like') {
            button.style.background = 'rgba(231, 76, 60, 0.1)';
        } else if (type === 'share') {
            button.style.background = 'rgba(102, 126, 234, 0.1)';
        }
        
        setTimeout(() => {
            button.style.transform = 'scale(1)';
            button.style.background = '';
        }, 200);
    }

    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'primary'} border-0`;
        toast.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 9999;
            min-width: 250px;
        `;
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 3000);
    }

    initAnimations() {
        // Stagger animation for cards
        const cards = document.querySelectorAll('.tech-card, .post-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }
}

// Initialize app when DOM is ready
let socialAppInstance;
document.addEventListener('DOMContentLoaded', () => {
    socialAppInstance = new SocialApp();
    new PostManager();
    
    // Export for debugging and global access
    window.socialApp = socialAppInstance;
    console.log('Social app initialized and exported to window.socialApp');
});

// Global functions for backward compatibility
window.showCreatePostModal = function(type = 'text') {
    const modal = new bootstrap.Modal(document.getElementById('createPostModal'));
    modal.show();
};

window.createPost = function() {
    const content = document.querySelector('#createPostModal textarea').value;
    if (content.trim()) {
        socialAppInstance.showToast('Bài viết đã được tạo thành công!', 'success');
        bootstrap.Modal.getInstance(document.getElementById('createPostModal')).hide();
        setTimeout(() => location.reload(), 500);
    } else {
        socialAppInstance.showToast('Vui lòng nhập nội dung bài viết!', 'warning');
    }
};

// Global function for comments (updated to use new approach)
window.submitComment = function(postId) {
    const input = document.querySelector(`#comments-${postId} .comment-input`);
    if (socialAppInstance && input) {
        socialAppInstance.submitComment(input);
    }
};

// Global function for image modal
window.openImageModal = function(imageSrc) {
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    document.getElementById('modalImage').src = imageSrc;
    modal.show();
};

/**
 * Post Manager - PROTOTYPE VERSION
 * Đơn giản để dễ điều chỉnh
 */
class PostManager {
    constructor() {
        this.bindEvents();
    }

    bindEvents() {
        // Xử lý nút tạo bài viết
        document.addEventListener('click', (e) => {
            if (e.target.closest('[onclick*="showCreatePostModal"]')) {
                e.preventDefault();
                this.showModal();
            }
        });

        // Xử lý submit
        const submitBtn = document.querySelector('#createPostModal .btn-primary');
        if (submitBtn) {
            submitBtn.addEventListener('click', () => this.createPost());
        }
    }

    showModal() {
        const modal = new bootstrap.Modal(document.getElementById('createPostModal'));
        modal.show();
    }

    async createPost() {
        const textarea = document.querySelector('#createPostModal textarea');
        const content = textarea.value.trim();
        
        if (!content) {
            alert('Vui lòng nhập nội dung!');
            return;
        }

        try {
            const response = await fetch('/WEB-SN/public/api/posts/create.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({content})
            });

            const result = await response.json();

            if (result.success) {
                alert('Đăng bài thành công!');
                bootstrap.Modal.getInstance(document.getElementById('createPostModal')).hide();
                textarea.value = '';
                location.reload(); // Đơn giản - reload trang
            } else {
                alert('Lỗi: ' + result.error);
            }
        } catch (error) {
            alert('Có lỗi xảy ra!');
        }
    }
}