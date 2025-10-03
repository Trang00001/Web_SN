<?php<?php<?php

/**

 * Xử lý tạo bài viết mới từ modal popup/**/**

 */

session_start(); * Xử lý tạo bài viết mới * Xử lý tạo bài viết mới



if ($_SERVER['REQUEST_METHOD'] === 'POST') { */ */

    $content = trim($_POST['content'] ?? '');

    session_start();session_start();

    if (!empty($content)) {

        // TODO: Lưu bài viết vào database

        // $postId = savePost($content, $_FILES['image'] ?? null);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $_SESSION['success_message'] = 'Đăng bài viết thành công!';

    } else {    $content = trim($_POST['content'] ?? '');    $content = trim($_POST['content'] ?? '');

        $_SESSION['error_message'] = 'Vui lòng nhập nội dung bài viết';

    }        

}

    if (!empty($content)) {    if (!empty($content)) {

// Redirect về trang home

header('Location: ../../home.html');        // TODO: Lưu bài viết vào database        // TODO: Lưu bài viết vào database

exit;

?>        // $postId = savePost($content, $_FILES['image'] ?? null);        // $postId = savePost($content, $_FILES['image'] ?? null);

                

        // Hiển thị thông báo thành công và redirect        // Hiển thị thông báo thành công và redirect

        $_SESSION['success_message'] = 'Đăng bài viết thành công!';        $_SESSION['success_message'] = 'Đăng bài viết thành công!';

    } else {    } else {

        $_SESSION['error_message'] = 'Vui lòng nhập nội dung bài viết';        $_SESSION['error_message'] = 'Vui lòng nhập nội dung bài viết';

    }    }

}}



// Redirect về trang home// Redirect về trang home

header('Location: ../../home.html');header('Location: ../../home.html');

exit;exit;

?>?>



// Xử lý form submit// if (!isset($_SESSION['user_id'])) {

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $content = trim($_POST['content'] ?? '');//     header('Location: ../auth/login.php');// Kiểm tra đăng nhập

    

    if (empty($content)) {//     exit;if (!isset($_SESSION['user_id'])) {

        $error = 'Vui lòng nhập nội dung bài viết';

    } else {// }    header('Location: /auth/login.php');

        // Giả lập tạo bài viết thành công

        $message = 'Đăng bài viết thành công!';    exit;

        // Trong thực tế sẽ lưu vào database

    }$message = '';}

}

?>$error = '';

<!DOCTYPE html>

<html lang="vi">$message = '';

