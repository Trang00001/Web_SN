# ✅ TESTING CHECKLIST: Post Features with Auto-Login# 🔍 TESTING CHECKLIST: Post Creation & Image Upload# 🧪 TESTING CHECKLIST - Social Network App



**Date:** 21/10/2025  

**Status:** 🧪 READY TO TEST

**Date:** 20/10/2025  ## ✅ **ĐÃ HOÀN THÀNH:**

---

**Issue:** Post không được tạo, không hiển thị, ảnh không upload

## 🎯 **SETUP:**

### **1. Database Setup** ✅

### **Auto-Login Active:**

- ✅ User: Alice (ID=1)---- [x] Import `schema.sql` thành công

- ✅ All API endpoints auto-login enabled

- ✅ No manual login required- [x] Import `stored_procedures.sql` thành công



---## 🧪 **STEP 1: Test API Trực Tiếp**- [x] Sample data đã được thêm (3 users, 3 posts)



## 📋 **QUICK TEST (3 Minutes):**- [x] Stored procedures cho posts đã hoạt động:



### **1. Create Simple Post:**### **Open Test Page:**  - `sp_CreatePost`

```

1. Open: http://localhost/WEB-SN/app/views/pages/posts/home.php```  - `sp_AddLike`

2. Click "Tạo bài viết"

3. Type: "Test với auto-login"http://localhost/WEB-SN/public/test_api.html  - `sp_RemoveLike`

4. Click "Đăng"

5. ✅ Post appears on feed?```  - `sp_AddComment`

```



### **2. Upload Image:**

```### **Test 1: Check Session**### **2. Backend API** ✅

1. Click "Tạo bài viết"

2. Type: "Post with image"Click button **"Check Session"**- [x] Database connection (Database.php)

3. Click "Ảnh/Video"

4. Select 1 image- [x] Models connect với stored procedures:

5. ✅ Preview shows?

6. Click "Đăng"**✅ Expected:**  - Post.php → sp_CreatePost

7. ✅ Post with image appears?

``````  - PostLike.php → sp_AddLike, sp_RemoveLike



### **3. Verify Database:**✅ LOGGED IN  - Comment.php → sp_AddComment

```sql

SELECT * FROM Post WHERE AuthorID = 1 ORDER BY PostID DESC LIMIT 2;Session active!- [x] API Endpoints hoạt động:

SELECT * FROM Image WHERE PostID IN (SELECT PostID FROM Post WHERE AuthorID = 1);

``````  - `/public/api/posts/create.php`

✅ Records exist?

  - `/public/api/posts/like.php`

---

**❌ If NOT logged in:**  - `/public/api/posts/comment.php`

## 🔍 **DETAILED TESTING:**

1. Open `http://localhost/WEB-SN/app/views/pages/posts/home.php` first

### **Test 1: Basic Post Creation** ⬜

2. Make sure you're logged in (auto-login as Alice)### **3. Frontend** ✅

**Expected:** Post created and saved to database

3. Then return to test_api.html- [x] `posts.js` gọi APIs với fetch()

**Steps:**

1. Open home page- [x] Fixed URL inconsistency (đã sửa `/WEB-SN/` thành `/public/`)

2. Open Console (F12)

3. Should see: `✅ SocialApp initialized`---- [x] Event handlers (like, comment, share)

4. Click "Tạo bài viết"

5. Nhập: "Test post #1"

6. Click "Đăng"

### **Test 2: Create Simple Post**---

**Check:**

- ⬜ Alert: "Đăng bài thành công!"1. Enter content: "Test API"

- ⬜ Page reloads

- ⬜ Post visible on feed2. Click **"Test Create Post"**## 🧪 **CẦN TEST:**

- ⬜ Database: `SELECT * FROM Post WHERE Content LIKE '%Test post #1%'`



---

**✅ Expected Response:**### **Test 1: Kiểm tra Session Authentication**

### **Test 2: Post with Images** ⬜

