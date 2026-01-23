# 🚀 HRM Dev - Setup Guide

Panduan lengkap untuk setup aplikasi HRM Development dari awal hingga siap digunakan.

---

## 📋 Prerequisites

Pastikan sudah terinstall:
- ✅ Docker & Docker Compose
- ✅ Git

---

## 🔧 Installation Steps

### 1️⃣ **Clone Repository**

```bash
cd /home/tako/hrm-dev
git pull origin main
```

---

### 2️⃣ **Setup Environment**

```bash
cd src
cp .env.example .env
```

Edit `.env` sesuai kebutuhan:
```env
APP_NAME="HRM Development"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=pgsql
DB_HOST=postgres_master
DB_PORT=5432
DB_DATABASE=test
DB_USERNAME=user123
DB_PASSWORD=password123
```

---

### 3️⃣ **Start Docker Containers**

```bash
cd /home/tako/hrm-dev
docker-compose up -d
```

Containers yang akan jalan:
- `postgres_master` - Database primary
- `postgres_slave` - Database standby (replication)
- `php_laravel_1` & `php_laravel_2` - PHP-FPM workers
- `nginx_laravel_1` & `nginx_laravel_2` - Nginx app servers
- `nginx_loadbalancer` - Load balancer (port 8080)
- `laravel_queue` - Queue worker

**Cek status:**
```bash
docker-compose ps
```

---

### 4️⃣ **Install Dependencies**

```bash
docker-compose exec php_laravel_1 composer install
```

---

### 5️⃣ **Generate Application Key**

```bash
docker-compose exec php_laravel_1 php artisan key:generate
```

---

### 6️⃣ **Run Migrations**

```bash
docker-compose exec php_laravel_1 php artisan migrate
```

Tables yang dibuat:
- `p_users` - Users
- `p_roles` - Roles
- `p_permissions` - Permissions
- `p_user_roles` - User-Role pivot
- `m_companies` - Master Companies
- `m_jabatan` - Master Jabatan
- `m_grup_jam_kerja` - Master Shift Groups
- `m_hari_libur` - Master Holidays
- ... dll

---

### 7️⃣ **Run Seeders (IMPORTANT!)**

```bash
docker-compose exec php_laravel_1 php artisan db:seed
```

Ini akan:
1. ✅ Create 11 users (10 fake + 1 test user)
2. ✅ Setup roles & permissions (HR & Admin)
3. ✅ Assign role HR ke user pertama
4. ✅ (Optional) Seed companies atau data master lainnya

---

## 🔐 RBAC (Role-Based Access Control) Setup

### Roles & Permissions yang Tersedia

#### **Role: HR** (Full Access)
- All permissions
- Dapat akses semua master data
- Permissions:
  - `worktime.view`, `worktime.update`
  - `employee.view`, `employee.create`, `employee.update`, `employee.delete`

#### **Role: Admin** (Limited Access)
- Hanya permissions: `worktime.view`, `worktime.update`

### Default User Credentials

Setelah seeding, gunakan user ini untuk login:

**User 1 (HR Role):**
```
Email: (check database - user id 1)
Username: (check database - user id 1)
Password: password
```

**Test User (HR Role):**
```
Email: test@example.com
Username: test
Password: password
```

---

## 👤 Cara Assign Role ke User

### Via Tinker (Manual)

```bash
docker-compose exec php_laravel_1 php artisan tinker
```

```php
// Assign role HR ke user
$user = App\Models\User::find(1);
$user->assignRole('hr');

// Assign role Admin
$user = App\Models\User::find(2);
$user->assignRole('admin');

// Check roles user
$user = App\Models\User::find(1);
$user->getRoleNames(); // Output: ["hr"]

// Check permissions
$user->getAllPermissions();
```

### Via Code (di Controller/Seeder)

```php
use App\Models\User;

// Create user dengan role
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'username' => 'johndoe',
    'password' => Hash::make('password'),
    'user_token' => Hash::make('john@example.com'),
]);

// Assign role
$user->assignRole('hr');

// Atau bisa langsung saat seeding
$user->syncRoles(['hr']);
```

---

## 🛡️ Cara Protect Routes dengan Role

### Method 1: Middleware di Routes

```php
// web.php
Route::prefix('master-data')->middleware(['role:hr'])->group(function () {
    Route::get('/grup-jam-kerja', [GrupJamKerjaController::class, 'index']);
});

// Multiple roles
Route::middleware(['role:hr|admin'])->group(function () {
    // Routes yang bisa diakses HR atau Admin
});
```

### Method 2: Permission-based

