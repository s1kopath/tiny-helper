# NAGADHAT Staging Deployment - SSH Deploy Key Method

## 📋 Overview

This deployment workflow uses **SSH Deploy Keys** to authenticate with your private GitHub repository. This is the **most secure and recommended method** for production and staging environments.

---

## 🔑 Authentication Method

- **Type**: SSH Deploy Key
- **Security**: ⭐⭐⭐⭐⭐ (Highest)
- **Best for**: Production, Staging, and long-term deployments
- **Advantages**:
  - Most secure method
  - Key is scoped to single repository
  - No password or token exposure
  - Easy to revoke without affecting other repos

---

## 📦 Prerequisites

Before setting up this workflow, ensure you have:

- [ ] Ubuntu server with SSH access
- [ ] Git installed on the server
- [ ] PHP and Composer installed
- [ ] Laravel project directory at `/var/www/html`
- [ ] Sudo privileges for reloading PHP-FPM
- [ ] GitHub private repository access

---

## 🚀 Setup Instructions

### Step 1: Generate SSH Deploy Key on Server

SSH into your staging server and run:

```bash
# Generate SSH key (press Enter for all prompts - no passphrase)
ssh-keygen -t ed25519 -C "nagadhat-staging-deploy" -f ~/.ssh/nagadhat_deploy_key

# Display the public key (you'll need this for GitHub)
cat ~/.ssh/nagadhat_deploy_key.pub
```

**Copy the entire output** - you'll add this to GitHub in the next step.

---

### Step 2: Add Deploy Key to GitHub

1. Go to your GitHub repository
2. Navigate to **Settings** → **Deploy keys**
3. Click **Add deploy key**
4. Configure the deploy key:
   - **Title**: `Staging Server Deploy Key`
   - **Key**: Paste the public key from Step 1
   - **Allow write access**: ❌ Leave unchecked (read-only is safer)
5. Click **Add key**

---

### Step 3: Configure SSH on Server

```bash
# Create/update SSH config
cat >> ~/.ssh/config << 'EOF'

Host github.com
    HostName github.com
    IdentityFile ~/.ssh/nagadhat_deploy_key
    IdentitiesOnly yes
EOF

# Set proper permissions
chmod 600 ~/.ssh/config
chmod 600 ~/.ssh/nagadhat_deploy_key
chmod 644 ~/.ssh/nagadhat_deploy_key.pub

# Test SSH connection to GitHub
ssh -T git@github.com
```

**Expected output**: `Hi USERNAME! You've successfully authenticated, but GitHub does not provide shell access.`

---

### Step 4: Initialize Git Repository on Server

```bash
# Navigate to your web directory
cd /var/www/html

# Initialize git (if not already initialized)
git init

# Add your GitHub repository as remote (replace with your repo URL)
git remote add origin git@github.com:YOUR_USERNAME/YOUR_REPO.git

# If remote already exists, update it:
git remote set-url origin git@github.com:YOUR_USERNAME/YOUR_REPO.git

# Verify remote URL (should start with git@github.com)
git remote -v

# Checkout development branch
git checkout -b development-branch

# Pull initial code
git pull origin development-branch
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
| `NAGADHAT_STG_SSH_KEY` | **Private SSH key** for GitHub Actions to connect to your server | Contents of your server's private key for SSH access |

**Important**: `NAGADHAT_STG_SSH_KEY` is different from the deploy key. This is the private key that GitHub Actions uses to SSH into your server.

---

### Step 7: Add Workflow File to Repository

1. In your repository, create this directory structure:
   ```
   .github/
   └── workflows/
       └── deploy-staging.yml
   ```

2. Copy the contents of `deploy-git-ssh.yml` into `deploy-staging.yml`

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
3. **Git pull** fetches the latest code from GitHub
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

### Problem: "Permission denied (publickey)"

**Solution**:
```bash
# Check if SSH key exists
ls -la ~/.ssh/nagadhat_deploy_key

# Test GitHub connection with verbose output
ssh -vT git@github.com

# Verify SSH config
cat ~/.ssh/config
```

### Problem: "Git pull asks for password"

**Solution**:
```bash
# Check remote URL format
git remote -v

# Should be: git@github.com:user/repo.git (SSH)
# If it shows https://, change it:
git remote set-url origin git@github.com:YOUR_USERNAME/YOUR_REPO.git
```

### Problem: "Failed to execute git pull"

**Solution**:
```bash
cd /var/www/html

# Check git status
git status

# If there are conflicts, stash changes
git stash

# Then pull
git pull origin development-branch
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
- Use separate deploy keys for each environment (staging, production)
- Keep deploy keys read-only when possible
- Rotate keys periodically (every 6-12 months)
- Use strong SSH keys (ed25519 or RSA 4096-bit)
- Store secrets in GitHub Secrets (never in code)

❌ **DON'T**:
- Share deploy keys across multiple servers
- Commit `.env` files to repository
- Use the same key for multiple repositories
- Grant write access unless absolutely necessary

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

Or create a manual rollback workflow (see bonus section below).

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

## 📞 Support

If you encounter issues:

1. Check the troubleshooting section above
2. Review GitHub Actions logs
3. Verify all secrets are correctly configured
4. Test SSH connection manually from your server

---

## ✅ Checklist

Before first deployment, ensure:

- [ ] SSH deploy key generated on server
- [ ] Public key added to GitHub Deploy Keys
- [ ] SSH config properly configured
- [ ] Git repository initialized at `/var/www/html`
- [ ] Remote origin set to SSH URL (git@github.com:...)
- [ ] All GitHub secrets configured
- [ ] Workflow file added to `.github/workflows/`
- [ ] `.env` file exists on server
- [ ] Directory permissions set correctly
- [ ] PHP-FPM version matches in workflow

---

**Method**: SSH Deploy Key  
**Security Level**: ⭐⭐⭐⭐⭐  
**Recommended for**: Production & Staging Environments
