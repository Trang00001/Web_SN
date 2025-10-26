<!-- Post Detail Modal -->
<div class="modal fade" id="postDetailModal" tabindex="-1" aria-labelledby="postDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="postDetailModalLabel">Chi tiết bài viết</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="postDetailContent">
                <!-- Content will be loaded dynamically -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Đang tải...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Post Detail Modal Styles */
#postDetailModal .modal-lg {
    max-width: 900px;
}

#postDetailModal .modal-body {
    max-height: 80vh;
}

.post-detail-header {
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.post-detail-content {
    padding: 1.5rem;
}

.post-detail-images {
    background: #000;
}

.post-detail-stats {
    padding: 1rem 1.5rem;
    border-top: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
}

.post-detail-actions {
    padding: 0.5rem 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.post-detail-comments {
    padding: 1.5rem;
    max-height: 400px;
    overflow-y: auto;
}

.comment-form {
    padding: 1rem 1.5rem;
    border-top: 1px solid #dee2e6;
    background: #f8f9fa;
}

.comment-item {
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.comment-item:last-child {
    margin-bottom: 0;
}

/* Scrollbar styles */
.post-detail-comments::-webkit-scrollbar {
    width: 6px;
}

.post-detail-comments::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.post-detail-comments::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.post-detail-comments::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