<head>

    <meta charset="UTF-8">// Xử lý form submit$error = '';

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tạo bài viết - Social Network</title>if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    <!-- Bootstrap 5 CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">    $content = trim($_POST['content'] ?? '');// Xử lý form submit

    <!-- Font Awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    <!-- Custom CSS -->

    <link rel="stylesheet" href="../../../public/assets/css/bootstrap-custom.css">    if (empty($content)) {    $postController = new PostController();

    <link rel="stylesheet" href="../../../public/assets/css/posts.css">

</head>        $error = 'Vui lòng nhập nội dung bài viết';    $result = $postController->createPost();

<body>

    <!-- Navigation -->    } else {    

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">

        <div class="container-fluid">        // Giả lập tạo bài viết thành công    if ($result['success']) {

            <a class="navbar-brand" href="../home.html">

                <i class="fas fa-users me-2"></i>Social Network        $message = 'Đăng bài viết thành công!';        $message = $result['message'];

            </a>

                    // Trong thực tế sẽ lưu vào database        // Redirect về home sau 2 giây

            <div class="d-flex align-items-center">

                <a href="../home.html" class="btn btn-outline-light me-2">    }        header("refresh:2;url=/posts/home.php");

                    <i class="fas fa-home me-1"></i>Về trang chủ

                </a>}    } else {

                <div class="dropdown">

                    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">?>        $error = $result['message'];

                        <i class="fas fa-user me-1"></i>Tài khoản

                    </button>    }

                    <ul class="dropdown-menu">

                        <li><a class="dropdown-item" href="../profile.html">Hồ sơ</a></li><!DOCTYPE html>}

                        <li><a class="dropdown-item" href="../auth/login.php">Đăng xuất</a></li>

                    </ul><html lang="vi">?>

                </div>

            </div><head>

        </div>

    </nav>    <meta charset="UTF-8"><!DOCTYPE html>



    <div class="container mt-5 pt-4">    <meta name="viewport" content="width=device-width, initial-scale=1.0"><html lang="vi">

        <div class="row justify-content-center">

            <div class="col-md-8">    <title>Tạo bài viết - Social Network</title><head>

                <!-- Page Header -->

                <div class="card card-custom mb-4">    <!-- Bootstrap 5 CSS -->    <meta charset="UTF-8">

                    <div class="card-header">

                        <h4 class="mb-0">    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">    <meta name="viewport" content="width=device-width, initial-scale=1.0">

                            <i class="fas fa-edit me-2"></i>Tạo bài viết mới

                        </h4>    <!-- Font Awesome -->    <title>Tạo bài viết - Social Network</title>

                    </div>

                </div>    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">



                <!-- Success/Error Messages -->    <!-- Custom CSS -->    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

                <?php if ($message): ?>

                    <div class="alert alert-success alert-dismissible fade show" role="alert">    <link rel="stylesheet" href="../../../public/assets/css/bootstrap-custom.css">    <link href="/assets/css/posts.css" rel="stylesheet">

                        <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($message) ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>    <link rel="stylesheet" href="../../../public/assets/css/posts.css">    <link href="/assets/css/global.css" rel="stylesheet">

                    </div>

                <?php endif; ?></head></head>



                <?php if ($error): ?><body><body>

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">

                        <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>    <!-- Navigation -->    <!-- Include Navbar -->

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                    </div>    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">    <?php include __DIR__ . '/../components/layout/navbar.php'; ?>

                <?php endif; ?>

        <div class="container-fluid">

                <!-- Create Post Form -->

                <div class="card card-custom">            <a class="navbar-brand" href="../home.html">    <div class="container mt-4">

                    <div class="card-body">

                        <form method="POST" enctype="multipart/form-data">                <i class="fas fa-users me-2"></i>Social Network        <div class="row">

                            <!-- User Info -->

                            <div class="d-flex align-items-center mb-4">            </a>            <div class="col-md-8 offset-md-2">

                                <div class="avatar-md me-3">

                                    <i class="fas fa-user text-white"></i>                            <div class="card">

                                </div>

                                <div>            <div class="d-flex align-items-center">                    <div class="card-header">

                                    <h6 class="mb-0">Người dùng hiện tại</h6>

                                    <small class="text-muted">Công khai</small>                <a href="../home.html" class="btn btn-outline-light me-2">                        <div class="d-flex align-items-center justify-content-between">

                                </div>

                            </div>                    <i class="fas fa-home me-1"></i>Về trang chủ                            <h5 class="mb-0">



                            <!-- Content Input -->                </a>                                <i class="fas fa-plus me-2"></i>

                            <div class="mb-4">

                                <label for="content" class="form-label">Nội dung bài viết</label>                <div class="dropdown">                                Tạo bài viết mới

                                <textarea 

                                    id="content"                     <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">                            </h5>

                                    name="content" 

                                    class="form-control"                         <i class="fas fa-user me-1"></i>Tài khoản                            <a href="/posts/home.php" class="btn btn-outline-secondary btn-sm">

                                    rows="6" 

                                    placeholder="Bạn đang nghĩ gì? Chia sẻ với mọi người..."                    </button>                                <i class="fas fa-times me-1"></i>

                                    required

                                ><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>                    <ul class="dropdown-menu">                                Hủy

                                <div class="form-text">Hãy chia sẻ những suy nghĩ tích cực của bạn!</div>

                            </div>                        <li><a class="dropdown-item" href="../profile.html">Hồ sơ</a></li>                            </a>



                            <!-- Media Upload -->                        <li><a class="dropdown-item" href="../auth/login.php">Đăng xuất</a></li>                        </div>

                            <div class="mb-4">

                                <label for="media" class="form-label">                    </ul>                    </div>

                                    <i class="fas fa-image me-1"></i>Đính kèm ảnh/video (tùy chọn)

                                </label>                </div>                    

                                <input type="file" class="form-control" id="media" name="media" accept="image/*,video/*">

                                <div class="form-text">Hỗ trợ: JPG, PNG, GIF, MP4 (tối đa 10MB)</div>            </div>                    <div class="card-body">

                            </div>

        </div>                        <!-- Success/Error Messages -->

                            <!-- Post Options -->

                            <div class="row mb-4">    </nav>                        <?php if ($message): ?>

                                <div class="col-md-6">

                                    <div class="card border-0 bg-light">                            <div class="alert alert-success">

                                        <div class="card-body p-3">

                                            <h6 class="card-title mb-2">    <div class="container mt-5 pt-4">                                <i class="fas fa-check-circle me-2"></i>

                                                <i class="fas fa-cog me-1"></i>Tùy chọn bài viết

                                            </h6>        <div class="row justify-content-center">                                <?= htmlspecialchars($message) ?>

                                            <div class="form-check">

                                                <input class="form-check-input" type="checkbox" id="allow_comments" name="allow_comments" checked>            <div class="col-md-8">                                <br><small>Đang chuyển hướng về trang chủ...</small>

                                                <label class="form-check-label" for="allow_comments">

                                                    Cho phép bình luận                <!-- Page Header -->                            </div>

                                                </label>

                                            </div>                <div class="card card-custom mb-4">                        <?php endif; ?>

                                            <div class="form-check">

                                                <input class="form-check-input" type="checkbox" id="allow_shares" name="allow_shares" checked>                    <div class="card-header">                        

                                                <label class="form-check-label" for="allow_shares">

                                                    Cho phép chia sẻ                        <h4 class="mb-0">                        <?php if ($error): ?>

                                                </label>

                                            </div>                            <i class="fas fa-edit me-2"></i>Tạo bài viết mới                            <div class="alert alert-danger">

                                        </div>

                                    </div>                        </h4>                                <i class="fas fa-exclamation-circle me-2"></i>

                                </div>

                                <div class="col-md-6">                    </div>                                <?= htmlspecialchars($error) ?>

                                    <div class="card border-0 bg-light">

                                        <div class="card-body p-3">                </div>                            </div>

                                            <h6 class="card-title mb-2">

                                                <i class="fas fa-users me-1"></i>Quyền riêng tư                        <?php endif; ?>

                                            </h6>

                                            <select class="form-select" name="privacy">                <!-- Success/Error Messages -->

                                                <option value="public">Công khai</option>

                                                <option value="friends">Bạn bè</option>                <?php if ($message): ?>                        <!-- User Info -->

                                                <option value="private">Chỉ mình tôi</option>

                                            </select>                    <div class="alert alert-success alert-dismissible fade show" role="alert">                        <div class="d-flex align-items-center mb-4">

                                        </div>

                                    </div>                        <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($message) ?>                            <div class="post-avatar me-3">

                                </div>

                            </div>                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">



                            <!-- Quick Actions -->                    </div>                                    <span class="text-white fw-bold fs-5"><?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)) ?></span>

                            <div class="d-flex gap-2 flex-wrap mb-4">

                                <button type="button" class="btn btn-outline-success btn-sm">                <?php endif; ?>                                </div>

                                    <i class="fas fa-smile me-1"></i>Thêm cảm xúc

                                </button>                            </div>

                                <button type="button" class="btn btn-outline-warning btn-sm">

                                    <i class="fas fa-map-marker-alt me-1"></i>Thêm địa điểm                <?php if ($error): ?>                            <div>

                                </button>

                                <button type="button" class="btn btn-outline-info btn-sm">                    <div class="alert alert-danger alert-dismissible fade show" role="alert">                                <h6 class="mb-0"><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></h6>

                                    <i class="fas fa-users me-1"></i>Tag bạn bè

                                </button>                        <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>                                <small class="text-muted">

                            </div>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>                                    <i class="fas fa-globe me-1"></i>

                            <!-- Submit Buttons -->

                            <div class="d-flex gap-2 justify-content-end">                    </div>                                    Công khai

                                <a href="../home.html" class="btn btn-outline-secondary">

                                    <i class="fas fa-times me-1"></i>Hủy                <?php endif; ?>                                </small>

                                </a>

                                <button type="submit" class="btn btn-primary-custom">                            </div>

                                    <i class="fas fa-paper-plane me-1"></i>Đăng bài viết

                                </button>                <!-- Create Post Form -->                        </div>

                            </div>

                        </form>                <div class="card card-custom">

                    </div>

                </div>                    <div class="card-body">                        <!-- Create Post Form -->



                <!-- Tips -->                        <form method="POST" enctype="multipart/form-data">                        <form method="POST" enctype="multipart/form-data" id="createPostForm">

                <div class="card card-custom mt-4">

                    <div class="card-body">                            <!-- User Info -->                            <div class="mb-4">

                        <h6 class="card-title">

                            <i class="fas fa-lightbulb me-1"></i>Mẹo viết bài hay                            <div class="d-flex align-items-center mb-4">                                <textarea class="form-control border-0 fs-5" 

                        </h6>

                        <ul class="mb-0 small text-muted">                                <div class="avatar-md me-3">                                          name="content" 

                            <li>Chia sẻ những câu chuyện thú vị từ cuộc sống</li>

                            <li>Sử dụng hashtag để tăng tương tác</li>                                    <i class="fas fa-user text-white"></i>                                          rows="6" 

                            <li>Đăng vào thời gian nhiều người online</li>

                            <li>Tránh nội dung tiêu cực hoặc spam</li>                                </div>                                          placeholder="Bạn đang nghĩ gì?"

                        </ul>

                    </div>                                <div>                                          required

                </div>

            </div>                                    <h6 class="mb-0">Người dùng hiện tại</h6>                                          style="resize: none; min-height: 150px;"></textarea>

        </div>

    </div>                                    <small class="text-muted">Công khai</small>                            </div>



    <!-- Bootstrap 5 JS -->                                </div>                            

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

                                </div>                            <!-- Media Upload -->

    <!-- Custom JS -->

    <script>                            <div class="mb-4">

        // Auto-resize textarea

        document.getElementById('content').addEventListener('input', function() {                            <!-- Content Input -->                                <div class="border rounded p-3">

            this.style.height = 'auto';

            this.style.height = this.scrollHeight + 'px';                            <div class="mb-4">                                    <div class="d-flex align-items-center mb-3">

        });

                                <label for="content" class="form-label">Nội dung bài viết</label>                                        <i class="fas fa-image text-success me-2"></i>

        // Preview uploaded image

        document.getElementById('media').addEventListener('change', function(e) {                                <textarea                                         <span class="fw-bold">Thêm vào bài viết của bạn</span>

            const file = e.target.files[0];

            if (file && file.type.startsWith('image/')) {                                    id="content"                                     </div>

                const reader = new FileReader();

                reader.onload = function(e) {                                    name="content"                                     

                    console.log('Image selected:', file.name);

                };                                    class="form-control"                                     <div class="mb-3">

                reader.readAsDataURL(file);

            }                                    rows="6"                                         <label for="mediaFile" class="form-label">Chọn ảnh/video</label>

        });

                                    placeholder="Bạn đang nghĩ gì? Chia sẻ với mọi người..."                                        <input type="file" 

        // Success message auto-redirect

        <?php if ($message): ?>                                    required                                               class="form-control" 

        setTimeout(function() {

            window.location.href = '../home.html';                                ><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>                                               name="media" 

        }, 2000);

        <?php endif; ?>                                <div class="form-text">Hãy chia sẻ những suy nghĩ tích cực của bạn!</div>                                               id="mediaFile"

    </script>

