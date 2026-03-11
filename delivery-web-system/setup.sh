#!/bin/bash

# ═══════════════════════════════════════════════════════════════
#  LDMS — Local Delivery Management System
#  Automated Setup Script (Linux / macOS)
# ═══════════════════════════════════════════════════════════════

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}"
echo "  ██╗     ██████╗ ███╗   ███╗███████╗"
echo "  ██║     ██╔══██╗████╗ ████║██╔════╝"
echo "  ██║     ██║  ██║██╔████╔██║███████╗"
echo "  ██║     ██║  ██║██║╚██╔╝██║╚════██║"
echo "  ███████╗██████╔╝██║ ╚═╝ ██║███████║"
echo "  ╚══════╝╚═════╝ ╚═╝     ╚═╝╚══════╝"
echo "  Local Delivery Management System"
echo -e "${NC}"

echo -e "${YELLOW}Step 1: Creating Laravel project...${NC}"
composer create-project laravel/laravel ldms --prefer-dist

echo -e "${YELLOW}Step 2: Copying LDMS application files...${NC}"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SRC="$SCRIPT_DIR/src"
DEST="$SCRIPT_DIR/ldms"

# Copy app files
cp -r "$SRC/app/Http/Controllers/"*    "$DEST/app/Http/Controllers/"
cp -r "$SRC/app/Http/Middleware/"*     "$DEST/app/Http/Middleware/"
cp -r "$SRC/app/Models/"*              "$DEST/app/Models/"

# Copy database files
cp -r "$SRC/database/migrations/"*    "$DEST/database/migrations/"
cp -r "$SRC/database/seeders/"*       "$DEST/database/seeders/"

# Copy routes & bootstrap
cp "$SRC/routes/web.php"              "$DEST/routes/web.php"
cp "$SRC/bootstrap/app.php"           "$DEST/bootstrap/app.php"

# Copy views
cp -r "$SRC/resources/views/"*        "$DEST/resources/views/"

echo -e "${YELLOW}Step 3: Setting up environment...${NC}"
cd "$DEST"
cp .env.example .env

echo ""
echo -e "${YELLOW}Step 4: Configure your database${NC}"
echo ""
echo -e "  Please enter your MySQL credentials:"
read -p "  Database name [ldms]: " DB_NAME
DB_NAME=${DB_NAME:-ldms}
read -p "  DB Username [root]: " DB_USER
DB_USER=${DB_USER:-root}
read -s -p "  DB Password: " DB_PASS
echo ""

# Update .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASS/" .env

echo ""
echo -e "${YELLOW}Step 5: Generating app key...${NC}"
php artisan key:generate

echo -e "${YELLOW}Step 6: Running migrations and seeding...${NC}"
echo -e "${YELLOW}  (Make sure MySQL is running and database '$DB_NAME' exists)${NC}"
echo ""
read -p "  Press ENTER when ready, or Ctrl+C to cancel..."
php artisan migrate --seed

echo ""
echo -e "${GREEN}═══════════════════════════════════════${NC}"
echo -e "${GREEN}  ✅  LDMS Setup Complete!${NC}"
echo -e "${GREEN}═══════════════════════════════════════${NC}"
echo ""
echo -e "  ${YELLOW}Start the server:${NC}"
echo -e "  cd ldms && php artisan serve"
echo ""
echo -e "  ${YELLOW}Open in browser:${NC} http://localhost:8000"
echo ""
echo -e "  ${YELLOW}Login credentials:${NC}"
echo -e "  Admin:  admin@ldms.com  / password"
echo -e "  Seller: sara@ldms.com   / password"
echo -e "  Driver: ali@ldms.com    / password"
echo ""