```json```php

**Expected:** Images uploaded and linked to post

{// Tạo file test: public/test_session.php

**Steps:**

1. Click "Tạo bài viết"  "success": true,<?php

2. Nhập: "Post with 3 images"

3. Click "Ảnh/Video"  "post_id": 123,session_start();

4. Select 3 images

5. ⬜ Preview shows 3 thumbnails?  "message": "Tạo bài viết thành công",$_SESSION['user_id'] = 1; // Alice

6. Click "Đăng"

  "image_count": 0$_SESSION['username'] = 'alice@test.com';

**Check:**

- ⬜ Alert: "Đăng bài thành công! (3 ảnh)"}echo "Session set! user_id = " . $_SESSION['user_id'];

- ⬜ Post shows images

- ⬜ Database: `SELECT * FROM Image WHERE PostID = ?` (3 rows)```?>

- ⬜ Files in: `E:\xampp\htdocs\WEB-SN\public\uploads\posts\`

```

---

**❌ Common Errors:**

### **Test 3: Like & Comment** ⬜

- `"Chưa đăng nhập"` → Login first**Test Steps:**

**Steps:**

1. Click ❤️ on any post- `"Nội dung không được để trống"` → Enter content1. Truy cập: `http://localhost/WEB-SN/public/test_session.php`

2. ⬜ Like count increases?

3. Click 💬 comment- HTTP 500 → Check PHP error log2. Verify session được set thành công

4. Type: "Nice!"

5. Press Enter

6. ⬜ Comment appears?

------

**Check:**

- ⬜ Database: `SELECT * FROM PostLike WHERE UserID = 1`

- ⬜ Database: `SELECT * FROM Comment WHERE UserID = 1`

### **Test 3: Upload Images**### **Test 2: Test Create Post API**

---

1. Enter content: "Test với ảnh"**Method:** POST  

## 📊 **CONSOLE VERIFICATION:**

2. Select 1-2 images**URL:** `http://localhost/WEB-SN/public/api/posts/create.php`  

Open Console (F12) and verify:

3. Click **"Upload Images & Create Post"****Headers:** `Content-Type: application/json`  

```javascript

// Should all return valid objects**Body:**

console.log(window.postManager);

console.log(window.socialApp);**✅ Expected:**```json



// Should show Alice's session```{

console.log('User ID:', 1);

```📤 Uploading 2 images...  "content": "Test post from API! 🎉"



**No errors in console?** ⬜ YES / ⬜ NO✅ Uploaded: /WEB-SN/public/uploads/posts/abc123.jpg}



---✅ Uploaded: /WEB-SN/public/uploads/posts/def456.jpg```



## 🚨 **IF SOMETHING FAILS:**📝 Creating post...



### **Problem: "Chưa đăng nhập" error**✅ POST CREATED!**Expected Response:**

**Fix:** Auto-login should prevent this. Check if files deployed correctly.

Post ID: 124```json

### **Problem: Post not saved to database**

**Check:**Images: 2{

```sql

SELECT * FROM Post ORDER BY PostID DESC LIMIT 5;```  "success": true,

```

If empty → Check API response in Network tab  "post_id": 4,



### **Problem: Image upload fails**---  "message": "Tạo bài viết thành công"

**Check:**

1. Folder exists: `E:\xampp\htdocs\WEB-SN\public\uploads\posts\`}

2. Network tab: upload_image.php returns success?

3. Console: Any errors?## 📊 **STEP 2: Verify Database**```



### **Problem: Button doesn't work**

**Fix:**

```javascript### **Check Posts:****Verify in Database:**

// In console:

document.getElementById('post-submit-btn').addEventListener('click', () => {```sql```sql

    alert('Button clicked!');

    window.postManager.createPost();-- In phpMyAdminSELECT * FROM Post WHERE PostID = 4;

});

```SELECT * FROM Post ORDER BY PostID DESC LIMIT 5;```



---```



