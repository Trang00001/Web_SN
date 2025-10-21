# ✅ Bước 3: Update Frontend - HOÀN THÀNH!

## 📦 Các thay đổi đã thực hiện:

### 1️⃣ **posts.js** - Call API thật ✅
**File:** `public/assets/js/posts.js`

#### Thay đổi:
- ✅ `toggleLike()` → `async toggleLike()`: Call `/public/api/posts/like.php`
- ✅ `submitComment()` → `async submitComment()`: Call `/public/api/posts/comment.php`
- ✅ Thêm error handling với try-catch
- ✅ Thêm disable button khi đang call API
- ✅ Update toast để hiển thị error type

#### Code mới:
```javascript
async toggleLike(button) {
    const postID = button.closest('.post-card').dataset.postId;
    const isLiked = button.classList.contains('liked');
    
    // Call API
    const response = await fetch('/public/api/posts/like.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            post_id: parseInt(postID),
            action: isLiked ? 'unlike' : 'like'
        })
    });
    
    // Update UI if success
    if (data.success) {
        // Toggle like state và update count
    }
}
```

---

### 2️⃣ **home.php** - Load posts từ database ✅
**File:** `app/views/pages/posts/home.php`

#### Thay đổi:
- ❌ Removed: `$defaultPosts` mock array
- ❌ Removed: `$_SESSION['new_posts']`
- ✅ Added: `require_once Post.php`
- ✅ Added: `$postModel->getAll()` để lấy posts từ DB
- ✅ Added: Time ago calculation (vừa xong, 2 giờ trước, etc.)
- ✅ Added: Fallback nếu không có posts hoặc có lỗi

#### Code mới:
```php
require_once __DIR__ . '/../../../models/Post.php';

$postModel = new Post(0);
$postsFromDB = $postModel->getAll();

// Transform database results
foreach ($postsFromDB as $row) {
    $posts[] = [
        'post_id' => $row['PostID'],
        'username' => $row['Username'],
        'content' => $row['Content'],
        'like_count' => $row['LikeCount'],
        'comment_count' => $row['CommentCount'],
        // ...
    ];
}
```

---

### 3️⃣ **post-card.php** - Hiển thị data thật ✅
**File:** `app/views/components/posts/post-card.php`

#### Đã có sẵn:
- ✅ `data-post-id="<?= $post_id ?>"` để JS lấy ID
- ✅ Hiển thị username, content, like_count, comment_count
- ✅ Render media_url nếu có

**Không cần thay đổi gì** - đã tương thích với data từ database!

---

### 4️⃣ **Stored Procedure** - Update sp_GetAllPosts ✅
**File:** `database/update_sp_GetAllPosts.sql`

#### Thay đổi:
- ✅ Thêm `LikeCount` (COUNT từ PostLike table)
- ✅ Thêm `CommentCount` (COUNT từ Comment table)
- ✅ Thêm `ImageUrl` field
- ✅ Alias `PostTime AS CreatedAt`

#### SQL mới:
```sql
CREATE PROCEDURE sp_GetAllPosts()
BEGIN
    SELECT 
        p.PostID,
        p.Content,
        p.PostTime AS CreatedAt,
        p.ImageUrl,
        a.Username,
        pr.AvatarURL,
        (SELECT COUNT(*) FROM PostLike pl WHERE pl.PostID = p.PostID) AS LikeCount,
        (SELECT COUNT(*) FROM Comment c WHERE c.PostID = p.PostID) AS CommentCount
    FROM Post p
    JOIN Account a ON p.AuthorID = a.AccountID
    LEFT JOIN Profile pr ON a.AccountID = pr.AccountID
    ORDER BY p.PostTime DESC;
END
```

---

## 🔧 Cần làm thủ công:

### ⚠️ UPDATE DATABASE PROCEDURE
File đã tạo sẵn: `database/update_sp_GetAllPosts.sql`

**Cách chạy:**
1. Mở **MySQL Workbench** hoặc **phpMyAdmin**
2. Chọn database `web_sn`
3. Copy nội dung file `update_sp_GetAllPosts.sql` và RUN
4. Hoặc dùng command line:
   ```bash
   mysql -u root -p web_sn < database/update_sp_GetAllPosts.sql
   ```

---

## 🧪 Testing Flow:

### Bước 1: Login
```
http://localhost:3000/public/auth/login.php
```

### Bước 2: Xem posts
```
http://localhost:3000/app/views/pages/posts/home.php
```
- Sẽ load posts từ database
- Nếu chưa có posts → hiện message "Chưa có bài viết nào"

### Bước 3: Test Like
1. Click nút "Thích" ở bất kỳ post nào
2. **Console log** sẽ hiển thị: `Like toggled via API, new count: X`
3. **Network tab** (F12) sẽ thấy POST request tới `/public/api/posts/like.php`
4. Check database:
   ```sql
   SELECT * FROM PostLike WHERE PostID = 1;
   ```

### Bước 4: Test Comment
1. Click nút "Bình luận" ở post
2. Nhập text và Enter
3. **Console log**: `Comment added via API: {...}`
4. **Network tab**: POST request tới `/public/api/posts/comment.php`
5. Check database:
   ```sql
   SELECT * FROM Comment WHERE PostID = 1;
   ```

---

## 📊 Tóm tắt thay đổi:

| File | Changes | Status |
|------|---------|--------|
| `posts.js` | Toggle like → async API call | ✅ Done |
| `posts.js` | Submit comment → async API call | ✅ Done |
| `home.php` | Mock data → Database query | ✅ Done |
| `post-card.php` | (No changes needed) | ✅ OK |
| `stored_procedures.sql` | Add LikeCount, CommentCount | ✅ Done |
| `update_sp_GetAllPosts.sql` | Update script cho DB | ✅ Created |

---

## 🎯 Next Steps (Bước 4):

1. ✅ Chạy `update_sp_GetAllPosts.sql` trong database
2. 🧪 Test UI integration end-to-end
3. 🐛 Debug nếu có lỗi
4. 🚀 Deploy hoặc tiếp tục phát triển tính năng khác

---

**Hiện tại đã HOÀN THÀNH Bước 3!** 🎉  
Chỉ cần update database procedure là có thể test được rồi!
