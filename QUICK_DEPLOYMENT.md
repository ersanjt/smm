# 🚀 SMM Turk Panel - Quick Deployment Guide

## 📥 **Step 1: Download Project**

The project is ready in your local directory. You need to:

1. **Create ZIP file** (already done: `smm-turk-panel.zip`)
2. **Upload to server** via cPanel File Manager

---

## 🔧 **Step 2: Server Access**

### **cPanel Login:**
- URL: `https://92.205.182.143:2083`
- Username: `bizdavar-vps`
- Password: `[Your Password]`

---

## 🗄️ **Step 3: Database Setup**

### **Create Database:**
1. **MySQL Databases** → **Create Database**
   - Database name: `bizdavar_smmturk`
   - Username: `bizdavar_smmuser`
   - Password: `SMMTurk2024!`
   - **Assign ALL PRIVILEGES**

### **Import Database:**
1. **phpMyAdmin** → Select `bizdavar_smmturk`
2. **Import** → Upload `sql_esjdev_boostpanel.sql`

---

## 📁 **Step 4: File Upload**

### **Upload Files:**
1. **File Manager** → `public_html/`
2. **Delete existing files**
3. **Upload** `smm-turk-panel.zip`
4. **Extract** the ZIP file
5. **Move contents** to root of public_html

---

## ⚙️ **Step 5: Quick Configuration**

### **Database Config:**
Edit `application/config/database.php`:
```php
'hostname' => 'localhost',
'username' => 'bizdavar_smmuser',
'password' => 'SMMTurk2024!',
'database' => 'bizdavar_smmturk',
```

### **API Config:**
Edit `public/api/services.php`:
```php
// SMMFA API Key
'api_key' => 'b9f64c03f177cc3dc754198a17b66bca',

// SMMFollows API Key  
'api_key' => 'fdbc545f49196428a53189f1ee14e015',
```

---

## 🌐 **Step 6: Domain Setup**

### **Add Domain:**
1. **Addon Domains** → Add `smm-turk.com`
2. **Document Root:** `public_html/public/`
3. **Enable SSL** → Let's Encrypt

---

## 🧪 **Step 7: Test**

### **Test URLs:**
- ✅ `https://smm-turk.com/`
- ✅ `https://smm-turk.com/panel/`
- ✅ `https://smm-turk.com/admin/`
- ✅ `https://smm-turk.com/api/services.php`

---

## 🎯 **Final Steps**

1. **Set Permissions:**
   ```bash
   chmod 755 public_html/
   chmod 644 public_html/*.php
   chmod 600 application/config/database.php
   ```

2. **Enable SSL:**
   - SSL/TLS → Force HTTPS

3. **Test Everything:**
   - All pages loading
   - API working
   - Admin panel accessible

---

## 🎊 **You're Done!**

**SMM Turk Panel is now live at:**
- **Main Site:** https://smm-turk.com/
- **User Panel:** https://smm-turk.com/panel/
- **Admin Panel:** https://smm-turk.com/admin/

**Admin Login:**
- Username: `admin`
- Password: `admin123`

---

**Need Help?** Check the detailed `DEPLOYMENT_STEPS.md` file! 📋
