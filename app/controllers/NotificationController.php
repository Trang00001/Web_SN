<?php
require_once __DIR__ . '/../models/Notification.php';

class NotificationController {
    // Lấy danh sách thông báo cho user đang đăng nhập
    public function fetch() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Not logged in']);
            return;
        }

        $notif = new Notification($_SESSION['user_id']);
        $data = $notif->getAll();

        echo json_encode($data);
    }

    // Đánh dấu đã đọc
    public function markAsRead() {
        if (!isset($_POST['notification_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing notification ID']);
            return;
        }

        $notif = new Notification(null);
        $notif->setNotificationID($_POST['notification_id']);
        $notif->markAsRead();

        echo json_encode(['success' => true]);
    }

    // (Tùy chọn) Xóa thông báo
    public function delete() {
        if (!isset($_POST['notification_id'])) return;

        $notif = new Notification(null);
        $notif->setNotificationID($_POST['notification_id']);
        $notif->delete();

        echo json_encode(['success' => true]);
    }
}
