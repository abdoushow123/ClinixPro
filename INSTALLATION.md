# ClinixPro Installation Guide

This guide will help you install and configure the ClinixPro Hospital Management System.

## System Requirements

- **Operating System**: Linux (Ubuntu 20.04+ recommended) or Windows Server
- **PHP**: 8.0 or higher
- **PostgreSQL**: 12 or higher
- **NGINX**: 1.18 or higher
- **PHP-FPM**: 8.0 or higher
- **Memory**: Minimum 2GB RAM (4GB recommended)
- **Disk Space**: Minimum 10GB free space

## Step 1: Install Dependencies

### Ubuntu/Debian

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install NGINX
sudo apt install nginx -y

# Install PHP and extensions
sudo apt install php8.0 php8.0-fpm php8.0-pgsql php8.0-mbstring php8.0-xml php8.0-curl php8.0-zip php8.0-bcmath -y

# Install PostgreSQL
sudo apt install postgresql postgresql-contrib -y
```

### Windows

1. Install PHP from https://windows.php.net/download/
2. Install PostgreSQL from https://www.postgresql.org/download/windows/
3. Install NGINX from http://nginx.org/en/download.html

## Step 2: Database Setup

### Create Database User

```bash
# Switch to postgres user
sudo -u postgres psql

# In PostgreSQL prompt:
CREATE USER clinixpro WITH PASSWORD 'your_secure_password';
CREATE DATABASE clinixpro OWNER clinixpro;
GRANT ALL PRIVILEGES ON DATABASE clinixpro TO clinixpro;
\q
```

### Import Schema

```bash
psql -U clinixpro -d clinixpro -f database/schema.sql
```

## Step 3: Application Setup

### Clone or Copy Files

```bash
# Copy project files to web directory
sudo cp -r clinixpro /var/www/
sudo chown -R www-data:www-data /var/www/clinixpro
```

### Configure Application

Edit `config/config.php`:

```php
'database' => [
    'driver' => 'pgsql',
    'host' => 'localhost',
    'port' => '5432',
    'database' => 'clinixpro',
    'username' => 'clinixpro',
    'password' => 'your_secure_password',
    // ...
],
```

### Set Permissions

```bash
sudo chmod -R 755 /var/www/clinixpro/public
sudo chmod -R 777 /var/www/clinixpro/logs
sudo chmod -R 777 /var/www/clinixpro/storage
```

## Step 4: NGINX Configuration

### Copy Configuration File

```bash
sudo cp nginx/clinixpro.conf /etc/nginx/sites-available/clinixpro
```

### Edit Configuration

Update paths in `/etc/nginx/sites-available/clinixpro`:

```nginx
root /var/www/clinixpro/public;
# Update other paths as needed
```

### Enable Site

```bash
sudo ln -s /etc/nginx/sites-available/clinixpro /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

## Step 5: PHP-FPM Configuration

### Copy Pool Configuration

```bash
sudo cp nginx/php-fpm.conf /etc/php/8.0/fpm/pool.d/clinixpro.conf
```

### Restart PHP-FPM

```bash
sudo systemctl restart php8.0-fpm
```

## Step 6: SSL Certificate (HTTPS)

### Using Let's Encrypt (Recommended)

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d clinixpro.yourdomain.com
```

### Self-Signed Certificate (Development Only)

```bash
sudo mkdir /etc/nginx/ssl
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/nginx/ssl/clinixpro.key \
    -out /etc/nginx/ssl/clinixpro.crt
```

## Step 7: Firewall Configuration

```bash
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

## Step 8: Initial Login

1. Access the application at `https://your-domain.com`
2. Login with default credentials:
   - Email: `admin@clinixpro.com`
   - Password: `Admin@123`
3. **IMPORTANT**: Change the default password immediately

## Step 9: Post-Installation Tasks

### Change Default Password

1. Go to Profile settings
2. Change password to a strong, unique password

### Configure Email Settings (Optional)

For password reset emails, configure SMTP settings in the application.

### Set Up Automated Backups

```bash
# Create backup script
sudo nano /usr/local/bin/clinixpro-backup.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/clinixpro"
mkdir -p $BACKUP_DIR

# Database backup
pg_dump -U clinixpro clinixpro > $BACKUP_DIR/clinixpro_db_$DATE.sql

# File backup
tar -czf $BACKUP_DIR/clinixpro_files_$DATE.tar.gz /var/www/clinixpro

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

```bash
sudo chmod +x /usr/local/bin/clinixpro-backup.sh

# Add to crontab for daily backups at 2 AM
sudo crontab -e
# Add: 0 2 * * * /usr/local/bin/clinixpro-backup.sh
```

### Configure Log Rotation

```bash
sudo nano /etc/logrotate.d/clinixpro
```

```
/var/www/clinixpro/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 0644 www-data www-data
}
```

## Troubleshooting

### 502 Bad Gateway

- Check if PHP-FPM is running: `sudo systemctl status php8.0-fpm`
- Check PHP-FPM error logs: `tail -f /var/log/php8.0-fpm.log`

### Database Connection Failed

- Verify PostgreSQL is running: `sudo systemctl status postgresql`
- Check database credentials in config
- Test connection: `psql -U clinixpro -d clinixpro`

### Permission Denied

- Check file permissions: `ls -la /var/www/clinixpro`
- Ensure www-data has proper access

### Session Issues

- Check storage directory permissions
- Verify session configuration in php.ini

## Security Checklist

- [ ] Change default admin password
- [ ] Enable HTTPS
- [ ] Configure firewall
- [ ] Set up automated backups
- [ ] Review and update security headers
- [ ] Disable debug mode in production
- [ ] Configure rate limiting
- [ ] Set up monitoring and alerting
- [ ] Regular security updates

## Support

For additional support, refer to the main README.md or contact the development team.