## ✅ **SUCCESS CRITERIA:**---



- ⬜ Can create posts (saved to database)Should see test posts created from API

- ⬜ Can upload images (files saved, linked to posts)

- ⬜ Can like posts (PostLike table updated)### **Test 3: Test Like/Unlike API**

- ⬜ Can comment (Comment table updated)

- ⬜ No console errors### **Check Images:****Method:** POST  

- ⬜ Posts display correctly on feed

```sql**URL:** `http://localhost/WEB-SN/public/api/posts/like.php`  

**All checked?** → **FEATURE COMPLETE!** 🎉

SELECT * FROM Image ORDER BY ImageID DESC LIMIT 10;**Body:**

---

``````json

**Start testing now!** 🚀

{

Should see image records with PostID  "post_id": 1,

  "action": "like"

### **Check Stored Procedure:**}

```sql```

CALL sp_GetAllPosts();

```**Expected Response:**

```json

Should return all posts including newly created ones{

  "success": true,

---  "action": "liked",

  "new_count": 3

## 🌐 **STEP 3: Test Main Page**}

```

### **Open Home Page:**

```**Verify in Database:**

http://localhost/WEB-SN/app/views/pages/posts/home.php```sql

```SELECT * FROM PostLike WHERE PostID = 1;

```

### **Open Console (F12)**

---

Look for these logs:

```### **Test 4: Test Comment API**

✅ SocialApp initialized**Method:** POST  

✅ Events bound successfully**URL:** `http://localhost/WEB-SN/public/api/posts/comment.php`  

🔧 Page fully loaded, checking postManager...**Body:**

✅ PostManager class exists: true```json

✅ window.postManager exists: true{

```  "post_id": 1,

  "content": "Great post! 👍"

### **Test Create Post:**}

1. Click "Tạo bài viết"```

2. Enter: "Test từ trang chính"

3. Click "Đăng"**Expected Response:**

4. Watch console for logs```json

5. Check Network tab (F12 > Network){

  "success": true,

**Expected in Network tab:**  "comment": {

- POST to `/WEB-SN/public/api/posts/create.php`    "CommentID": 4,

- Status: 200    "Content": "Great post! 👍",

- Response: `{"success": true, ...}`    "Username": "alice@test.com"

  }

---}

```

## 🐛 **COMMON ISSUES & FIXES:**

**Verify in Database:**

### **Issue 1: API works but main page doesn't**```sql

SELECT * FROM Comment WHERE PostID = 1;

**Cause:** JavaScript không gọi API đúng cách```



**Debug:**---

```javascript

// Add to createPost() method### **Test 5: Test Frontend UI**

console.log('🔴 createPost() called');1. **Mở trang home:**

console.log('Content:', content);   - URL: `http://localhost/WEB-SN/app/views/pages/posts/home.php`

console.log('Calling API...');   - Hoặc: `http://localhost/WEB-SN/public/index.php`

```

2. **Test Like button:**

---   - Click nút "Thích" trên bài viết

   - Verify số lượng like tăng

### **Issue 2: Post created but không hiển thị**   - Kiểm tra database có thêm record mới



**Cause:** sp_GetAllPosts không return posts3. **Test Comment:**

   - Click nút "Bình luận"

**Fix:**   - Nhập comment và nhấn Enter

```sql   - Verify comment xuất hiện

-- Re-import stored procedures   - Kiểm tra database

-- In phpMyAdmin > Import > Choose:

E:\xampp\htdocs\WEB-SN\database\stored_procedures.sql4. **Test Create Post:**

```   - Click nút "Tạo bài viết"

   - Nhập nội dung

---   - Click "Đăng"

   - Verify post mới xuất hiện trên feed

### **Issue 3: Modal không đóng**

---

**Cause:** bootstrap.Modal.getInstance returns null

## ⚠️ **KNOWN ISSUES TO FIX:**

**Fix:**

```javascript### **Issue 1: Session Authentication**

// Try alternative close method- Hiện tại dùng `$_SESSION['user_id']`

const modal = document.getElementById('createPostModal');- Chưa có trang login hoàn chỉnh

const bsModal = bootstrap.Modal.getInstance(modal);- **Solution:** Tạo file test để set session tạm thời

if (bsModal) {

    bsModal.hide();### **Issue 2: BASE_URL Config**

} else {```php

    modal.classList.remove('show');// config.php line 9

    document.body.classList.remove('modal-open');define("BASE_URL", "http://localhost/WEB-SN/");

    const backdrop = document.querySelector('.modal-backdrop');

    if (backdrop) backdrop.remove();// Nhưng fetch() dùng:

}fetch('/public/api/posts/...')  // ❓ Thiếu /WEB-SN/

``````



---**Cần check:**

- Dự án có dùng `.htaccess` rewrite không?

### **Issue 4: Nút "Ảnh/Video" không mở file picker**- Hoặc cần update fetch URL thành: `/WEB-SN/public/api/posts/...`



**Debug in console:**### **Issue 3: Error Handling**

```javascript- Chưa có error logging

// Test directly- Chưa có validation chi tiết cho input

document.getElementById('post-image-input').click();

```---



If that works, button onclick is the problem## 🎯 **NEXT STEPS:**



---1. **Set session test** (tạo file test_session.php)

2. **Test APIs** với Postman hoặc browser

### **Issue 5: Images không upload**3. **Fix BASE_URL** nếu cần

4. **Test UI** với real user flow

**Check:**5. **Add error logging** và monitoring

1. Directory exists:

```powershell---

Test-Path "E:\xampp\htdocs\WEB-SN\public\uploads\posts"

```## 📊 **Sample Test Accounts:**



2. Test upload API directly in test_api.html| AccountID | Email | Username | Password (hash) |

|-----------|-------|----------|-----------------|

3. Check browser console for errors| 1 | alice@test.com | alice | (hashed) |

| 2 | bob@test.com | bob | (hashed) |

4. Check PHP error log:| 3 | charlie@test.com | charlie | (hashed) |

```

E:\xampp\apache\logs\error.log---

```

**Được tạo:** ${new Date().toLocaleDateString('vi-VN')}  

---**Status:** Ready for testing 🚀


## 📝 **DEBUGGING COMMANDS:**

### **Browser Console:**
```javascript
// Check objects
console.log(window.postManager);
console.log(window.postManager.selectedImages);

// Test button
document.getElementById('post-submit-btn').click();

// Test file input
document.getElementById('post-image-input').click();

// Test API
fetch('/WEB-SN/public/api/posts/create.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ content: 'Test từ console' })
}).then(r => r.json()).then(console.log);
```

### **PowerShell:**
```powershell
# Check files deployed
Get-FileHash "e:\Web_SN\Web_SN\public\assets\js\posts.js"
Get-FileHash "E:\xampp\htdocs\WEB-SN\public\assets\js\posts.js"

# Check uploads directory
Test-Path "E:\xampp\htdocs\WEB-SN\public\uploads\posts"
Get-ChildItem "E:\xampp\htdocs\WEB-SN\public\uploads\posts"

# View recent PHP errors
Get-Content "E:\xampp\apache\logs\error.log" -Tail 50
```

---

## 📞 **WHAT TO REPORT:**

Nếu vẫn lỗi, báo cáo:

1. **Test API Results** (từ test_api.html):
   - Test 1: Check Session → ?
   - Test 2: Create Post → ?
   - Test 3: Upload Images → ?

2. **Database Check**:
```sql
SELECT COUNT(*) FROM Post;
SELECT * FROM Post ORDER BY PostID DESC LIMIT 3;
```

3. **Console Output** (khi click Đăng):
   - Copy all console logs
   - Copy Network tab (POST requests)

4. **PHP Error Log**:
   - Last 20 lines from error.log

---

**Hãy test từng bước theo thứ tự!** 🔍
