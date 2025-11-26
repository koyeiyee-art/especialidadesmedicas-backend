# PHP Admin Panel Setup

## Installation Instructions

1. **Upload files to your PHP server**
   - Place the `admin/` folder on your web server
   - URL will be: `https://yoursite.com/admin/`

2. **Create MySQL database**
   - Run the `setup.sql` file in your MySQL/MariaDB
   - Or manually create the database and table

3. **Configure database connection**
   - Edit `config.php`
   - Update `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`

4. **Change admin credentials**
   - Edit `config.php`
   - Change `ADMIN_USERNAME` and `ADMIN_PASSWORD`
   - For production, use `password_hash()` for passwords

5. **Test the login**
   - Navigate to: `https://yoursite.com/admin/`
   - Login with your credentials

## Default Credentials
- Username: `admin`
- Password: `admin123`

**⚠️ CHANGE THESE IMMEDIATELY!**

## Security Notes
- This uses session-based authentication
- Change credentials in `config.php` before deploying
- Use HTTPS in production
- Consider adding password hashing with `password_hash()` and `password_verify()`
- Add CSRF protection for production use

## Files
- `index.php` - Login page
- `dashboard.php` - Admin dashboard
- `logout.php` - Logout handler
- `config.php` - Configuration and database
- `setup.sql` - Database schema
