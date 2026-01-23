# Shree Hindutakht Deployment Guide

## Overview

This guide provides step-by-step instructions for deploying the Shree Hindutakht platform to a production environment. The deployment process covers both the web application and the Android app.

## Prerequisites

### Server Requirements
- PHP >= 8.0
- Composer
- Node.js >= 16.x
- npm >= 8.x
- Apache or Nginx web server
- SQLite or MySQL database
- Git (for deployment)

### Local Development Requirements
- XAMPP or similar local development environment
- Android Studio (for Android app)

## Web Application Deployment

### 1. Clone the Repository

```bash
git clone <repository-url>
cd hindutakht
```

### 2. Install PHP Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the example environment file and configure it for production:

```bash
cp .env.example .env
```

Edit the `.env` file with production settings:

```env
APP_NAME="Shree Hindutakht"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# JWT Settings
JWT_SECRET=your_jwt_secret_here
JWT_TTL=60
JWT_REFRESH_TTL=20160
```

Generate the application key:

```bash
php artisan key:generate
```

### 5. Database Setup

Run database migrations:

```bash
php artisan migrate --force
```

### 6. Asset Compilation

Compile and minify frontend assets:

```bash
npm run build
```

### 7. Storage Link

Create a symbolic link for storage:

```bash
php artisan storage:link
```

### 8. File Permissions

Set proper file permissions:

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 9. Apache Configuration

Configure your Apache virtual host:

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path/to/hindutakht/public

    <Directory /path/to/hindutakht/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/hindutakht_error.log
    CustomLog ${APACHE_LOG_DIR}/hindutakht_access.log combined
</VirtualHost>
```

Ensure `mod_rewrite` is enabled:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 10. SSL Configuration (Recommended)

For production, always use HTTPS. You can obtain a free SSL certificate from Let's Encrypt:

```bash
sudo certbot --apache -d yourdomain.com
```

## Hostinger-Specific Deployment

### File Structure
On Hostinger shared hosting, the file structure should be:

```
public_html/
├── .htaccess
├── index.php
├── favicon.ico
├── manifest.json
├── sw.js
├── css/
├── js/
├── images/
└── storage/ (symlink to ../storage/app/public)
```

### Path Configuration
Update the `bootstrap/app.php` file if needed to adjust paths for Hostinger:

```php
$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);
```

### Database Configuration
In Hostinger control panel:
1. Create a MySQL database
2. Create a database user
3. Update `.env` with Hostinger database credentials

### Storage Configuration
Ensure the storage symlink is properly configured:

```bash
php artisan storage:link
```

If the symlink doesn't work on Hostinger, manually copy files or adjust the filesystem configuration in `config/filesystems.php`.

## Android App Deployment

### 1. Update URLs

In the Android app, update all URLs to point to your production domain:

File: `hindutakht_android/app/src/main/java/com/hindutakht/app/MainActivity.java`
```java
private static final String BASE_URL = "https://yourdomain.com/";
```

File: `hindutakht_android/app/src/main/java/com/hindutakht/app/SplashActivity.java`
```java
private static final String BASE_URL = "https://yourdomain.com/";
```

### 2. Update App Information

Update the app name and package information in:
- `hindutakht_android/app/src/main/AndroidManifest.xml`
- `hindutakht_android/app/build.gradle`

### 3. Build Release APK

In Android Studio:
1. Build → Generate Signed Bundle / APK
2. Select APK
3. Create or use existing keystore
4. Build the release APK

### 4. App Signing

For Google Play Store submission, you'll need to sign your app:
1. Create a keystore file
2. Generate a signed APK
3. Align the APK with zipalign
4. Verify the APK signature

### 5. Testing

Before deployment:
1. Test the app on multiple Android versions
2. Verify authentication flow
3. Test offline functionality
4. Check image loading and caching
5. Verify all navigation works correctly

## Post-Deployment Tasks

### 1. Create Admin Account

Create an initial admin account through the database or admin interface:

```sql
INSERT INTO admins (username, email, password, name, created_at, updated_at) 
VALUES ('admin', 'admin@yourdomain.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', NOW(), NOW());
```

Note: The password hash is for "password". Change it immediately after login.

### 2. Configure Cron Jobs

Set up scheduled tasks for Laravel:

```bash
* * * * * cd /path/to/hindutakht && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Monitoring

Set up monitoring for:
- Application uptime
- Database performance
- Error logging
- Resource usage

### 4. Backup Strategy

Implement regular backups for:
- Database
- Uploaded files
- Application code

## Maintenance

### Updating the Application

To update the application:

1. Put the application in maintenance mode:
   ```bash
   php artisan down
   ```

2. Pull the latest code:
   ```bash
   git pull origin main
   ```

3. Install/update dependencies:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build
   ```

4. Run migrations:
   ```bash
   php artisan migrate --force
   ```

5. Clear caches:
   ```bash
   php artisan cache:clear
   php artisan route:clear
   php artisan config:clear
   php artisan view:clear
   ```

6. Bring the application back online:
   ```bash
   php artisan up
   ```

### Database Maintenance

Regular database maintenance tasks:
- Optimize tables
- Check for and repair corruption
- Update statistics for query optimization
- Monitor database size and growth

### Security Updates

Regular security maintenance:
- Update Laravel and dependencies
- Apply security patches
- Review and update permissions
- Monitor for vulnerabilities
- Update SSL certificates

## Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error
- Check file permissions
- Verify `.env` configuration
- Check Apache error logs
- Ensure all dependencies are installed

#### 2. 404 Not Found
- Verify Apache mod_rewrite is enabled
- Check .htaccess configuration
- Ensure public directory is correctly set

#### 3. Database Connection Error
- Verify database credentials in `.env`
- Check database server is running
- Ensure database user has proper permissions

#### 4. Image Upload Issues
- Check storage directory permissions
- Verify PHP upload limits in php.ini
- Ensure sufficient disk space

#### 5. Authentication Problems
- Check JWT configuration
- Verify session storage settings
- Review authentication middleware

### Log Files

Check these log files for debugging:
- Laravel logs: `storage/logs/laravel.log`
- Apache logs: `/var/log/apache2/error.log`
- PHP logs: Check php.ini for error_log setting

### Performance Monitoring

Monitor these metrics:
- Page load times
- Database query performance
- Memory usage
- API response times
- Error rates

## Scaling Considerations

### Horizontal Scaling
- Load balancer setup
- Multiple application servers
- Shared storage for uploaded files
- Database replication

### Vertical Scaling
- Increase server resources
- Optimize database queries
- Implement caching strategies
- Use CDN for static assets

### Caching Strategies
- Redis or Memcached for session storage
- Database query caching
- HTTP caching headers
- Browser caching for static assets

## Backup and Recovery

### Backup Strategy
- Daily database backups
- Weekly full application backups
- Monthly offsite backups
- Automated backup scripts

### Recovery Plan
- Documented recovery procedures
- Regular backup testing
- Disaster recovery plan
- Rollback procedures

## Support and Maintenance

### Contact Information
- Development team contact
- Hosting provider support
- Third-party service contacts

### Documentation
- Keep documentation updated
- Maintain deployment records
- Document customizations
- Track version changes

This deployment guide should help you successfully deploy the Shree Hindutakht platform to a production environment. Always test thoroughly in a staging environment before deploying to production.