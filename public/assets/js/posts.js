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

    async toggleLike(button) {
        if (!button) return;
        
        console.log('Toggle like called');
        const isLiked = button.classList.contains('liked');
        const postCard = button.closest('.post-card');
        const postID = postCard.dataset.postId;
        
        if (!postID) {
            this.showToast('Không tìm thấy ID bài viết', 'error');
            return;
        }
        
        const icon = button.querySelector('i');
        const text = button.querySelector('span');
        const likeCount = postCard.querySelector('.like-count');
        
        // Disable button during API call
        button.disabled = true;
        
        try {
            // Call API
            const response = await fetch('/api/posts/like.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    post_id: parseInt(postID),
                    action: isLiked ? 'unlike' : 'like'
                })
            });
            
            const data = await response.json();
            
            if (!response.ok || !data.success) {
                throw new Error(data.error || 'Có lỗi xảy ra');
            }
            
            // Update UI on success
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
            console.log('Like toggled via API, new count:', count);
            
        } catch (error) {
            console.error('Error toggling like:', error);
            this.showToast(error.message || 'Không thể thích bài viết', 'error');
        } finally {
            button.disabled = false;
        }
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

    async submitComment(input) {
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
        const postID = postCard.dataset.postId;
        
        if (!postID) {
            this.showToast('Không tìm thấy ID bài viết', 'error');
            return;
        }
        
        // Disable input during API call
        input.disabled = true;
        
        try {
            // Call API
            const response = await fetch('/api/posts/comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    post_id: parseInt(postID),
                    content: text
                })
            });
            
            const data = await response.json();
            
            if (!response.ok || !data.success) {
                throw new Error(data.error || 'Có lỗi xảy ra');
            }
            
            // Create new comment element
            const commentData = data.comment;
            const newComment = document.createElement('div');
            newComment.className = 'comment-item';
            newComment.innerHTML = `
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                     style="width: 32px; height: 32px;">
                    <span class="text-white small fw-bold">${commentData.username.charAt(0).toUpperCase()}</span>
                </div>
                <div class="comment-content">
                    <div class="bg-light rounded p-2">
                        <small class="fw-bold text-primary">${commentData.username}</small>
                        <div>${commentData.content}</div>
                    </div>
                    <small class="text-muted">${commentData.created_at}</small>
                </div>
            `;
            
            commentsList.appendChild(newComment);
            input.value = '';
            
            // Update comment count
            const commentCount = postCard.querySelector('.comment-count');
            let count = parseInt(commentCount.textContent.match(/\d+/)[0]) || 0;
            commentCount.textContent = (count + 1) + ' bình luận';
            
            this.showToast('Đã thêm bình luận', 'success');
            console.log('Comment added via API:', commentData);
            
        } catch (error) {
            console.error('Error submitting comment:', error);
            this.showToast(error.message || 'Không thể thêm bình luận', 'error');
        } finally {
            input.disabled = false;
        }
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
        const bgClass = type === 'success' ? 'success' : type === 'warning' ? 'warning' : type === 'error' ? 'danger' : 'primary';
        toast.className = `toast align-items-center text-white bg-${bgClass} border-0`;
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
let postManager;
document.addEventListener('DOMContentLoaded', () => {
    socialAppInstance = new SocialApp();
    postManager = new PostManager();
    
    // Export for debugging and global access
    window.socialApp = socialAppInstance;
    window.postManager = postManager;
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
        this.selectedImages = [];
        this.bindEvents();
        this.initImageUpload();
    }

    bindEvents() {
        console.log('🔧 Binding events...');
        
        // Xử lý form submit
        document.addEventListener('submit', (e) => {
            if (e.target.id === 'create-post-form') {
                console.log('✅ Form submitted!');
                e.preventDefault();
                this.createPost();
            }
        });
        
        // Xử lý nút tạo bài viết (show modal)
        document.addEventListener('click', (e) => {
            if (e.target.closest('[onclick*="showCreatePostModal"]')) {
                console.log('✅ Show modal button clicked!');
                e.preventDefault();
                this.showModal();
            }
        });
    }

    showModal() {
        const modal = new bootstrap.Modal(document.getElementById('createPostModal'));
        modal.show();
    }

    async createPost() {
        console.log('🔴 createPost() called!');
        
        const textarea = document.querySelector('#post-content-textarea');
        if (!textarea) {
            console.error('❌ Textarea not found!');
            alert('Lỗi: Không tìm thấy textarea!');
            return;
        }
        
        const content = textarea.value.trim();
        console.log('📝 Content:', content);
        
        if (!content) {
            alert('Vui lòng nhập nội dung!');
            return;
        }

        try {
            // Upload images first if any
            const imageURLs = [];
            if (this.selectedImages.length > 0) {
                const uploadBtn = document.querySelector('#post-submit-btn');
                uploadBtn.disabled = true;
                uploadBtn.textContent = 'Đang upload ảnh...';
                
                for (const file of this.selectedImages) {
                    const formData = new FormData();
                    formData.append('image', file);
                    
                    const uploadResponse = await fetch('/api/posts/upload_image.php', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const uploadResult = await uploadResponse.json();
                    if (uploadResult.success) {
                        imageURLs.push(uploadResult.image_url);
                    }
                }
                
                uploadBtn.textContent = 'Đăng';
                uploadBtn.disabled = false;
            }
            
            // Create post with images
            const response = await fetch('/api/posts/create.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    content: content,
                    image_urls: imageURLs
                })
            });

            const result = await response.json();

            if (result.success) {
                alert('Đăng bài thành công!' + (result.image_count > 0 ? ` (${result.image_count} ảnh)` : ''));
                bootstrap.Modal.getInstance(document.getElementById('createPostModal')).hide();
                textarea.value = '';
                this.clearImagePreview();
                location.reload();
            } else {
                alert('Lỗi: ' + result.error);
            }
        } catch (error) {
            alert('Có lỗi xảy ra: ' + error.message);
        }
    }
    
    initImageUpload() {
        // Use event delegation since modal might not be in DOM yet
        document.addEventListener('change', (e) => {
            if (e.target && e.target.id === 'post-image-input') {
                const files = Array.from(e.target.files);
                this.selectedImages = files;
                this.showImagePreview(files);
            }
        });
    }
    
    showImagePreview(files) {
        const container = document.getElementById('image-preview-container');
        const list = document.getElementById('image-preview-list');
        
        if (!container || !list) return;
        
        list.innerHTML = '';
        
        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'position-relative';
                div.style.width = '100px';
                div.style.height = '100px';
                div.innerHTML = `
                    <img src="${e.target.result}" class="img-fluid rounded" style="width: 100%; height: 100%; object-fit: cover;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="window.postManager.removeImage(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                list.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
        
        container.style.display = files.length > 0 ? 'block' : 'none';
    }
    
    removeImage(index) {
        this.selectedImages.splice(index, 1);
        const input = document.getElementById('post-image-input');
        input.value = '';
        this.showImagePreview(this.selectedImages);
    }
    
    clearImagePreview() {
        this.selectedImages = [];
        const container = document.getElementById('image-preview-container');
        const list = document.getElementById('image-preview-list');
        const input = document.getElementById('post-image-input');
        
        if (container) container.style.display = 'none';
        if (list) list.innerHTML = '';
        if (input) input.value = '';
    }
}

// Export app instance for global access
window.app = window.socialApp;