</body>                            </div>                                               accept="image/*,video/*"

</html>
                                               onchange="previewMedia(this)">

                            <!-- Media Upload -->                                        <small class="text-muted">Hỗ trợ: JPG, PNG, GIF, MP4 (tối đa 10MB)</small>

                            <div class="mb-4">                                    </div>

                                <label for="media" class="form-label">                                    

                                    <i class="fas fa-image me-1"></i>Đính kèm ảnh/video (tùy chọn)                                    <!-- Media Preview -->

                                </label>                                    <div id="mediaPreview" class="mt-3" style="display: none;">

                                <input type="file" class="form-control" id="media" name="media" accept="image/*,video/*">                                        <div class="position-relative d-inline-block">

                                <div class="form-text">Hỗ trợ: JPG, PNG, GIF, MP4 (tối đa 10MB)</div>                                            <img id="previewImg" class="img-fluid rounded" style="max-height: 300px; max-width: 100%;">

                            </div>                                            <video id="previewVideo" class="img-fluid rounded" style="max-height: 300px; max-width: 100%;" controls>

                                                <source id="videoSource" type="">

                            <!-- Post Options -->                                            </video>

                            <div class="row mb-4">                                            <button type="button" 

                                <div class="col-md-6">                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2"

                                    <div class="card border-0 bg-light">                                                    onclick="removeMediaPreview()">

                                        <div class="card-body p-3">                                                <i class="fas fa-times"></i>

                                            <h6 class="card-title mb-2">                                            </button>

                                                <i class="fas fa-cog me-1"></i>Tùy chọn bài viết                                        </div>

                                            </h6>                                    </div>

                                            <div class="form-check">                                </div>

                                                <input class="form-check-input" type="checkbox" id="allow_comments" name="allow_comments" checked>                            </div>

                                                <label class="form-check-label" for="allow_comments">                            

                                                    Cho phép bình luận                            <!-- Action Buttons -->

                                                </label>                            <div class="d-grid">

                                            </div>                                <button type="submit" class="btn btn-primary btn-lg">

                                            <div class="form-check">                                    <i class="fas fa-paper-plane me-2"></i>

                                                <input class="form-check-input" type="checkbox" id="allow_shares" name="allow_shares" checked>                                    Đăng bài

                                                <label class="form-check-label" for="allow_shares">                                </button>

                                                    Cho phép chia sẻ                            </div>

                                                </label>                        </form>

                                            </div>                    </div>

                                        </div>                </div>

                                    </div>                

                                </div>                <!-- Quick Tips -->

                                <div class="col-md-6">                <div class="card mt-4">

                                    <div class="card border-0 bg-light">                    <div class="card-body">

                                        <div class="card-body p-3">                        <h6 class="card-title">

                                            <h6 class="card-title mb-2">                            <i class="fas fa-lightbulb text-warning me-2"></i>

                                                <i class="fas fa-users me-1"></i>Quyền riêng tư                            Mẹo viết bài hay

                                            </h6>                        </h6>

                                            <select class="form-select" name="privacy">                        <ul class="mb-0 small text-muted">

                                                <option value="public">Công khai</option>                            <li>Chia sẻ những điều thú vị trong cuộc sống</li>

                                                <option value="friends">Bạn bè</option>                            <li>Sử dụng ảnh để bài viết sinh động hơn</li>

                                                <option value="private">Chỉ mình tôi</option>                            <li>Tương tác với bạn bè qua comments</li>

                                            </select>                            <li>Tôn trọng quyền riêng tư của người khác</li>

                                        </div>                        </ul>

                                    </div>                    </div>

                                </div>                </div>

                            </div>            </div>

        </div>

                            <!-- Quick Actions -->    </div>

                            <div class="d-flex gap-2 flex-wrap mb-4">

                                <button type="button" class="btn btn-outline-success btn-sm">    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

                                    <i class="fas fa-smile me-1"></i>Thêm cảm xúc    <script src="/assets/js/posts.js"></script>

                                </button>    

                                <button type="button" class="btn btn-outline-warning btn-sm">    <script>

                                    <i class="fas fa-map-marker-alt me-1"></i>Thêm địa điểm        // Media preview functionality

                                </button>        function previewMedia(input) {

                                <button type="button" class="btn btn-outline-info btn-sm">            const file = input.files[0];

                                    <i class="fas fa-users me-1"></i>Tag bạn bè            const preview = document.getElementById('mediaPreview');

                                </button>            const previewImg = document.getElementById('previewImg');

                            </div>            const previewVideo = document.getElementById('previewVideo');

            const videoSource = document.getElementById('videoSource');

                            <!-- Submit Buttons -->            

                            <div class="d-flex gap-2 justify-content-end">            if (file) {

                                <a href="../home.html" class="btn btn-outline-secondary">                const reader = new FileReader();

                                    <i class="fas fa-times me-1"></i>Hủy                reader.onload = function(e) {

                                </a>                    preview.style.display = 'block';

                                <button type="submit" class="btn btn-primary-custom">                    

                                    <i class="fas fa-paper-plane me-1"></i>Đăng bài viết                    if (file.type.startsWith('image/')) {

                                </button>                        previewImg.style.display = 'block';

                            </div>                        previewVideo.style.display = 'none';

                        </form>                        previewImg.src = e.target.result;

                    </div>                    } else if (file.type.startsWith('video/')) {

                </div>                        previewImg.style.display = 'none';

                        previewVideo.style.display = 'block';

                <!-- Tips -->                        videoSource.src = e.target.result;

                <div class="card card-custom mt-4">                        videoSource.type = file.type;

                    <div class="card-body">                        previewVideo.load();

                        <h6 class="card-title">                    }

                            <i class="fas fa-lightbulb me-1"></i>Mẹo viết bài hay                };

                        </h6>                reader.readAsDataURL(file);

                        <ul class="mb-0 small text-muted">            } else {

                            <li>Chia sẻ những câu chuyện thú vị từ cuộc sống</li>                preview.style.display = 'none';

                            <li>Sử dụng hashtag để tăng tương tác</li>            }

                            <li>Đăng vào thời gian nhiều người online</li>        }

                            <li>Tránh nội dung tiêu cực hoặc spam</li>        

                        </ul>        function removeMediaPreview() {

                    </div>            document.getElementById('mediaFile').value = '';

                </div>            document.getElementById('mediaPreview').style.display = 'none';

            </div>        }

        </div>    </script>

    </div></body>

</html>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Auto-resize textarea
        document.getElementById('content').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });

        // Preview uploaded image
        document.getElementById('media').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create preview (you can add preview container in HTML)
                    console.log('Image selected:', file.name);
                };
                reader.readAsDataURL(file);
            }
        });

        // Success message auto-redirect
        <?php if ($message): ?>
        setTimeout(function() {
            window.location.href = '../home.html';
        }, 2000);
        <?php endif; ?>
    </script>
</body>
</html>