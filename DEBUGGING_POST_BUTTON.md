# 🔍 DEBUG GUIDE: Nút Đăng Bài & Upload Ảnh

**Date:** 20/10/2025  
**Status:** 🔍 DEBUGGING

---

## ✅ **FILES VERIFIED:**

### **Deployment Status:**
- ✅ `public/assets/js/posts.js` - Hash MATCH (deployed correctly)
- ✅ `app/views/pages/posts/home.php` - HTML structure correct
- ✅ PostManager constructor has `this.selectedImages = []`
- ✅ Button has `id="post-submit-btn"`
- ✅ Image button has `onclick="document.getElementById('post-image-input').click()"`

---

## 🧪 **TESTING STEPS:**

### **Step 1: Clear Browser Cache**

**Chrome/Edge:**
1. Press `Ctrl + Shift + Delete`
2. Select "Cached images and files"
3. Click "Clear data"
4. OR press `Ctrl + Shift + R` (Hard Refresh)

**Firefox:**
1. Press `Ctrl + Shift + Delete`
2. Select "Cache"
3. Click "Clear Now"
4. OR press `Ctrl + F5`

---

### **Step 2: Test Basic Functionality**

Open test page:
```
http://localhost/WEB-SN/public/test_post_button.html
```

**Tests to run:**

1. ✅ **Test 1: Basic Button**
   - Click "Click Me!" button
   - Should show timestamp below

2. ✅ **Test 2: Image Upload Outside Modal**
   - Click on "Ảnh/Video" area
   - File picker should open
   - Select images
   - Should show selected files list

3. ✅ **Test 3: Check Global Objects**
   - Click "Check window.postManager"
   - Should show object types

4. ✅ **Test 4: Modal Test**
   - Click "Open Test Modal"
   - Modal should open
   - Click "Ảnh/Video" in modal
   - Select images
   - Click "Đăng" button
   - Should show alert

---

### **Step 3: Test Real Page**

Open actual page:
```
http://localhost/WEB-SN/app/views/pages/posts/home.php
```

**Open Browser Console (F12):**

Check for errors:
```javascript
// Should see these logs:
"Social app initialized and exported to window.socialApp"
"SocialApp initialized"
"Events bound successfully"
```

**Test in Console:**
```javascript
// Check if objects exist
console.log(window.postManager);
console.log(window.socialApp);

// Check PostManager
console.log(typeof window.postManager);
console.log(window.postManager.selectedImages);

// Manually test button
document.getElementById('post-submit-btn').click();
```

---

### **Step 4: Check Network Tab**

1. Open DevTools (F12)
2. Go to "Network" tab
3. Filter by "JS"
4. Look for `posts.js`
5. Check:
   - Status should be 200
   - Size should be ~15-20 KB
   - Check "Response" tab for content

**If you see 304 (Not Modified):**
- Browser is using cached version
- Do hard refresh (Ctrl+Shift+R)

---

## 🐛 **COMMON ISSUES:**

### **Issue 1: Button Not Responding**

**Symptoms:**
- Click "Đăng" button, nothing happens
- No console errors

**Solutions:**
```javascript
// Check if event listener is bound
const btn = document.getElementById('post-submit-btn');
console.log(btn); // Should show button element
console.log(btn.onclick); // Should show null (using addEventListener)

// Check PostManager
console.log(window.postManager); // Should show object

// Manually bind
btn.addEventListener('click', () => {
    console.log('Button clicked manually!');
    window.postManager.createPost();
});
```

---

### **Issue 2: Image Button Not Opening File Picker**

**Symptoms:**
- Click "Ảnh/Video", nothing happens

**Solutions:**
```javascript
// Test onclick directly
document.getElementById('post-image-input').click();

// If that works, check the div
const div = document.querySelector('[onclick*="post-image-input"]');
console.log(div); // Should show div element
console.log(div.onclick); // Should show function

// Manually test
div.click();
```

---

### **Issue 3: selectedImages Undefined**

**Symptoms:**
- Error: "Cannot read property 'length' of undefined"

**Solutions:**
```javascript
// Check initialization
console.log(window.postManager.selectedImages); // Should show []

// If undefined, manually initialize
window.postManager.selectedImages = [];

// Check if initImageUpload was called
console.log(window.postManager.initImageUpload); // Should show function
```

