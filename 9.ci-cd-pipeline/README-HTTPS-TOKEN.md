# NAGADHAT Staging Deployment - Personal Access Token (HTTPS) Method

## 📋 Overview

This deployment workflow uses **GitHub Personal Access Token (PAT)** to authenticate with your private GitHub repository via HTTPS. This method is easier to set up but slightly less secure than SSH keys.

---

## 🔑 Authentication Method

- **Type**: Personal Access Token (HTTPS)
- **Security**: ⭐⭐⭐ (Good)
- **Best for**: Quick setups, testing, temporary environments
- **Advantages**:
  - Easier to set up than SSH keys
  - Works well with HTTPS-only environments
  - Can be used across multiple repositories
  - Easy to rotate and manage

---

## 📦 Prerequisites

Before setting up this workflow, ensure you have:

- [ ] Ubuntu server with SSH access
- [ ] Git installed on the server
- [ ] PHP and Composer installed
- [ ] Laravel project directory at `/var/www/html`
- [ ] Sudo privileges for reloading PHP-FPM
- [ ] GitHub private repository access
- [ ] Ability to create GitHub Personal Access Tokens

---

## 🚀 Setup Instructions

### Step 1: Create GitHub Personal Access Token

1. Go to GitHub and click your profile picture → **Settings**
2. Scroll down and click **Developer settings** (bottom left)
3. Click **Personal access tokens** → **Tokens (classic)**
4. Click **Generate new token** → **Generate new token (classic)**
5. Configure the token:
   - **Note**: `Nagadhat Staging Server Deploy Token`
   - **Expiration**: Choose duration (90 days, 1 year, or No expiration)
   - **Scopes**: Check ✅ **repo** (Full control of private repositories)
6. Click **Generate token**
7. **⚠️ IMPORTANT**: Copy the token immediately! You won't be able to see it again.

**Your token will look like**: `ghp_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`

---

### Step 2: Configure Git Credentials on Server

SSH into your staging server and run:

```bash
# Navigate to your web directory
cd /var/www/html

# Initialize git repository (if not already initialized)
git init

# Configure Git credential helper to store credentials
git config --global credential.helper store

# Add your GitHub repository as remote using HTTPS
# Replace YOUR_USERNAME and YOUR_REPO with your actual values
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git

# If remote already exists, update it:
git remote set-url origin https://github.com/YOUR_USERNAME/YOUR_REPO.git

# Verify remote URL (should start with https://)
git remote -v
```

---

### Step 3: Store GitHub Credentials

Now we'll pull the code for the first time, which will prompt for credentials:

```bash
# Checkout and pull from development branch
git checkout -b development-branch
git pull origin development-branch

# When prompted, enter:
# Username: YOUR_GITHUB_USERNAME
# Password: YOUR_PERSONAL_ACCESS_TOKEN (paste the token you created)
```

**Note**: The password is your Personal Access Token, not your GitHub password!

After successful authentication, credentials are stored in `~/.git-credentials` and won't be asked again.

---

### Step 4: Verify Stored Credentials

```bash
# Check if credentials are stored (optional)
cat ~/.git-credentials

# Should show: https://USERNAME:TOKEN@github.com
```

**⚠️ Security Note**: Credentials are stored in **plain text**. For better security:

```bash
# Use Git Credential Manager (more secure)
# Install libsecret
sudo apt-get install libsecret-1-0 libsecret-1-dev

# Configure git to use libsecret
git config --global credential.helper /usr/share/doc/git/contrib/credential/libsecret/git-credential-libsecret
```

---

### Step 5: Setup Laravel Environment

