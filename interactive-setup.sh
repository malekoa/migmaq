#!/bin/bash

set -e

PROJECT_DIR="/var/www/migmaq"
CADDYFILE="/etc/caddy/Caddyfile"
ENV_FILE="$PROJECT_DIR/.env"

echo "🌐 Welcome to the Learn Mi'gmaq Setup Script!"
echo "============================================="
echo

# Ask for domain name
read -p "Do you have a domain name to use with this site? (y/N): " use_domain

if [[ "$use_domain" =~ ^[Yy]$ ]]; then
    read -p "Enter your domain name (e.g., learnmigmaq.example.com): " domain
else
    domain=":80"
    echo "⚠️  Proceeding with local setup (no HTTPS, no domain)"
fi

# Ask for SMTP credentials
echo
echo "📧 Let's set up SMTP for email (Gmail only)."
read -p "Enter the Gmail address used for sending email: " smtp_user
read -p "Enter the Gmail app password (not your real password!): " smtp_pass

# Update and install dependencies
echo
echo "🔧 Installing Caddy, PHP 8.3, and dependencies..."
sudo apt update
sudo apt install -y debian-keyring debian-archive-keyring apt-transport-https curl php8.3 php8.3-fpm php8.3-sqlite3

# Install Caddy
curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/gpg.key' | \
  sudo gpg --dearmor -o /usr/share/keyrings/caddy-stable-archive-keyring.gpg

curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' | \
  sed 's/^deb /deb [signed-by=\/usr\/share\/keyrings\/caddy-stable-archive-keyring.gpg] /' | \
  sudo tee /etc/apt/sources.list.d/caddy-stable.list

sudo apt update
sudo apt install -y caddy

# Create Caddyfile
echo
echo "📝 Creating Caddyfile at $CADDYFILE..."

sudo tee "$CADDYFILE" > /dev/null <<EOF
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
sudo chmod 664 "$PROJECT_DIR/data/data.db" || true

# Create .env file
echo "🔐 Writing environment variables to $ENV_FILE..."
cat <<EOF > "$ENV_FILE"
SMTP_USERNAME=$smtp_user
SMTP_PASSWORD=$smtp_pass
EOF

sudo chown www-data:www-data "$ENV_FILE"
sudo chmod 600 "$ENV_FILE"

# Open firewall ports
echo "🔥 Configuring UFW firewall..."
sudo ufw allow 80,443/tcp
sudo ufw allow out to any port 587
sudo ufw --force enable

# Final service restart
echo "🚀 Restarting services..."
sudo systemctl restart php8.3-fpm
sudo systemctl restart caddy

echo
echo "✅ Setup complete!"
if [[ "$domain" != ":80" ]]; then
  echo "🌐 Your site should be live at https://$domain"
else
  echo "🌐 Your site is available at http://YOUR_SERVER_IP/"
fi

echo "📩 Don't forget to request port 587 be unblocked by DigitalOcean for email to work!"
