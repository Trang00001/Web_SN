# 🧪 Hướng dẫn Test API với Postman trong VS Code

## 📦 Bước 1: Import Collection

1. **Mở Postman Extension** trong VS Code:
   - Nhấn `Ctrl+Shift+P` → gõ `Postman: Focus on Collections View`
   - Hoặc click icon Postman ở Activity Bar bên trái

2. **Import Collection**:
   - Click vào **"Import"** 
   - Chọn file: `postman/Web_SN_API_Tests.postman_collection.json`
   - ✅ Sẽ thấy collection **"Web_SN API Tests"** với 4 requests

---

## 🔐 Bước 2: Tạo Session (Quan trọng!)

API cần **session login** để hoạt động. Có 2 cách:

### Cách 1: Login qua Browser trước (Dễ nhất)
1. Mở `http://localhost:3000` → Login
2. Keep tab đó mở
3. Test trong Postman (sẽ dùng chung session)

### Cách 2: Dùng Cookie trong Postman
1. Sau khi login ở browser, lấy cookie `PHPSESSID`
2. Thêm vào Header của mỗi request:
   ```
   Cookie: PHPSESSID=<your_session_id>
   ```

---

## 🚀 Bước 3: Test các API

### Test 1: Like Post
1. Click vào **"Like Post"** trong collection
2. Kiểm tra:
   - Method: `POST`
   - URL: `http://localhost:3000/public/api/posts/like.php`
   - Body (JSON):
     ```json
     {
       "post_id": 1,
       "type": 1
     }
     ```
3. Click **"Send"**
4. **Expected Response**:
   ```json
   {
     "success": true,
     "message": "Liked successfully",
     "data": {
       "post_id": 1,
       "like_type": 1
     }
   }
   ```

### Test 2: Unlike Post
1. Click **"Unlike Post"**
2. Body:
   ```json
   {
     "post_id": 1,
     "type": 0
   }
   ```
3. Expected: `{"success": true, "message": "Unliked successfully"}`

### Test 3: Add Comment
1. Click **"Add Comment"**
2. Body:
   ```json
   {
     "post_id": 1,
     "content": "Test comment from Postman!"
   }
   ```
3. Expected: Trả về comment_id và thông tin comment

### Test 4: Create Post
1. Click **"Create Post"**
2. Body:
   ```json
   {
     "content": "My first post from Postman API!",
     "category_id": 1
   }
   ```
3. Expected: Trả về post_id mới

---

## 🎯 Các Test Cases quan trọng

### ✅ Test Success Cases
- [ ] Like post với type=1 (like)
- [ ] Like post với type=2 (love), type=3 (haha)...
- [ ] Unlike post (type=0)
- [ ] Add comment với content hợp lệ
- [ ] Create post với content và category_id

### ❌ Test Error Cases
- [ ] Like post **không có session** → Error: "Unauthorized"
- [ ] Like post với **post_id không tồn tại** → Error database
- [ ] Comment với **content rỗng** → Error: "Content cannot be empty"
- [ ] Comment với **content > 1000 ký tự** → Error: "Content too long"
- [ ] Create post với **content > 5000 ký tự** → Error validation

---

## 📊 Response Status Codes

| Code | Meaning | Example |
|------|---------|---------|
| `200` | Success | Like/Comment/Post created successfully |
| `400` | Bad Request | Missing fields, invalid data |
| `401` | Unauthorized | Not logged in |
| `405` | Method Not Allowed | Using GET instead of POST |
| `500` | Server Error | Database error, exception |

---

## 🔍 Debug Tips

### Nếu nhận lỗi "Unauthorized":
```bash
# Kiểm tra session trong database
SELECT * FROM web_sn.sessions WHERE session_id = '<your_phpsessid>';
```

### Nếu nhận lỗi "Post not found":
```bash
# Kiểm tra post có tồn tại
SELECT * FROM web_sn.posts WHERE PostID = 1;
```

### Nếu nhận lỗi 500:
- Check file `e:\Web_SN\Web_SN\app\models\*.php`
- Check stored procedures trong database
- Xem PHP error log

---

## 📝 Response Examples

### Success - Like Post
```json
{
  "success": true,
  "message": "Liked successfully",
  "data": {
    "post_id": 1,
    "like_type": 1
  }
}
```

### Success - Add Comment
```json
{
  "success": true,
  "message": "Comment added successfully",
  "data": {
    "comment_id": 123,
    "post_id": 1,
    "content": "Test comment",
    "created_at": "2025-10-18 10:30:00"
  }
}
```

### Error - Unauthorized
```json
{
  "success": false,
  "error": "Unauthorized"
}
```

### Error - Missing Fields
```json
{
  "success": false,
  "error": "Missing required fields"
}
```

---

## 🎓 Next Steps

Sau khi test API thành công:
1. ✅ Verify data trong database
2. ✅ Update `posts.js` để call API thật
3. ✅ Test UI integration
4. ✅ Remove mock data hoàn toàn

---

**Tip**: Dùng Postman Environment để switch giữa `localhost:3000` và production URL sau này!
