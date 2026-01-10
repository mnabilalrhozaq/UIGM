# 📝 Panduan Penamaan File CodeIgniter 4

## 🎯 Prinsip Umum Penamaan

### ✅ Do's (Lakukan)
- Gunakan nama yang **descriptive** dan **meaningful**
- Konsisten dengan **convention** yang sudah ada
- Gunakan **English** untuk semua nama file
- Ikuti **PSR-4** autoloading standard
- Gunakan **singular** untuk nama class/model

### ❌ Don'ts (Jangan)
- Nama file terlalu pendek atau cryptic
- Menggunakan spasi dalam nama file
- Menggunakan karakter special (!, @, #, dll)
- Mixing language (English + Indonesian)
- Nama file yang ambigu

## 📁 Penamaan Berdasarkan Tipe File

### 🎮 Controllers
**Location:** `app/Controllers/`

**Convention:** PascalCase + "Controller" suffix (optional)

```php
✅ BENAR:
- AdminPusat/Dashboard.php
- User/Dashboard.php  
- Auth/LoginController.php
- Api/NotificationController.php

❌ SALAH:
- adminpusat.php
- user_dashboard.php
- login-controller.php
- notification_api.php
```

**Namespace Example:**
```php
<?php
namespace App\Controllers\AdminPusat;

class Dashboard extends BaseController
{
    // ...
}
```

### 🗄️ Models
**Location:** `app/Models/`

**Convention:** PascalCase + "Model" suffix

```php
✅ BENAR:
- UserModel.php
- PenilaianModel.php
- WasteModel.php
- NotificationModel.php
- UnitModel.php

❌ SALAH:
- user.php
- penilaian_model.php
- waste-model.php
- notification.php
```

**Class Example:**
```php
<?php
namespace App\Models;

class UserModel extends Model
{
    protected $table = 'users';
    // ...
}
```

### 👁️ Views
**Location:** `app/Views/`

**Convention:** lowercase + underscore

```php
✅ BENAR:
- admin_pusat/dashboard.php
- user/dashboard.php
- auth/login.php
- partials/sidebar_admin.php
- layouts/main_layout.php

❌ SALAH:
- AdminPusat/Dashboard.php
- user-dashboard.php
- authLogin.php
- sidebar.admin.php
```

### ⚙️ Config Files
**Location:** `app/Config/`

**Convention:** PascalCase

```php
✅ BENAR:
- Routes.php
- Database.php
- Filters.php
- App.php
- CustomConfig.php

❌ SALAH:
- routes.php
- database_config.php
- filters-config.php
- app.config.php
```

### 🔒 Filters
**Location:** `app/Filters/`

**Convention:** PascalCase + "Filter" suffix

```php
✅ BENAR:
- RoleFilter.php
- AuthFilter.php
- AdminFilter.php
- ApiAuthFilter.php

❌ SALAH:
- role_filter.php
- auth.php
- admin-filter.php
- api_auth.php
```

### 🛠️ Helpers
**Location:** `app/Helpers/`

**Convention:** lowercase + underscore + "_helper" suffix

```php
✅ BENAR:
- auth_helper.php
- notification_helper.php
- file_upload_helper.php
- custom_helper.php

❌ SALAH:
- AuthHelper.php
- notification.php
- fileUpload_helper.php
- customHelper.php
```

### 📚 Libraries
**Location:** `app/Libraries/`

**Convention:** PascalCase

```php
✅ BENAR:
- EmailService.php
- FileUploader.php
- NotificationService.php
- CustomLibrary.php

❌ SALAH:
- email_service.php
- file-uploader.php
- notification.php
- custom.library.php
```

## 🗂️ Penamaan Folder

### 📁 Folder Structure Convention

```
✅ BENAR:
app/
├── Controllers/
│   ├── AdminPusat/     # PascalCase untuk role
│   ├── User/           # PascalCase untuk role  
│   └── Api/            # PascalCase untuk tipe
├── Views/
│   ├── admin_pusat/    # lowercase + underscore
│   ├── user/           # lowercase
│   └── partials/       # lowercase
└── Models/             # PascalCase

❌ SALAH:
app/
├── controllers/        # should be PascalCase
├── Admin-Pusat/        # no hyphens
├── USER/               # not all caps
└── view/               # should be plural
```

## 🗄️ Database & SQL Files

### 📊 SQL Files
**Location:** `database/sql/`

**Convention:** lowercase + underscore + descriptive

```sql
✅ BENAR:
database/sql/
├── schema.sql
├── initial_data.sql
├── patches/
│   ├── 001_add_notifications_table.sql
│   ├── 002_fix_nilai_input_field.sql
│   └── 003_add_waste_management.sql
└── exports/
    └── backup_2024_01_15.sql

❌ SALAH:
- Database_Export.sql
- add-notifications.sql
- fix_nilai_input.SQL
- backup.sql (not descriptive)
```

### 🗃️ Migration Files
**Location:** `app/Database/Migrations/`

**Convention:** CI4 standard (timestamp + descriptive)

```php
✅ BENAR:
- 2024-01-15-120000_CreateUsersTable.php
- 2024-01-15-130000_AddNotificationsTable.php
- 2024-01-15-140000_ModifyPenilaianTable.php

❌ SALAH:
- create_users.php
- notifications.php
- modify-penilaian.php
```

## 📄 Documentation Files

### 📚 Documentation Convention
**Location:** `docs/`

**Convention:** lowercase + underscore + .md extension

```markdown
✅ BENAR:
docs/
├── development/
│   ├── setup_guide.md
│   ├── api_documentation.md
│   └── deployment_guide.md
├── fixes/
│   ├── dashboard_fixes.md
│   ├── routing_fixes.md
│   └── database_fixes.md
└── user_guide/
    ├── admin_manual.md
    └── user_manual.md

❌ SALAH:
- Setup-Guide.md
- API_Documentation.md
- deploymentGuide.md
- Dashboard Fixes.md (space)
```

## 🎨 Asset Files

### 🎨 CSS Files
**Location:** `public/assets/css/`

**Convention:** lowercase + hyphen + descriptive

```css
✅ BENAR:
public/assets/css/
├── admin-dashboard.css
├── user-interface.css
├── auth-pages.css
└── common-styles.css

❌ SALAH:
- AdminDashboard.css
- user_interface.css
- auth.css (not descriptive)
- common.CSS
```

### ⚡ JavaScript Files
**Location:** `public/assets/js/`

**Convention:** lowercase + hyphen + descriptive

```javascript
✅ BENAR:
public/assets/js/
├── admin-dashboard.js
├── user-interface.js
├── notification-handler.js
└── common-utils.js

❌ SALAH:
- AdminDashboard.js
- user_interface.js
- notification.js (not descriptive)
- utils.JS
```

### 🖼️ Image Files
**Location:** `public/assets/img/`

**Convention:** lowercase + hyphen + descriptive

```
✅ BENAR:
public/assets/img/
├── logo-polban.png
├── icon-dashboard.svg
├── bg-login-page.jpg
└── avatar-default.png

❌ SALAH:
- Logo_POLBAN.png
- dashboardIcon.svg
- background.jpg (not descriptive)
- default.PNG
```

## 🧪 Test Files

### 🧪 Test Convention
**Location:** `tests/`

**Convention:** descriptive + "Test" suffix

```php
✅ BENAR:
tests/
├── unit/
│   ├── UserModelTest.php
│   ├── AuthControllerTest.php
│   └── NotificationServiceTest.php
├── integration/
│   ├── LoginFlowTest.php
│   ├── DashboardAccessTest.php
│   └── ApiEndpointTest.php
└── fixtures/
    ├── user_data.php
    └── sample_notifications.php

❌ SALAH:
- user_test.php
- AuthTest.php (not descriptive)
- login-test.php
- test_dashboard.php
```

## 🔧 Script Files

### ⚙️ Utility Scripts
**Location:** `scripts/`

**Convention:** lowercase + underscore + descriptive

```php
✅ BENAR:
scripts/
├── setup/
│   ├── install_dependencies.php
│   └── configure_environment.php
├── maintenance/
│   ├── cleanup_logs.php
│   ├── backup_database.php
│   └── optimize_images.php
└── deployment/
    ├── deploy_production.php
    └── rollback_version.php

❌ SALAH:
- Install.php
- cleanup-logs.php
- backupDB.php
- deploy.php (not descriptive)
```

## 📋 Checklist Penamaan

### ✅ Before Creating New File

- [ ] Apakah nama file descriptive dan meaningful?
- [ ] Apakah mengikuti convention folder yang sesuai?
- [ ] Apakah konsisten dengan file sejenis yang sudah ada?
- [ ] Apakah menggunakan English dan tidak ada typo?
- [ ] Apakah namespace/class name sesuai dengan nama file?

### ✅ Before Commit

- [ ] Review semua nama file baru
- [ ] Pastikan tidak ada file dengan nama yang ambigu
- [ ] Check autoloading masih berfungsi
- [ ] Test aplikasi setelah rename file
- [ ] Update documentation jika ada perubahan major

## 💡 Tips & Best Practices

### 🎯 Naming Tips

1. **Be Descriptive**: `UserDashboardController.php` lebih baik dari `Dashboard.php`
2. **Be Consistent**: Jika pakai `Controller` suffix, pakai di semua controller
3. **Be Specific**: `LoginFormView.php` lebih baik dari `Form.php`
4. **Be Future-Proof**: Nama yang mudah dipahami 6 bulan kemudian

### 🔄 Refactoring Guidelines

1. **Rename Gradually**: Jangan rename semua file sekaligus
2. **Update References**: Pastikan semua reference terupdate
3. **Test Thoroughly**: Test setiap rename untuk memastikan tidak break
4. **Document Changes**: Catat perubahan nama file yang significant

---

**💡 Remember:** Good naming is an investment in code maintainability!