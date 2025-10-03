<?php
// Ví dụ danh sách lời mời kết bạn
$requests = ["Nguyễn Văn H", "Lê Thị I"];
?>

<div class="row g-3">
<?php foreach ($requests as $r): ?>
    <div class="col-md-6">
        <div class="card p-2">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="avatar-md rounded-circle me-3" style="background: linear-gradient(45deg,#4facfe,#00f2fe); width:50px; height:50px;"></div>
                    <span><?= $r ?></span>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-success">Chấp nhận</button>
                    <button class="btn btn-sm btn-danger">Từ chối</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
