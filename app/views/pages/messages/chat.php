<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Chat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
  <h3 class="mb-4">Cuộc trò chuyện với Nguyễn Văn A</h3>
  
  <!-- Khung chat -->
  <div class="card shadow-sm">
    <div class="card-body" style="height: 400px; overflow-y: auto; background-color: #f8f9fa;">
      <!-- Tin nhắn demo -->
      <div class="d-flex mb-3">
        <img src="https://i.pravatar.cc/40?img=1" class="rounded-circle me-2" width="40" height="40">
        <div>
          <div class="bg-white p-2 rounded shadow-sm">Xin chào!</div>
          <small class="text-muted">10:30 AM</small>
        </div>
      </div>

      <div class="d-flex mb-3 justify-content-end">
        <div>
          <div class="bg-primary text-white p-2 rounded shadow-sm">Chào bạn!</div>
          <small class="text-muted d-block text-end">10:32 AM</small>
        </div>
      </div>
    </div>
    
    <!-- Nhập tin nhắn -->
    <div class="card-footer">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Nhập tin nhắn...">
        <button class="btn btn-primary">Gửi</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
