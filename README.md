# ClinixPro - Hospital Management System

A secure, professional, and scalable Hospital Management System built with native PHP following MVC architecture.

## Features

- **Authentication & Authorization**: Secure login with RBAC (Role-Based Access Control)
- **Patient Management**: Complete patient registration and records management
- **Medical Records**: Comprehensive medical history tracking
- **Hospitalization**: Room allocation and admission management
- **Laboratory**: Test requests and results management
- **Pharmacy**: Medication inventory and prescription tracking
- **Billing**: Invoice generation and payment processing
- **Insurance**: Insurance claims management
- **Audit Logging**: Complete activity tracking

## Technology Stack

- **Backend**: Native PHP 8.0+ with OOP
- **Architecture**: MVC (Model-View-Controller)
- **Database**: PostgreSQL
- **Web Server**: NGINX with PHP-FPM
- **Frontend**: HTML5, CSS3, Bootstrap 5, Vanilla JavaScript
- **Security**: PDO with prepared statements, CSRF protection, XSS protection

## Project Structure

```
clinixpro/
├── app/
│   ├── controllers/      # Application controllers
│   ├── models/          # Data models
│   ├── views/           # View templates
│   ├── middlewares/     # Middleware classes
│   ├── core/            # Core classes (Router, Database, etc.)
│   ├── helpers/         # Helper functions
│   └── services/        # Service layer
├── public/
│   ├── assets/
│   │   ├── css/         # Stylesheets
│   │   ├── js/          # JavaScript files
│   │   └── images/      # Images
│   └── index.php        # Front controller
├── config/              # Configuration files
├── routes/              # Route definitions
├── logs/                # Application logs
├── storage/             # Storage directory
├── database/            # Database schema
└── nginx/               # NGINX configuration
```

## Installation

### Prerequisites

- PHP 8.0 or higher
- PostgreSQL 12 or higher
- NGINX
- Composer (for dependency management, optional)

### Database Setup

1. Create a PostgreSQL database:
```sql
CREATE DATABASE clinixpro;
```

2. Import the schema:
```bash
psql -U your_username -d clinixpro -f database/schema.sql
```

3. Update database configuration in `config/config.php`

### NGINX Configuration

1. Copy the NGINX configuration:
```bash
cp nginx/clinixpro.conf /etc/nginx/sites-available/clinixpro
```

2. Create a symbolic link:
```bash
ln -s /etc/nginx/sites-available/clinixpro /etc/nginx/sites-enabled/
```

3. Update the configuration with your server paths

4. Test and restart NGINX:
```bash
nginx -t
systemctl restart nginx
```

### PHP-FPM Configuration

1. Copy the PHP-FPM pool configuration:
```bash
cp nginx/php-fpm.conf /etc/php/8.0/fpm/pool.d/clinixpro.conf
```

2. Restart PHP-FPM:
```bash
systemctl restart php8.0-fpm
```

### Environment Configuration

Create a `.env` file in the project root (optional, can use config file directly):

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://clinixpro.local

DB_HOST=localhost
DB_PORT=5432
DB_NAME=clinixpro
DB_USER=clinixpro
DB_PASSWORD=your_password

SESSION_DOMAIN=.clinixpro.local
LOG_LEVEL=info
```

### File Permissions

Set appropriate permissions:
```bash
chmod -R 755 public
chmod -R 777 logs storage
```

## Default Credentials

After installation, you can log in with:

- **Email**: admin@clinixpro.com
- **Password**: Admin@123

**Important**: Change the default password immediately after first login.

## Security Features

- **SQL Injection Protection**: All database queries use PDO prepared statements
- **XSS Protection**: Input sanitization and output escaping
- **CSRF Protection**: Token-based CSRF validation for all POST requests
- **Secure Sessions**: HTTP-only, secure cookies with SameSite policy
- **Password Hashing**: Argon2ID algorithm for password hashing
- **Rate Limiting**: NGINX-based rate limiting and application-level checks
- **Account Lockout**: Automatic lockout after multiple failed login attempts
- **Audit Logging**: Complete activity tracking for security monitoring

## User Roles

The system includes the following roles:

1. **Administrator**: Full system access
2. **Doctor**: Medical records and patient management
3. **Nurse**: Patient care and basic records
4. **Receptionist**: Patient registration and appointments
5. **Pharmacist**: Pharmacy and prescriptions
6. **Laboratory Staff**: Lab tests and results

## Development

### Running in Development Mode

1. Set `APP_DEBUG=true` in configuration
2. Enable error reporting
3. Use local development server:
```bash
php -S localhost:8000 -t public
```

### Code Style

- Follow PSR-12 coding standards
- Use strict type declarations where possible
- Write descriptive comments for complex logic
- Keep methods focused and small

### Testing

Run tests (when implemented):
```bash
phpunit
```

## Deployment

### Production Checklist

1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Use HTTPS with valid SSL certificates
3. Configure proper file permissions
4. Enable NGINX security headers
5. Set up regular database backups
6. Configure log rotation
7. Enable monitoring and alerting
8. Review and update security settings

### Backup Strategy

- Database: Daily automated backups
- Files: Weekly backups of storage directory
- Configuration: Version control for config files

## Support

For issues, questions, or contributions, please refer to the project documentation or contact the development team.

## License

This project is proprietary software. All rights reserved.

## Credits

Developed as a secure, professional Hospital Management System following industry best practices for cybersecurity and software architecture.
