#!/bin/bash
set -e

echo "========================================="
echo " 🚀 WebERP / WebPOS - Starting up"
echo "========================================="

# Switch to root for composer/npm installs (we have to, since we dropped permissions)
cd /var/www/html

# ------------------------------------------
#  1) Install Composer dependencies
# ------------------------------------------
if [ ! -d "vendor" ] || [ -z "$(ls -A vendor 2>/dev/null)" ]; then
    echo ""
    echo "📦 Installing PHP (composer) dependencies (this may take a few minutes)..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
else
    echo "✅ Composer dependencies already installed."
fi

# ------------------------------------------
#  2) Install NPM dependencies
# ------------------------------------------
if [ ! -d "node_modules" ] || [ -z "$(ls -A node_modules 2>/dev/null)" ]; then
    echo ""
    echo "📦 Installing Node (npm) dependencies..."
    npm install
else
    echo "✅ npm dependencies already installed."
fi

# ------------------------------------------
#  3) Build frontend assets
# ------------------------------------------
if [ ! -d "public/build" ]; then
    echo "⚙️  Building frontend assets (vite)..."
    npm run build
else
    echo "✅ Frontend assets already built."
fi

# ------------------------------------------
#  4) Create .env if it doesn't exist
# ------------------------------------------
if [ ! -f ".env" ]; then
    echo "⚙️  Creating .env from .env.example..."
    cp .env.example .env
fi

# ------------------------------------------
#  5) Ensure APP_KEY exists
# ------------------------------------------
if ! grep -q "^APP_KEY=base64:" .env; then
    echo "🔑 Generating APP_KEY..."
    php artisan key:generate --force
fi

# Override DB settings to point to the Docker MySQL service
sed -i "s/^DB_HOST=.*/DB_HOST=db/" .env
sed -i "s/^DB_PORT=.*/DB_PORT=3306/" .env
sed -i "s/^DB_DATABASE=.*/DB_DATABASE=weberp/" .env
sed -i "s/^DB_USERNAME=.*/DB_USERNAME=weberp/" .env
sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=secret/" .env
echo "APP_INSTALLED=yes" >> .env 2>/dev/null || true

# Wait for MySQL to be ready
echo ""
echo "⏳ Waiting for database (db:3306) to be ready..."
until mysqladmin ping -h"db" -u"weberp" -p"secret" --silent 2>/dev/null; do
    sleep 2
    echo "   still waiting..."
done
echo "✅ Database is ready!"

# ------------------------------------------
#  6) Run migrations + seeds (idempotent)
# ------------------------------------------
echo ""
echo "🗄️  Running migrations and seeders..."
php artisan migrate --force --seed || {
    echo "ℹ️  Database was not empty, running migrations only..."
    php artisan migrate --force
}

# ------------------------------------------
#  6.5) Set a known admin password so you can log in
# ------------------------------------------
ADMIN_EMAIL="${ADMIN_EMAIL:-admin@weberp.app}"
ADMIN_PASSWORD="${ADMIN_PASSWORD:-admin123}"

echo "🔐 Ensuring admin user ($ADMIN_EMAIL) exists with password: $ADMIN_PASSWORD"
php artisan tinker --execute="
\$email = '$ADMIN_EMAIL';
\$user = \App\Models\User::where('email', \$email)->first();
if (!\$user) {
    \$role = \App\Models\Backend\Role::first() ?? \App\Models\Backend\Role::create(['name' => 'superadmin', 'permissions' => json_encode([])]);
    \$user = new \App\Models\User();
    \$user->name = 'Admin';
    \$user->email = \$email;
    \$user->user_type = 'superadmin';
    \$user->role_id = \$role->id;
    \$user->permissions = \$role->permissions ?? [];
    \$user->email_verified = 1;
}
\$user->password = Hash::make('$ADMIN_PASSWORD');
\$user->save();
echo 'Admin user ready.';
" || echo "⚠️  (Admin user setup failed — you can create one manually with tinker)"

# ------------------------------------------
#  7) Create storage link
# ------------------------------------------
if [ ! -L "public/storage" ]; then
    echo "🔗 Creating storage symlink..."
    php artisan storage:link || true
fi

# ------------------------------------------
#  8) Cache config/routes/clear
# ------------------------------------------
php artisan config:clear
php artisan cache:clear

# ------------------------------------------
#  9) Fix permissions (best-effort)
# ------------------------------------------
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo ""
echo "========================================="
echo " ✅ WebERP / WebPOS is READY!"
echo " "
echo "    Web App (Nginx):   http://localhost:8080"
echo "    Web App (Artisan): http://localhost:8000"
echo "    phpMyAdmin:        http://localhost:8081"
echo " "
echo "    Admin email:       ${ADMIN_EMAIL:-admin@weberp.app}"
echo "    Admin password:    ${ADMIN_PASSWORD:-admin123}"
echo "========================================="
echo ""

# ------------------------------------------
#  Start PHP built-in server on 0.0.0.0:8000
# ------------------------------------------
exec php artisan serve --host=0.0.0.0 --port=8000
