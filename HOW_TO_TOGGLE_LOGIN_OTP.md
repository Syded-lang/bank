# 🔐 How to Toggle Login OTP On/Off

## Quick Answer

**To ENABLE Login OTP:**
1. Login to Admin Panel: `https://eastbridgeatlantic.com/admin`
2. Go to: **Settings** → **System Configuration**
3. Find: **OTP Via Email** section
4. Click: **Enable** button (turns green)
5. Done! Users will now need OTP when logging in

**To DISABLE Login OTP:**
1. Login to Admin Panel: `https://eastbridgeatlantic.com/admin`
2. Go to: **Settings** → **System Configuration**  
3. Find: **OTP Via Email** section
4. Click: **Disable** button (turns red)
5. Done! Users can login with just username/password

---

## Detailed Instructions

### Method 1: Using Admin Panel (Recommended) ✅

This is the **easiest** method - no database or code changes needed!

#### Step-by-Step:

1. **Access Admin Panel**
   - URL: `https://eastbridgeatlantic.com/admin`
   - Login with your admin credentials

2. **Navigate to Settings**
   - Click **Settings** in the left sidebar
   - Click **System Configuration**

3. **Find OTP Settings**
   - Scroll down to find **"OTP Via Email"** section
   - It will show:
     ```
     OTP Via Email
     Control send OTP to the user via Email from here.
     [Enable/Disable Button]
     ```

4. **Toggle the Setting**
   - **GREEN (Enabled)** = Login OTP is ACTIVE
   - **RED (Disabled)** = Login OTP is INACTIVE

5. **Click Update/Save**
   - Changes take effect immediately
   - No cache clearing needed

#### What This Controls:

```php
// In LoginController.php:
if (checkIsOtpEnable()) {
    // This checks: otp_email OR otp_sms OR user has 2FA enabled
    // If ANY are true, OTP is required
}
```

**Important**: The `checkIsOtpEnable()` function checks:
- `otp_email` module (controlled by admin panel)
- `otp_sms` module (controlled by admin panel)  
- User's 2FA status (Google Authenticator)

If **any** of these are enabled, login OTP will be required.

---

### Method 2: Using Database (Advanced) ⚙️

If you prefer direct database access:

#### To ENABLE Login OTP:

```sql
-- Connect to database
mysql -u u299375718_user -p'Fishpoder123' u299375718_db

-- Update the modules JSON
UPDATE general_settings 
SET modules = JSON_SET(modules, '$.otp_email', 1)
WHERE id = 1;

-- Verify
SELECT JSON_EXTRACT(modules, '$.otp_email') as otp_email_status 
FROM general_settings WHERE id = 1;
-- Should return: 1
```

#### To DISABLE Login OTP:

```sql
UPDATE general_settings 
SET modules = JSON_SET(modules, '$.otp_email', 0)
WHERE id = 1;
```

#### After Database Changes:

```bash
# SSH into server
ssh u299375718@37.44.246.142 -p 65002

# Clear caches
cd ~/domains/eastbridgeatlantic.com/public_html/core
php artisan cache:clear
php artisan config:clear
```

---

### Method 3: Using SSH/Tinker (For Developers) 💻

```bash
# SSH into server
ssh u299375718@37.44.246.142 -p 65002

# Navigate to project
cd ~/domains/eastbridgeatlantic.com/public_html/core

# Open Tinker
php artisan tinker

# Check current status
>>> gs()->modules->otp_email
=> 0  // Disabled

# Enable Login OTP
>>> $gs = gs(); $modules = (array)$gs->modules; $modules['otp_email'] = 1; $gs->modules = $modules; $gs->save();

# Disable Login OTP  
>>> $gs = gs(); $modules = (array)$gs->modules; $modules['otp_email'] = 0; $gs->modules = $modules; $gs->save();

# Exit
>>> exit
```

---

## Current Status

**Login OTP Status**: ❌ **DISABLED**

Based on your screenshot, the **OTP Via Email** module is currently **DISABLED** (red button).

