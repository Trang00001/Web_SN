/**
 * Posts JavaScript - Social Network
 * Xử lý tương tác với posts: like, comment
 */

document.addEventListener('DOMContentLoaded', function() {
    initializePostInteractions();
});

/**
 * Initialize post interactions
 */
function initializePostInteractions() {
    // Like button handlers
    document.addEventListener('click', function(e) {
        if (e.target.closest('.like-btn')) {
            e.preventDefault();
            handleLikeToggle(e.target.closest('.like-btn'));
        }
    });

    // Comment toggle handlers
    document.addEventListener('click', function(e) {
        if (e.target.closest('.comment-toggle-btn')) {
            e.preventDefault();
            handleCommentToggle(e.target.closest('.comment-toggle-btn'));
        }
    });

    // Submit comment handlers
    document.addEventListener('click', function(e) {
        if (e.target.closest('.comment-submit-btn')) {
            e.preventDefault();
            handleCommentSubmit(e.target.closest('.comment-submit-btn'));
        }
    });

    // Enter key on comment input
    document.addEventListener('keypress', function(e) {
        if (e.target.classList.contains('comment-input') && e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            const submitBtn = e.target.parentNode.querySelector('.comment-submit-btn');
            if (submitBtn && e.target.value.trim()) {
                handleCommentSubmit(submitBtn);
            }
        }
    });
}

/**
 * Handle like button toggle
 */
function handleLikeToggle(likeBtn) {
    const icon = likeBtn.querySelector('i');
    const postCard = likeBtn.closest('.post-card');
    const countElement = postCard.querySelector('.likes-count');
    const isLiked = likeBtn.classList.contains('liked');
    
    if (isLiked) {
        // Unlike
        likeBtn.classList.remove('liked');
        icon.className = 'far fa-heart';
        icon.style.color = '#6c757d';
        likeBtn.querySelector('.like-text').textContent = 'Thích';
        const currentCount = parseInt(countElement.textContent) || 0;
        countElement.textContent = Math.max(0, currentCount - 1);
    } else {
        // Like
        likeBtn.classList.add('liked');
        icon.className = 'fas fa-heart';
        icon.style.color = '#e74c3c';
        likeBtn.querySelector('.like-text').textContent = 'Đã thích';
        const currentCount = parseInt(countElement.textContent) || 0;
        countElement.textContent = currentCount + 1;
    }
    
    // Add animation
    likeBtn.style.transform = 'scale(1.2)';
    setTimeout(() => {
        likeBtn.style.transform = 'scale(1)';
    }, 200);
    
    console.log('Like toggled:', isLiked ? 'Unliked' : 'Liked');
}

/**
 * Handle comment section toggle
 */
function handleCommentToggle(toggleBtn) {
    const postCard = toggleBtn.closest('.post-card');
    const commentsSection = postCard.querySelector('.post-comments');
    
    if (commentsSection) {
        const isVisible = commentsSection.style.display !== 'none';
        commentsSection.style.display = isVisible ? 'none' : 'block';
        
        const icon = toggleBtn.querySelector('i');
        if (icon) {
            icon.className = isVisible ? 'fas fa-comment' : 'fas fa-comment-dots';
        }
        
        // Focus on comment input if showing
        if (!isVisible) {
            const commentInput = commentsSection.querySelector('.comment-input');
            if (commentInput) {
                setTimeout(() => commentInput.focus(), 100);
            }
        }
    }
    
    console.log('Comments toggled');
}

/**
 * Handle comment submission
 */
function handleCommentSubmit(submitBtn) {
    const commentInput = submitBtn.parentNode.querySelector('.comment-input');
    const commentsList = submitBtn.closest('.post-comments').querySelector('.comments-list');
    const commentText = commentInput.value.trim();
    
    if (!commentText) {
        alert('Vui lòng nhập nội dung bình luận');
        return;
    }
    
    // Create new comment element
    const newComment = document.createElement('div');
    newComment.className = 'comment-item d-flex mb-3';
    newComment.innerHTML = `
        <div class="avatar-sm me-3" style="background: #4a7c59; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-user text-white"></i>
        </div>
        <div class="comment-content flex-grow-1">
            <div class="d-flex align-items-center mb-1">
                <strong class="me-2 text-success">Bạn</strong>
                <small class="text-muted">Vừa xong</small>
            </div>
            <div class="comment-text p-2 bg-light rounded">${commentText}</div>
        </div>
    `;
    
    // Add to comments list with animation
    newComment.style.opacity = '0';
    newComment.style.transform = 'translateY(20px)';
    commentsList.appendChild(newComment);
    
    // Animate in
    setTimeout(() => {
        newComment.style.transition = 'all 0.3s ease';
        newComment.style.opacity = '1';
        newComment.style.transform = 'translateY(0)';
    }, 10);
    
    // Clear input
    commentInput.value = '';
    
    // Update comment count
    const postCard = submitBtn.closest('.post-card');
    const commentCount = postCard.querySelector('.comments-count');
    if (commentCount) {
        const currentCount = parseInt(commentCount.textContent) || 0;
        commentCount.textContent = currentCount + 1;
    }
    
    // Scroll to new comment
    setTimeout(() => {
        newComment.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }, 300);
    
    console.log('Comment added:', commentText);
}

/**
 * Utility function to format time
 */
function timeAgo(date) {
    const now = new Date();
    const diff = now - date;
    const minutes = Math.floor(diff / 60000);
    
    if (minutes < 1) return 'Vừa xong';
    if (minutes < 60) return `${minutes} phút trước`;
    
    const hours = Math.floor(minutes / 60);
    if (hours < 24) return `${hours} giờ trước`;
    
    const days = Math.floor(hours / 24);
    return `${days} ngày trước`;
}

console.log('Posts.js loaded successfully');
