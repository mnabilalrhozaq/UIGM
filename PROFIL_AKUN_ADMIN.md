# Fitur: Profil Akun Admin

## Deskripsi
Mengubah menu "Pengaturan" menjadi "Profil Akun" dengan fitur lengkap untuk mengelola informasi profil dan keamanan akun admin.

## Perubahan yang Dilakukan

### 1. Sidebar Menu
**File**: `app/Views/partials/sidebar_admin_pusat.php`

#### Sebelum:
```html
<a href="/admin-pusat/pengaturan">
    <i class="fas fa-cogs"></i>
    <span>Pengaturan</span>
</a>
```

#### Sesudah:
```html
<a href="/admin-pusat/profil">
    <i class="fas fa-user-circle"></i>
    <span>Profil Akun</span>
</a>
```

### 2. Controller Baru
**File**: `app/Controllers/Admin/Profil.php`

**Methods**:
- `index()` - Menampilkan halaman profil
- `update()` - Update informasi profil
- `changePassword()` - Ubah password
- `validateSession()` - Validasi session admin

### 3. View Baru
**File**: `app/Views/admin_pusat/profil.php`

**Sections**:
1. Profile Card (Sidebar kiri)
2. Edit Profil Form
3. Ubah Password Form

### 4. Routes Baru
**File**: `app/Config/Routes/Admin/profil.php`

```php
$routes->get('profil', 'Admin\\Profil::index');
$routes->post('profil/update', 'Admin\\Profil::update');
$routes->post('profil/change-password', 'Admin\\Profil::changePassword');
```

## Fitur Profil Akun

### 1. Profile Card (Sidebar Kiri)

#### Informasi yang Ditampilkan:
- **Avatar**: Icon user besar
- **Nama Lengkap**: Nama admin
- **Role Badge**: Badge dengan role (Admin Pusat/Super Admin)
- **Email**: Email admin
- **No. Telepon**: Nomor telepon (jika ada)
- **Unit**: Nama unit (jika ada)
- **Tanggal Bergabung**: Tanggal registrasi

#### Contoh Display:
```
┌─────────────────────────┐
│    👤 (Avatar Icon)     │
│                         │
│   John Doe              │
│   [Admin Pusat]         │
│                         │
│ 📧 john@example.com     │
│ 📱 08123456789          │
│ 🏢 Unit Pusat           │
│ 📅 Bergabung: 01 Jan 24 │
└─────────────────────────┘
```

### 2. Edit Profil Form

#### Field yang Dapat Diubah:
- ✅ **Nama Lengkap** (required)
- ✅ **Email** (required, unique)
- ✅ **No. Telepon** (optional)

#### Field yang Tidak Dapat Diubah:
- ❌ **Username** (disabled)
- ❌ **Role** (disabled)

#### Validasi:
- Nama lengkap wajib diisi
- Email wajib diisi dan format valid
- Email harus unique (tidak boleh sama dengan user lain)
- No. telepon optional

#### Flow Update Profil:
```
1. User ubah data di form
2. Klik "Simpan Perubahan"
3. Validasi di controller
4. Update database
5. Update session
6. Tampilkan success message
7. Reload halaman
```

### 3. Ubah Password Form

#### Field:
- **Password Lama** (required)
- **Password Baru** (required, min 6 karakter)
- **Konfirmasi Password Baru** (required, harus sama dengan password baru)

#### Fitur:
- Toggle show/hide password (icon mata)
- Validasi password lama
- Validasi password baru minimal 6 karakter
- Validasi konfirmasi password harus sama

#### Flow Ubah Password:
```
1. User isi password lama
2. User isi password baru
3. User konfirmasi password baru
4. Klik "Ubah Password"
5. Validasi password lama benar
6. Validasi password baru >= 6 karakter
7. Validasi konfirmasi sama
8. Update password di database
9. Tampilkan success message
10. Form di-reset
```

## API Endpoints

### 1. GET /admin-pusat/profil
**Deskripsi**: Menampilkan halaman profil

**Response**: HTML page

**Data yang Ditampilkan**:
- User data dari database
- Unit data (jika ada)

### 2. POST /admin-pusat/profil/update
**Deskripsi**: Update informasi profil

**Request Body**:
```json
{
  "nama_lengkap": "John Doe",
  "email": "john@example.com",
  "no_telepon": "08123456789"
}
```

**Response Success**:
```json
{
  "success": true,
  "message": "Profil berhasil diperbarui"
}
```

**Response Error**:
```json
{
  "success": false,
  "message": "Email sudah digunakan oleh user lain"
}
```

### 3. POST /admin-pusat/profil/change-password
**Deskripsi**: Ubah password

**Request Body**:
```json
{
  "password_lama": "oldpass123",
  "password_baru": "newpass123",
  "password_konfirmasi": "newpass123"
}
```

**Response Success**:
```json
{
  "success": true,
  "message": "Password berhasil diubah"
}
```

**Response Error**:
```json
{
  "success": false,
  "message": "Password lama tidak sesuai"
}
```

## Validasi

### Update Profil:
- ✅ Nama lengkap tidak boleh kosong
- ✅ Email tidak boleh kosong
- ✅ Email harus format valid
- ✅ Email harus unique (kecuali email sendiri)
- ✅ Session harus valid
- ✅ Role harus admin_pusat atau super_admin

