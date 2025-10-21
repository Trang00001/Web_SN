# 🚀 IMPORT STORED PROCEDURES - UPDATED

**Date:** 19/10/2025  
**File:** `database/stored_procedures.sql`  
**Status:** ✅ Updated with fixed sp_GetAllPosts

---

## ✅ **ĐÃ FIX:**

- `sp_GetAllPosts` không còn dùng `p.ImageUrl` (không tồn tại)
- Thêm subquery lấy ảnh từ bảng `Image`
- Thêm DROP statements để có thể re-import

---

## 📋 **IMPORT VÀO DATABASE:**

### **Cách 1: Qua phpMyAdmin** ⭐ (Recommended)

1. **Mở phpMyAdmin:**
   ```
   http://localhost/phpmyadmin
   ```

2. **Chọn database `SocialNetworkDB`** (bên trái)

3. **Click tab "Import"** (ở thanh menu trên)

4. **Click "Choose File"**

5. **Chọn file:**
   ```
   E:\xampp\htdocs\WEB-SN\database\stored_procedures.sql
   ```

6. **Scroll xuống, click "Go"**

7. **Verify:**
   - Thấy message: "X queries executed successfully"
   - Không có error màu đỏ

---

### **Cách 2: Qua MySQL Command Line**

```bash
mysql -u root -p SocialNetworkDB < E:\xampp\htdocs\WEB-SN\database\stored_procedures.sql
```

*(Nhập password MySQL nếu có)*

---

### **Cách 3: Copy & Paste SQL** (nếu file quá lớn)

1. Mở file `E:\xampp\htdocs\WEB-SN\database\stored_procedures.sql` trong Notepad
2. Copy toàn bộ nội dung (Ctrl+A, Ctrl+C)
3. Mở phpMyAdmin → SocialNetworkDB → Tab "SQL"
4. Paste vào SQL editor
5. Click "Go"

---

## 🧪 **VERIFY SAU KHI IMPORT:**

### **1. Check stored procedures tồn tại:**

```sql
SHOW PROCEDURE STATUS WHERE Db = 'SocialNetworkDB';
```

**Expected:** Thấy 28+ procedures

### **2. Test sp_GetAllPosts:**

```sql
CALL sp_GetAllPosts();
```

**Expected:**
- Không có error "Unknown column 'p.ImageUrl'"
- Trả về posts với columns: PostID, Content, CreatedAt, ImageUrl, Username, AvatarURL, LikeCount, CommentCount

### **3. Test debug endpoint:**

```
http://localhost/WEB-SN/public/debug_posts.php
```

**Expected:**
```json
{
  "ok": true,
  "rows_count": 3,
  "rows_sample": [...]
}
```

### **4. Test UI:**

```
http://localhost/WEB-SN/app/views/pages/posts/home.php
```

**Expected:** Posts hiển thị bình thường

---

## 📊 **STORED PROCEDURES INCLUDED:**

### **Authentication (6)**
- sp_RegisterUser
- sp_LoginUser
- sp_CheckEmailExists
- sp_GetUserProfile
- sp_UpdateUserProfile
- sp_DeleteUser

### **Posts (10)**
- sp_CreatePost
- sp_UpdatePost
- sp_DeletePost
- **sp_GetAllPosts** ← **FIXED!**
- sp_GetPostById
- sp_GetUserPosts
- sp_AddLike
- sp_RemoveLike
- sp_AddComment
- sp_GetCommentsForPost

### **Friends (6)**
- sp_SendFriendRequest
- sp_CancelFriendRequest
- sp_AcceptFriendRequest
- sp_RejectFriendRequest
- sp_RemoveFriend
- sp_GetFriends

### **Messages (4)**
- sp_CreateChatBox
- sp_SendMessage
- sp_GetChatHistory
- sp_MarkMessageAsRead

### **Notifications (4)**
- sp_CreateNotification
- sp_GetNotifications
- sp_MarkNotificationAsRead
- sp_DeleteNotification

### **Search (2)**
- sp_SearchUsers
- sp_SearchPosts

---

## ⚠️ **IMPORTANT NOTES:**

- File đã có DROP statements → An toàn khi re-import
- Không cần xóa procedures cũ thủ công
- Tất cả procedures sẽ được recreate
- Data trong tables KHÔNG bị ảnh hưởng

---

## 🎯 **NEXT STEPS:**

1. ✅ Import `stored_procedures.sql` qua phpMyAdmin
2. ✅ Test `sp_GetAllPosts` bằng CALL
3. ✅ Refresh debug_posts.php → Verify "ok": true
4. ✅ Refresh home.php → Verify posts hiển thị
5. ✅ Test tạo post mới, like, comment

---

**File location:**
- Source: `E:\Web_SN\Web_SN\database\stored_procedures.sql`
- Htdocs: `E:\xampp\htdocs\WEB-SN\database\stored_procedures.sql`

**Ready to import!** 🚀
