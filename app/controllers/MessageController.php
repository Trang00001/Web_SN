<?php
class MessageController {
    public function index() {
        $title = "Tin nhắn";
        require __DIR__ . '/../views/pages/messages/index.php';
    }
    
    public function chat($id = null) {
        require __DIR__ . '/../views/pages/messages/chat.php';
    }
}