### Ubah Password:
- ✅ Semua field wajib diisi
- ✅ Password lama harus benar
- ✅ Password baru minimal 6 karakter
- ✅ Konfirmasi password harus sama dengan password baru
- ✅ Session harus valid

## Keamanan

### 1. Session Validation
```php
private function validateSession(): bool
{
    $session = session();
    $user = $session->get('user');
    
    return $session->get('isLoggedIn') && 
           isset($user['role']) &&
           in_array($user['role'], ['admin_pusat', 'super_admin']);
}
```

### 2. CSRF Protection
- Semua form menggunakan CSRF token
- Token di-generate otomatis
- Token di-validate di setiap request

### 3. Password Storage
- Saat ini: Plain text (development)
- Production: Harus menggunakan password_hash()

### 4. Email Uniqueness
- Check email sudah digunakan user lain
- Exclude email user sendiri saat update

## UI/UX Features

### 1. Toggle Password Visibility
```javascript
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById('icon_' + fieldId);
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
```

### 2. Alert Messages
- Success: Green alert dengan icon check
- Error: Red alert dengan icon exclamation
- Auto dismiss setelah 5 detik
- Smooth fade in/out animation

### 3. Form Reset
- Form ubah password di-reset setelah berhasil
- Form edit profil reload halaman setelah berhasil

### 4. Responsive Design
- Mobile friendly
- Profile card sticky di desktop
- Stack vertical di mobile

## Testing Checklist

### Test Update Profil:
- [ ] Login sebagai admin
- [ ] Buka menu "Profil Akun"
- [ ] Ubah nama lengkap
- [ ] Ubah email
- [ ] Ubah no. telepon
- [ ] Klik "Simpan Perubahan"
- [ ] ✅ Data berhasil diupdate
- [ ] ✅ Session terupdate
- [ ] ✅ Halaman reload dengan data baru

### Test Validasi Email:
- [ ] Coba ubah email ke email user lain
- [ ] ✅ Harus muncul error "Email sudah digunakan"
- [ ] Coba ubah email ke email sendiri
- [ ] ✅ Harus berhasil (tidak error)

### Test Ubah Password:
- [ ] Isi password lama yang salah
- [ ] ✅ Harus error "Password lama tidak sesuai"
- [ ] Isi password baru < 6 karakter
- [ ] ✅ Harus error "Password minimal 6 karakter"
- [ ] Isi konfirmasi password berbeda
- [ ] ✅ Harus error "Password tidak cocok"
- [ ] Isi semua field dengan benar
- [ ] ✅ Password berhasil diubah
- [ ] ✅ Form di-reset

### Test Toggle Password:
- [ ] Klik icon mata di password lama
- [ ] ✅ Password terlihat
- [ ] Klik lagi
- [ ] ✅ Password tersembunyi

### Test Responsive:
- [ ] Buka di desktop
- [ ] ✅ Profile card sticky di kiri
- [ ] Buka di mobile
- [ ] ✅ Profile card di atas
- [ ] ✅ Form di bawah

## File Structure

```
app/
├── Controllers/
│   └── Admin/
│       └── Profil.php (NEW)
├── Views/
│   └── admin_pusat/
│       └── profil.php (NEW)
├── Config/
│   ├── Routes.php (MODIFIED)
│   └── Routes/
│       └── Admin/
│           └── profil.php (NEW)
└── Views/
    └── partials/
        └── sidebar_admin_pusat.php (MODIFIED)
```

## Database Impact

### Table: users
**Fields yang Dapat Diupdate**:
- `nama_lengkap`
- `email`
- `no_telepon`
- `password`
- `updated_at`

**Fields yang Tidak Dapat Diupdate**:
- `username`
- `role`
- `unit_id`
- `created_at`

## Backward Compatibility

- ✅ Route `/admin-pusat/pengaturan` masih bisa diakses (jika ada)
- ✅ Tidak ada breaking changes
- ✅ Data existing tetap valid
- ✅ Session tidak berubah

## Future Improvements

### 1. Upload Avatar
- Tambah fitur upload foto profil
- Crop dan resize otomatis
- Storage di server atau cloud

### 2. Two-Factor Authentication
- SMS OTP
- Email OTP
- Google Authenticator

### 3. Activity Log
- Log setiap perubahan profil
- Log login history
- Log password changes

### 4. Password Strength Meter
- Visual indicator kekuatan password
- Saran password yang kuat
- Check password di database breach

### 5. Email Verification
- Kirim email verifikasi saat ubah email
- Konfirmasi email baru sebelum update

## Kesimpulan

Fitur Profil Akun memberikan admin kemampuan untuk:
1. ✅ Melihat informasi profil lengkap
2. ✅ Mengubah informasi personal (nama, email, telepon)
3. ✅ Mengubah password dengan aman
4. ✅ Toggle visibility password
5. ✅ Validasi data yang ketat
6. ✅ UI/UX yang user-friendly

Menu "Pengaturan" telah berhasil diubah menjadi "Profil Akun" dengan fitur yang lebih fokus pada manajemen akun personal admin.
