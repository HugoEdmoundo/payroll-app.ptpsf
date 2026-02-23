# GitHub Actions Setup Guide

## Step 1: Generate SSH Key on VPS

Login to your VPS and generate SSH key:

```bash
ssh-keygen -t rsa -b 4096 -C "github-actions"
```

Press Enter for all prompts (no passphrase).

Add public key to authorized_keys:
```bash
cat ~/.ssh/id_rsa.pub >> ~/.ssh/authorized_keys
```

Copy private key (you'll need this for GitHub):
```bash
cat ~/.ssh/id_rsa
```

## Step 2: Setup GitHub Secrets

Go to your GitHub repository:
1. Click **Settings**
2. Click **Secrets and variables** > **Actions**
3. Click **New repository secret**

Add these secrets:

### VPS_HOST
Your VPS IP address or domain
```
Example: 192.168.1.100
or: vps.your-domain.com
```

### VPS_USERNAME
SSH username (usually `root` or your user)
```
Example: root
or: ubuntu
or: your-username
```

### VPS_SSH_KEY
Paste the ENTIRE private key from Step 1
```
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAABlwAAAAdzc2gtcn
...
(all lines)
...
-----END OPENSSH PRIVATE KEY-----
```

### VPS_PORT
SSH port (default is 22)
```
22
```

### VPS_DEPLOY_PATH
Full path where application will be deployed
```
Example: /var/www/payroll-psf
or: /home/username/payroll-psf
```

## Step 3: Prepare VPS Directory Structure

On your VPS, create the deployment directory:

```bash
mkdir -p /var/www/payroll-psf/{releases,storage}
cd /var/www/payroll-psf
```

Create initial .env file:
```bash
nano .env
```

Paste your production environment variables (see .env.production.example).

Set permissions:
```bash
chmod 600 .env
chmod -R 775 storage
chown -R www-data:www-data storage
```

## Step 4: Test GitHub Actions

1. Make a small change to your code
2. Commit and push:
   ```bash
   git add .
   git commit -m "Test deployment"
   git push origin main
   ```

3. Go to GitHub repository > **Actions** tab
4. Watch the deployment workflow run
5. Check for any errors

## Step 5: Verify Deployment

SSH to your VPS:
```bash
cd /var/www/payroll-psf/current
php artisan --version
```

Visit your domain and test the application.

## Troubleshooting

### SSH Connection Failed
- Verify VPS_HOST is correct
- Check VPS_PORT (default: 22)
- Ensure SSH key is correct (no extra spaces/newlines)
- Test SSH manually: `ssh -i private_key username@host`

### Permission Denied
- Check VPS_USERNAME is correct
- Verify SSH key is added to authorized_keys
- Check file permissions on VPS

### Deployment Path Not Found
- Verify VPS_DEPLOY_PATH exists
- Create directory: `mkdir -p /var/www/payroll-psf`
- Check user has write permissions

### Migration Failed
- Check database credentials in .env
- Verify MySQL is running
- Test database connection manually

## Manual Trigger

You can also trigger deployment manually:

1. Go to GitHub repository > **Actions**
2. Click **Deploy to VPS** workflow
3. Click **Run workflow**
4. Select branch and click **Run workflow**

## Rollback

If deployment fails, rollback on VPS:

```bash
cd /var/www/payroll-psf
ls -la releases/  # Find previous release
rm -f current
ln -s releases/PREVIOUS_RELEASE current
systemctl reload php8.2-fpm
systemctl reload nginx
```

## Security Best Practices

1. **Never commit .env file**
2. **Use strong passwords** for database
3. **Keep SSH keys secure** (never share private key)
4. **Limit SSH access** (use firewall rules)
5. **Regular backups** of database and files
6. **Monitor logs** for suspicious activity
7. **Keep system updated** (`apt update && apt upgrade`)

## Next Steps

After successful deployment:

1. ✅ Setup SSL certificate (Let's Encrypt)
2. ✅ Configure firewall (UFW)
3. ✅ Setup automated backups
4. ✅ Configure monitoring
5. ✅ Test all features
6. ✅ Create superadmin user
7. ✅ Setup cron jobs

See DEPLOYMENT.md for detailed instructions.
