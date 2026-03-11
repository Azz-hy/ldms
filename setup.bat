@echo off
setlocal enabledelayedexpansion

echo.
echo  =========================================
echo   LDMS - Local Delivery Management System
echo   Windows Setup Script
echo  =========================================
echo.

echo [1/6] Creating Laravel project...
composer create-project laravel/laravel ldms --prefer-dist
if %errorlevel% neq 0 (
    echo ERROR: Composer failed. Make sure Composer is installed.
    echo Download from: https://getcomposer.org/download/
    pause
    exit /b 1
)

echo [2/6] Copying LDMS application files...
set SRC=%~dp0src
set DEST=%~dp0ldms

:: Controllers & Middleware
xcopy "%SRC%\app\Http\Controllers\*"  "%DEST%\app\Http\Controllers\" /Y /Q
xcopy "%SRC%\app\Http\Middleware\*"   "%DEST%\app\Http\Middleware\"  /Y /Q
xcopy "%SRC%\app\Models\*"            "%DEST%\app\Models\"           /Y /Q

:: Database
xcopy "%SRC%\database\migrations\*"  "%DEST%\database\migrations\"  /Y /Q
xcopy "%SRC%\database\seeders\*"     "%DEST%\database\seeders\"     /Y /Q

:: Routes, bootstrap, views
copy  "%SRC%\routes\web.php"          "%DEST%\routes\web.php"        /Y
copy  "%SRC%\bootstrap\app.php"       "%DEST%\bootstrap\app.php"     /Y
xcopy "%SRC%\resources\views"         "%DEST%\resources\views"       /Y /E /Q

echo [3/6] Setting up environment...
cd /d "%DEST%"
copy .env.example .env

echo.
echo [4/6] Database Configuration
echo.
set /p DB_NAME=Database name [ldms]: 
if "!DB_NAME!"=="" set DB_NAME=ldms

set /p DB_USER=DB Username [root]: 
if "!DB_USER!"=="" set DB_USER=root

set /p DB_PASS=DB Password: 

:: Update .env using PowerShell
powershell -Command "(Get-Content .env) -replace 'DB_DATABASE=.*', 'DB_DATABASE=!DB_NAME!' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'DB_USERNAME=.*', 'DB_USERNAME=!DB_USER!' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'DB_PASSWORD=.*', 'DB_PASSWORD=!DB_PASS!' | Set-Content .env"

echo [5/6] Generating app key...
php artisan key:generate

echo.
echo [6/6] Running migrations and seeding...
echo Make sure MySQL is running and database '!DB_NAME!' exists in phpMyAdmin!
echo.
pause
php artisan migrate --seed

echo.
echo  =========================================
echo   Setup Complete!
echo  =========================================
echo.
echo  Start the server:
echo    cd ldms
echo    php artisan serve
echo.
echo  Open: http://localhost:8000
echo.
echo  Login:
echo    Admin:  admin@ldms.com  / password
echo    Seller: sara@ldms.com   / password
echo    Driver: ali@ldms.com    / password
echo.
pause
