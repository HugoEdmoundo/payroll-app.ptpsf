# Fix untuk Error "Attempt to read property 'name' on null"

## Penyebab Error
User di database tidak punya `role_id` (NULL), sehingga ketika akses `auth()->user()->role->name` akan error.

## Solusi yang Sudah Diterapkan

### 1. Safety Checks di Views
Semua view sekarang cek apakah role ada sebelum akses property:
```php
// Sebelum (ERROR jika role NULL):
{{ auth()->user()->role->name }}

// Sesudah (AMAN):
{{ auth()->user()->role ? auth()->user()->role->name : 'No Role' }}
```

### 2. Helper Method di User Model
Tambah method `isSuperadmin()` yang aman dari null:
```php
public function isSuperadmin()
{
    return $this->role && $this->role->is_superadmin;
}
```

### 3. Update Semua Controllers
Semua controller sekarang pakai `isSuperadmin()` bukan `role->is_superadmin`:
```php
// Sebelum (ERROR jika role NULL):
if (!auth()->user()->role->is_superadmin) {
    abort(403);
}

// Sesudah (AMAN):
if (!auth()->user()->isSuperadmin()) {
    abort(403);
}
```

## Yang Perlu Dilakukan di VPS

### Opsi 1: Assign Role ke User yang Belum Punya Role (RECOMMENDED)

Login ke VPS dan jalankan:

```bash
cd /opt/just-atesting
php artisan tinker
```

Lalu jalankan command ini:

```php
// Cek user yang belum punya role
$usersWithoutRole = App\Models\User::whereNull('role_id')->get();
echo "Users without role: " . $usersWithoutRole->count() . "\n";

// Ambil role default (User)
$defaultRole = App\Models\Role::where('is_default', true)->first();

// Jika tidak ada role default, ambil role pertama yang bukan superadmin
if (!$defaultRole) {
    $defaultRole = App\Models\Role::where('is_superadmin', false)->first();
}

// Assign role ke semua user yang belum punya role
foreach ($usersWithoutRole as $user) {
    $user->role_id = $defaultRole->id;
    $user->save();
    echo "Assigned role '{$defaultRole->name}' to user: {$user->email}\n";
}

echo "Done!\n";
exit;
```

### Opsi 2: Buat Role Default Jika Belum Ada

Jika belum ada role "User" sebagai default:

```bash
php artisan tinker
```

```php
// Buat role default
$role = App\Models\Role::create([
    'name' => 'User',
    'description' => 'Default user role',
    'is_superadmin' => false,
    'is_default' => true
]);

// Assign beberapa permission dasar
$permissions = App\Models\Permission::whereIn('key', [
    'dashboard.view',
    'karyawan.view'
])->get();

$role->permissions()->sync($permissions->pluck('id'));

echo "Default role created!\n";
exit;
```

### Opsi 3: Update User Tertentu Jadi Superadmin

Jika ada user yang harus jadi superadmin:

```bash
php artisan tinker
```

```php
// Cari user by email
$user = App\Models\User::where('email', 'admin@example.com')->first();

// Ambil role superadmin
$superadminRole = App\Models\Role::where('is_superadmin', true)->first();

// Assign role
$user->role_id = $superadminRole->id;
$user->save();

echo "User {$user->email} is now superadmin!\n";
exit;
```

## Testing

Setelah assign role, test dengan:

1. Logout dari aplikasi
2. Login kembali
3. Cek apakah error sudah hilang
4. Cek apakah menu dan button muncul sesuai permission

## Catatan Penting

- Setiap user HARUS punya role
- Jika user baru dibuat, pastikan assign role
- Role "Superadmin" harus ada di database
- Role "User" sebaiknya ada sebagai default role

## Prevention untuk Future

Update UserController untuk auto-assign role saat create user baru:

```php
public function store(Request $request)
{
    // ... validation ...
    
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $request->role_id ?? Role::where('is_default', true)->first()->id, // AUTO ASSIGN DEFAULT ROLE
        'is_active' => true,
    ]);
    
    // ...
}
```
