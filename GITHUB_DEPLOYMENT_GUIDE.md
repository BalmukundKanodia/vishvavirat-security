# Automated Deployment with GitHub

## Why Use GitHub Deployment?

Instead of manually uploading files every time you make changes, you can:
- ✅ Make changes on your computer
- ✅ Push to GitHub
- ✅ Hostinger automatically updates your website
- ✅ Keep version history of all changes
- ✅ Easily rollback if something breaks

---

## Setup Process Overview

```
Your Computer (Local)
    ↓
Push changes to GitHub
    ↓
GitHub Repository
    ↓
Hostinger pulls automatically
    ↓
Website Updated! ✅
```

---

## Part 1: Setup Git on Your Computer

### Step 1: Check if Git is Installed

**On Mac/Linux:**
```bash
git --version
```

**On Windows:**
- Open Command Prompt or PowerShell
- Type: `git --version`

**If Git is NOT installed:**
- **Mac:** Install Xcode Command Line Tools or download from https://git-scm.com
- **Windows:** Download from https://git-scm.com/download/win
- **Linux:** `sudo apt-get install git`

### Step 2: Configure Git (First Time Only)

Open Terminal/Command Prompt and run:

```bash
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
```

---

## Part 2: Create GitHub Repository

### Step 1: Create GitHub Account
1. Go to https://github.com
2. Click "Sign up"
3. Create account (it's free)
4. Verify your email

### Step 2: Create New Repository
1. Click the **"+"** icon (top right)
2. Select **"New repository"**
3. Fill in details:
   - **Repository name:** `vishvavirat-security`
   - **Description:** "VISHVAVIRAT SECURITY website"
   - **Visibility:** **Private** (recommended) or Public
   - **DO NOT** initialize with README
4. Click **"Create repository"**

### Step 3: Note Repository URL
After creating, you'll see a URL like:
```
https://github.com/yourusername/vishvavirat-security.git
```
**Save this URL - you'll need it!**

---

## Part 3: Initialize Git in Your Project

### Step 1: Open Terminal in Project Folder

**On Mac:**
1. Open Terminal
2. Type: `cd ` (with space at end)
3. Drag your project folder into Terminal
4. Press Enter

**On Windows:**
1. Open your project folder in File Explorer
2. Shift + Right-click in folder
3. Select "Open PowerShell window here" or "Open Command Prompt here"

**You should now be in your project directory**

### Step 2: Initialize Git Repository

Run these commands one by one:

```bash
# Initialize git repository
git init

# Add all files to git
git add .

# Create first commit
git commit -m "Initial commit - VISHVAVIRAT SECURITY website"

# Link to GitHub repository (replace URL with yours)
git remote add origin https://github.com/yourusername/vishvavirat-security.git

# Push to GitHub
git branch -M main
git push -u origin main
```

### Step 3: Verify Upload
1. Go to your GitHub repository page
2. Refresh the page
3. You should see all your files! ✅

---

## Part 4: Connect Hostinger to GitHub

### Method 1: Using Hostinger's Git Integration (Recommended)

#### Step 1: Access Git in Hostinger
1. Login to Hostinger hPanel
2. Go to your website
3. Look for **"Git"** or **"Advanced" → "Git"**
4. Click on it

#### Step 2: Create Deployment
1. Click **"Create Deployment"** or **"Connect Repository"**
2. Choose **"GitHub"**
3. Click **"Authorize Hostinger"** (you'll be redirected to GitHub)
4. Login to GitHub and authorize Hostinger
5. Select your repository: `vishvavirat-security`
6. Choose branch: `main`
7. Set deployment path: `public_html`
8. Click **"Create"** or **"Deploy"**

#### Step 3: Configure Auto-Deploy
1. Enable **"Automatic deployments"**
2. This means every time you push to GitHub, Hostinger updates automatically
3. ✅ Setup complete!

### Method 2: Manual Git Pull (Alternative)

If Hostinger doesn't have Git integration UI:

#### Step 1: Setup SSH Access
1. In Hostinger, go to **Advanced → SSH Access**
2. Enable SSH
3. Note down SSH credentials

#### Step 2: Connect via SSH
```bash
ssh username@yourserver.hostinger.com
```

#### Step 3: Clone Repository
```bash
cd public_html
git clone https://github.com/yourusername/vishvavirat-security.git .
```

#### Step 4: Pull Updates (When You Make Changes)
```bash
cd public_html
git pull origin main
```

---

## Part 5: Your New Workflow (After Setup)

### Daily Work Process:

#### 1. Make Changes Locally
- Edit files on your computer
- Test locally if possible

#### 2. Commit Changes
Open Terminal in project folder:

```bash
# See what files changed
git status

# Add all changed files
git add .

# Create commit with message describing changes
git commit -m "Updated contact form styling"

# Push to GitHub
git push
```

#### 3. Automatic Deployment
- Hostinger automatically pulls changes from GitHub
- Wait 1-2 minutes
- Website is updated! ✅

### Example: Updating Contact Form

```bash
# 1. Edit contact.html on your computer
# 2. Save the file
# 3. Open Terminal in project folder
# 4. Run these commands:

git add contact.html
git commit -m "Updated contact form - added new field"
git push

# Done! Hostinger updates automatically in 1-2 minutes
```

---

## Part 6: Important Security Notes

### Files to NEVER Commit to GitHub

Your `.gitignore` file already protects these:

```
api/config.php         ← Contains database passwords
logs/*.log            ← Contains error logs
.htaccess             ← May contain sensitive settings
```

### Keep config.php Safe

**Problem:** config.php has your database password
**Solution:** Already handled with `.gitignore`

**How it works:**
1. `config.sample.php` is in GitHub (no passwords)
2. `config.php` is NOT in GitHub (has passwords)
3. After deployment, manually create config.php on Hostinger
4. Only need to do this ONCE

### First Time Deployment Checklist:

When deploying via GitHub for the first time:

1. ✅ Push all files to GitHub
2. ✅ Connect Hostinger to GitHub
3. ✅ Wait for deployment
4. ⚠️ Manually create `api/config.php` on Hostinger (one time only)
5. ⚠️ Add your database credentials to config.php
6. ✅ Test website

**After first deployment:**
- Just push to GitHub
- Hostinger updates automatically
- No need to touch config.php again

---

## Part 7: Common Git Commands

### Check Status
```bash
git status
# Shows what files changed
```

### Add Files
```bash
# Add specific file
git add filename.html

# Add all files
git add .

# Add all HTML files
git add *.html
```

### Commit Changes
```bash
# Commit with message
git commit -m "Your message here"

# See commit history
git log
```

### Push to GitHub
```bash
# Push to main branch
git push

# Force push (careful!)
git push -f
```

### Pull Latest Changes
```bash
# Get latest from GitHub
git pull
```

### View Differences
```bash
# See what changed in a file
git diff filename.html

# See all changes
git diff
```

### Undo Changes
```bash
# Undo changes to a file (before commit)
git checkout filename.html

# Undo last commit (keep changes)
git reset --soft HEAD~1

# Undo last commit (discard changes)
git reset --hard HEAD~1
```

---

## Part 8: Branch Strategy (Optional but Recommended)

### Why Use Branches?

- Test changes safely before going live
- Work on features without breaking live site
- Easy rollback if something goes wrong

### Create Development Branch

```bash
# Create and switch to dev branch
git checkout -b development

# Make changes and test
# ... edit files ...

# Commit to dev branch
git add .
git commit -m "Testing new feature"
git push -u origin development

# When ready, merge to main
git checkout main
git merge development
git push
```

### Workflow with Branches:

```
main branch          ← Live website (Hostinger deploys this)
  ↑
  merge after testing
  ↑
development branch   ← Test changes here first
```

---

## Part 9: Troubleshooting Git/GitHub

### Problem: "Permission denied" when pushing

**Solution:**
```bash
# Use HTTPS instead of SSH
git remote set-url origin https://github.com/yourusername/vishvavirat-security.git

# Or setup SSH keys (advanced)
```

### Problem: "Failed to push some refs"

**Solution:**
```bash
# Pull first, then push
git pull origin main
git push
```

### Problem: Merge conflicts

**Solution:**
```bash
# See conflicted files
git status

# Edit files manually to resolve conflicts
# Look for <<<<<<, ======, >>>>>>

# After fixing
git add .
git commit -m "Resolved conflicts"
git push
```

### Problem: Accidentally committed config.php

**Solution:**
```bash
# Remove from git (keeps file locally)
git rm --cached api/config.php

# Commit the removal
git commit -m "Remove config.php from git"
git push

# Make sure .gitignore has: api/config.php
```

### Problem: Want to undo last commit

**Solution:**
```bash
# Undo commit but keep changes
git reset --soft HEAD~1

# Undo commit and discard changes
git reset --hard HEAD~1
```

---

## Part 10: Alternative Deployment Methods

### Method 1: FTP Sync Tools

**FileZilla (Free):**
- Download: https://filezilla-project.org
- Connect to Hostinger via FTP
- Synchronize folders (it only uploads changed files)
- Still manual, but faster than copying everything

**How to use:**
1. Install FileZilla
2. Add site with Hostinger FTP credentials
3. Connect
4. Right-click on public_html → Synchronize
5. Upload only changed files

### Method 2: VS Code SFTP Extension

**If you use VS Code:**
1. Install "SFTP" extension
2. Configure with Hostinger FTP details
3. Save file → Auto-uploads to Hostinger
4. Very convenient!

**Setup:**
```json
{
  "name": "Hostinger",
  "host": "ftp.yourdomain.com",
  "protocol": "ftp",
  "port": 21,
  "username": "your-ftp-username",
  "password": "your-ftp-password",
  "remotePath": "/public_html",
  "uploadOnSave": true
}
```

### Method 3: GitHub Actions (Advanced)

**Fully automated CI/CD:**
- Push to GitHub
- Runs tests automatically
- Deploys to Hostinger via FTP
- Most professional approach

**Not recommended for beginners** but very powerful.

---

## Part 11: Best Practices

### Commit Messages

**Good commit messages:**
```bash
git commit -m "Add new service page for security guards"
git commit -m "Fix contact form validation bug"
git commit -m "Update footer with new contact info"
```

**Bad commit messages:**
```bash
git commit -m "changes"
git commit -m "fix"
git commit -m "asdfasdf"
```

### Commit Frequency

**How often to commit:**
- ✅ After completing a feature
- ✅ After fixing a bug
- ✅ Before taking a break
- ✅ At end of work day

**Don't:**
- ❌ Commit broken code to main branch
- ❌ Commit every 2 minutes
- ❌ Go weeks without committing

### Before Pushing to Live

**Checklist:**
```
□ Tested changes locally
□ No broken links
□ Forms still working
□ Images loading
□ CSS/JS not broken
□ Commit message is clear
```

---

## Part 12: Deployment Workflow Summary

### One-Time Setup (Do Once):

```
1. Install Git on computer ✅
2. Create GitHub account ✅
3. Create repository ✅
4. Initialize git in project ✅
5. Push to GitHub ✅
6. Connect Hostinger to GitHub ✅
7. Enable auto-deploy ✅
8. Create config.php on Hostinger ✅
```

### Every Time You Make Changes:

```
1. Edit files on computer
2. git add .
3. git commit -m "Description"
4. git push
5. Wait 1-2 minutes
6. Website updated! ✅
```

**That's it! Much easier than manual upload!**

---

## Part 13: Quick Reference

### Most Used Commands:

```bash
# Daily workflow
git status           # Check what changed
git add .            # Stage all changes
git commit -m "msg"  # Create commit
git push             # Upload to GitHub

# Checking things
git log              # See commit history
git diff             # See changes
git branch           # List branches

# Fixing mistakes
git checkout file    # Undo changes to file
git reset --soft     # Undo last commit
```

### Emergency Commands:

```bash
# Something broke? Go back to working version
git log              # Find commit hash of working version
git reset --hard abc123def  # Replace abc123def with actual hash
git push -f          # Force push (careful!)
```

---

## Comparison: Manual vs GitHub Deployment

| Task | Manual Upload | GitHub Deployment |
|------|---------------|-------------------|
| **Initial Setup** | 15 min | 30 min (one time) |
| **Making Changes** | Edit → Upload via FTP (10 min) | Edit → `git push` (30 sec) |
| **Safety** | No backups | All versions saved |
| **Rollback** | Hope you have backup | `git reset` to any version |
| **Team Work** | Very difficult | Easy collaboration |
| **Tracking Changes** | Manual notes | Automatic history |
| **Deployment** | Manual every time | Automatic |

**Winner:** GitHub Deployment! 🏆

---

## Next Steps

### Option 1: Start with GitHub (Recommended)
1. Follow Part 2-6 of this guide
2. Set up automated deployment
3. Enjoy easy updates!

### Option 2: Start Simple, Upgrade Later
1. Deploy manually first (follow main deployment guide)
2. Get website working
3. Later, set up GitHub when comfortable

### Option 3: Use FTP Sync
1. Install FileZilla
2. Use synchronize feature
3. Only uploads changed files
4. Middle ground between manual and automated

---

## Need Help?

### Git Learning Resources:
- **GitHub Guides:** https://guides.github.com
- **Git Tutorial:** https://git-scm.com/docs/gittutorial
- **Interactive Learning:** https://learngitbranching.js.org

### Common Questions:

**Q: Do I need to pay for GitHub?**
A: No! Private repositories are free.

**Q: What if I mess up Git?**
A: Worst case: delete .git folder and start fresh. Your files are safe.

**Q: Can I use both methods?**
A: Not recommended. Choose one: GitHub OR manual FTP.

**Q: How long does auto-deployment take?**
A: Usually 1-2 minutes after pushing to GitHub.

---

**Once you set up GitHub deployment, you'll never want to go back to manual uploads!** 🚀
