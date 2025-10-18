#!/bin/bash
# Hostinger Deployment Script
# Run this via SSH: bash deploy.sh

set -e  # Exit on error

echo "═══════════════════════════════════════════════════════════════"
echo "🚀 ViserBank Deployment to Hostinger"
echo "═══════════════════════════════════════════════════════════════"
echo ""

# Configuration
GITHUB_REPO="https://github.com/Syded-lang/bank.git"
INSTALL_DIR="$HOME/public_html"
BACKUP_DIR="$HOME/backups"
DB_NAME="u299375718_db"
DB_USER="u299375718_user"
DB_PASS="Fishpoder123"
SITE_URL="https://eastbridgeatlantic.com"

echo "📋 Configuration:"
echo "   Install Directory: $INSTALL_DIR"
echo "   Database: $DB_NAME"
echo "   Site URL: $SITE_URL"
echo ""

# Step 1: Backup existing files
echo "📦 Step 1: Creating backup..."
mkdir -p "$BACKUP_DIR"
if [ -d "$INSTALL_DIR/core" ]; then
    BACKUP_NAME="core_backup_$(date +%Y%m%d_%H%M%S)"
    mv "$INSTALL_DIR/core" "$BACKUP_DIR/$BACKUP_NAME"
    echo "✅ Backup created: $BACKUP_DIR/$BACKUP_NAME"
else
    echo "ℹ️  No existing installation found"
fi

# Step 2: Clone repository
echo ""
echo "📥 Step 2: Cloning repository..."
cd "$INSTALL_DIR"
rm -rf temp_repo
git clone "$GITHUB_REPO" temp_repo
echo "✅ Repository cloned"

# Step 3: Move files to correct location
echo ""
echo "📁 Step 3: Moving files..."
mv temp_repo/core .
mv temp_repo/.htaccess .
mv temp_repo/index.php .
mkdir -p install
if [ -f "temp_repo/install/database.sql" ]; then
    mv temp_repo/install/database.sql install/
fi
rm -rf temp_repo
echo "✅ Files moved to public_html"

# Step 4: Set permissions
echo ""
echo "🔐 Step 4: Setting permissions..."
cd "$INSTALL_DIR/core"
chmod -R 755 .
chmod -R 777 storage
chmod -R 777 bootstrap/cache
echo "✅ Permissions set"

# Step 5: Create .env file
echo ""
echo "⚙️  Step 5: Creating .env file..."
cat > .env << 'ENVFILE'
APP_NAME=ViserBank
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://eastbridgeatlantic.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u299375718_db
DB_USERNAME=u299375718_user
DB_PASSWORD=Fishpoder123

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

PURCHASECODE=BYPASSED-LICENSE-PRODUCTION-$(date +%s)
ENVFILE
echo "✅ .env file created"

# Step 6: Generate application key
echo ""
echo "🔑 Step 6: Generating application key..."
php artisan key:generate --force
echo "✅ Application key generated"

# Step 7: Clear caches
echo ""
echo "🧹 Step 7: Clearing all caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo "✅ Caches cleared"

# Step 8: Database import instructions
echo ""
echo "═══════════════════════════════════════════════════════════════"
echo "📊 Step 8: Database Import"
echo "═══════════════════════════════════════════════════════════════"
echo ""
echo "⚠️  MANUAL ACTION REQUIRED!"
echo ""
echo "Import the database using ONE of these methods:"
echo ""
echo "Option A - Via MySQL command:"
echo "  mysql -h localhost -u $DB_USER -p$DB_PASS $DB_NAME < $INSTALL_DIR/install/database.sql"
echo ""
echo "Option B - Via phpMyAdmin (RECOMMENDED):"
echo "  1. Go to: https://eastbridgeatlantic.com:2083"
echo "  2. Open phpMyAdmin"
echo "  3. Select database: $DB_NAME"
echo "  4. Click 'Import' tab"
echo "  5. Upload: $INSTALL_DIR/install/database.sql"
echo "  6. Click 'Go'"
echo ""
read -p "Press Enter after importing the database..."

# Step 9: Final cache clear
echo ""
echo "🧹 Step 9: Final cache clear..."
php artisan config:clear
php artisan cache:clear
echo "✅ Final cleanup complete"

# Summary
echo ""
echo "═══════════════════════════════════════════════════════════════"
echo "✅ DEPLOYMENT COMPLETE!"
echo "═══════════════════════════════════════════════════════════════"
echo ""
echo "🌐 Your site is now available at:"
echo "   $SITE_URL"
echo ""
echo "🔐 Admin panel:"
echo "   $SITE_URL/admin"
echo ""
echo "📁 Installation location:"
echo "   $INSTALL_DIR/core"
echo ""
echo "💾 Backup location:"
echo "   $BACKUP_DIR"
echo ""
echo "🔍 Check logs if there are issues:"
echo "   tail -50 $INSTALL_DIR/core/storage/logs/laravel.log"
echo ""
echo "═══════════════════════════════════════════════════════════════"
