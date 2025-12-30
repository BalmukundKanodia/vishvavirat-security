# 📸 IMAGE GUIDE - Add Professional Images to Your Website

Based on the American Guard Services website, you need **professional, high-quality images** of security personnel and facilities.

---

## 🎯 **REQUIRED IMAGES**

### **Hero Slider Images** (3 images)

Save these in `/images/` folder:

1. **`hero-security.jpg`** (1920x800px)
   - Professional security guard in uniform
   - Standing confidently, outdoor/building entrance
   - High quality, professional lighting

2. **`hero-guard.jpg`** (1920x800px)
   - Security personnel at work
   - Could be: monitoring, patrolling, or at control desk
   - Professional setting

3. **`hero-facility.jpg`** (1920x800px)
   - Facility management staff working
   - Housekeeping or maintenance in action
   - Clean, professional environment

---

### **Service Images** (6 images - 1200x800px each)

1. **`service-bouncer.jpg`**
   - Personal security guard/bouncer in formal attire
   - Professional, strong presence
   - High-end security look

2. **`service-government.jpg`**
   - Security guard in government/institutional setting
   - Formal uniform
   - Professional demeanor

3. **`service-driver.jpg`**
   - Professional driver next to vehicle
   - Clean uniform, professional appearance
   - Luxury/executive car setting

4. **`service-housekeeping.jpg`**
   - Housekeeping staff at work
   - Hotel/hospital setting
   - Professional cleaning attire

5. **`service-gardener.jpg`**
   - Gardener maintaining landscape
   - Professional appearance
   - Well-maintained garden setting

6. **`service-maid.jpg`**
   - Domestic help in professional attire
   - Clean, organized setting
   - Professional and trustworthy appearance

---

### **Industry Images** (4 images - 1200x800px each)

1. **`industry-hospital.jpg`**
   - Healthcare facility
   - Could show: hospital entrance, corridor with security, or medical building

2. **`industry-hotel.jpg`**
   - Hotel/hospitality setting
   - Luxury hotel entrance or lobby
   - Professional security presence

3. **`industry-school.jpg`**
   - Educational institution
   - School/college campus
   - Security at entrance or campus area

4. **`industry-apartment.jpg`**
   - Residential complex
   - Apartment building entrance
   - Gated community with security

---

### **About Section Image** (1 image)

1. **`about-security.jpg`** (800x1000px)
   - Team of security personnel
   - OR: Security guard in professional uniform
   - Conveys trust and professionalism

---

### **Logo & Icons**

1. **`logo.png`** (500x500px, transparent background)
   - Your company logo
   - PNG format with transparency
   - Professional design

2. **`favicon.png`** (512x512px)
   - Small icon for browser tab
   - Can be simplified version of logo

---

## 📥 **WHERE TO GET IMAGES**

### **Option 1: Professional Photoshoot (BEST)**
- Hire a photographer
- Photograph your actual staff
- Most authentic and trustworthy
- Cost: ₹5,000-15,000 for full shoot

### **Option 2: Stock Photos (FREE/PAID)**

