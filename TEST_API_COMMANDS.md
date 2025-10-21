# 🧪 TEST API - Find Root Cause# API Testing Commands



**Date:** 21/10/2025  ## 1️⃣ Test Like API

**Status:** 🔍 DEBUGGING```bash

# PowerShell

---$headers = @{

    "Content-Type" = "application/json"

## 🎯 **TEST PAGE CREATED:**}

$body = @{

```    post_id = 1

http://localhost/WEB-SN/public/test_api.html    type = 1

```} | ConvertTo-Json



**Interactive testing interface with 6 comprehensive tests!**Invoke-WebRequest -Uri "http://localhost:3000/public/api/posts/like.php" -Method POST -Headers $headers -Body $body

```

---

```bash

## ⚡ **QUICK START:**# cURL (Git Bash)

curl -X POST http://localhost:3000/public/api/posts/like.php \

### **1. Open Test Page:**  -H "Content-Type: application/json" \

```  -d '{"post_id": 1, "type": 1}'

http://localhost/WEB-SN/public/test_api.html```

```

---

### **2. Run Test 1 (Simple Post):**

- Click "🚀 Create Post" button## 2️⃣ Test Comment API

- Check status badge (green/red)```bash

- Read result output# PowerShell

$body = @{

### **3. Run Test 6 (Full Flow):**    post_id = 1

- Select 1-2 images    content = "Test comment from API"

- Click "🚀 Create Post with Image"} | ConvertTo-Json

- Watch step-by-step output

Invoke-WebRequest -Uri "http://localhost:3000/public/api/posts/comment.php" -Method POST -Headers $headers -Body $body

---```



## 🔍 **WHAT EACH TEST DOES:**```bash

# cURL

| Test | Purpose | What It Checks |curl -X POST http://localhost:3000/public/api/posts/comment.php \

|------|---------|---------------|  -H "Content-Type: application/json" \

| 1️⃣ Simple Post | Create post without images | API `/create.php`, database insert, stored procedure |  -d '{"post_id": 1, "content": "Test comment from API"}'

| 2️⃣ Upload Image | Upload single image file | API `/upload_image.php`, file storage, permissions |```

| 3️⃣ Get Posts | Fetch all posts | Database connection, `debug_posts.php` |

| 4️⃣ Check DB | Verify database status | Connection, stored procedures status |---

| 5️⃣ Check Session | Verify auto-login | Session management, user_id=1 |

| 6️⃣ Full Flow | Complete workflow | Upload + Create (most comprehensive) |## 3️⃣ Test Create Post API

```bash

---# PowerShell

$body = @{

## 📊 **EXPECTED OUTPUTS:**    content = "Test post from API"

    category_id = 1

### **✅ Test 1 Success:**} | ConvertTo-Json

```

✅ POST CREATED!Invoke-WebRequest -Uri "http://localhost:3000/public/api/posts/create.php" -Method POST -Headers $headers -Body $body

```

Status: 200

Post ID: 123```bash

Message: Đăng bài thành công# cURL

```curl -X POST http://localhost:3000/public/api/posts/create.php \

  -H "Content-Type: application/json" \

### **❌ Test 1 Failure Examples:**  -d '{"content": "Test post from API", "category_id": 1}'

```

**Error: "Không thể lưu bài viết vào database"**

```---

Cause: Stored procedure sp_CreatePost not imported or incorrect

Fix: Import database/stored_procedures.sql## Expected Responses

```

### Success (có session login):

**Error: "Call to undefined method"**```json

```{

Cause: Database::callProcedureWithOutParam() missing  "success": true,

Fix: Check core/Database.php has the method  "message": "...",

```  "data": { ... }

}

**Error: "Chưa đăng nhập"**```

```

Cause: Auto-login not working### Error (chưa login):

Fix: Check session code in create.php```json

```{

  "success": false,

---  "error": "Unauthorized"

}

## 🛠️ **MOST LIKELY ISSUES:**```



### **Issue #1: Stored Procedures Not Imported** ⭐⭐⭐⭐⭐### Error (thiếu dữ liệu):

```json

**Symptoms:**{

- Test 1 fails with "Không thể lưu bài viết"  "success": false,

- Database query error  "error": "Missing required fields"

}

**Fix:**```

```sql

-- Open phpMyAdmin---

-- Select SocialNetworkDB

-- Go to Import tab## Quick Test với JavaScript Console

-- Choose: E:\Web_SN\Web_SN\database\stored_procedures.sql

-- Click GoMở browser tại `http://localhost:3000`, login, rồi chạy:



