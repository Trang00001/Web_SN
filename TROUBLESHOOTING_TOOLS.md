# 🛠️ TROUBLESHOOTING TOOLS

**Date:** 20/10/2025  
**Purpose:** Debug nút Đăng bài và Upload ảnh

---

## 🧪 **DIAGNOSTIC PAGES:**

### **1. Test Post Button**
```
http://localhost/WEB-SN/public/test_post_button.html
```

**What it tests:**
- ✅ Basic button click functionality
- ✅ Image upload button (outside modal)
- ✅ Global object availability
- ✅ Modal with image upload
- ✅ Submit button in modal

**How to use:**
1. Open page
2. Run each test (4 tests total)
3. Check if all tests pass
4. If any test fails, that's where the problem is

---

### **2. Diagnostic Tool**
```
http://localhost/WEB-SN/public/diagnostic.html
```

**What it checks:**
- 🔍 Script loading status
- 🔍 Global objects (PostManager, SocialApp)
- 🔍 DOM elements (buttons, inputs)
- 🔍 Manual test capability
- 🔍 Console log capture

**How to use:**
1. Open page
2. Wait for auto-check (green/red indicators)
3. Click "Run Check" buttons
4. Review results in each section

---

## 📋 **VERIFICATION CHECKLIST:**

### **Files Deployed:**
```powershell
# Check if files are deployed correctly
Get-FileHash "e:\Web_SN\Web_SN\public\assets\js\posts.js"
Get-FileHash "E:\xampp\htdocs\WEB-SN\public\assets\js\posts.js"
# Hashes should MATCH
```

### **Structure Verification:**
```powershell
# Check PostManager constructor
Select-String -Path "E:\xampp\htdocs\WEB-SN\public\assets\js\posts.js" -Pattern "this.selectedImages" -Context 0,2

# Check button ID
Select-String -Path "E:\xampp\htdocs\WEB-SN\app\views\pages\posts\home.php" -Pattern "post-submit-btn"

# Check image button onclick
Select-String -Path "E:\xampp\htdocs\WEB-SN\app\views\pages\posts\home.php" -Pattern "post-image-input" -Context 1,1
```

---

## 🔍 **BROWSER CONSOLE COMMANDS:**

### **Quick Check:**
```javascript
// Paste this in browser console (F12)
console.log('=== QUICK DIAGNOSTIC ===');
console.log('PostManager class:', typeof PostManager);
console.log('window.postManager:', typeof window.postManager);
console.log('Submit button:', document.getElementById('post-submit-btn'));
console.log('File input:', document.getElementById('post-image-input'));

if (window.postManager) {
    console.log('✅ postManager exists');
    console.log('selectedImages:', window.postManager.selectedImages);
} else {
    console.log('❌ postManager NOT FOUND');
}
```

### **Manual Button Bind:**
```javascript
// If button still not working, run this:
const btn = document.getElementById('post-submit-btn');
if (btn) {
    btn.addEventListener('click', function() {
        console.log('🔴 Manual click handler fired');
        if (window.postManager) {
            window.postManager.createPost();
        } else {
            console.error('postManager not available');
        }
    });
    console.log('✅ Manual handler added');
}
```

### **Manual Image Button Bind:**
```javascript
// If image button not working:
const imageBtn = document.querySelector('[onclick*="post-image-input"]');
if (imageBtn) {
    imageBtn.onclick = function() {
        console.log('🔴 Manual image button clicked');
        document.getElementById('post-image-input').click();
    };
    console.log('✅ Manual image handler added');
}
```

---

## 🚨 **COMMON PROBLEMS & SOLUTIONS:**

### **Problem 1: "window.postManager is undefined"**

**Cause:** Script not loaded or DOMContentLoaded not fired

**Solution:**
```javascript
// Check if script loaded
console.log('Script loaded:', typeof PostManager !== 'undefined');

// If class exists but instance doesn't, create it
if (typeof PostManager !== 'undefined' && !window.postManager) {
    window.postManager = new PostManager();
    console.log('✅ Created postManager manually');
}
```

---

### **Problem 2: Button clicks but nothing happens**

**Cause:** Event listener not bound

