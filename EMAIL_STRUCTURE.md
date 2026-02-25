# Email Structure Documentation

## Struktur Email di Sistem

### 1. Email (Login Email)
**Field**: `email`
**Purpose**: Email untuk login ke sistem
**Editable by**: Hanya Superadmin
**Validation**: 
- Must be unique
- Must be valid email format
- Required field

**Rules:**
- User TIDAK BISA edit email login sendiri
- Hanya Superadmin yang bisa edit email login user
- Email ini digunakan untuk authentication
- Tidak bisa digunakan untuk forgot password

### 2. Email Valid (Verification Email)
**Field**: `email_valid`
**Purpose**: Email untuk verifikasi forgot password dan notifikasi
**Editable by**: User sendiri dan Superadmin
**Validation**:
- Must be valid email format
- Optional (nullable)
- Recommended to be different from login email

**Rules:**
- User BISA edit email_valid sendiri di profile
- Email ini digunakan untuk:
  - Forgot password OTP
  - Login notifications
  - Security alerts
  - System notifications
- Jika tidak diisi, forgot password tidak bisa digunakan

## Use Cases

### Scenario 1: User Lupa Password
1. User klik "Forgot Password"
2. System minta input email
3. User input `email_valid` (bukan email login)
4. System kirim OTP ke `email_valid`
5. User verifikasi OTP
6. User reset password
7. User login dengan `email` (login email) dan password baru

### Scenario 2: Login Notification
1. User login dengan `email` (login email)
2. System kirim notifikasi ke `email_valid`
3. Email berisi: waktu login, IP address, device info

### Scenario 3: Security Alert
1. System detect suspicious activity
2. System kirim alert ke `email_valid`
3. User bisa take action

## Database Schema

```sql
-- users table
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE NOT NULL,  -- Login email (only superadmin can edit)
    email_valid VARCHAR(255) NULL,        -- Verification email (user can edit)
    password VARCHAR(255),
    -- ... other fields
);
```

## UI/UX

### Profile Page

**Login Email Section:**
```
┌─────────────────────────────────────┐
│ Login Email                         │
│ ┌─────────────────────────────────┐ │
│ │ user@example.com                │ │
│ └─────────────────────────────────┘ │
│ 🔒 Only admin can change this      │
└─────────────────────────────────────┘
```

**Verification Email Section:**
```
┌─────────────────────────────────────┐
│ Verification Email                  │
│ ┌─────────────────────────────────┐ │
│ │ personal@gmail.com              │ │
│ └─────────────────────────────────┘ │
│ ✏️  You can edit this              │
│ ℹ️  Used for password recovery     │
└─────────────────────────────────────┘
```

### Forgot Password Flow

**Step 1: Enter Email**
```
┌─────────────────────────────────────┐
│ Forgot Password                     │
│                                     │
│ Enter your verification email:     │
│ ┌─────────────────────────────────┐ │
│ │                                 │ │
│ └─────────────────────────────────┘ │
│                                     │
│ ⚠️  Use your verification email,   │
│    not your login email            │
│                                     │
│ [Send OTP]                          │
└─────────────────────────────────────┘
```

**Step 2: Verify OTP**
```
┌─────────────────────────────────────┐
│ Verify OTP                          │
│                                     │
│ Enter 6-digit code sent to:        │
│ personal@gmail.com                  │
│                                     │
│ ┌───┬───┬───┬───┬───┬───┐          │
│ │   │   │   │   │   │   │          │
│ └───┴───┴───┴───┴───┴───┘          │
│                                     │
│ Expires in: 04:32                   │
│                                     │
│ [Verify] [Resend OTP]               │
└─────────────────────────────────────┘
```

## Security Considerations

### Why Separate Emails?

1. **Security**: Login email tetap rahasia, verification email bisa lebih public
2. **Flexibility**: User bisa ganti verification email tanpa ganti login
3. **Recovery**: Jika email_valid di-hack, login email masih aman
4. **Control**: Admin kontrol penuh atas login credentials

### Best Practices:

1. **Encourage Different Emails**:
   - Login email: work email (company domain)
   - Verification email: personal email (gmail, yahoo, etc)

2. **Validation**:
   - Both emails must be valid format
   - email_valid should be verified (send verification email)
   - Warn user if both emails are same

3. **Notifications**:
   - Always send to email_valid
   - Never expose login email in notifications
   - Include security tips in emails

## Implementation Checklist

### Database:
- [x] Add email_valid column to users table
- [x] Update User model fillable
- [ ] Add email_valid to user seeder

### Controllers:
- [ ] Update ProfileController to allow email_valid edit
- [ ] Update UserController (admin) to edit both emails
- [ ] Create ForgotPasswordController with email_valid validation

### Views:
- [ ] Update profile edit form
- [ ] Create forgot password form
- [ ] Add email_valid field to user create/edit (admin)

### Validation:
- [ ] Validate email_valid format
- [ ] Check email_valid exists for forgot password
- [ ] Warn if email and email_valid are same

### Email Templates:
- [ ] OTP email template
- [ ] Login notification template
- [ ] Security alert template
- [ ] Password reset confirmation template

## Example Code

### Profile Controller (User Edit email_valid)
```php
public function updateEmail(Request $request)
{
    $request->validate([
        'email_valid' => 'required|email|max:255',
    ]);
    
    $user = auth()->user();
    
    // Warn if same as login email
    if ($request->email_valid === $user->email) {
        return back()->with('warning', 'Sebaiknya gunakan email yang berbeda dari email login.');
    }
    
    $user->update([
        'email_valid' => $request->email_valid
    ]);
    
    // Send verification email
    Mail::to($request->email_valid)->send(new VerifyEmailValid($user));
    
    return back()->with('success', 'Email verifikasi berhasil diupdate. Silakan cek email untuk verifikasi.');
}
```

### Forgot Password Controller
```php
public function sendOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);
    
    // Find user by email_valid (NOT email!)
    $user = User::where('email_valid', $request->email)->first();
    
    if (!$user) {
        return back()->with('error', 'Email verifikasi tidak ditemukan.');
    }
    
    // Generate OTP
    $otp = rand(100000, 999999);
    
    // Save OTP
    PasswordResetOtp::create([
        'email' => $request->email,
        'otp' => $otp,
        'expires_at' => now()->addMinutes(5)
    ]);
    
    // Send OTP
    Mail::to($request->email)->send(new OtpMail($otp));
    
    return redirect()->route('verify-otp')->with('email', $request->email);
}
```

## Testing Scenarios

### Test 1: User Edit email_valid
- [x] User can edit email_valid in profile
- [ ] Validation works
- [ ] Warning shown if same as login email
- [ ] Verification email sent

### Test 2: Admin Edit Both Emails
- [ ] Admin can edit both email and email_valid
- [ ] Validation works for both
- [ ] User notified of changes

### Test 3: Forgot Password
- [ ] User enters email_valid
- [ ] OTP sent to email_valid
- [ ] OTP verification works
- [ ] Password reset successful
- [ ] User can login with email (not email_valid)

### Test 4: Security
- [ ] Cannot use email for forgot password
- [ ] Cannot reset password without email_valid
- [ ] OTP expires after 5 minutes
- [ ] Rate limiting works

