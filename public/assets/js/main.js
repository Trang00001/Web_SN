// Main JavaScript file for Social Network

class SocialNetwork {
    constructor() {
        this.currentUser = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.checkAuthStatus();
    }

    bindEvents() {
        // Navigation events
        document.addEventListener('DOMContentLoaded', () => {
            this.initializeNavigation();
            this.initializeSearch();
            this.initializeMobileMenu();
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

    // ========================================
    // UTILITY FUNCTIONS - Format & Helper
    // ========================================

    // ========================================
    // UTILITY FUNCTIONS - Format & Helper
    // ========================================

    /**
     * Format timestamp to human-readable format
     * @param {Date} timestamp - The timestamp to format
     * @return {string} Formatted time string
     */
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

    // ========================================
    // SEARCH FUNCTIONALITY
    // ========================================

    // ========================================
    // SEARCH FUNCTIONALITY
    // ========================================

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

    // ========================================
    // NOTIFICATION SYSTEM
    // ========================================

    // ========================================
    // NOTIFICATION SYSTEM
    // ========================================

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

    // ========================================
    // POST ACTIONS - Upload, Feeling, Location
    // ========================================

    /**
     * Upload photo/video for post
     * TODO: Implement actual file upload to server
     */
    uploadPhoto() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*,video/*';
        input.onchange = (e) => {
            const file = e.target.files[0];
            if (file) {
                this.showNotification('Đang tải ảnh lên...');
                // TODO: Implement actual upload
                // const formData = new FormData();
                // formData.append('file', file);
                // fetch('/api/upload', { method: 'POST', body: formData })
                
                setTimeout(() => {
                    this.showNotification('Đã tải ảnh lên thành công!');
                }, 2000);
            }
        };
        input.click();
    }

    /**
     * Add feeling/emotion to post
     * TODO: Implement feeling selector UI
     */
    addFeeling() {
        const feelings = ['😊 Vui vẻ', '😍 Yêu thích', '😎 Tự tin', '🤔 Suy nghĩ', '😴 Buồn ngủ'];
        const selectedFeeling = feelings[Math.floor(Math.random() * feelings.length)];
        this.showNotification(`Đã thêm cảm xúc: ${selectedFeeling}`);
        // TODO: Implement actual feeling selector modal
    }

    /**
     * Add location to post
     * TODO: Implement location picker with map
     */
    addLocation() {
        this.showNotification('Đang lấy vị trí...');
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.showNotification('Đã thêm vị trí thành công!');
                    // TODO: Reverse geocoding to get place name
                },
                (error) => {
                    this.showNotification('Không thể lấy vị trí', 'error');
                }
            );
        } else {
            this.showNotification('Trình duyệt không hỗ trợ định vị', 'error');
        }
    }

    // ========================================
    // STATE MANAGEMENT
    // ========================================
    // ========================================
    // STATE MANAGEMENT
    // ========================================

    saveReaction(postId, reactionType, isLiked) {
        // TODO: Replace with actual API call
        // For now, store in localStorage for demo
        const reactions = JSON.parse(localStorage.getItem('reactions') || '{}');
        reactions[`${postId}_${reactionType}`] = isLiked;
        localStorage.setItem('reactions', JSON.stringify(reactions));
    }

    checkAuthStatus() {
        // Check if user is logged in
        const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
        if (!isLoggedIn && !window.location.pathname.includes('login')) {
            // TODO: Redirect to login if not authenticated
            // window.location.href = 'login.html';
        }
    }
}

// ============================================
// FORM VALIDATION UTILITIES
// ============================================

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

// ============================================
// LOGIN MANAGEMENT
// ============================================
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