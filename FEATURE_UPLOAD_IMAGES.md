# 📸 FEATURE: Upload Multiple Images for Posts

**Date:** 20/10/2025  
**Status:** ✅ COMPLETED

---

## 🎯 **FEATURES:**

- ✅ Upload multiple images khi tạo post
- ✅ Preview ảnh trước khi đăng
- ✅ Remove ảnh từ preview
- ✅ Lưu ảnh vào bảng `Image` với `PostID`
- ✅ API upload xử lý file (JPEG, PNG, GIF, WebP)
- ✅ Max 5MB per image

---

## 📊 **DATABASE CHANGES:**

### **1. Updated `sp_CreatePost`:**
```sql
CREATE PROCEDURE sp_CreatePost(
    IN p_authorID INT, 
    IN p_content TEXT, 
    OUT p_postID INT  -- ← NEW!
)
BEGIN
    INSERT INTO Post (AuthorID, Content) VALUES (p_authorID, p_content);
    SET p_postID = LAST_INSERT_ID();  -- ← Return PostID
END
```

### **2. New `sp_AddPostImage`:**
```sql
CREATE PROCEDURE sp_AddPostImage(
    IN p_postID INT, 
    IN p_imageURL VARCHAR(255)
)
BEGIN
    INSERT INTO Image (PostID, ImageURL) VALUES (p_postID, p_imageURL);
END
```

---

## 📁 **FILES UPDATED:**

### **Frontend:**
1. **`app/views/pages/posts/home.php`**
   - Added image upload input
   - Added preview container
   - Added "Ảnh/Video" button

2. **`public/assets/js/posts.js`**
   - Added `selectedImages` array
   - Added `initImageUpload()` method
   - Added `showImagePreview()` method
   - Added `removeImage()` method
   - Updated `createPost()` to upload images first

### **Backend:**
3. **`public/api/posts/upload_image.php`** (NEW)
   - Upload ảnh lên server
   - Validate file type & size
   - Return image URL

4. **`public/api/posts/create.php`**
   - Accept `image_urls` array
   - Insert images after creating post

5. **`app/models/Post.php`**
   - Updated `create()` to use OUT parameter
   - Get PostID after insert

6. **`app/models/Image.php`**
   - Use stored procedure `sp_AddPostImage`

7. **`core/Database.php`**
   - Added `callProcedureWithOutParam()` method

8. **`database/stored_procedures.sql`**
   - Updated `sp_CreatePost` with OUT param
   - Added `sp_AddPostImage`
   - Added DROP for `sp_AddPostImage`

---

## 🚀 **SETUP STEPS:**

### **1. Import Updated Stored Procedures:**

```
http://localhost/phpmyadmin
```

1. Select `SocialNetworkDB`
2. Tab "Import"
3. Choose `E:\xampp\htdocs\WEB-SN\database\stored_procedures.sql`
4. Click "Go"

### **2. Verify Upload Folder:**

```
E:\xampp\htdocs\WEB-SN\public\uploads\posts\
```

Folder đã tự động được tạo!

---

## 🧪 **TESTING:**

### **Test 1: Upload Single Image**

1. Open: `http://localhost/WEB-SN/app/views/pages/posts/home.php`
2. Click "Tạo bài viết"
3. Nhập nội dung: "Test post với 1 ảnh"
4. Click "Ảnh/Video" button
5. Chọn 1 ảnh
6. Xem preview
7. Click "Đăng"
8. Expected: "Đăng bài thành công! (1 ảnh)"

### **Test 2: Upload Multiple Images**

1. Nhập nội dung: "Test post với 3 ảnh"
2. Click "Ảnh/Video"
3. Chọn 3 ảnh (Ctrl+Click)
4. Xem preview 3 ảnh
5. Click "Đăng"
6. Expected: "Đăng bài thành công! (3 ảnh)"

### **Test 3: Remove Image from Preview**

1. Chọn 3 ảnh
2. Click nút X trên ảnh thứ 2
3. Expected: Preview còn 2 ảnh

### **Test 4: Verify in Database**

```sql
-- Check post created
SELECT * FROM Post ORDER BY PostID DESC LIMIT 1;

-- Check images
SELECT * FROM Image WHERE PostID = 
(SELECT MAX(PostID) FROM Post);
```

### **Test 5: Verify File Uploaded**

Check folder:
```
E:\xampp\htdocs\WEB-SN\public\uploads\posts\
```

Should see uploaded images with format: `{uniqid}_{timestamp}.{ext}`

---

## 📸 **UI FLOW:**

```
1. User clicks "Tạo bài viết"
   ↓
2. Modal opens with textarea và "Ảnh/Video" button
   ↓
3. User clicks "Ảnh/Video"
   ↓
4. File picker opens (multiple selection)
   ↓
5. User selects images
   ↓
6. Preview shows selected images với nút X
   ↓
7. User can remove images by clicking X
   ↓
8. User nhập nội dung
   ↓
9. User clicks "Đăng"
   ↓
10. JavaScript uploads images one by one
    ↓
11. Button shows "Đang upload ảnh..."
    ↓
12. After all uploaded, call create.php với image_urls
    ↓
13. API creates post, gets PostID
    ↓
14. API inserts images vào Image table
    ↓
15. Response: {success: true, post_id: X, image_count: Y}
    ↓
16. Modal closes, page reloads
    ↓
17. Post với ảnh hiển thị trên feed
```

---

## 🔒 **SECURITY:**

- ✅ Validate file type (only images)
- ✅ Validate file size (max 5MB)
- ✅ Unique filename (uniqid + timestamp)
- ✅ Authentication required
- ✅ File MIME type check with `finfo`

---

## ⚠️ **LIMITATIONS:**

1. **No image compression** (future: add resize/compress)
2. **No progress bar** (future: show upload progress)
3. **Sequential upload** (future: parallel upload)
4. **No image editing** (future: crop, filter)

---

## 🎨 **NEXT FEATURES:**

- [ ] Drag & drop upload
- [ ] Image compression
- [ ] Upload progress bar
- [ ] Image preview in lightbox
- [ ] Multiple image gallery display
- [ ] Delete image after post created
- [ ] Edit post images

---

## 📊 **DATABASE SCHEMA:**

```
Post (PostID, AuthorID, Content, PostTime)
  ↓ 1:N
Image (ImageID, PostID, ImageURL)
```

One post can have **multiple images** ✅

---

**All Done! Test the feature now!** 🚀
