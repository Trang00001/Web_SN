# 🧪 TEST API ENDPOINTS - POSTS

## 📝 Setup Test Environment

Trước khi test, cần:
1. ✅ Import database schema & stored procedures
2. ✅ Có ít nhất 1 user trong database
3. ✅ Set session user_id (hoặc login)

## 🔧 Test với Postman/Insomnia

### **1. TEST CREATE POST**

**Endpoint:** `POST http://localhost/Web_SN/public/api/posts/create.php`

**Headers:**
```
Content-Type: application/json
Cookie: PHPSESSID=your_session_id
```

**Body (JSON):**
```json
{
  "content": "Test post from Postman! 🚀",
  "media_url": ""
}
```

**Expected Response (Success):**
```json
{
  "success": true,
  "post_id": 123,
  "message": "Đăng bài thành công"
}
```

**Expected Response (Error - Not logged in):**
```json
{
  "success": false,
  "error": "Chưa đăng nhập"
}
```

---

### **2. TEST LIKE POST**

**Endpoint:** `POST http://localhost/Web_SN/public/api/posts/like.php`

**Headers:**
```
Content-Type: application/json
Cookie: PHPSESSID=your_session_id
```

**Body (JSON) - Like:**
```json
{
  "post_id": 1,
  "action": "like"
}
```

**Expected Response:**
```json
{
  "success": true,
  "action": "liked",
  "message": "Đã thích bài viết"
}
```

**Body (JSON) - Unlike:**
```json
{
  "post_id": 1,
  "action": "unlike"
}
```

**Expected Response:**
```json
{
  "success": true,
  "action": "unliked",
  "message": "Đã bỏ thích"
}
```

---

### **3. TEST COMMENT POST**

**Endpoint:** `POST http://localhost/Web_SN/public/api/posts/comment.php`

**Headers:**
```
Content-Type: application/json
Cookie: PHPSESSID=your_session_id
```

**Body (JSON):**
```json
{
  "post_id": 1,
  "content": "Great post! Thanks for sharing 👍"
}
```

**Expected Response:**
```json
{
  "success": true,
  "comment": {
    "post_id": 1,
    "username": "User",
    "avatar": "/public/assets/images/default-avatar.png",
    "content": "Great post! Thanks for sharing 👍",
    "created_at": "Vừa xong"
  },
  "message": "Đã thêm bình luận"
}
```

---

## 🔄 Test với cURL (Command Line)

### **Test Create Post:**
```bash
curl -X POST http://localhost/Web_SN/public/api/posts/create.php \
  -H "Content-Type: application/json" \
  -b "PHPSESSID=your_session_id" \
  -d '{"content":"Test from cURL"}'
```

### **Test Like:**
```bash
curl -X POST http://localhost/Web_SN/public/api/posts/like.php \
  -H "Content-Type: application/json" \
  -b "PHPSESSID=your_session_id" \
  -d '{"post_id":1,"action":"like"}'
```

### **Test Comment:**
```bash
curl -X POST http://localhost/Web_SN/public/api/posts/comment.php \
  -H "Content-Type: application/json" \
  -b "PHPSESSID=your_session_id" \
  -d '{"post_id":1,"content":"Test comment"}'
```

---

## 🐛 Test với PHP (Quick Test)

Tạo file `test_api.php` trong root:

```php
<?php
session_start();

// Giả lập login
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'Test User';

// Test Create Post
echo "<h2>Test Create Post</h2>";
$_POST['content'] = "Test post from PHP";
include 'public/api/posts/create.php';

// Test Like
echo "<h2>Test Like</h2>";
$_POST['post_id'] = 1;
$_POST['action'] = 'like';
include 'public/api/posts/like.php';

// Test Comment
echo "<h2>Test Comment</h2>";
$_POST['post_id'] = 1;
$_POST['content'] = "Test comment from PHP";
include 'public/api/posts/comment.php';
?>
```

---

## ✅ CHECKLIST TESTING

### **Create Post API**
- [ ] Test tạo post thành công
- [ ] Test content trống → Error 400
- [ ] Test content quá dài (>5000 chars) → Error 400
- [ ] Test chưa login → Error 401
- [ ] Verify post xuất hiện trong database

### **Like API**
- [ ] Test like post lần đầu → Success
- [ ] Test unlike post → Success
- [ ] Test like lại post đã like → Error/Success
- [ ] Test post_id không tồn tại → Error
- [ ] Test chưa login → Error 401
- [ ] Verify like count trong database

### **Comment API**
- [ ] Test thêm comment thành công
- [ ] Test content trống → Error 400
- [ ] Test content quá dài (>1000 chars) → Error 400
- [ ] Test post_id không tồn tại → Error
- [ ] Test chưa login → Error 401
- [ ] Verify comment xuất hiện trong database

---

## 🔍 DEBUG TIPS

### **1. Check Session:**
```php
<?php
session_start();
var_dump($_SESSION);
?>
```

### **2. Check Database Connection:**
```php
<?php
require_once 'core/Database.php';
$db = new Database();
var_dump($db->conn);
?>
```

### **3. Check Stored Procedures:**
```sql
-- MySQL
SHOW PROCEDURE STATUS WHERE Db = 'social_network';

-- Test manually
CALL sp_CreatePost(1, 'Test content', NULL);
CALL sp_AddLike(1, 1);
CALL sp_AddComment(1, 1, 'Test comment');
```

### **4. Enable Error Display:**
```php
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
```

---

## 📊 EXPECTED DATABASE CHANGES

### **After Create Post:**
```sql
SELECT * FROM Post ORDER BY PostID DESC LIMIT 1;
-- Should see new post with your content
```

### **After Like:**
```sql
SELECT * FROM PostLike WHERE PostID = 1;
-- Should see new row with your AccountID
```

### **After Comment:**
```sql
SELECT * FROM Comment WHERE PostID = 1 ORDER BY CommentID DESC LIMIT 1;
-- Should see new comment with your content
```

---

## 🎯 NEXT STEPS

Sau khi test API thành công:
1. ✅ Update JavaScript `posts.js` để call API
2. ✅ Update `home.php` load posts từ database
3. ✅ Test UI interactions
4. ✅ Deploy

---

**Happy Testing!** 🚀
