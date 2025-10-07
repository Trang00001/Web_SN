<?php
// Dữ liệu mẫu
$requests = [
  ["id" => 1, "name" => "Nguyễn Văn A", "avatar" => "https://i.pravatar.cc/100?img=1"],
  ["id" => 2, "name" => "Trần Thị B", "avatar" => "https://i.pravatar.cc/100?img=2"],
  ["id" => 3, "name" => "Lê Văn C", "avatar" => "https://i.pravatar.cc/100?img=3"]
];
?>

<div class="row">
  <?php foreach ($requests as $r): ?>
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm text-center">
        <img src="<?= $r['avatar'] ?>" class="card-img-top rounded-circle mx-auto mt-3" style="width:80px;height:80px;" alt="<?= $r['name'] ?>">
        <div class="card-body">
          <h5 class="card-title"><?= $r['name'] ?></h5>
          <button class="btn btn-success btn-accept" data-id="<?= $r['id'] ?>">Chấp nhận</button>
          <button class="btn btn-danger btn-decline" data-id="<?= $r['id'] ?>">Từ chối</button>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