```php
Route::middleware(['permission:employee.create'])->group(function () {
    Route::post('/employee', [EmployeeController::class, 'store']);
});
```

### Method 3: Di Controller

```php
public function __construct()
{
    $this->middleware('role:hr');
    // atau
    $this->middleware('permission:employee.create');
}

// Check di method
public function edit($id)
{
    if (!auth()->user()->hasRole('hr')) {
        abort(403, 'Unauthorized');
    }
    
    // atau
    if (!auth()->user()->can('employee.update')) {
        abort(403, 'Unauthorized');
    }
}
```

### Method 4: Di Blade View

```blade
@role('hr')
    <button>Edit</button>
@endrole

@hasrole('hr|admin')
    <div>Content for HR or Admin</div>
@endhasrole

@can('employee.delete')
    <button>Delete</button>
@endcan
```

---

## 🔑 Login Flow

1. **Navigate to:** `http://localhost:8080/login`
2. **Enter credentials:**
   - Email: `test@example.com`
   - Password: `password`
3. **Access master data:** `http://localhost:8080/master-data/grup-jam-kerja`

---

## 📊 Cara Tambah Role & Permission Baru

### 1. Edit `RolePermissionSeeder.php`

```php
// database/seeders/RolePermissionSeeder.php

$permissions = [
    // ... existing permissions
    'salary.view',
    'salary.create',
    'salary.update',
    'salary.delete',
];

// Create new role
$manager = Role::firstOrCreate(['name' => 'manager']);
$manager->syncPermissions([
    'employee.view',
    'salary.view',
]);
```

### 2. Re-run Seeder

```bash
docker-compose exec php_laravel_1 php artisan db:seed --class=RolePermissionSeeder
```

---

## 🧪 Testing

### Run All Tests

```bash
docker-compose exec php_laravel_1 php artisan test
```

### Run Specific Test

```bash
docker-compose exec php_laravel_1 php artisan test --filter=GrupJamKerjaControllerTest
```

---

## 📦 Useful Commands

### Clear Cache

```bash
docker-compose exec php_laravel_1 php artisan cache:clear
docker-compose exec php_laravel_1 php artisan config:clear
docker-compose exec php_laravel_1 php artisan route:clear
```

### Clear Permission Cache

```bash
docker-compose exec php_laravel_1 php artisan permission:cache-reset
```

### Check Routes

```bash
docker-compose exec php_laravel_1 php artisan route:list
```

### Check Database

```bash
docker-compose exec php_laravel_1 php artisan tinker
```

```php
// Count users
App\Models\User::count();

// Show all roles
Spatie\Permission\Models\Role::with('permissions')->get();

// Show users with roles
App\Models\User::with('roles')->get();
```

---

## 🐛 Troubleshooting

### Issue: "Permission denied" errors

```bash
# Fix permissions
docker-compose exec php_laravel_1 chown -R www-data:www-data storage bootstrap/cache
docker-compose exec php_laravel_1 chmod -R 775 storage bootstrap/cache
```

### Issue: Cannot access localhost:8080

1. Check containers are running: `docker-compose ps`
2. Restart containers: `docker-compose restart`
3. Check logs: `docker-compose logs nginx_loadbalancer`

### Issue: Database connection error

1. Check database is healthy: `docker-compose ps postgres_master`
2. Wait for health check to pass (may take 30s-1min)
3. Restart PHP containers: `docker-compose restart php_laravel_1 php_laravel_2`

---

## 🎯 Quick Start (TL;DR)

```bash
# 1. Start containers
cd /home/tako/hrm-dev
docker-compose up -d

# 2. Install & setup
docker-compose exec php_laravel_1 composer install
docker-compose exec php_laravel_1 php artisan key:generate
docker-compose exec php_laravel_1 php artisan migrate
docker-compose exec php_laravel_1 php artisan db:seed

# 3. Access application
# URL: http://localhost:8080/login
# Email: test@example.com
# Password: password
```

---

## 📚 Additional Resources

- **Laravel Permission Docs:** https://spatie.be/docs/laravel-permission
- **Laravel Docs:** https://laravel.com/docs
- **Docker Compose Docs:** https://docs.docker.com/compose/

---

## ✅ Checklist

After setup, verify:

- [ ] Docker containers running (`docker-compose ps`)
- [ ] Database migrated (`p_users` table exists)
- [ ] Seeder completed (11 users, 2 roles created)
- [ ] Can access http://localhost:8080
- [ ] Can login with test user
- [ ] Can access master data (HR role working)
- [ ] Tests passing (`php artisan test`)

---

**Setup Complete! Happy Coding! 🎉**
