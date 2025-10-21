# 🔧 FIX: Unknown column 'p.ImageUrl'

**Date:** 19/10/2025  
**Error:** `Unknown column 'p.ImageUrl' in 'field list'`  
**Status:** ✅ FIXED

---

## 🐛 **ROOT CAUSE:**

Stored procedure `sp_GetAllPosts` đang SELECT `p.ImageUrl` từ bảng `Post`, nhưng:

❌ **Bảng `Post` KHÔNG có column `ImageUrl`**

Theo schema:
```sql
CREATE TABLE Post (
    PostID INT,
    AuthorID INT,
    Content TEXT,
    PostTime DATETIME,
    SharedFromPostID INT
    -- ❌ KHÔNG có ImageUrl
);

CREATE TABLE Image (
    ImageID INT,
    PostID INT,
    ImageURL VARCHAR(255)  -- ✅ Ảnh ở đây
);
```

---

## ✅ **SOLUTION:**

Sửa `sp_GetAllPosts` để:
1. Xóa `p.ImageUrl` (không tồn tại)
2. Thêm subquery JOIN với bảng `Image`
3. Lấy ảnh đầu tiên của post (nếu có)

**Fixed Code:**
```sql
CREATE PROCEDURE sp_GetAllPosts()
BEGIN
    SELECT 
        p.PostID,
        p.Content,
        p.PostTime AS CreatedAt,
        (SELECT i.ImageURL FROM Image i WHERE i.PostID = p.PostID LIMIT 1) AS ImageUrl,
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

## 🚀 **APPLY FIX:**

### **Cách 1: Qua phpMyAdmin** ⭐ (Recommended)

1. Mở: http://localhost/phpmyadmin
2. Chọn database `SocialNetworkDB`
3. Tab **SQL**
4. Copy toàn bộ nội dung file `database/fix_sp_GetAllPosts.sql`
5. Paste vào SQL editor
6. Click **Go**

### **Cách 2: Qua MySQL Command Line**

```bash
mysql -u root -p SocialNetworkDB < E:\Web_SN\Web_SN\database\fix_sp_GetAllPosts.sql
```

### **Cách 3: Run từng lệnh**

```sql
USE SocialNetworkDB;

DROP PROCEDURE IF EXISTS sp_GetAllPosts;

DELIMITER //
CREATE PROCEDURE sp_GetAllPosts()
BEGIN
    SELECT 
        p.PostID,
        p.Content,
        p.PostTime AS CreatedAt,
        (SELECT i.ImageURL FROM Image i WHERE i.PostID = p.PostID LIMIT 1) AS ImageUrl,
        a.Username,
        pr.AvatarURL,
        (SELECT COUNT(*) FROM PostLike pl WHERE pl.PostID = p.PostID) AS LikeCount,
        (SELECT COUNT(*) FROM Comment c WHERE c.PostID = p.PostID) AS CommentCount
    FROM Post p
    JOIN Account a ON p.AuthorID = a.AccountID
    LEFT JOIN Profile pr ON a.AccountID = pr.AccountID
    ORDER BY p.PostTime DESC;
END //
DELIMITER ;
```

---

## 🧪 **VERIFY FIX:**

### **1. Test Stored Procedure:**
```sql
CALL sp_GetAllPosts();
-- Should return all posts with ImageUrl column (NULL if no image)
```

### **2. Test Debug Endpoint:**
```
http://localhost/WEB-SN/public/debug_posts.php
```
**Expected:**
```json
{
  "ok": true,
  "rows_count": 3,
  "rows_sample": [
    {
      "PostID": "4",
      "Content": "...",
      "CreatedAt": "2025-10-19 ...",
      "ImageUrl": null,
      "Username": "alice@test.com",
      "AvatarURL": null,
      "LikeCount": "0",
      "CommentCount": "0"
    }
  ]
}
```

### **3. Test UI:**
```
http://localhost/WEB-SN/app/views/pages/posts/home.php
```
- Bài viết phải hiển thị
- Không còn lỗi "Unknown column"

---

## 📊 **IMPACT:**

### **Before:**
- ❌ Error: Unknown column 'p.ImageUrl'
- ❌ Không load được posts
- ❌ UI hiển thị "System: Lỗi khi tải bài viết"

### **After:**
- ✅ Stored procedure execute thành công
- ✅ Posts được load từ database
- ✅ UI hiển thị posts (có/không có ảnh)

---

## 📁 **FILES UPDATED:**

1. ✅ `database/stored_procedures.sql` - Fixed sp_GetAllPosts
2. ✅ `database/fix_sp_GetAllPosts.sql` - Quick fix script

---

## ⚠️ **NOTES:**

- Bảng `Post` không chứa trường ảnh trực tiếp
- Ảnh được lưu riêng ở bảng `Image` (1 post có thể có nhiều ảnh)
- Stored procedure lấy ảnh đầu tiên (`LIMIT 1`) để hiển thị preview
- Nếu post không có ảnh → `ImageUrl` = NULL

---

**Next Step:** Run fix script trong phpMyAdmin và refresh trang home.php! 🚀
