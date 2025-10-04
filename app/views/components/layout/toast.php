<?php
/**
 * Toast notification component
 */
?>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11000;">
    <!-- Success Toast Template -->
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                <span class="toast-message">Thành công!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>

    <!-- Error Toast Template -->
    <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-exclamation-circle me-2"></i>
                <span class="toast-message">Có lỗi xảy ra!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>

    <!-- Info Toast Template -->
    <div id="infoToast" class="toast align-items-center text-white bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-info-circle me-2"></i>
                <span class="toast-message">Thông tin!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>

    <!-- Warning Toast Template -->
    <div id="warningToast" class="toast align-items-center text-white bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <span class="toast-message">Cảnh báo!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
/**
 * Show toast notification
 * @param {string} message - Toast message
 * @param {string} type - Toast type (success, error, info, warning)
 * @param {number} duration - Auto hide duration in milliseconds (default: 3000)
 */
function showToast(message, type = 'success', duration = 3000) {
    const toastId = type + 'Toast';
    const toastElement = document.getElementById(toastId);
    
    if (toastElement) {
        // Update message
        const messageSpan = toastElement.querySelector('.toast-message');
        if (messageSpan) {
            messageSpan.textContent = message;
        }
        
        // Show toast
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: duration
        });
        toast.show();
    }
}

/**
 * Show success toast
 * @param {string} message
 */
function showSuccessToast(message) {
    showToast(message, 'success');
}

/**
 * Show error toast
 * @param {string} message
 */
function showErrorToast(message) {
    showToast(message, 'error');
}

/**
 * Show info toast
 * @param {string} message
 */
function showInfoToast(message) {
    showToast(message, 'info');
}

/**
 * Show warning toast
 * @param {string} message
 */
function showWarningToast(message) {
    showToast(message, 'warning');
}
</script>

<style>
.toast-container .toast {
    margin-bottom: 0.5rem;
    min-width: 300px;
}

.toast {
    --bs-toast-border-radius: 0.5rem;
    --bs-toast-box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.toast .toast-body {
    display: flex;
    align-items: center;
    font-weight: 500;
}

.toast.show {
    animation: slideInRight 0.3s ease-out;
}

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

.toast.hide {
    animation: slideOutRight 0.3s ease-in;
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
</style>

