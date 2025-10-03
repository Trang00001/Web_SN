```php
<?php
// layouts/blank.php
// Layout trống, dùng cho popup hoặc trang tối giản
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blank - Website</title>
    <link rel="stylesheet" href="<?php echo __DIR__ . '/../assets/css/global.css'; ?>">
</head>
<body>
    <?php 
    if (isset($content)) {
        echo $content; // nội dung tuỳ chỉnh
    }
    ?>
</body>
</html>
```
