# 🚀 QUICK START GUIDE - Testing Your Social Network

## ✅ **Đã hoàn thành:**
1. ✅ Database imported (SocialNetworkDB)
2. ✅ Stored procedures created
3. ✅ Backend APIs ready
4. ✅ Frontend connected to APIs
5. ✅ Fixed URL inconsistencies in `posts.js`

---

## 🎯 **BẮT ĐẦU TEST NGAY:**

### **Bước 1: Set Session (BẮT BUỘC)**
Truy cập để tạo session test:
```
http://localhost/WEB-SN/public/test_session.php
```

✨ File này sẽ:
- Set `$_SESSION['user_id'] = 1` (Alice)
- Cung cấp UI để test các APIs
- Hiển thị kết quả trực quan

---

### **Bước 2: Test APIs**

Sau khi set session, bạn có thể:

#### **Option A: Test qua UI của test_session.php** ⭐ (Dễ nhất)
1. Click các nút test trên trang
2. Xem kết quả ngay lập tức
3. Check database để verify

#### **Option B: Test qua Frontend**
```
http://localhost/WEB-SN/app/views/pages/posts/home.php
```
- Click nút "Thích" → Test Like API
- Click nút "Bình luận" → Test Comment API
- Tạo bài viết mới → Test Create Post API

#### **Option C: Test qua Postman/Thunder Client**
Import collection từ: `postman/Web_SN_API_Tests.postman_collection.json`

---

### **Bước 3: Verify trong Database**

Sử dụng MySQL extension trong VS Code hoặc phpMyAdmin:

```sql
-- Xem tất cả posts
SELECT * FROM Post;

-- Xem likes
SELECT * FROM PostLike;

-- Xem comments
SELECT * FROM Comment;

-- Xem post với thông tin chi tiết
SELECT 
    p.PostID,
    p.Content,
    p.PostTime,
    a.Username,
    COUNT(DISTINCT pl.AccountID) as TotalLikes,
    COUNT(DISTINCT c.CommentID) as TotalComments
FROM Post p
LEFT JOIN Account a ON p.AuthorID = a.AccountID
LEFT JOIN PostLike pl ON p.PostID = pl.PostID
LEFT JOIN Comment c ON p.PostID = c.PostID
GROUP BY p.PostID
ORDER BY p.PostTime DESC;
```

---

## 🐛 **Troubleshooting:**

### **Lỗi: "Please login to continue"**
**Nguyên nhân:** Chưa set session  
**Giải pháp:** Truy cập `test_session.php` trước

### **Lỗi: "404 Not Found" khi gọi API**
**Nguyên nhân:** URL không đúng  
**Kiểm tra:**
- Project có ở đúng folder `htdocs/WEB-SN/` không?
- Apache có đang chạy không?
- Check console browser để xem URL thực tế

### **Lỗi: "Unknown database 'SocialNetworkDB'"**
**Nguyên nhân:** Database chưa được import  
**Giải pháp:** Import lại qua phpMyAdmin:
```
http://localhost/phpmyadmin
→ Import → Chọn schema.sql
→ Import → Chọn stored_procedures.sql
```

### **Lỗi: "Call to undefined procedure"**
**Nguyên nhân:** Stored procedures chưa được tạo  
**Giải pháp:** Import file `stored_procedures.sql`

---

## 📊 **Sample Test Data:**

Database đã có sẵn 3 users và 3 posts:

| AccountID | Email | Username | Posts |
|-----------|-------|----------|-------|
| 1 | alice@test.com | alice | 1 post |
| 2 | bob@test.com | bob | 1 post |
| 3 | charlie@test.com | charlie | 1 post |

**Test với user Alice (ID=1)** đã được set mặc định trong `test_session.php`

---

## 🎨 **Test Scenarios:**

### **Scenario 1: Like một bài viết**
1. Set session (test_session.php)
2. Click "Test Like API"
3. Nhập Post ID = 1
4. Check database: `SELECT * FROM PostLike WHERE PostID = 1;`
5. Expected: Thêm 1 record mới

### **Scenario 2: Comment vào bài viết**
1. Click "Test Comment API"
2. Nhập Post ID = 1, Content = "Great post!"
3. Check database: `SELECT * FROM Comment WHERE PostID = 1;`
4. Expected: Comment mới xuất hiện

### **Scenario 3: Tạo bài viết mới**
1. Click "Test Create Post"
2. Nhập content
3. Check database: `SELECT * FROM Post ORDER BY PostID DESC LIMIT 1;`
4. Expected: Post mới với AuthorID = 1

### **Scenario 4: Unlike bài viết**
1. Like một post trước (Scenario 1)
2. Click "Test Like API" lần nữa với cùng Post ID
3. API sẽ unlike (xóa record)
4. Check database: Record bị xóa

---

## 🔥 **Next Steps After Testing:**

Nếu tất cả tests PASS:
- [ ] Implement Login/Register UI đầy đủ
- [ ] Add real-time notifications
- [ ] Implement profile pages
- [ ] Add friend system
- [ ] Add messaging system

Nếu có lỗi:
- [ ] Check console browser (F12)
- [ ] Check PHP error logs
- [ ] Check database connection
- [ ] Verify stored procedures exist

---

## 📞 **Need Help?**

1. Check `TESTING_CHECKLIST.md` để biết chi tiết
2. Check `TEST_API_COMMANDS.md` để xem test commands
3. Check console browser (F12 → Console)
4. Check PHP errors trong Apache logs

---

**Happy Testing! 🚀**

Được tạo: ${new Date().toLocaleDateString('vi-VN')}
