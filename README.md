# BoostPanel SMM Application

A complete Social Media Marketing (SMM) panel built with CodeIgniter framework.

## 🚀 Features

- **Complete SMM Panel**: Manage social media marketing services
- **User Management**: User registration, login, and profile management
- **Order System**: Place and manage SMM orders
- **Payment Integration**: Multiple payment gateways (PayPal, Stripe, etc.)
- **Admin Dashboard**: Complete admin panel for management
- **API Support**: RESTful API for external integrations
- **Multi-language**: Support for multiple languages
- **Responsive Design**: Mobile-friendly interface

## 🛠 Technology Stack

- **Framework**: CodeIgniter 3.1.11
- **Database**: MySQL
- **Frontend**: Bootstrap 4, jQuery, Font Awesome
- **PHP**: 8.2+
- **Payment Gateways**: PayPal, Stripe, 2Checkout, CoinPayments, etc.

## 📋 Requirements

- PHP >= 5.6
- MySQL 5.7+
- Apache/Nginx with mod_rewrite
- PHP Extensions: CURL, OpenSSL, PDO, Mbstring

## 🚀 Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/ersanjt/smm-boostpanel.git
   cd smm-boostpanel
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Database Setup**
   - Create a MySQL database
   - Import `sql_esjdev_boostpanel.sql`
   - Update database configuration in `application/config/database.php`

4. **Configure Application**
   - Update `application/config/config.php` with your settings
   - Set proper file permissions

5. **Run the application**
   ```bash
   php -S localhost:8000
   ```

## 🌐 Live Demo

- **Local Development**: http://localhost:8000
- **Production**: [Coming Soon]

## 📁 Project Structure

```
smm-boostpanel/
├── application/          # Application code
│   ├── controllers/     # Controllers
│   ├── models/         # Models
│   ├── views/          # Views
│   └── config/         # Configuration
├── public/             # Public assets
├── system/            # CodeIgniter system files
├── sql/               # Database files
└── documentation/     # Documentation
```

## 🔧 Configuration

### Database Configuration
Update `application/config/database.php`:
```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'your_username',
    'password' => 'your_password',
    'database' => 'your_database',
    'dbdriver' => 'mysqli',
);
```

### Application Configuration
Update `application/config/config.php`:
```php
$config['base_url'] = 'https://yourdomain.com/';
```

## 🚀 Deployment

### Vercel Deployment
1. Connect your GitHub repository to Vercel
2. Configure environment variables
3. Deploy automatically

### Traditional Hosting
1. Upload files to your web server
2. Configure database
3. Set proper file permissions
4. Update configuration files

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📞 Support

For support, email support@yourdomain.com or create an issue in this repository.

## 🙏 Acknowledgments

- CodeIgniter Framework
- Bootstrap
- All contributors and supporters

---

**Made with ❤️ by Ersan J. Tabrizi**