# GitHub Deployment Guide

## Deploy to GitHub for Client Preview

This guide will help you push the VISHVAVIRAT Security website to GitHub for client review.

---

## Prerequisites

- GitHub account
- Git installed on your computer (already done ✅)
- Repository initialized (already done ✅)

---

## Step 1: Create GitHub Repository

1. Go to [GitHub](https://github.com)
2. Click the **+** icon (top right) → **New repository**
3. Fill in repository details:
   - **Repository name**: `vishvavirat-security` (or any name)
   - **Description**: Professional security and facility management website
   - **Visibility**: Choose **Private** or **Public**
   - **DO NOT** initialize with README (we already have one)
4. Click **Create repository**

---

## Step 2: Connect Local Repository to GitHub

Copy the commands from GitHub's "push an existing repository" section, or use these:

```bash
# Add GitHub as remote origin (replace YOUR_USERNAME and REPO_NAME)
git remote add origin https://github.com/YOUR_USERNAME/vishvavirat-security.git

# Rename branch to main (GitHub default)
git branch -M main

# Push code to GitHub
git push -u origin main
```

**Example:**
```bash
git remote add origin https://github.com/balmukund/vishvavirat-security.git
git branch -M main
git push -u origin main
```

---

## Step 3: Enable GitHub Pages (Optional - For Live Preview)

If you want the client to see a live preview:

1. Go to your repository on GitHub
2. Click **Settings** (top menu)
3. Scroll down to **Pages** (left sidebar)
4. Under **Source**, select:
   - Branch: **main**
   - Folder: **/ (root)**
5. Click **Save**
6. Wait 2-3 minutes
7. Your site will be live at: `https://YOUR_USERNAME.github.io/vishvavirat-security/`

**Note:** GitHub Pages serves static HTML. The contact forms (PHP backend) won't work on GitHub Pages. They require a PHP server like Hostinger.

---

## Step 4: Share with Client

### Option A: Share Repository Link (Private Access)
1. Go to your repository → **Settings** → **Collaborators**
2. Click **Add people**
3. Enter client's GitHub username
4. Client can view code and files

### Option B: Share Live Preview (GitHub Pages)
- Send client the GitHub Pages URL: `https://YOUR_USERNAME.github.io/vishvavirat-security/`
- They can browse the site directly (design and content only)

### Option C: Share Repository Download Link
- Send: `https://github.com/YOUR_USERNAME/vishvavirat-security/archive/refs/heads/main.zip`
- Client can download and review locally

---

## What the Client Will See

### Working Features:
✅ Complete website design and layout
✅ All 6 service pages with professional copywriting
✅ Navigation and menu functionality
✅ Hero image slider with arrows
✅ Mobile responsive design
✅ All static content

### Not Working (GitHub Pages only):
❌ Contact form submissions (requires PHP server)
❌ Email notifications (requires Hostinger deployment)
❌ Database storage (requires MySQL)

**Note:** These features will work once deployed to Hostinger.

---

## Updating After Client Feedback

When you make changes based on client feedback:

```bash
# After editing files
git add .
git commit -m "Updated based on client feedback: [describe changes]"
git push origin main
```

Changes will automatically update on GitHub Pages within 1-2 minutes.

---

## Current Repository Status

✅ Git repository initialized
✅ All files committed
✅ README updated with new design info
✅ Ready to push to GitHub

**Commits:**
1. Initial commit with all pages and features
2. README updated to reflect new design

**Files included:** 54 files (HTML, CSS, JS, images, documentation)

---

## Next Steps

1. Create GitHub repository
2. Push code using commands above
3. Enable GitHub Pages for live preview (optional)
4. Share link with client
5. Collect feedback
6. Make updates
7. **Final deployment to Hostinger** (see DEPLOYMENT_GUIDE.md)

---

## Important Notes

- The old design is preserved as `index-old.html`
- The new design is now the default `index.html`
- All navigation links point to the new design
- Contact form PHP backend is included but won't work on GitHub Pages
- Full functionality requires Hostinger deployment with PHP/MySQL

---

## Support

For Hostinger deployment after client approval, see **DEPLOYMENT_GUIDE.md**

---

**Current Status:** Ready for GitHub Push ✅
