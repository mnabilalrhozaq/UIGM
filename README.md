# Waste Management System

Sistem manajemen sampah berbasis web menggunakan CodeIgniter 4 dengan fitur multi-role dan security enhancement.

## 🚀 Quick Start

### Requirements
- PHP 8.0+
- MySQL 5.7+
- Composer
- Apache/Nginx

### Installation
```bash
# Clone repository
git clone [repository-url]

# Install dependencies
composer install

# Setup environment
cp .env.example .env

# Configure database di .env
database.default.hostname = localhost
database.default.database = eksperimen
database.default.username = root
database.default.password = 

# Import database
mysql -u root eksperimen < database/quick_import.sql

# Set permissions
chmod -R 777 writable/
```

### Access
- **URL**: `http://localhost/eksperimen`
- **Admin Pusat**: admin / admin123
- **User**: userjti / user123
- **Pengelola TPS**: pengelolatps / password123

## 🛡️ Security Features

Sistem dilengkapi dengan enterprise-grade security:
- ✅ Enhanced Session Security (timeout, regeneration, hijacking detection)
- ✅ Input Validation & Sanitization (XSS, SQL injection protection)
- ✅ Rate Limiting (login, API, brute force prevention)
- ✅ Enhanced Authentication & Access Control
- ✅ Security Headers Protection
- ✅ Comprehensive Security Logging
- ✅ Full IPv6 Support

Detail: [README_SECURITY_IMPLEMENTATION.md](README_SECURITY_IMPLEMENTATION.md)

## 📁 Structure

```
app/
├── Controllers/        # Application controllers
│   ├── AdminPusat/    # Admin central controllers
│   ├── User/          # User controllers
│   └── Auth.php       # Authentication
├── Models/            # Database models
├── Views/             # View templates
├── Filters/           # Security filters
├── Libraries/         # Custom libraries
└── Config/            # Configuration files

database/              # Database files
public/               # Public assets
writable/             # Logs & cache
```

## 🔑 User Roles

1. **Admin Pusat**: Manajemen sistem, harga, review data
2. **User**: Input data sampah per unit
3. **Pengelola TPS**: Manajemen data TPS
4. **Super Admin**: Full system access

## 📊 Features

- Multi-role authentication & authorization
- Dashboard dinamis per role
- Waste management (CRUD)
- Price management
- Data review & approval
- Feature toggle system
- Security event logging
- Export data (Excel, PDF)

## 🔧 Development

```bash
# Run development server
php spark serve

# Clear cache
php spark cache:clear

# Run migrations
php spark migrate

# Run tests
./vendor/bin/phpunit
```

## 📝 Documentation

- [Security Implementation](README_SECURITY_IMPLEMENTATION.md)
- [Changelog](CHANGELOG.md)
- [Contributing](CONTRIBUTING.md)

## 📄 License

[LICENSE](LICENSE)

## 🤝 Contributing

Lihat [CONTRIBUTING.md](CONTRIBUTING.md) untuk panduan kontribusi.

---

**Version**: 2.0.0  
**Last Updated**: January 2026  
**Status**: Production Ready ✅
