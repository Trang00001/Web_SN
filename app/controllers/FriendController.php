<?php
class FriendController {
    public function index() {
        $title = "Bạn bè";
        require __DIR__ . '/../views/pages/friends/index.php';
    }
    
    public function requests() {
        require __DIR__ . '/../views/pages/friends/requests.php';
    }
}
