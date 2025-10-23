# 🚀 راهنمای Deploy پروژه PHP/CodeIgniter

## Railway Deployment

### مراحل Deploy:

1. **ورود به Railway:**
   - برو به [railway.app](https://railway.app)
   - Sign in with GitHub

2. **ایجاد پروژه جدید:**
   - کلیک روی "New Project"
   - انتخاب "Deploy from GitHub repo"
   - انتخاب repository: `ersanjt/smm`

3. **تنظیمات خودکار:**
   - Railway خودکار PHP را تشخیص می‌دهد
   - دیتابیس PostgreSQL اضافه می‌شود
   - SSL خودکار فعال می‌شود

4. **تنظیمات دیتابیس:**
   - در Railway dashboard
   - Variables tab
   - اضافه کردن متغیرهای محیطی

### متغیرهای محیطی مورد نیاز:

```env
CI_ENV=production
DB_HOST=localhost
DB_USERNAME=root
DB_PASSWORD=
DB_DATABASE=bizdavar_smm
```

### دستورات مفید:

```bash
# نصب Railway CLI
npm install -g @railway/cli

# Login
railway login

# Deploy
railway deploy

# مشاهده logs
railway logs
```

## گزینه‌های دیگر:

### Render.com
1. برو به [render.com](https://render.com)
2. Sign up with GitHub
3. New Web Service
4. Connect repository
5. Build Command: `composer install`
6. Start Command: `php -S 0.0.0.0:$PORT`

### Heroku
1. برو به [heroku.com](https://heroku.com)
2. New App
3. Connect GitHub
4. Deploy branch: master

## فایل‌های آماده:

✅ `railway.json` - تنظیمات Railway
✅ `nixpacks.toml` - پیکربندی PHP
✅ `composer.json` - تنظیمات Composer
✅ `Procfile` - برای Heroku
✅ `app.json` - تنظیمات Heroku

## نکات مهم:

- پروژه PHP/CodeIgniter آماده deploy است
- دیتابیس MySQL باید import شود
- تنظیمات دیتابیس در `application/config/database.php`
- فایل‌های حساس در `.gitignore` قرار دارند
