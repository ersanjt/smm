# SMM Turk Panel - Deployment Steps for Your Server

## 🚀 Complete Deployment Guide for 92.205.182.143

### 📋 **Server Information**
- **IP Address:** 92.205.182.143
- **cPanel:** AlmaLinux 8
- **Username:** bizdavar-vps
- **Domain:** smm-turk.com (to be configured)

---

## 🔧 **Step 1: Access cPanel**

1. **Open Browser:**
   - Go to: `https://92.205.182.143:2083`
   - Or: `https://smm-turk.com:2083`

2. **Login:**
   - Username: `bizdavar-vps`
   - Password: `[Your Password]`

---

## 🗄️ **Step 2: Database Setup**

### **Create Database:**
1. Go to **"MySQL Databases"**
2. Create new database:
   - Database name: `bizdavar_smmturk`
   - Username: `bizdavar_smmuser`
   - Password: `SMMTurk2024!`
3. Assign user to database with **ALL PRIVILEGES**

### **Import Database:**
1. Go to **"phpMyAdmin"**
2. Select `bizdavar_smmturk` database
3. Click **"Import"**
4. Upload `sql_esjdev_boostpanel.sql` file

---

## 📁 **Step 3: File Upload**

### **Method 1: File Manager (Recommended)**
1. Go to **"File Manager"**
2. Navigate to `public_html/`
3. Delete all existing files
4. Upload the entire SMM project folder
5. Extract if needed

### **Method 2: FTP Upload**
1. Use FTP client (FileZilla)
2. Host: `92.205.182.143`
3. Username: `bizdavar-vps`
4. Password: `[Your Password]`
5. Upload to `/public_html/`

---

## ⚙️ **Step 4: Configuration**

### **Database Configuration:**
Edit `application/config/database.php`:
```php
$db['default'] = array(
    'dsn'      => '',
    'hostname' => 'localhost',
    'username' => 'bizdavar_smmuser',
    'password' => 'SMMTurk2024!',
    'database' => 'bizdavar_smmturk',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt'  => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
```

### **API Configuration:**
Edit `public/api/services.php`:
```php
// SMMFA API Configuration
$external_api_config = [
    'base_url' => 'https://smmfa.com/api/v2',
    'api_key' => 'b9f64c03f177cc3dc754198a17b66bca',
    'timeout' => 30
];

// SMMFollows API Configuration
$smmfollows_api_config = [
    'base_url' => 'https://smmfollows.com/api/v2',
    'api_key' => 'fdbc545f49196428a53189f1ee14e015',
    'timeout' => 30
];
```

---

## 🔒 **Step 5: Security Setup**

### **File Permissions:**
```bash
# Set proper permissions
chmod 755 public_html/
chmod 644 public_html/*.php
chmod 755 public_html/application/
chmod 755 public_html/public/
chmod 600 public_html/application/config/database.php
```

### **Security Headers:**
The `.htaccess` file is already configured with:
- HTTPS redirect
- Security headers
- Access control
- Compression

---

## 🌐 **Step 6: Domain Configuration**

### **Set Document Root:**
1. Go to **"Subdomains"** or **"Addon Domains"**
2. Add domain: `smm-turk.com`
3. Point to: `public_html/public/`

### **SSL Certificate:**
1. Go to **"SSL/TLS"**
2. Enable **"Force HTTPS Redirect"**
3. Install SSL certificate (Let's Encrypt recommended)

---

## 🧪 **Step 7: Testing**

### **Test URLs:**
- Main site: `https://smm-turk.com/`
- User panel: `https://smm-turk.com/panel/`
- Admin panel: `https://smm-turk.com/admin/`
- API: `https://smm-turk.com/api/services.php`

### **API Testing:**
```bash
# Test services API
curl -X GET "https://smm-turk.com/api/services.php?endpoint=services"

# Test balance API
curl -X GET "https://smm-turk.com/api/services.php?endpoint=balance"
```

---

## 📊 **Step 8: Performance Optimization**

### **Enable Caching:**
1. Go to **"Software"** → **"Select PHP Version"**
2. Enable **OPcache**
3. Set memory limit to 256MB

### **Compression:**
The `.htaccess` file already includes compression settings.

---

## 🔧 **Step 9: Final Configuration**

### **Update Base URL:**
Edit `public/assets/js/api.js`:
```javascript
class APIManager {
    constructor() {
        this.baseURL = 'https://smm-turk.com/api/';
        // ... rest of the code
    }
}
```

### **Update All API Calls:**
Replace all `http://localhost:8000` with `https://smm-turk.com`

---

## ✅ **Final Checklist**

- [ ] Database created and imported
- [ ] Files uploaded to public_html/
- [ ] Database configuration updated
- [ ] API keys configured
- [ ] Domain pointing to public_html/public/
- [ ] SSL certificate installed
- [ ] File permissions set correctly
- [ ] All URLs working
- [ ] API endpoints responding
- [ ] Admin panel accessible

---

## 🎯 **Access Information**

### **Admin Panel:**
- URL: `https://smm-turk.com/admin/`
- Username: `admin`
- Password: `admin123`

### **API Endpoints:**
- Services: `https://smm-turk.com/api/services.php?endpoint=services`
- Balance: `https://smm-turk.com/api/services.php?endpoint=balance`
- Orders: `https://smm-turk.com/api/services.php?endpoint=add_order`

---

## 🆘 **Troubleshooting**

### **Common Issues:**
1. **500 Error:** Check file permissions
2. **Database Error:** Verify database credentials
3. **API Not Working:** Check API keys
4. **SSL Issues:** Install SSL certificate

### **Support:**
- Check error logs in cPanel
- Verify all configurations
- Test each component individually

---

**SMM Turk Panel - Ready for Production!** 🚀
