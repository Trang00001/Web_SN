<?php
/**
 * Authentication API Handler
 * Handles login, register, logout actions
 */

require_once __DIR__ . '/../controllers/AuthController.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$auth = new AuthController();

switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->login();
        } else {
            $auth->showLogin();
        }
        break;
        
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->register();
        } else {
            $auth->showRegister();
        }
        break;
        
    case 'logout':
        $auth->logout();
        break;
        
    case 'forgot':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->resetPassword();
        } else {
            $auth->showForgot();
        }
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Action not found']);
        break;
}
