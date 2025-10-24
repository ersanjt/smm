# SMM Turk Panel - cPanel Deployment Guide

## 🚀 Complete Deployment Guide for smm-turk.com

### 📋 **Prerequisites**
- cPanel hosting account
- PHP 7.4+ support
- MySQL database
- SSL certificate (recommended)

### 🔧 **Step 1: Database Setup**

1. **Create Database:**
   - Login to cPanel
   - Go to "MySQL Databases"
   - Create database: `smmturk_main`
   - Create user: `smmturk_user`
   - Set password: `StrongPassword123!`
   - Assign user to database with ALL PRIVILEGES

2. **Import Database:**
   - Go to "phpMyAdmin"
   - Select `smmturk_main` database
   - Import `sql_esjdev_boostpanel.sql`

### 🔧 **Step 2: File Upload**

1. **Upload Files:**
   - Download project from GitHub
   - Upload all files to `public_html/` directory
   - Ensure proper file permissions (644 for files, 755 for directories)

2. **Set Permissions:**
   ```bash
   chmod 755 public_html/
   chmod 644 public_html/*.php
   chmod 755 public_html/application/
   chmod 755 public_html/public/
   ```

### 🔧 **Step 3: Configuration**

1. **Database Configuration:**
   - Edit `application/config/database.php`
   - Update database credentials:
   ```php
   'hostname' => 'localhost',
   'username' => 'smmturk_user',
   'password' => 'StrongPassword123!',
   'database' => 'smmturk_main',
   ```

2. **API Configuration:**
   - Edit `public/api/services.php`
   - Update API keys:
   ```php
   // SMMFA API
   'api_key' => 'b9f64c03f177cc3dc754198a17b66bca',
   
   // SMMFollows API
   'api_key' => 'fdbc545f49196428a53189f1ee14e015',
   ```

### 🔧 **Step 4: Domain Configuration**

1. **Set Document Root:**
   - Point domain to `public_html/public/`
   - Or create `.htaccess` in root:
   ```apache
   RewriteEngine On
   RewriteRule ^$ public/ [L]
   RewriteRule (.*) public/$1 [L]
   ```

2. **SSL Configuration:**
   - Enable SSL certificate
   - Force HTTPS redirect

### 🔧 **Step 5: Security Setup**

1. **File Permissions:**
   ```bash
   chmod 600 application/config/database.php
   chmod 644 public/.htaccess
   ```

2. **Security Headers:**
   - Add to `.htaccess`:
   ```apache
   Header always set X-Content-Type-Options nosniff
   Header always set X-Frame-Options DENY
   Header always set X-XSS-Protection "1; mode=block"
   ```

### 🔧 **Step 6: Testing**

1. **Test URLs:**
   - Main site: `https://smm-turk.com/`
   - User panel: `https://smm-turk.com/panel/`
   - Admin panel: `https://smm-turk.com/admin/`
   - API: `https://smm-turk.com/api/services.php`

2. **API Testing:**
   ```bash
   curl -X GET "https://smm-turk.com/api/services.php?endpoint=services"
   curl -X GET "https://smm-turk.com/api/services.php?endpoint=balance"
   ```

### 🔧 **Step 7: Performance Optimization**

1. **Enable Caching:**
   - Enable OPcache in cPanel
   - Set cache headers in `.htaccess`

2. **Compression:**
   ```apache
   <IfModule mod_deflate.c>
       AddOutputFilterByType DEFLATE text/plain
       AddOutputFilterByType DEFLATE text/html
       AddOutputFilterByType DEFLATE text/xml
       AddOutputFilterByType DEFLATE text/css
       AddOutputFilterByType DEFLATE application/xml
       AddOutputFilterByType DEFLATE application/xhtml+xml
       AddOutputFilterByType DEFLATE application/rss+xml
       AddOutputFilterByType DEFLATE application/javascript
       AddOutputFilterByType DEFLATE application/x-javascript
   </IfModule>
   ```

### 🔧 **Step 8: Monitoring**

1. **Error Logs:**
   - Monitor error logs in cPanel
   - Set up email notifications for errors

2. **Performance Monitoring:**
   - Use cPanel's resource usage tools
   - Monitor database performance

### 📊 **Final Checklist**

- [ ] Database created and imported
- [ ] Files uploaded with correct permissions
- [ ] Database configuration updated
- [ ] API keys configured
- [ ] SSL certificate installed
- [ ] Domain pointing to correct directory
- [ ] Security headers configured
- [ ] Caching enabled
- [ ] Error monitoring set up
- [ ] All URLs working correctly

### 🎯 **Access URLs**

- **Main Website:** https://smm-turk.com/
- **User Panel:** https://smm-turk.com/panel/
- **Admin Panel:** https://smm-turk.com/admin/
- **API Documentation:** https://smm-turk.com/panel/api.html

### 🔐 **Default Credentials**

- **Admin Panel:** admin / admin123
- **API Keys:** Configured in services.php

### 📞 **Support**

For deployment issues, check:
1. Error logs in cPanel
2. Database connection
3. File permissions
4. API connectivity
5. SSL certificate status

---

**SMM Turk Panel - Professional SMM Management System**