```bash
# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### Step 6: Configure GitHub Secrets

In your GitHub repository:

1. Go to **Settings** → **Secrets and variables** → **Actions**
2. Click **New repository secret**
3. Add these secrets:

| Secret Name | Description | Example Value |
|-------------|-------------|---------------|
| `NAGADHAT_STG_HOST` | Server IP or hostname | `192.168.1.100` or `staging.example.com` |
| `NAGADHAT_STG_USER` | SSH username | `ubuntu` or `deployer` |
| `NAGADHAT_STG_PORT` | SSH port | `22` (default) or custom port |
| `NAGADHAT_STG_SSH_KEY` | **Private SSH key** for GitHub Actions to connect to your server | Contents of your server's private key |

**Note**: The SSH key here is for GitHub Actions to connect to your server, not for Git authentication (which uses the Personal Access Token).

---

### Step 7: Add Workflow File to Repository

1. In your repository, create this directory structure:
   ```
   .github/
   └── workflows/
       └── deploy-staging.yml
   ```

2. Copy the contents of `deploy-git-https.yml` into `deploy-staging.yml`

3. Commit and push:
   ```bash
   git add .github/workflows/deploy-staging.yml
   git commit -m "Add staging deployment workflow"
   git push origin development-branch
   ```

---

## 🎯 How It Works

When you push to the `development-branch`:

1. **GitHub Actions triggers** the workflow
2. **SSH connection** is established to your staging server
3. **Git pull** fetches latest code using stored credentials (PAT)
4. **Composer** installs/updates dependencies
5. **Laravel optimization** caches configs, routes, and views
6. **Permissions** are set correctly
7. **PHP-FPM** is reloaded
8. **Deployment completes** ✅

---

## 🔧 Optional Configuration

### Enable Database Migrations

To automatically run migrations on deployment, uncomment these lines in the workflow:

```yaml
# echo "🗄️  Running database migrations..."
# php artisan migrate --force
```

### Enable Database Seeding

To automatically seed the database, uncomment:

```yaml
# echo "🌱 Seeding database..."
# php artisan db:seed --force
```

### Adjust PHP Version

Update the PHP-FPM reload command if you use a different PHP version:

```bash
sudo systemctl reload php8.3-fpm  # Change to your PHP version
```

---

## 🔍 Troubleshooting

### Problem: "Authentication failed" or "Could not read from remote repository"

**Solution 1 - Verify Token**:
```bash
# Check if credentials are stored
cat ~/.git-credentials

# If not, re-enter credentials
git pull origin development-branch
# Enter username and PAT when prompted
```

**Solution 2 - Update Remote URL**:
```bash
# Update remote with embedded token (less secure but works)
git remote set-url origin https://YOUR_TOKEN@github.com/YOUR_USERNAME/YOUR_REPO.git
```

### Problem: "Token expired"

**Solution**:
```bash
# Generate new token on GitHub (see Step 1)
# Remove old credentials
rm ~/.git-credentials

# Pull again and enter new token
git pull origin development-branch
```

### Problem: "Git asking for password on every pull"

**Solution**:
```bash
# Verify credential helper is configured
git config --global credential.helper

# Should return: store

# If not, configure it:
git config --global credential.helper store

# Pull once more to store credentials
git pull origin development-branch
```

### Problem: "Permission denied to repository"

**Solution**:
```bash
# Verify your token has 'repo' scope
# Regenerate token with correct permissions (Step 1)

# Verify remote URL
git remote -v

# Should be: https://github.com/YOUR_USERNAME/YOUR_REPO.git
```

### Problem: "Composer install fails"

**Solution**:
```bash
# Update Composer
composer self-update

# Clear Composer cache
composer clear-cache

# Try installing again
composer install --no-dev --optimize-autoloader
```

---

## 🔐 Security Best Practices

✅ **DO**:
- Set token expiration dates (rotate every 90 days)
- Use minimal required scopes (only 'repo' for this use case)
- Store tokens in GitHub Secrets when possible
- Use Git Credential Manager for encrypted storage
- Revoke tokens when no longer needed
- Create separate tokens for different servers/environments

❌ **DON'T**:
- Share tokens in code or commit them to repository
- Use tokens with excessive permissions
- Leave tokens with "No expiration" unless absolutely necessary
- Commit `.git-credentials` file to version control
- Use your GitHub password instead of PAT

---

## 🔄 Token Rotation

To update your Personal Access Token:

```bash
# 1. Generate new token on GitHub (see Step 1)

# 2. Remove old credentials
rm ~/.git-credentials

# 3. Pull code and enter new credentials
cd /var/www/html
git pull origin development-branch
# Username: YOUR_GITHUB_USERNAME
# Password: NEW_PERSONAL_ACCESS_TOKEN