**Free Sources:**
1. **Unsplash** (https://unsplash.com)
   - Search: "security guard", "security personnel", "housekeeping"
   - High quality, free for commercial use

2. **Pexels** (https://www.pexels.com)
   - Search: "security", "facility management", "cleaning staff"
   - Free, no attribution required

3. **Pixabay** (https://pixabay.com)
   - Search: "security guard", "driver", "maid"
   - Free for commercial use

**Paid Sources (Better Quality):**
1. **Shutterstock** (https://www.shutterstock.com)
   - Search: "indian security guard", "security services"
   - 10 images: ~$49/month

2. **iStock** (https://www.istockphoto.com)
   - High quality, professional images
   - Similar pricing to Shutterstock

### **Option 3: Use AI Generation (Modern Approach)**
1. **Midjourney** or **DALL-E**
   - Generate custom images
   - Prompt: "Professional Indian security guard in uniform, standing confidently"
   - Cost: ~$10-30/month

---

## 🎨 **IMAGE SPECIFICATIONS**

### **Dimensions**

| Image Type | Size (pixels) | Aspect Ratio |
|-----------|---------------|--------------|
| Hero Slider | 1920 x 800 | 12:5 |
| Service Cards | 1200 x 800 | 3:2 |
| Industry Cards | 1200 x 800 | 3:2 |
| About Section | 800 x 1000 | 4:5 |
| Logo | 500 x 500 | 1:1 |
| Favicon | 512 x 512 | 1:1 |

### **Quality Requirements**
- Format: JPG for photos, PNG for logo/icons
- File size: < 500KB per image (optimize!)
- Resolution: 72 DPI for web
- Color mode: RGB

---

## 🛠️ **HOW TO OPTIMIZE IMAGES**

### **Step 1: Resize Images**

Use online tools:
1. **ResizeImage.net** (https://resizeimage.net)
2. **ILoveIMG** (https://www.iloveimg.com/resize-image)

### **Step 2: Compress Images**

Use these tools to reduce file size:
1. **TinyPNG** (https://tinypng.com) - BEST, reduces 70% without quality loss
2. **Compressor.io** (https://compressor.io)
3. **ImageOptim** (Mac app)

**Target:** Each image should be < 500KB

### **Step 3: Convert to WebP (Optional - Better Performance)**

WebP = 30% smaller than JPG!

Use: **Convertio** (https://convertio.co/jpg-webp/)

---

## 📂 **HOW TO ADD IMAGES TO YOUR WEBSITE**

### **Step 1: Download/Create Images**

Get your images from stock sites or photoshoot.

### **Step 2: Rename Files Correctly**

Rename to match these exact names:

```
hero-security.jpg
hero-guard.jpg
hero-facility.jpg
service-bouncer.jpg
service-government.jpg
service-driver.jpg
service-housekeeping.jpg
service-gardener.jpg
service-maid.jpg
industry-hospital.jpg
industry-hotel.jpg
industry-school.jpg
industry-apartment.jpg
about-security.jpg
logo.png
favicon.png
```

### **Step 3: Optimize Images**

1. Resize to correct dimensions
2. Compress with TinyPNG
3. Ensure file size < 500KB each

### **Step 4: Upload to /images/ Folder**

**Via FTP:**
```
Upload all images to:
/public_html/images/
```

**Via File Manager:**
1. cPanel → File Manager
2. Navigate to: public_html/images/
3. Click "Upload"
4. Select all your images
5. Upload

### **Step 5: Test**

Visit your website:
```
https://www.viratsecurity.com/index-new.html
```

All images should load!

---

## 🎨 **TEMPORARY PLACEHOLDER IMAGES**

Until you get real images, create simple placeholders:

### **Using Placeholder Services**

**Method 1: Placeholder.com**
Replace image sources in HTML temporarily:

```html
<!-- Hero Images -->
<img src="https://via.placeholder.com/1920x800/1a1a1a/c9a961?text=Security+Services" alt="Security">

<!-- Service Images -->
<img src="https://via.placeholder.com/1200x800/2a2a2a/ffffff?text=Personal+Bouncer" alt="Bouncer">
```

**Method 2: Unsplash Random Images**
```html
<img src="https://source.unsplash.com/1920x800/?security,guard" alt="Security">
```

---

## 📋 **IMAGE CHECKLIST**

Before going live, ensure:

- [ ] All 18 images downloaded/created
- [ ] Images resized to correct dimensions
- [ ] Images compressed (< 500KB each)
- [ ] Files renamed correctly
- [ ] Images uploaded to /images/ folder
- [ ] Website tested - all images loading
- [ ] Images are professional quality
- [ ] Images represent your actual services
- [ ] No copyright issues

---

## 🚀 **QUICK START: Get Images in 1 Hour**

**Step-by-Step:**

1. **Go to Unsplash.com** (10 min)
2. **Search & Download:**
   - "security guard" → 3 images for hero slider
   - "security personnel" → 6 images for services
   - "hospital entrance" → industry-hospital.jpg
   - "hotel lobby" → industry-hotel.jpg
   - "school campus" → industry-school.jpg
   - "apartment building" → industry-apartment.jpg
   - "security team" → about-security.jpg

3. **Go to TinyPNG.com** (20 min)
   - Upload all images
   - Download compressed versions

4. **Rename Files** (5 min)
   - Rename to exact names from list above

5. **Upload to Website** (10 min)
   - FTP or File Manager
   - Upload to /images/ folder

6. **Test** (5 min)
   - Visit your website
   - Check all images load

**Total Time: 50 minutes!**

---

## 💡 **PRO TIPS**

1. **Consistency:** Use images with similar lighting/style
2. **Indian Context:** Prefer images showing Indian security personnel
3. **Professional Quality:** Avoid blurry or low-quality images
4. **Diversity:** Show different types of security/facility staff
5. **Real People:** Avoid overly "stock photo" looking images
6. **Brand Colors:** Consider images that match your color scheme (dark blue, gold)

---

## 🎯 **RECOMMENDED IMAGE SEARCH TERMS**

**For Unsplash/Pexels:**

- "professional security guard"
- "security officer uniform"
- "security personnel india"
- "hotel security"
- "hospital security"
- "bodyguard professional"
- "executive driver"
- "housekeeping staff hotel"
- "facility management"
- "residential security"
- "campus security"

---

## ❓ **NEED HELP?**

If you need help finding or creating images:
1. Use the Quick Start guide above (Unsplash + TinyPNG)
2. Consider hiring on Fiverr (₹500-2000 for image sourcing)
3. Professional photoshoot for authentic brand images

---

**Once you have images, your website will look exactly like American Guard Services!**

The structure and design are already matching - you just need the professional photos.