---

### **Issue 4: Files Not Cached/Updated**

**Symptoms:**
- Changes not appearing after refresh

**Solutions:**

**Option 1: Disable cache (temporary)**
1. Open DevTools (F12)
2. Go to "Network" tab
3. Check "Disable cache" checkbox
4. Keep DevTools open while testing

**Option 2: Force reload**
```
Windows: Ctrl + F5 or Ctrl + Shift + R
Mac: Cmd + Shift + R
```

**Option 3: Clear cache manually**
```
Chrome: chrome://settings/clearBrowserData
Edge: edge://settings/clearBrowserData
```

---

## 📋 **CONSOLE COMMANDS:**

### **Check Everything:**
```javascript
// 1. Check if classes exist
console.log('PostManager:', typeof PostManager);
console.log('SocialApp:', typeof SocialApp);

// 2. Check instances
console.log('postManager instance:', window.postManager);
console.log('socialApp instance:', window.socialApp);

// 3. Check properties
console.log('selectedImages:', window.postManager?.selectedImages);

// 4. Check methods
console.log('createPost:', typeof window.postManager?.createPost);
console.log('initImageUpload:', typeof window.postManager?.initImageUpload);

// 5. Check DOM elements
console.log('Submit button:', document.getElementById('post-submit-btn'));
console.log('File input:', document.getElementById('post-image-input'));
console.log('Textarea:', document.getElementById('post-content-textarea'));

// 6. Test file input manually
const fileInput = document.getElementById('post-image-input');
fileInput.addEventListener('change', (e) => {
    console.log('Files selected:', e.target.files);
});
fileInput.click(); // Opens file picker
```

---

## 🔧 **MANUAL FIX (If All Else Fails):**

If buttons still not working, add this to browser console:

```javascript
// Manually bind submit button
const submitBtn = document.getElementById('post-submit-btn');
submitBtn.addEventListener('click', async function() {
    console.log('Manual submit triggered');
    
    const textarea = document.getElementById('post-content-textarea');
    const content = textarea.value.trim();
    
    if (!content) {
        alert('Vui lòng nhập nội dung!');
        return;
    }
    
    try {
        const response = await fetch('/WEB-SN/public/api/posts/create.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                content: content,
                image_urls: []
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Đăng bài thành công!');
            location.reload();
        } else {
            alert('Lỗi: ' + result.error);
        }
    } catch (error) {
        alert('Có lỗi xảy ra: ' + error.message);
    }
});

// Manually bind image button
const imageBtn = document.querySelector('[onclick*="post-image-input"]');
if (imageBtn) {
    imageBtn.onclick = function() {
        document.getElementById('post-image-input').click();
    };
}

console.log('✅ Manual bindings added');
```

---

## 📱 **QUICK TEST CHECKLIST:**

- [ ] Clear browser cache (Ctrl+Shift+R)
- [ ] Open console (F12), check for errors
- [ ] Check `window.postManager` exists
- [ ] Check `window.postManager.selectedImages` is array
- [ ] Click "Đăng" button, see console log
- [ ] Click "Ảnh/Video", file picker opens
- [ ] Select images, see preview
- [ ] Submit with images, check network tab

---

## 🎯 **EXPECTED BEHAVIOR:**

### **When clicking "Đăng" button:**
```
1. createPost() method called
2. Console: "Creating post with content: ..."
3. If images: Upload each image
4. Button text changes: "Đang upload ảnh..."
5. API calls to upload_image.php
6. API call to create.php
7. Alert: "Đăng bài thành công!"
8. Page reloads
```

### **When clicking "Ảnh/Video":**
```
1. onclick fires
2. document.getElementById('post-image-input').click()
3. File picker opens (native OS dialog)
4. User selects files
5. 'change' event fires on input
6. initImageUpload listener catches event
7. showImagePreview() called
8. Thumbnails displayed
```

---

## 📞 **IF STILL NOT WORKING:**

Report these details:
1. Browser version (Chrome/Edge/Firefox?)
2. Console errors (copy exact error message)
3. Network tab (any failed requests?)
4. Result of: `console.log(window.postManager)`
5. Result of test page: http://localhost/WEB-SN/public/test_post_button.html

---

**Debug systematically and we'll find the issue!** 🔍
