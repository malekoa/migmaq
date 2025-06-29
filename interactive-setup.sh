#!/bin/bash

set -e

PROJECT_DIR="/var/www/migmaq"
CADDYFILE="/etc/caddy/Caddyfile"
ENV_FILE="$PROJECT_DIR/.env"
DATA_DIR="$PROJECT_DIR/data"
DB_FILE="$DATA_DIR/data.db"
PUBLIC_IP=$(curl -s https://ipinfo.io/ip)

echo "🌐 Welcome to the Learn Mi'gmaq Setup Script!"
echo "============================================="
echo

# Ask for domain name
read -p "Do you have a domain name to use with this site? (y/N): " use_domain

if [[ "$use_domain" =~ ^[Yy]$ ]]; then
  read -p "Enter your domain name (e.g., learnmigmaq.example.com): " domain
  echo "⚠️  Remember to create an A record pointing to your droplet's IP address!"
  echo "Your droplet's IP address is: $PUBLIC_IP"
  read -p "Press Enter to continue..."
else
  domain=":80"
  echo "⚠️  Proceeding with local setup (no HTTPS, no domain)"
fi

# Ask for SMTP credentials
echo
echo "📧 Let's set up SMTP for email (Gmail only)."
read -p "Enter the Gmail address used for sending email: " smtp_user
read -p "Enter the Gmail app password (not your real password! If you haven't generated an app password, learn how to do so here: https://support.google.com/accounts/answer/185833?hl=en#app-passwords): " raw_smtp_pass
smtp_pass=$(echo "$raw_smtp_pass" | tr -d ' ')

# Create data directory if needed
mkdir -p "$DATA_DIR"

# Download or create the database
echo
echo "📥 Setting up the database..."

read -p "Enter a URL to download a prebuilt SQLite database (leave blank to create an empty one): " db_url

if [[ -z "$db_url" ]]; then
  echo "📄 Creating empty database at $DB_FILE..."
  sqlite3 "$DB_FILE" "VACUUM;"  # Just to create a valid SQLite file
else
  echo "🌐 Downloading database from $db_url..."
  curl -L "$db_url" -o "$DB_FILE"
fi

# Update and install dependencies
echo
echo "🔧 Installing PHP 8.3, Caddy, and other dependencies..."

sudo apt update
sudo apt install -y php8.3 php8.3-fpm php8.3-mbstring php8.3-sqlite3 php8.3-curl php8.3-xml php8.3-cli unzip

# Install Caddy
sudo apt install -y debian-keyring debian-archive-keyring apt-transport-https curl
curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/gpg.key' | sudo gpg --dearmor -o /usr/share/keyrings/caddy-stable-archive-keyring.gpg
curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' | sudo tee /etc/apt/sources.list.d/caddy-stable.list
sudo apt update
sudo apt install -y caddy

# Create Caddyfile
echo
echo "📝 Creating Caddyfile at $CADDYFILE..."

sudo tee "$CADDYFILE" >/dev/null <<EOF
$domain {
    root * $PROJECT_DIR/public
    php_fastcgi unix//run/php/php8.3-fpm.sock
    file_server
}
EOF

# Reload Caddy
echo "🔁 Reloading Caddy..."
sudo systemctl restart caddy

# Set permissions
echo "🔒 Setting permissions..."
sudo chown -R www-data:www-data "$PROJECT_DIR"
sudo chmod -R 755 "$PROJECT_DIR"

sudo chown www-data:www-data "$DATA_DIR"
sudo chown www-data:www-data "$DB_FILE"
sudo chmod 775 "$DATA_DIR"
sudo chmod 664 "$DB_FILE"

# Create .env file
echo "🔐 Writing environment variables to $ENV_FILE..."
cat <<EOF >"$ENV_FILE"
SMTP_USERNAME=$smtp_user
SMTP_PASSWORD=$smtp_pass
EOF

sudo chown www-data:www-data "$ENV_FILE"
sudo chmod 600 "$ENV_FILE"

# Configure UFW firewall
echo "🔥 Configuring UFW firewall..."
sudo ufw allow ssh
sudo ufw allow 80,443/tcp
sudo ufw allow out to any port 587
sudo ufw --force enable

# Final service restart
echo "🚀 Restarting services..."

PHP_FPM_SERVICE=$(systemctl list-units --type=service | grep -o 'php[0-9.]*-fpm' | head -n1)

if [[ -n "$PHP_FPM_SERVICE" ]]; then
  sudo systemctl restart "$PHP_FPM_SERVICE"
else
  echo "⚠️  Could not detect PHP-FPM service. Please restart it manually if needed."
fi

sudo systemctl restart caddy

echo
echo "✅ Setup complete!"
if [[ "$domain" != ":80" ]]; then
  echo "🌐 Your site should be live at https://$domain"
else
  echo "🌐 Your site is available at http://$PUBLIC_IP/"
fi

echo "📩 Don't forget to request port 587 be unblocked by DigitalOcean for email to work!"