To check current status:
```bash
ssh u299375718@37.44.246.142 -p 65002
cd ~/domains/eastbridgeatlantic.com/public_html/core
php artisan tinker --execute="dump(gs()->modules->otp_email);"
```

---

## Understanding the System

### How Login OTP Works:

```
User enters credentials
         ↓
System validates password
         ↓
   [OTP CHECK HERE]
         ↓
    Is OTP enabled?
    (otp_email OR otp_sms OR user has 2FA)
         ↓
    YES → Send OTP → Verify → Login
    NO  → Direct Login
```

### Files Involved:

1. **LoginController.php** (Line 107-138)
   - Contains the OTP check logic
   - Calls `checkIsOtpEnable()`

2. **helpers.php** (Line 638-644)
   - Contains `checkIsOtpEnable()` function
   - Checks module settings

3. **general_settings table**
   - Stores `modules` JSON column
   - Contains `otp_email` and `otp_sms` flags

---

## Testing

### Test When ENABLED:

1. Go to: `https://eastbridgeatlantic.com/user/login`
2. Enter: Username + Password
3. Click: "SIGN IN"
4. **Expected**:
   - Success notification: "OTP sent to your email successfully"
   - Redirected to OTP verification page
   - Email received with 6-digit code
   - Enter code → Login successful

### Test When DISABLED:

1. Go to: `https://eastbridgeatlantic.com/user/login`
2. Enter: Username + Password  
3. Click: "SIGN IN"
4. **Expected**:
   - Direct login to dashboard
   - No OTP page
   - No email sent

---

## Troubleshooting

### "OTP not being sent even though enabled"

1. Check email configuration:
   ```bash
   ssh u299375718@37.44.246.142 -p 65002
   cd ~/domains/eastbridgeatlantic.com/public_html/core
   php artisan tinker --execute="dump(gs('mail_config'));"
   ```

2. Check LOGIN_OTP template exists:
   ```sql
   SELECT * FROM notification_templates WHERE act = 'LOGIN_OTP';
   ```

3. Check email notifications enabled:
   ```sql
   SELECT en FROM general_settings WHERE id = 1;
   -- Should return: 1
   ```

### "OTP still required even though disabled"

1. Check if user has 2FA enabled:
   ```sql
   SELECT username, ts FROM users WHERE username = 'YourUsername';
   -- If ts = 1, user has 2FA enabled (separate from OTP modules)
   ```

2. Clear all caches:
   ```bash
   cd ~/domains/eastbridgeatlantic.com/public_html/core
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

---

## Best Practices

### Security Recommendations:

1. **Keep OTP ENABLED for Production**
   - Provides additional security layer
   - Prevents unauthorized access even if password is compromised

2. **Disable ONLY for Development/Testing**
   - Speeds up testing
   - Avoid entering OTP repeatedly during development

3. **Monitor Failed Login Attempts**
   - Check `user_logins` table regularly
   - Watch for brute force patterns

4. **Use Strong Passwords**
   - Minimum 8 characters
   - Mix uppercase, lowercase, numbers, symbols

---

## Quick Reference

| Action | Admin Panel | Database Value |
|--------|------------|----------------|
| Enable Login OTP | Click "Enable" | `otp_email = 1` |
| Disable Login OTP | Click "Disable" | `otp_email = 0` |
| Check Status | View button color | `SELECT modules FROM general_settings` |

---

## Related Settings

Other OTP-related settings you can control:

1. **OTP Via SMS** - Send OTP via text message
2. **Email Notification** - Enable/disable all email notifications  
3. **SMS Notification** - Enable/disable all SMS notifications
4. **OTP Time** - How long OTP codes are valid (in seconds)

All these can be toggled in the Admin Panel under **Settings** → **System Configuration**.

---

## Support

If you need help:
- Check Laravel logs: `storage/logs/laravel.log`
- Check email logs: `notification_logs` table
- Test email settings: Admin → Notification → Email Settings → Send Test Mail

---

**Git Commit**: 39ee6fb  
**Last Updated**: October 18, 2025  
**Status**: ✅ Deployed to Production