# 4. Verify it works
git pull origin development-branch  # Should work without asking for password
```

---

## 📊 Deployment Logs

To view deployment logs:

1. Go to your GitHub repository
2. Click **Actions** tab
3. Select the latest workflow run
4. Click on the **deploy** job to see detailed logs

---

## 🔄 Rollback Instructions

If you need to rollback to a previous commit:

```bash
# SSH into your server
ssh user@your-server

# Navigate to app directory
cd /var/www/html

# View recent commits
git log --oneline -10

# Rollback to a specific commit
git reset --hard COMMIT_HASH

# Re-run Laravel optimizations
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🎁 Bonus: Rollback Workflow

Create `.github/workflows/rollback-staging.yml`:

```yaml
name: ROLLBACK STAGING

on:
  workflow_dispatch:
    inputs:
      commit:
        description: 'Commit hash to rollback to'
        required: true
        type: string

jobs:
  rollback:
    runs-on: ubuntu-latest
    steps:
      - name: 🔙 Rollback Deployment
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.NAGADHAT_STG_HOST }}
          username: ${{ secrets.NAGADHAT_STG_USER }}
          port: ${{ secrets.NAGADHAT_STG_PORT }}
          key: ${{ secrets.NAGADHAT_STG_SSH_KEY }}
          script: |
            set -e
            cd /var/www/html
            
            echo "🔙 Rolling back to commit: ${{ github.event.inputs.commit }}"
            git reset --hard ${{ github.event.inputs.commit }}
            
            composer install --no-dev --optimize-autoloader
            php artisan optimize:clear
            php artisan config:cache
            php artisan route:cache
            php artisan view:cache
            
            echo "✅ Rollback completed!"
```

To use: Go to **Actions** → **ROLLBACK STAGING** → **Run workflow** → Enter commit hash

---

## 🔄 Migration from HTTPS to SSH

If you want to migrate to SSH Deploy Keys later (more secure):

```bash
# On server, generate SSH key
ssh-keygen -t ed25519 -C "deploy" -f ~/.ssh/deploy_key

# Add public key to GitHub Deploy Keys
cat ~/.ssh/deploy_key.pub

# Configure SSH
cat >> ~/.ssh/config << 'EOF'
Host github.com
    IdentityFile ~/.ssh/deploy_key
    IdentitiesOnly yes
EOF

# Change remote from HTTPS to SSH
git remote set-url origin git@github.com:YOUR_USERNAME/YOUR_REPO.git

# Test
git pull origin development-branch
```

Then use the SSH workflow instead.

---

## 📞 Support

If you encounter issues:

1. Check the troubleshooting section above
2. Review GitHub Actions logs
3. Verify all secrets are correctly configured
4. Ensure Personal Access Token has correct permissions and hasn't expired
5. Test git pull manually on the server

---

## ✅ Checklist

Before first deployment, ensure:

- [ ] Personal Access Token created with 'repo' scope
- [ ] Token copied and saved securely
- [ ] Git credential helper configured on server
- [ ] Git repository initialized at `/var/www/html`
- [ ] Remote origin set to HTTPS URL
- [ ] Credentials stored after first pull
- [ ] All GitHub secrets configured
- [ ] Workflow file added to `.github/workflows/`
- [ ] `.env` file exists on server
- [ ] Directory permissions set correctly
- [ ] PHP-FPM version matches in workflow

---

## ⚖️ SSH vs HTTPS Comparison

| Feature | HTTPS (This Method) | SSH Deploy Keys |
|---------|---------------------|-----------------|
| Setup Complexity | ⭐⭐ (Easy) | ⭐⭐⭐ (Medium) |
| Security | ⭐⭐⭐ (Good) | ⭐⭐⭐⭐⭐ (Excellent) |
| Token Rotation | Required | Not required |
| Multi-repo | Can reuse token | Need separate keys |
| Firewall Friendly | ✅ Yes (Port 443) | Sometimes blocked |
| Best For | Quick setups, testing | Production, Staging |

---

**Method**: Personal Access Token (HTTPS)  
**Security Level**: ⭐⭐⭐  
**Recommended for**: Development & Testing Environments  
**Migrate to SSH for**: Production Environments
