<?php
// Giả lập dữ liệu tin nhắn
$messages = [
    [
        "avatar" => "https://i.pravatar.cc/40?img=1",
        "name" => "Nguyễn Văn A",
        "content" => "Xin chào, bạn có rảnh không?",
        "time" => "10:30 AM"
    ],
    [
        "avatar" => "https://i.pravatar.cc/40?img=2",
        "name" => "Trần Thị B",
        "content" => "Hôm nay học thế nào rồi?",
        "time" => "11:15 AM"
    ],
    [
        "avatar" => "https://i.pravatar.cc/40?img=3",
        "name" => "Lê Văn C",
        "content" => "Nhớ gửi mình file bài tập nhé!",
        "time" => "12:05 PM"
    ]
];
?>

<?php foreach ($messages as $msg): ?>
<div class="d-flex align-items-start p-2 border-bottom bg-white">
    <!-- Avatar -->
    <img src="<?= $msg['avatar'] ?>"
         alt="<?= $msg['name'] ?>"
         class="rounded-circle me-3"
         width="40" height="40">

    <!-- Nội dung tin nhắn -->
    <div class="flex-grow-1">
        <div class="d-flex justify-content-between">
            <strong><?= $msg['name'] ?></strong>
            <small class="text-muted"><?= $msg['time'] ?></small>
        </div>
        <p class="mb-0 text-muted"><?= $msg['content'] ?></p>
    </div>
</div>
<?php endforeach; ?>