-- Verify:```javascript

SHOW PROCEDURE STATUS WHERE Db = 'SocialNetworkDB';// Test Like

fetch('/public/api/posts/like.php', {

-- Should see:  method: 'POST',

-- sp_CreatePost  headers: {'Content-Type': 'application/json'},

-- sp_AddPostImage  body: JSON.stringify({post_id: 1, type: 1})

-- sp_GetAllPosts})

```.then(r => r.json())

.then(console.log);

---

// Test Comment

### **Issue #2: Missing callProcedureWithOutParam Method** ⭐⭐⭐⭐fetch('/public/api/posts/comment.php', {

  method: 'POST',

**Symptoms:**  headers: {'Content-Type': 'application/json'},

- Error: "Call to undefined method Database::callProcedureWithOutParam()"  body: JSON.stringify({post_id: 1, content: 'Test comment'})

})

**Fix:**.then(r => r.json())

Check `core/Database.php` has this method. If missing, the stored procedure was added but Database class wasn't updated..then(console.log);

```

---

### **Issue #3: Auto-Login Not Working** ⭐⭐⭐

**Symptoms:**
- 401 Unauthorized
- "Chưa đăng nhập"

**Fix:**
Verify these files were deployed:
- create.php (with auto-login code)
- upload_image.php (with auto-login code)

---

### **Issue #4: Upload Directory Missing** ⭐⭐

**Symptoms:**
- Test 2 fails
- "Failed to move uploaded file"

**Fix:**
```powershell
New-Item -ItemType Directory -Path "E:\xampp\htdocs\WEB-SN\public\uploads\posts" -Force
```

---

## 🎯 **STEP-BY-STEP DEBUGGING:**

### **Step 1: Test API Directly**
```
Run Test 1 on test page
↓
If fails → Read error message
↓
Match error to issues above
↓
Apply fix
↓
Re-test
```

### **Step 2: Check Database**
```sql
-- After successful Test 1:
SELECT * FROM Post ORDER BY PostID DESC LIMIT 1;

-- Should see your test post
-- If NOT → Stored procedure issue
```

### **Step 3: Test Image Upload**
```
Run Test 2
↓
Upload a small image
↓
Check result
↓
If success → Check file exists in uploads/posts/
↓
If file missing → Permission issue
```

### **Step 4: Full Integration Test**
```
Run Test 6 with 2 images
↓
Watch step-by-step output
↓
Should see:
  ✅ Image 1 uploaded
  ✅ Image 2 uploaded
  ✅ Post created
↓
Verify in database:
  SELECT * FROM Post WHERE PostID = [returned_id];
  SELECT * FROM Image WHERE PostID = [returned_id];
```

---

## 📋 **DEBUGGING FLOWCHART:**

```
Start
  ↓
Run Test 1
  ↓
Success? ──YES──→ Run Test 2
  ↓                   ↓
  NO               Success? ──YES──→ Run Test 6
  ↓                   ↓                   ↓
Check error         NO                Success?
  ↓                   ↓                   ↓
"database"?      "upload"?           YES → ✅ FIXED!
  ↓                   ↓                   ↓
Import            Create             NO → Check
procedures        directory          error details
  ↓                   ↓
"method"?        "permission"?
  ↓                   ↓
Add method       Fix perms
Database.php
  ↓
"login"?
  ↓
Check
auto-login
code
```

---

## 🔧 **QUICK FIXES:**

### **Fix 1: Import Stored Procedures**
```
1. http://localhost/phpmyadmin
2. SocialNetworkDB → Import
3. Choose: database/stored_procedures.sql
4. Go
```

### **Fix 2: Create Upload Directory**
```powershell
New-Item -ItemType Directory -Force -Path "E:\xampp\htdocs\WEB-SN\public\uploads\posts"
```

### **Fix 3: Redeploy Files**
```powershell
Copy-Item "e:\Web_SN\Web_SN\public\api\posts\*.php" "E:\xampp\htdocs\WEB-SN\public\api\posts\" -Force
```

---

## ✅ **SUCCESS CRITERIA:**

- [ ] Test 1 returns success (Post ID returned)
- [ ] Test 2 uploads image (File URL returned)
- [ ] Test 3 shows posts list
- [ ] Test 6 creates post with images
- [ ] Database has Post record
- [ ] Database has Image records
- [ ] Files exist in uploads/posts folder

**All checked?** → **System is working!** 🎉

---

## 📝 **REPORT TEMPLATE:**

If tests fail, provide this info:

```
Test Failed: [Test number]
Error Message: [Exact error from test page]
Browser Console: [Any errors from F12 console]
Database Check: [Result of SELECT * FROM Post]
Procedures Check: [Result of SHOW PROCEDURE STATUS]
```

---

**Test now and report results! Test page isolates the exact issue!** 🚀
