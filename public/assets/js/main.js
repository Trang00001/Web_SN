// Main JavaScript file for Social Network

class SocialNetwork {
    constructor() {
        this.currentUser = null;
        this.posts = [];
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadPosts();
        this.checkAuthStatus();
    }

    bindEvents() {
        // Navigation events
        document.addEventListener('DOMContentLoaded', () => {
            this.initializeNavigation();
            this.initializePostCreator();
            this.initializeSearch();
            this.initializeMobileMenu();
        });

        // Post interactions
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('reaction-btn')) {
                this.handleReaction(e.target);
            }
            if (e.target.classList.contains('post-input')) {
                this.showPostModal();
            }
        });

        // Search functionality
        const searchBox = document.querySelector('.search-box');
        if (searchBox) {
            searchBox.addEventListener('input', (e) => {
                this.performSearch(e.target.value);
            });
        }
    }

    initializeNavigation() {
        const currentPage = window.location.pathname.split('/').pop().replace('.html', '') || 'home';
        const menuItems = document.querySelectorAll('.sidebar-menu a');
        
        menuItems.forEach(item => {
            const href = item.getAttribute('href');
            if (href && href.includes(currentPage)) {
                item.classList.add('active');
            }
        });
    }

    initializePostCreator() {
        const postActions = document.querySelectorAll('.post-action');
        postActions.forEach(action => {
            action.addEventListener('click', () => {
                const actionType = action.dataset.action;
                this.handlePostAction(actionType);
            });
        });
    }

    initializeSearch() {
        const filterChips = document.querySelectorAll('.filter-chip');
        filterChips.forEach(chip => {
            chip.addEventListener('click', () => {
                filterChips.forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                this.filterResults(chip.dataset.filter);
            });
        });
    }

    initializeMobileMenu() {
        const menuToggle = document.createElement('button');
        menuToggle.className = 'mobile-menu-toggle';
        menuToggle.innerHTML = '☰';
        menuToggle.style.cssText = `
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        `;

        const headerLeft = document.querySelector('.header-left');
        if (headerLeft) {
            headerLeft.prepend(menuToggle);
        }

        menuToggle.addEventListener('click', () => {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
        });

        // Show menu toggle on mobile
        const checkMobile = () => {
            if (window.innerWidth <= 768) {
                menuToggle.style.display = 'block';
            } else {
                menuToggle.style.display = 'none';
                document.querySelector('.sidebar').classList.remove('open');
            }
        };

        window.addEventListener('resize', checkMobile);
        checkMobile();
    }

    handleReaction(button) {
        const postId = button.closest('.post').dataset.postId;
        const reactionType = button.dataset.reaction;
        
        button.classList.toggle('liked');
        
        // Update reaction count
        const countSpan = button.querySelector('.reaction-count');
        if (countSpan) {
            let count = parseInt(countSpan.textContent) || 0;
            count = button.classList.contains('liked') ? count + 1 : count - 1;
            countSpan.textContent = count;
        }

        // Animate button
        button.style.transform = 'scale(1.1)';
        setTimeout(() => {
            button.style.transform = 'scale(1)';
        }, 150);

        this.saveReaction(postId, reactionType, button.classList.contains('liked'));
    }

    handlePostAction(actionType) {
        switch(actionType) {
            case 'photo':
                this.uploadPhoto();
                break;
            case 'feeling':
                this.addFeeling();
                break;
            case 'checkin':
                this.addLocation();
                break;
            default:
                // Unknown action type
                break;
        }
    }

    showPostModal() {
        // Create and show post creation modal
        const modal = document.createElement('div');
        modal.className = 'post-modal';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Tạo bài viết</h3>
                    <button class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="post-creator-modal">
                        <div class="user-info">
                            <div class="user-avatar"></div>
                            <span class="user-name">Người dùng</span>
                        </div>
                        <textarea class="post-content-input" placeholder="Bạn đang nghĩ gì?"></textarea>
                        <div class="post-options">
                            <button class="option-btn" data-option="photo">📷 Ảnh/Video</button>
                            <button class="option-btn" data-option="feeling">😊 Cảm xúc</button>
                            <button class="option-btn" data-option="location">📍 Địa điểm</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary create-post-btn">Đăng bài</button>
                </div>
            </div>
        `;

        // Add modal styles
        const modalStyles = `
            .post-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 10000;
            }
            .modal-content {
                background: white;
                border-radius: 12px;
                width: 90%;
                max-width: 500px;
                max-height: 80vh;
                overflow-y: auto;
            }
            .modal-header {
                padding: 20px;
                border-bottom: 1px solid #e4e6ea;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .modal-close {
                background: none;
                border: none;
                font-size: 24px;
                cursor: pointer;
                color: #65676b;
            }
            .post-creator-modal {
                padding: 20px;
            }
            .user-info {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 15px;
            }
            .user-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background-color: #ddd;
            }
            .post-content-input {
                width: 100%;
                min-height: 120px;
                border: none;
                outline: none;
                font-size: 16px;
                resize: vertical;
                font-family: inherit;
            }
            .post-options {
                display: flex;
                gap: 10px;
                margin-top: 15px;
                flex-wrap: wrap;
            }
            .option-btn {
                padding: 8px 15px;
                border: 1px solid #ddd;
                border-radius: 20px;
                background: white;
                cursor: pointer;
                transition: background-color 0.2s;
            }
            .option-btn:hover {
                background-color: #f0f2f5;
            }
            .modal-footer {
                padding: 15px 20px;
                border-top: 1px solid #e4e6ea;
            }
        `;

        // Add styles to head if not already added
        if (!document.querySelector('#modal-styles')) {
            const styleSheet = document.createElement('style');
            styleSheet.id = 'modal-styles';
            styleSheet.textContent = modalStyles;
            document.head.appendChild(styleSheet);
        }

        document.body.appendChild(modal);

        // Bind modal events
        modal.querySelector('.modal-close').addEventListener('click', () => {
            document.body.removeChild(modal);
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                document.body.removeChild(modal);
            }
        });

        modal.querySelector('.create-post-btn').addEventListener('click', () => {
            const content = modal.querySelector('.post-content-input').value;
            if (content.trim()) {
                this.createPost(content);
                document.body.removeChild(modal);
            }
        });

        // Focus on textarea
        modal.querySelector('.post-content-input').focus();
    }

    createPost(content) {
        const post = {
            id: Date.now(),
            content: content,
            author: 'Người dùng hiện tại',
            timestamp: new Date(),
            likes: 0,
            comments: 0,
            shares: 0
        };

        this.posts.unshift(post);
        this.renderPosts();
        this.showNotification('Đã đăng bài thành công!');
    }

    loadPosts() {
        // Load sample posts
        this.posts = [
            {
                id: 1,
                author: 'Nguyễn Văn A',
                avatar: '',
                content: 'Chào mừng đến với mạng xã hội của chúng tôi! 🎉',
                timestamp: new Date(Date.now() - 1000 * 60 * 30), // 30 minutes ago
                likes: 15,
                comments: 3,
                shares: 2,
                image: null
            },
            {
                id: 2,
                author: 'Trần Thị B',
                avatar: '',
                content: 'Hôm nay thật là một ngày tuyệt vời! Hy vọng mọi người cũng có một ngày vui vẻ như mình. 😊',
                timestamp: new Date(Date.now() - 1000 * 60 * 60 * 2), // 2 hours ago
                likes: 8,
                comments: 1,
                shares: 0,
                image: null
            },
            {
                id: 3,
                author: 'Lê Minh C',
                avatar: '',
                content: 'Chia sẻ một số tips học lập trình hiệu quả:\n1. Luyện tập thường xuyên\n2. Tham gia cộng đồng\n3. Xây dựng dự án thực tế',
                timestamp: new Date(Date.now() - 1000 * 60 * 60 * 5), // 5 hours ago
                likes: 23,
                comments: 7,
                shares: 5,
                image: null
            }
        ];

        this.renderPosts();
    }

    renderPosts() {
        const postsContainer = document.querySelector('.posts-container');
        if (!postsContainer) return;

        postsContainer.innerHTML = this.posts.map(post => `
            <div class="card">
                <div class="card-body">
                    <div class="post" data-post-id="${post.id}">
                        <div class="post-header">
                            <div class="post-avatar"></div>
                            <div class="post-info">
                                <h4>${post.author}</h4>
                                <div class="post-time">${this.formatTime(post.timestamp)}</div>
                            </div>
                        </div>
                        <div class="post-content">${post.content}</div>
                        ${post.image ? `<img src="${post.image}" alt="Post image" class="post-image">` : ''}
                        <div class="post-stats">
                            <span>${post.likes} lượt thích</span>
                            <span>${post.comments} bình luận • ${post.shares} chia sẻ</span>
                        </div>
                        <div class="post-reactions">
                            <button class="reaction-btn" data-reaction="like">
                                👍 Thích <span class="reaction-count">${post.likes}</span>
                            </button>
                            <button class="reaction-btn" data-reaction="comment">
                                💬 Bình luận
                            </button>
                            <button class="reaction-btn" data-reaction="share">
                                📤 Chia sẻ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    formatTime(timestamp) {
        const now = new Date();
        const diff = now - timestamp;
        const minutes = Math.floor(diff / (1000 * 60));
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));

        if (minutes < 1) return 'Vừa xong';
        if (minutes < 60) return `${minutes} phút trước`;
        if (hours < 24) return `${hours} giờ trước`;
        return `${days} ngày trước`;
    }

    performSearch(query) {
        if (query.length < 2) return;

        // Simulate search delay
        setTimeout(() => {
            const results = this.mockSearchResults(query);
            this.displaySearchResults(results);
        }, 300);
    }

    mockSearchResults(query) {
        const mockData = [
            { type: 'user', name: 'Nguyễn Văn A', username: '@nguyenvana', avatar: '' },
            { type: 'user', name: 'Trần Thị B', username: '@tranthib', avatar: '' },
            { type: 'post', content: 'Bài viết về ' + query, author: 'Lê Minh C' },
            { type: 'group', name: 'Nhóm ' + query, members: 150 }
        ];

        return mockData.filter(item => 
            item.name?.toLowerCase().includes(query.toLowerCase()) ||
            item.content?.toLowerCase().includes(query.toLowerCase())
        );
    }

    displaySearchResults(results) {
        const resultsContainer = document.querySelector('.search-results');
        if (!resultsContainer) return;

        if (results.length === 0) {
            resultsContainer.innerHTML = '<p>Không tìm thấy kết quả nào.</p>';
            return;
        }

        resultsContainer.innerHTML = results.map(result => `
            <div class="search-result-item">
                <div class="result-avatar"></div>
                <div class="result-info">
                    <h4>${result.name || result.content}</h4>
                    <div class="result-details">
                        ${result.username || result.author || `${result.members} thành viên`}
                    </div>
                </div>
            </div>
        `).join('');
    }

    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            background: ${type === 'success' ? '#4a7c59' : '#e74c3c'};
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 10001;
            animation: slideInRight 0.3s ease-out;
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    saveReaction(postId, reactionType, isLiked) {
        // In real app, this would make API call
        // Store reaction state for demo purposes
        const reactions = JSON.parse(localStorage.getItem('reactions') || '{}');
        reactions[`${postId}_${reactionType}`] = isLiked;
        localStorage.setItem('reactions', JSON.stringify(reactions));
    }

    checkAuthStatus() {
        // Check if user is logged in
        const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
        if (!isLoggedIn && !window.location.pathname.includes('login')) {
            // Redirect to login if not authenticated
            // window.location.href = 'login.html';
        }
    }

    uploadPhoto() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*,video/*';
        input.onchange = (e) => {
            const file = e.target.files[0];
            if (file) {
                this.showNotification('Đang tải ảnh lên...');
                // Simulate upload delay
                setTimeout(() => {
                    this.showNotification('Đã tải ảnh lên thành công!');
                }, 2000);
            }
        };
        input.click();
    }

    addFeeling() {
        const feelings = ['😊 Vui vẻ', '😍 Yêu thích', '😎 Tự tin', '🤔 Suy nghĩ', '😴 Buồn ngủ'];
        const selectedFeeling = feelings[Math.floor(Math.random() * feelings.length)];
        this.showNotification(`Đã thêm cảm xúc: ${selectedFeeling}`);
    }

    addLocation() {
        this.showNotification('Đang lấy vị trí...');
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.showNotification('Đã thêm vị trí thành công!');
                },
                (error) => {
                    this.showNotification('Không thể lấy vị trí', 'error');
                }
            );
        } else {
            this.showNotification('Trình duyệt không hỗ trợ định vị', 'error');
        }
    }
}

// Form validation utilities
class FormValidator {
    static validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    static validatePassword(password) {
        return password.length >= 6;
    }

    static validateRequired(value) {
        return value.trim().length > 0;
    }

    static showError(input, message) {
        const errorDiv = input.parentNode.querySelector('.error-message') || document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        errorDiv.style.cssText = 'color: #e74c3c; font-size: 14px; margin-top: 5px;';
        
        if (!input.parentNode.querySelector('.error-message')) {
            input.parentNode.appendChild(errorDiv);
        }
        
        input.style.borderColor = '#e74c3c';
    }

    static clearError(input) {
        const errorDiv = input.parentNode.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.remove();
        }
        input.style.borderColor = '#ddd';
    }
}

// Login functionality
class LoginManager {
    static handleLogin(email, password) {
        // Simulate login process
        if (FormValidator.validateEmail(email) && FormValidator.validatePassword(password)) {
            localStorage.setItem('isLoggedIn', 'true');
            localStorage.setItem('userEmail', email);
            window.location.href = 'home.html';
            return true;
        }
        return false;
    }

    static handleLogout() {
        localStorage.removeItem('isLoggedIn');
        localStorage.removeItem('userEmail');
        window.location.href = 'login.html';
    }
}

// Initialize the application
const app = new SocialNetwork();

// Add notification animations to CSS
const animationStyles = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = animationStyles;
document.head.appendChild(styleSheet);