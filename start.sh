#!/bin/bash
# =============================================
#  WebERP / WebPOS — One-click start script
#  Usage: ./start.sh
# =============================================
set -e

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${BLUE}=========================================${NC}"
echo -e "${BLUE} 🚀  WebERP / WebPOS — Starting up${NC}"
echo -e "${BLUE}=========================================${NC}"

# --- Detect Docker & Docker Compose ---
command -v docker >/dev/null 2>&1 || {
    echo -e "${YELLOW}❌ Docker is not installed.${NC}"
    echo "   Please install Docker first:"
    echo "   • Windows/Mac: https://docs.docker.com/get-docker/"
    echo "   • Ubuntu: sudo apt install docker.io docker-compose-plugin"
    echo "   • Fedora: sudo dnf install docker docker-compose-plugin"
    exit 1
}

# Try new `docker compose` first, fall back to `docker-compose`
if docker compose version >/dev/null 2>&1; then
    COMPOSE="docker compose"
elif command -v docker-compose >/dev/null 2>&1; then
    COMPOSE="docker-compose"
else
    echo -e "${YELLOW}❌ Docker Compose is not installed.${NC}"
    echo "   Please install Docker Compose:"
    echo "   https://docs.docker.com/compose/install/"
    exit 1
fi

# --- If user passes --stop or --down, stop containers ---
if [ "$1" == "--stop" ] || [ "$1" == "stop" ] || [ "$1" == "down" ]; then
    echo -e "${BLUE}🛑 Stopping WebERP containers...${NC}"
    $COMPOSE down
    echo -e "${GREEN}✅ Stopped.${NC}"
    exit 0
fi

# --- If --reset, wipe database and restart ---
if [ "$1" == "--reset" ]; then
    echo -e "${YELLOW}⚠️  Resetting database (all data will be lost)...${NC}"
    read -p "   Type 'yes' to confirm: " confirm
    if [ "$confirm" != "yes" ]; then
        echo "   Aborted."
        exit 0
    fi
    $COMPOSE down -v
    echo -e "${GREEN}✅ Database removed, restarting...${NC}"
fi

# --- Build & start containers ---
echo ""
echo -e "${BLUE}📥 Building & starting containers (first run may take several minutes)...${NC}"
$COMPOSE up -d --build

# Wait a bit for first-time provisioning
echo ""
echo -e "${BLUE}⏳ Waiting for the app to finish first-time setup...${NC}"
echo "   (composer install, npm install, migrations, etc.)"
echo "   You can follow progress with: ${YELLOW}docker logs -f weberp-app${NC}"

# Give the entrypoint a head start
sleep 5

echo ""
echo -e "${GREEN}=========================================${NC}"
echo -e "${GREEN} ✅ WebERP / WebPOS is starting!${NC}"
echo -e "${GREEN}=========================================${NC}"
echo ""
echo -e " 📍 ${BLUE}Web App (Nginx):${NC}     http://localhost:8080"
echo -e " 📍 ${BLUE}Web App (Artisan):${NC}   http://localhost:8000"
echo -e " 📍 ${BLUE}phpMyAdmin:${NC}          http://localhost:8081"
echo ""
echo -e " 🔑 ${GREEN}Default login:${NC}"
echo -e "    Email:    ${YELLOW}admin@weberp.app${NC}"
echo -e "    Password: ${YELLOW}admin123${NC}"
echo ""
echo -e " 🛑  Stop:    ${YELLOW}./start.sh stop${NC}"
echo -e " 🔄 Reset:   ${YELLOW}./start.sh --reset${NC}"
echo -e " 📋 Logs:    ${YELLOW}docker logs -f weberp-app${NC}"
echo ""

# Open the browser automatically (Linux/macOS)
sleep 2
URL="http://localhost:8080"
if command -v xdg-open >/dev/null 2>&1; then
    xdg-open "$URL" >/dev/null 2>&1 || true
elif command -v open >/dev/null 2>&1; then
    open "$URL" >/dev/null 2>&1 || true
elif command -v start >/dev/null 2>&1; then
    start "$URL" >/dev/null 2>&1 || true
fi
