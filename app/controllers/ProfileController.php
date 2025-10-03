<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Helpers.php';

class ProfileController {
    public function index() {
        ensure_session_started();
        $user = null;
        if (!empty($_SESSION['user_id'])) {
            $user = (new User())->findById((int)$_SESSION['user_id']);
        }
        $name = $user?->username ?? 'Khách';
        $email = $user?->email ?? 'guest@example.com';
        $avatar = $user?->avatar ?? 'https://i.pravatar.cc/200';

        include __DIR__ . '/../views/pages/profile/profile.php';
    }
}
