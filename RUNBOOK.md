## Setting Up the Learn Mi'gmaq Website on a DigitalOcean Droplet (Using Caddy and PHP 8.3)

> ⚠️ **Note:** There is an interactive script called `interactive-setup.sh` that automates much of this setup. It's useful for quickly bootstrapping the server, especially if you're repeating this process or want to avoid manual configuration.

This guide summarizes the sequence of shell commands used to set up the Learn Mi'gmaq website on a DigitalOcean droplet. Follow these steps in order:

---

### Why Caddy?

Caddy is a modern, powerful web server that simplifies HTTPS and configuration:

* **Automatic TLS certificates**: Caddy automatically provisions and renews Let's Encrypt TLS certificates, which are required to serve over HTTPS.
* **Simple configuration**: The `Caddyfile` syntax is easy to read and write, especially for smaller projects.
* **Built-in reverse proxy**: Easily proxy traffic to PHP-FPM (the website is build using PHP, so we need this).
* **Secure defaults**: Caddy enforces modern TLS configurations without needing extra setup.

---

### 1. Clone the Repository

```bash
git clone https://github.com/malekoa/migmaq.git
```

### 2. Install and Configure Caddy

```bash
sudo apt update
sudo apt install -y debian-keyring debian-archive-keyring apt-transport-https curl
curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/gpg.key' | \
  sudo gpg --dearmor -o /usr/share/keyrings/caddy-stable-archive-keyring.gpg
curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' | \
  sed 's/^deb /deb [signed-by=\/usr\/share\/keyrings\/caddy-stable-archive-keyring.gpg] /' | \
  sudo tee /etc/apt/sources.list.d/caddy-stable.list
sudo apt update
sudo apt install caddy
```

### 3. Configure Caddy

There are two  ways to configure Caddy:

#### 🔒 **Option 1: Production Setup with a Domain**

This setup enables HTTPS automatically using Let's Encrypt.

1. Set up an A Record for your domain to point to your droplet’s IP address:

   * **Type:** A Record
   * **Host:** e.g. `learnmigmaq` (or `@` to point the root domain)
   * **Value:** your droplet IP (e.g. `167.71.167.78`)

   You can usually do this through your domain registrar's DNS settings dashboard. Make sure the A Record propagates properly, which might take a few minutes to a couple of hours. You can test propagation with:

   ```bash
   dig learnmigmaq.malek.in +short
   ```

   or

   ```bash
   nslookup learnmigmaq.malek.in
   ```

2. Edit your Caddyfile (located at `/etc/caddy/Caddyfile`):

```
sudo vim /etc/caddy/Caddyfile
```

Replace the contents with the following:

```caddyfile
yoururl.com {
    root * /var/www/migmaq/public
    php_fastcgi unix//run/php/php8.3-fpm.sock
    file_server
}
```

3. Save the file and reload Caddy:

```bash
sudo systemctl reload caddy
```

#### 🧪 **Option 2: Local Testing Without a Domain**

If you don’t have a domain and just want to test locally or via IP address, you can listen on port 80:

```caddyfile
:80 {
    root * /var/www/migmaq/public
    php_fastcgi unix//run/php/php8.3-fpm.sock
    file_server
}
```

Then restart Caddy:

```bash
sudo systemctl restart caddy
```

> ⚠️ Note: This method does **not** support HTTPS or automatic TLS certificates.

---

### 4. Open Firewall Ports

```bash
sudo ufw allow 80,443/tcp
sudo ufw allow out to any port 587
sudo ufw enable
```

### 5. Install PHP and Required Modules

```bash
sudo apt install php8.3 php8.3-fpm php8.3-sqlite3
```

### 6. Configure PHP

Edit pool configuration (in most cases, this doesn't require changes, but you can confirm settings like `user`, `group`, or `listen`):

```bash
sudo vim /etc/php/8.3/fpm/pool.d/www.conf
```

Edit `php.ini` **only if you want to enable debugging features** like displaying errors directly in the browser. This can help when debugging issues such as incorrect permissions:

```bash
sudo vim /etc/php/8.3/fpm/php.ini
```

Look for and enable the following lines:

```ini
display_errors = On
display_startup_errors = On
```

Then restart PHP:

```bash
sudo systemctl restart php8.3-fpm
```

### 7. Set Ownership and Permissions

```bash
sudo chown -R www-data:www-data /var/www/migmaq/
sudo chmod -R 755 /var/www/migmaq/
sudo chmod 664 /var/www/migmaq/data/data.db
```

### 8. Replace Apache with Caddy (if necessary)

```bash
sudo systemctl stop apache2
sudo systemctl disable apache2
sudo systemctl start caddy
```

### 9. Prepare the Included Database

The repository now includes the SQLite database file (`data.db`), so there's no need to download it separately. You just need to make sure the file has the correct permissions:

```bash
cd /var/www/migmaq/data/
sudo chmod 664 data.db
sudo chown www-data:www-data data.db
```

### 10. Create Environment File

Your `.env` file should contain environment variables such as SMTP credentials for sending mail. For example:

```env
SMTP_USERNAME=your_email@gmail.com
SMTP_PASSWORD=your_google_app_password
```

> 🔐 **Note:** `SMTP_PASSWORD` must be a [Google App Password](https://support.google.com/accounts/answer/185833). Regular Google passwords will not work if two-factor authentication is enabled.

#### How to generate a Google App Password:

1. Go to [https://myaccount.google.com/security](https://myaccount.google.com/security)
2. Enable 2-Step Verification if it’s not already enabled.
3. Under "Signing in to Google," click on **App passwords**.
4. Select the app (e.g., "Mail") and the device (e.g., "Other") and generate the password.
5. Copy the 16-character password shown and paste it into your `.env` file as `SMTP_PASSWORD`.

Then create the `.env` file:

```bash
cd /var/www/migmaq/
touch .env
vim .env
```

### 11. Restart Services

```bash
sudo systemctl restart php8.3-fpm
sudo systemctl restart caddy
```

### 12. Test Email Sending (Optional)

DigitalOcean droplets have outbound port 587 (used for sending email) blocked by default. You must open a support ticket asking DigitalOcean to unblock this port. The request typically takes around **24 hours** to process.

Once it's been unblocked, you can test SMTP connectivity with:

```bash
nc -vz smtp.gmail.com 587
nc -vz smtp.gmail.com 465
```

---

> ✅ Make sure to reload or restart services (like Caddy and PHP-FPM) after changes to configs or permissions.
