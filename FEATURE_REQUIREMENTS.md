# Feature Requirements - Payroll System

## URGENT: Fix Production Error 500

### Current Issue:
- Site: https://just-atesting.hugoedm.fun
- Error: 500 Internal Server Error
- Cause: Missing dashboard view or cache issues

### Immediate Fix:
1. SSH ke server
2. Run: `bash fix-production.sh`
3. Or manually:
```bash
cd /opt/just-atesting
php artisan optimize:clear
php artisan cache:clear
php artisan view:clear
composer dump-autoload --optimize
php artisan config:cache
php artisan route:cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Feature 1: Dual Dashboard (Admin & User)

### Requirements:
- **Admin Dashboard**: Full stats, user management, system overview
- **User Dashboard**: Limited stats, personal info, recent activities
- **Permission-based**: Dashboard content based on role permissions

### Implementation:
```
resources/views/dashboard/
├── admin.blade.php  (for users with admin permissions)
└── user.blade.php   (for regular users)
```

### Stats to Show:

**Admin Dashboard:**
- Total Users
- Total Karyawan
- Total Roles
- Recent Activities
- System Stats
- Quick Actions (User Management, Role Management, Settings)

**User Dashboard:**
- Personal Info
- Recent Karyawan (if has permission)
- Quick Actions (based on permissions)
- Activity Log (own activities)

---

## Feature 2: Profile Management

### Current Issue:
- Profile page tidak bisa dibuka

### Requirements:
- View & Edit Profile
- Change Password
- Activity Log
- Security Settings

### Fields:
- Name
- Email
- Phone
- Address
- Profile Photo
- Current Password (for verification)
- New Password
- Confirm Password

### Security:
- Password must be strong (min 12 chars, uppercase, lowercase, number, special char)
- Password manager recommendation
- Show password strength indicator
- Require current password to change

---

## Feature 3: Password Manager Integration

### Requirements:
- Recommend password managers (Bitwarden, KeePass, 1Password)
- Password strength indicator
- Generate strong password button
- Password requirements display

### Password Rules:
- Minimum 12 characters
- At least 1 uppercase letter
- At least 1 lowercase letter
- At least 1 number
- At least 1 special character
- Cannot reuse last 5 passwords

### UI Elements:
- Password strength meter (Weak/Medium/Strong/Very Strong)
- Generate password button
- Show/Hide password toggle
- Copy to clipboard button
- Password manager recommendation banner

---

## Feature 4: Login Activity Monitoring

### Requirements:
- Track all login activities
- Show login history
- Notify on new login
- Track session duration
- Show logout time

### Data to Track:
- User ID
- Login Time
- Logout Time
- IP Address
- User Agent (Browser/Device)
- Location (if possible)
- Session Duration
- Status (Active/Logged Out)

### Database Table:
```sql
CREATE TABLE login_activities (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT,
    login_at TIMESTAMP,
    logout_at TIMESTAMP NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    location VARCHAR(255) NULL,
    session_duration INT NULL,
    status VARCHAR(20),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Features:
- **For Superadmin**: View all users' login activities
- **For Users**: View only their own login activities
- **Dashboard Widget**: Recent login activities
- **Email Notification**: New login from unknown device/location
- **Security Alert**: Multiple failed login attempts

---

## Feature 5: Forgot Password with OTP

### Requirements:
- No registration (admin creates users)
- Forgot password flow with OTP
- Email verification
- Secure password reset

### Flow:
1. User clicks "Forgot Password"
2. Enter registered email
3. System validates email exists in users table
4. Generate 6-digit OTP
5. Send OTP to email
6. User enters OTP (5 minutes expiry)
7. If valid, show reset password form
8. User sets new password
9. Confirm password reset
10. Redirect to login

### Database Table:
```sql
CREATE TABLE password_reset_otps (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255),
    otp VARCHAR(6),
    expires_at TIMESTAMP,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_otp (otp)
);
```

### Security:
- OTP expires in 5 minutes
- Maximum 3 OTP requests per hour per email
- OTP can only be used once
- Rate limiting on forgot password endpoint
- Log all password reset attempts

---

## Feature 6: Data Validation & Duplicate Prevention

### Requirements:
- Validate all data before insert/update
- Prevent duplicate entries
- Show clear error messages
- Consistent validation across all modules

### Validation Rules by Module:

#### Karyawan:
- Email must be unique
- NPWP must be unique (if provided)
- BPJS numbers must be unique (if provided)
- No rekening must be unique per bank
- Cannot delete if has payroll data

#### Pengaturan Gaji:
- Combination of (jenis_karyawan, jabatan, lokasi_kerja) must be unique
- Cannot delete if used in Acuan Gaji

#### NKI:
- Combination of (id_karyawan, periode) must be unique
- Cannot add if karyawan doesn't exist
- Cannot delete if used in Acuan Gaji

#### Absensi:
- Combination of (id_karyawan, periode) must be unique
- Cannot add if karyawan doesn't exist
- Cannot delete if used in Acuan Gaji

#### Kasbon:
- Cannot add if karyawan doesn't exist
- Cannot delete if has unpaid cicilan
- Cannot approve if already approved

#### Acuan Gaji:
- Combination of (id_karyawan, periode) must be unique
- Cannot add if periode doesn't have required data (NKI, Absensi)
- Cannot delete if used in Hitung Gaji

#### Hitung Gaji:
- Combination of (karyawan_id, periode) must be unique
- Cannot add if Acuan Gaji doesn't exist
- Cannot delete if used in Slip Gaji

#### Users:
- Email must be unique
- Cannot delete if user is logged in
- Cannot delete own account
- Cannot delete if has activity logs

#### Roles:
- Name must be unique
- Cannot delete if assigned to users
- Cannot delete system roles (Superadmin, User)

### Error Messages:
- Clear and specific
- Show which field has error
- Suggest solution
- Use Indonesian language

---

## Implementation Priority

### Phase 1: URGENT (Fix Production)
1. ✅ Fix deployment script
2. ✅ Create fix-production.sh
3. 🔄 Deploy and test

### Phase 2: Critical Features
1. Dual Dashboard (Admin & User)
2. Fix Profile Page
3. Login Activity Tracking

### Phase 3: Security Features
1. Password Manager Integration
2. Forgot Password with OTP
3. Email Notifications

### Phase 4: Data Validation
1. Implement validation rules
2. Add duplicate prevention
3. Improve error messages

---

## Files to Create/Modify

### New Files:
- `app/Models/LoginActivity.php`
- `app/Models/PasswordResetOtp.php`
- `app/Http/Controllers/Auth/ForgotPasswordController.php`
- `app/Http/Controllers/Auth/ResetPasswordController.php`
- `app/Mail/OtpMail.php`
- `app/Mail/NewLoginNotification.php`
- `resources/views/dashboard/admin.blade.php`
- `resources/views/dashboard/user.blade.php`
- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/verify-otp.blade.php`
- `resources/views/auth/reset-password.blade.php`
- `resources/views/profile/edit.blade.php`
- `resources/views/profile/security.blade.php`
- `resources/views/profile/activity.blade.php`
- `database/migrations/xxxx_create_login_activities_table.php`
- `database/migrations/xxxx_create_password_reset_otps_table.php`

### Modified Files:
- `app/Http/Controllers/DashboardController.php`
- `app/Http/Controllers/ProfileController.php`
- `app/Http/Controllers/Auth/LoginController.php`
- `routes/web.php`
- All model files (add validation)
- All controller files (add duplicate check)

---

## Testing Checklist

### Production Fix:
- [ ] Site loads without 500 error
- [ ] Dashboard displays correctly
- [ ] Login works
- [ ] Navigation works

### Dashboard:
- [ ] Admin dashboard shows all stats
- [ ] User dashboard shows limited stats
- [ ] Permission-based content works
- [ ] Quick actions work

### Profile:
- [ ] Can view profile
- [ ] Can edit profile
- [ ] Can change password
- [ ] Password validation works
- [ ] Activity log displays

### Login Activity:
- [ ] Login tracked correctly
- [ ] Logout tracked correctly
- [ ] Session duration calculated
- [ ] IP and user agent captured
- [ ] Dashboard widget shows activities

### Forgot Password:
- [ ] Email validation works
- [ ] OTP sent to email
- [ ] OTP verification works
- [ ] OTP expires after 5 minutes
- [ ] Password reset works
- [ ] Rate limiting works

### Data Validation:
- [ ] Duplicate prevention works
- [ ] Error messages clear
- [ ] All modules validated
- [ ] Cannot delete referenced data

---

## Notes

- All features must be permission-based
- Use Indonesian language for user-facing text
- Follow existing code style
- Add proper error handling
- Log all security-related events
- Test thoroughly before deployment

