#!/bin/bash
# =============================================
#  WebERP — Local dev start (no Docker)
#  Requires: PHP 8.1+, Composer, Node 16+, MySQL running locally
#  Usage: ./dev.sh
# =============================================
set -e

GREEN='\033[0;32m'; BLUE='\033[0;34m'; YELLOW='\033[1;33m'; NC='\033[0m'

echo -e "${BLUE}🔧 WebERP local dev setup${NC}"

command -v php >/dev/null 2>&1 || { echo -e "${YELLOW}❌ PHP is required.${NC}"; exit 1; }
command -v composer >/dev/null 2>&1 || { echo -e "${YELLOW}❌ Composer is required.${NC}"; exit 1; }
command -v npm >/dev/null 2>&1 || { echo -e "${YELLOW}❌ Node/npm is required.${NC}"; exit 1; }

if [ ! -f ".env" ]; then cp .env.example .env; echo "✅ Created .env"; fi

if [ ! -d "vendor" ]; then
    echo -e "${BLUE}📦 Installing composer dependencies...${NC}"
    composer install
fi

if [ ! -d "node_modules" ]; then
    echo -e "${BLUE}📦 Installing npm dependencies...${NC}"
    npm install
fi

if ! grep -q "^APP_KEY=base64:" .env; then
    php artisan key:generate
fi

php artisan migrate --seed
php artisan storage:link 2>/dev/null || true

echo ""
echo -e "${GREEN}✅ Setup complete. Starting PHP server on http://localhost:8000${NC}"
php artisan serve