**Solution:**
```javascript
// Check if createPost method exists
console.log(window.postManager?.createPost);

// Manually trigger
if (window.postManager) {
    window.postManager.createPost();
}
```

---

### **Problem 3: File picker not opening**

**Cause:** onclick not working

**Solution:**
```javascript
// Test file input directly
document.getElementById('post-image-input').click();

// If that works, issue is with button onclick
// Add manual handler (see above)
```

---

### **Problem 4: Cached old version**

**Cause:** Browser cache

**Solutions:**
1. **Hard Refresh:** `Ctrl + Shift + R` (Windows) or `Cmd + Shift + R` (Mac)
2. **Disable cache:** DevTools > Network tab > Check "Disable cache"
3. **Clear cache:** `Ctrl + Shift + Delete`

---

## 📊 **STEP-BY-STEP DEBUG PROCESS:**

### **Step 1: Verify Files**
```powershell
# In PowerShell
$source = Get-FileHash "e:\Web_SN\Web_SN\public\assets\js\posts.js"
$dest = Get-FileHash "E:\xampp\htdocs\WEB-SN\public\assets\js\posts.js"
if($source.Hash -eq $dest.Hash) { 
    Write-Host "✅ Files match" -ForegroundColor Green 
} else { 
    Write-Host "❌ Files different - Copy again!" -ForegroundColor Red 
    Copy-Item $source.Path $dest.Path -Force
}
```

### **Step 2: Test Isolation**
```
1. Open: http://localhost/WEB-SN/public/test_post_button.html
2. Run all 4 tests
3. If all pass → Issue is in main page
4. If any fail → Issue is in JavaScript
```

### **Step 3: Check Main Page**
```
1. Open: http://localhost/WEB-SN/app/views/pages/posts/home.php
2. Open Console (F12)
3. Look for errors (red text)
4. Check: console.log(window.postManager)
```

### **Step 4: Diagnostic Page**
```
1. Open: http://localhost/WEB-SN/public/diagnostic.html
2. Check auto-test results
3. Click "Run Check" buttons
4. Review each section
```

### **Step 5: Manual Fix**
```javascript
// If all else fails, paste this in console:
(function() {
    console.log('🔧 Applying manual fix...');
    
    // Create postManager if missing
    if (typeof PostManager !== 'undefined' && !window.postManager) {
        window.postManager = new PostManager();
        console.log('✅ Created postManager');
    }
    
    // Bind submit button
    const submitBtn = document.getElementById('post-submit-btn');
    if (submitBtn && window.postManager) {
        submitBtn.onclick = () => window.postManager.createPost();
        console.log('✅ Bound submit button');
    }
    
    // Bind image button
    const imageBtn = document.querySelector('[onclick*="post-image-input"]');
    if (imageBtn) {
        imageBtn.onclick = () => document.getElementById('post-image-input').click();
        console.log('✅ Bound image button');
    }
    
    console.log('✅ Manual fix applied!');
})();
```

---

## 📞 **WHAT TO REPORT:**

If still not working, provide:

1. **Browser Info:**
   - Browser name & version
   - OS (Windows/Mac/Linux)

2. **Console Output:**
   - Any red errors
   - Result of: `console.log(window.postManager)`
   - Result of: `console.log(typeof PostManager)`

3. **Test Results:**
   - Result from test_post_button.html
   - Result from diagnostic.html

4. **Network Tab:**
   - Is posts.js loading? (Status 200/304?)
   - Any failed requests?

---

## 🎯 **QUICK FIXES:**

### **Fix 1: Force Script Reload**
Add version query param:
```html
<script src="/WEB-SN/public/assets/js/posts.js?v=2"></script>
```

### **Fix 2: Inline Script**
Add at end of home.php:
```html
<script>
console.log('Checking postManager...', window.postManager);
if (!window.postManager && typeof PostManager !== 'undefined') {
    window.postManager = new PostManager();
    console.log('Created postManager:', window.postManager);
}
</script>
```

### **Fix 3: Direct Event**
Change button in home.php:
```html
<button type="button" class="btn btn-primary w-100" 
        onclick="if(window.postManager) window.postManager.createPost()">
    Đăng
</button>
```

---

**Use these tools to find the exact issue!** 🔍
