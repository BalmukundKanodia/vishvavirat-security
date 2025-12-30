<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo isset($page_description) ? htmlspecialchars($page_description) : 'Professional security guards, personal bouncers, drivers, housekeeping, and facility management services in Bangalore. Trained, verified, and reliable staff for hospitals, hotels, schools, and residential complexes.'; ?>">
    <meta name="keywords" content="security services bangalore, personal bouncer, security guard, driver services, housekeeping staff, gardener, maid services, facility management, trained security personnel, police verified staff">
    <meta name="author" content="VISHVAVIRAT SECURITY & FACILITY INDIA PVT. LTD.">
    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo isset($page_title) ? htmlspecialchars($page_title) : 'VISHVAVIRAT SECURITY & FACILITY INDIA PVT. LTD.'; ?>">
    <meta property="og:description" content="<?php echo isset($page_description) ? htmlspecialchars($page_description) : 'Professional security and facility management services in Bangalore'; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
    <meta property="og:image" content="/images/og-image.jpg">

    <!-- Title -->
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' | ' : ''; ?>VISHVAVIRAT SECURITY & FACILITY INDIA PVT. LTD.</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/favicon.png">
    <link rel="apple-touch-icon" href="/images/apple-touch-icon.png">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/css/style.css">

    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SecurityService",
        "name": "VISHVAVIRAT SECURITY & FACILITY INDIA PVT. LTD.",
        "description": "Professional security guards, personal bouncers, drivers, housekeeping, and facility management services",
        "url": "https://www.viratsecurity.com",
        "logo": "https://www.viratsecurity.com/images/logo.png",
        "telephone": "+91-XXXXXXXXXX",
        "email": "viratagencies770@gmail.com",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "6, 1st Floor, Annapura Main Road, Opp. Dreamz Lodge, Sudhamanagar",
            "addressLocality": "Bangalore",
            "postalCode": "560027",
            "addressCountry": "IN"
        },
        "areaServed": "Bangalore, Karnataka, India",
        "priceRange": "$$",
        "sameAs": [
            "https://www.facebook.com/viratsecurity",
            "https://www.linkedin.com/company/viratsecurity"
        ]
    }
    </script>
</head>
<body>

<!-- Header -->
<header class="header">
    <!-- Top Bar -->
    <div class="topbar">
        <div class="container">
            <div class="topbar-info">
                <a href="mailto:viratagencies770@gmail.com">
                    <span>✉</span> viratagencies770@gmail.com
                </a>
                <a href="tel:+91XXXXXXXXXX">
                    <span>✆</span> +91-XXXXXXXXXX
                </a>
            </div>
            <div class="topbar-social">
                <span>Trusted | Professional | Verified</span>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="/">
                    <span>VISHVAVIRAT</span> SECURITY
                </a>
            </div>

            <ul class="nav-menu">
                <li><a href="/" <?php echo ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index.php') ? 'class="active"' : ''; ?>>Home</a></li>

                <li><a href="/about.html" <?php echo (strpos($_SERVER['REQUEST_URI'], 'about') !== false) ? 'class="active"' : ''; ?>>About Us</a></li>

                <li class="dropdown">
                    <a href="/services.html" class="dropdown-toggle" <?php echo (strpos($_SERVER['REQUEST_URI'], 'services') !== false) ? 'class="active"' : ''; ?>>Services</a>
                    <ul class="dropdown-menu">
                        <li><a href="/services/personal-bouncer.html">Personal Bouncer</a></li>
                        <li><a href="/services/government-security-guard.html">Government Department Security Guard</a></li>
                        <li><a href="/services/driver.html">Professional Driver</a></li>
                        <li><a href="/services/housekeeping.html">Housekeeping Staff</a></li>
                        <li><a href="/services/gardener.html">Gardener Services</a></li>
                        <li><a href="/services/maid.html">Maid / Domestic Help</a></li>
                    </ul>
                </li>

                <li><a href="/industries.html" <?php echo (strpos($_SERVER['REQUEST_URI'], 'industries') !== false) ? 'class="active"' : ''; ?>>Industries We Serve</a></li>

                <li><a href="/why-choose-us.html" <?php echo (strpos($_SERVER['REQUEST_URI'], 'why-choose-us') !== false) ? 'class="active"' : ''; ?>>Why Choose Us</a></li>

                <li><a href="/contact.html" <?php echo (strpos($_SERVER['REQUEST_URI'], 'contact') !== false) ? 'class="active"' : ''; ?>>Contact Us</a></li>

                <li><a href="/contact.html" class="btn btn-accent">Get Quote</a></li>
            </ul>

            <button class="mobile-menu-toggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>
</header>
