<?php
/**
 * notification.php
 * Trang hiển thị thông báo đơn giản và tự động chuyển hướng nếu cần
 * 
 * Biến nhận:
 * - $msg: nội dung thông báo
 * - $isSuccess: true/false (để đổi màu)
 * - $redirect: URL để chuyển hướng (hoặc null)
 */

if (!isset($msg)) $msg = "Không có thông báo.";
if (!isset($isSuccess)) $isSuccess = false;
if (!isset($redirect)) $redirect = null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông báo</title>
    <style>
        body {
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            animation: fadeIn 0.8s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .box {
            background: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
            animation: fadeIn 0.8s ease;
        }
        .msg {
            font-size: 16px;
            color: <?= $isSuccess ? '#28a745' : '#dc3545' ?>;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            background: <?= $isSuccess ? '#28a745' : '#007bff' ?>;
            color: white;
            font-weight: 500;
            transition: 0.2s;
        }
        .btn:hover { opacity: 0.9; }
    </style>
</head>
<body>
    <div class="box">
        <p class="msg"><?= htmlspecialchars($msg) ?></p>
        <?php if ($isSuccess): ?>
            <p>⏳ Vui lòng đợi trong giây lát...</p>
        <?php else: ?>
            <a href="/auth/forgot" class="btn">← Quay lại</a>
        <?php endif; ?>
    </div>

    <?php if ($redirect): ?>
        <script>
            setTimeout(() => {
                window.location.href = "<?= $redirect ?>";
            }, 5000);
        </script>
    <?php endif; ?>
</body>
</html>
