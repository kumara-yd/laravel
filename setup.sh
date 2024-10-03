#!/bin/bash

check_directory_not_empty() {
    if [ "$(ls -A "$1")" ]; then
        echo "1"
    else
        echo "0"
    fi
}

# Pastikan composer telah terpasang
if ! command -v composer > /dev/null 2>&1;  then
    echo "Composer tidak ditemukan. Silakan instal Composer terlebih dahulu."
    exit 1
fi

read -p "Masukkan nama aplikasi Laravel: " nama_aplikasi

# Meminta pengguna memasukkan versi Laravel
read -p "Masukkan versi Laravel (contoh: 11.*): " laravel_version

echo "Create Laravel project"
if [ $(check_directory_not_empty "$nama_aplikasi") -eq 0 ]; then
# Melakukan instalasi paket Laravel Installer secara global
composer create-project laravel/laravel "$nama_aplikasi" "$laravel_version"

fi

# Pindah ke dalam folder
cd $nama_aplikasi
php artisan key:generate

echo "MYSQL: "
sed -i "s/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/g" .env

read -p "DB HOST: " db_host
read -p "DB PORT: " db_port
read -p "DB DATABASE: " db_database
read -p "DB USERNAME: " db_user
read -p "DB PASSWORD: " db_pass

sed -i "s/# DB_HOST=127.0.0.1/DB_HOST=$db_host/g" .env
sed -i "s/# DB_PORT=3306/DB_PORT=$db_port/g" .env
sed -i "s/# DB_DATABASE=laravel/DB_DATABASE=$db_database/g" .env
sed -i "s/# DB_USERNAME=root/DB_USERNAME=$db_user/g" .env
sed -i "s/# DB_PASSWORD=/DB_PASSWORD=$db_pass/g" .env

echo "============== Install Laravel Breeze =============="
# Melakukan instalasi paket Laravel Breeze
composer require laravel/breeze --dev
php artisan breeze:install
php artisan migrate
npm install

echo "============== Install Spatie Laravel Permission =============="
# Melakukan instalasi paket Spatie Laravel Permission
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan optimize:clear
php artisan migrate
php artisan permission:create-role admin

echo "============== Additional files =============="
# Copy additional files
cp -r ../src/app .
cp -r ../src/resources .
cp -r ../src/routes .
cp -r ../src/storage .
cp -r ../src/lang .

# membuat link storage
php artisan storage:link

echo "============== Admin Middleware ==============="
php artisan make:middleware SuperAdminMiddleware

cat << 'EOF' > app/Http/Middleware/SuperAdminMiddleware.php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  \$request
     * @param  \Closure  \$next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403); // Akses ditolak untuk non-Super Admin
        }

        return $next($request);
    }
}
EOF

echo "============== Firts user as admin Middleware ==============="
php artisan make:middleware RegisterAsAdmin
cat << 'EOF' > app/Http/Middleware/RegisterAsAdmin.php
<?php

namespace App\Http\Middleware;

use Closure;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Auth;

class RegisterAsAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return $next($request);
        }

        if (Auth::check() && count(User::all()) === 1) {
            $user = Auth::user();
            $user->assignRole('admin');

            //
            $role = Role::where('name', 'admin')->first();

            // Pastikan peran ditemukan sebelum memberikan izin
            if ($role) {
                $permissions = [
                    Permission::create(['name' => 'users.*']),
                    Permission::create(['name' => 'roles.*']),
                    Permission::create(['name' => 'navigations.*']),
                    Permission::create(['name' => 'preferences.*']),
                ];

                // Berikan multiple permissions ke peran
                $role->syncPermissions($permissions);
            }
        }

        return $next($request);
    }
}
EOF


# Menambahkan "use App\Http\Middleware\SuperAdminMiddleware;" pada baris ke-6
sed -i '6i\use App\\Http\\Middleware\\LocaleManager;' bootstrap/app.php
sed -i '6i\use App\\Http\\Middleware\\RegisterAsAdmin;' bootstrap/app.php
sed -i '6i\use App\\Http\\Middleware\\SuperAdminMiddleware;' bootstrap/app.php

# Menambahkan "$middleware->append(SuperAdminMiddleware::class);" pada baris ke-17
sed -i '17i\\t$middleware->alias(['\"locale\"'=>LocaleManager::class, '\"first.user\"'=>RegisterAsAdmin::class, '\"auth.admin\"'=>SuperAdminMiddleware::class]);' bootstrap/app.php

# Ubah redirect sesuai role
sed -i "s/return redirect()->intended(route('dashboard', absolute: false));/\$role = auth()->user()->roles->first(); if (\$role->name == 'admin') { return redirect()->intended(route('dashboard', absolute: false)); } else { return redirect()->intended('\/home'); }/g" app/Http/Controllers/Auth/AuthenticatedSessionController.php

echo "============== Insert traits to controller =============="
sed -i '4i\use App\\Traits\\DisplayTrait;' app/Http/Controllers/Controller.php
sed -i '8i\use DisplayTrait;' app/Http/Controllers/Controller.php

echo "============== PWA support =============="
composer require silviolleite/laravelpwa
php artisan vendor:publish --provider="LaravelPWA\Providers\LaravelPWAServiceProvider"

# pagination bootstrap support
sed -i '5i\use Illuminate\\Pagination\\Paginator;' app/Providers/AppServiceProvider.php
sed -i '24i\Paginator::useBootstrap();' app/Providers/AppServiceProvider.php

# Enable wildcard 
sed -i "s/'enable_wildcard_permission' => false,/'enable_wildcard_permission' => true,/" config/permission.php

echo "============== Migrations and Seeders =============="
# Copy database
cp -r ../src/database .
php artisan migrate --seed

echo "Installation completed"


