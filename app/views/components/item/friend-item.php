<?php
$allFriends = [
    "Nguyễn Văn A", "Trần Thị B", "Lê Minh C", "Hoàng Văn D", "Ngô Thị E",
    "Phạm Thị F", "Lý Văn G", "Đặng Thị H", "Vũ Minh I", "Trịnh Thị J",
    "Bùi Văn K", "Nguyễn Thị L"
];
$suggestedFriends = ["Hoàng Văn D", "Đặng Thị H", "Vũ Minh I", "Trịnh Thị J",
    "Bùi Văn K", "Nguyễn Thị L"];
$requests = ["Phạm Thị F","Đặng Thị H", "Vũ Minh I", "Trịnh Thị J",
    "Bùi Văn K", "Nguyễn Thị L"];
$tab = $_GET['tab'] ?? 'all';
?>
<div class="row g-3">
<?php
if ($tab === 'all') {
    foreach ($allFriends as $f) {
        echo '<div class="col-md-6">
                <div class="card p-2">
                  <div class="d-flex align-items-center">
                    <div class="avatar-md rounded-circle me-3" style="background: linear-gradient(45deg,#667eea,#764ba2); width:50px; height:50px;"></div>
                    <span>'.$f.'</span>
                  </div>
                </div>
              </div>';
    }
} elseif ($tab === 'suggested') {
    foreach ($suggestedFriends as $f) {
        echo '<div class="col-md-6">
                <div class="card p-2 d-flex justify-content-between align-items-center">
                  <div class="d-flex align-items-center">
                    <div class="avatar-md rounded-circle me-3" style="background: linear-gradient(45deg,#f093fb,#f5576c); width:50px; height:50px;"></div>
                    <span>'.$f.'</span>
                  </div>
                  <button class="btn btn-sm btn-primary">Kết bạn</button>
                </div>
              </div>';
    }
} elseif ($tab === 'requests') {
    foreach ($requests as $r) {
        echo '<div class="col-md-6">
                <div class="card p-2 d-flex justify-content-between align-items-center">
                  <div class="d-flex align-items-center">
                    <div class="avatar-md rounded-circle me-3" style="background: linear-gradient(45deg,#4facfe,#00f2fe); width:50px; height:50px;"></div>
                    <span>'.$r.'</span>
                  </div>
                  <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-success">Chấp nhận</button>
                    <button class="btn btn-sm btn-danger">Từ chối</button>
                  </div>
                </div>
              </div>';
    }
}
?>
</div>
