/**
 * VISHVAVIRAT SECURITY & FACILITY INDIA PVT. LTD.
 * Main JavaScript - Production Ready
 *
 * Features:
 * - Mobile navigation
 * - Form validation & submission
 * - Smooth scrolling
 * - Lazy loading
 * - CSRF protection
 */

'use strict';

// ============================================
// GLOBAL CONFIGURATION
// ============================================
const CONFIG = {
    API_ENDPOINT: '/api/contact.php',
    RECAPTCHA_SITE_KEY: '', // Add your reCAPTCHA site key
    LAZY_LOAD_OFFSET: 200,
    SCROLL_OFFSET: 80
};

// ============================================
// CSRF TOKEN MANAGEMENT
// ============================================
const CSRFToken = {
    get: function() {
        let token = sessionStorage.getItem('csrf_token');
        if (!token) {
            token = this.generate();
            sessionStorage.setItem('csrf_token', token);
        }
        return token;
    },

    generate: function() {
        return Array.from(crypto.getRandomValues(new Uint8Array(32)))
            .map(b => b.toString(16).padStart(2, '0'))
            .join('');
    },

    validate: function(token) {
        return token === this.get();
    }
};

// ============================================
// MOBILE NAVIGATION
// ============================================
class MobileNavigation {
    constructor() {
        this.toggle = document.querySelector('.mobile-menu-toggle');
        this.menu = document.querySelector('.nav-menu');
        this.dropdowns = document.querySelectorAll('.dropdown');

        this.init();
    }

    init() {
        if (!this.toggle || !this.menu) return;

        // Toggle main menu
        this.toggle.addEventListener('click', () => {
            this.menu.classList.toggle('active');
            this.toggle.classList.toggle('active');
        });

        // Handle dropdowns on mobile
        this.dropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('.dropdown-toggle');

            toggle.addEventListener('click', (e) => {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    dropdown.classList.toggle('active');
                }
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.navbar')) {
                this.menu.classList.remove('active');
                this.toggle.classList.remove('active');
            }
        });
    }
}

// ============================================
// FORM VALIDATION & SUBMISSION
// ============================================
class FormHandler {
    constructor(formSelector) {
        this.form = document.querySelector(formSelector);
        if (!this.form) return;

        this.submitBtn = this.form.querySelector('button[type="submit"]');
        this.alertBox = this.form.querySelector('.form-alert');

        this.init();
    }

    init() {
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleSubmit();
        });

        // Real-time validation
        const inputs = this.form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
        });
    }

    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let message = '';

        // Required validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            message = 'This field is required';
        }

        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                message = 'Please enter a valid email address';
            }
        }

        // Phone validation (Indian format)
        if (field.type === 'tel' && value) {
            const phoneRegex = /^[6-9]\d{9}$/;
            if (!phoneRegex.test(value.replace(/\s/g, ''))) {
                isValid = false;
                message = 'Please enter a valid 10-digit mobile number';
            }
        }

        // Name validation (no numbers or special chars)
        if (field.name === 'name' && value) {
            const nameRegex = /^[a-zA-Z\s.]+$/;
            if (!nameRegex.test(value)) {
                isValid = false;
                message = 'Name should contain only letters';
            }
        }

        this.setFieldError(field, isValid, message);
        return isValid;
    }

    setFieldError(field, isValid, message) {
        const existingError = field.parentElement.querySelector('.field-error');

        if (!isValid) {
            field.style.borderColor = 'var(--danger-color)';

            if (!existingError) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'field-error';
                errorDiv.style.color = 'var(--danger-color)';
                errorDiv.style.fontSize = '0.875rem';
                errorDiv.style.marginTop = '0.25rem';
                errorDiv.textContent = message;
                field.parentElement.appendChild(errorDiv);
            } else {
                existingError.textContent = message;
            }
        } else {
            field.style.borderColor = 'var(--gray-lighter)';
            if (existingError) {
                existingError.remove();
            }
        }
    }

    validateForm() {
        const inputs = this.form.querySelectorAll('input[required], textarea[required], select[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    async handleSubmit() {
        // Validate form
        if (!this.validateForm()) {
            this.showAlert('Please correct the errors before submitting', 'error');
            return;
        }

        // Prepare form data
        const formData = new FormData(this.form);

        // Add CSRF token
        formData.append('csrf_token', CSRFToken.get());

        // Add source page
        formData.append('source_page', window.location.pathname);

        // Add timestamp
        formData.append('timestamp', new Date().toISOString());

        // Show loading state
        this.setLoading(true);

        try {
            const response = await fetch(CONFIG.API_ENDPOINT, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showAlert(result.message || 'Thank you! Your inquiry has been submitted successfully. We will contact you shortly.', 'success');
                this.form.reset();

                // Track conversion (if analytics is set up)
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'form_submission', {
                        'event_category': 'Contact',
                        'event_label': formData.get('service') || 'General'
                    });
                }
            } else {
                this.showAlert(result.message || 'An error occurred. Please try again.', 'error');
            }
        } catch (error) {
            console.error('Form submission error:', error);
            this.showAlert('Unable to submit form. Please try again or contact us directly.', 'error');
        } finally {
            this.setLoading(false);
        }
    }

    setLoading(isLoading) {
        if (isLoading) {
            this.submitBtn.classList.add('btn-loading');
            this.submitBtn.disabled = true;
            this.submitBtn.textContent = 'Submitting...';
        } else {
            this.submitBtn.classList.remove('btn-loading');
            this.submitBtn.disabled = false;
            this.submitBtn.textContent = 'Submit Inquiry';
        }
    }

    showAlert(message, type) {
        if (!this.alertBox) {
            this.alertBox = document.createElement('div');
            this.alertBox.className = 'form-alert';
            this.form.insertBefore(this.alertBox, this.form.firstChild);
        }

        this.alertBox.textContent = message;
        this.alertBox.className = `form-alert ${type}`;
        this.alertBox.style.display = 'block';

        // Scroll to alert
        this.alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        // Auto-hide success messages
        if (type === 'success') {
            setTimeout(() => {
                this.alertBox.style.display = 'none';
            }, 5000);
        }
    }
}

// ============================================
// SMOOTH SCROLLING
// ============================================
class SmoothScroll {
    constructor() {
        this.init();
    }

    init() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                const targetId = anchor.getAttribute('href');
                if (targetId === '#') return;

                const target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    const offsetTop = target.offsetTop - CONFIG.SCROLL_OFFSET;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }
}

// ============================================
// LAZY LOADING IMAGES
// ============================================
class LazyLoader {
    constructor() {
        this.images = document.querySelectorAll('img[data-src]');
        this.init();
    }

    init() {
        if ('IntersectionObserver' in window) {
            this.observer = new IntersectionObserver(
                (entries) => this.handleIntersection(entries),
                {
                    rootMargin: `${CONFIG.LAZY_LOAD_OFFSET}px`
                }
            );

            this.images.forEach(img => this.observer.observe(img));
        } else {
            // Fallback for older browsers
            this.images.forEach(img => this.loadImage(img));
        }
    }

    handleIntersection(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                this.loadImage(entry.target);
                this.observer.unobserve(entry.target);
            }
        });
    }

    loadImage(img) {
        const src = img.getAttribute('data-src');
        if (!src) return;

        img.src = src;
        img.removeAttribute('data-src');
        img.classList.add('loaded');
    }
}

// ============================================
// STICKY HEADER
// ============================================
class StickyHeader {
    constructor() {
        this.header = document.querySelector('.header');
        this.lastScroll = 0;
        this.init();
    }

    init() {
        if (!this.header) return;

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;

            if (currentScroll > 100) {
                this.header.classList.add('scrolled');
            } else {
                this.header.classList.remove('scrolled');
            }

            this.lastScroll = currentScroll;
        });
    }
}

// ============================================
// INPUT SANITIZATION
// ============================================
const Sanitizer = {
    sanitizeHTML: function(str) {
        const temp = document.createElement('div');
        temp.textContent = str;
        return temp.innerHTML;
    },

    sanitizeInput: function(input) {
        return input
            .trim()
            .replace(/[<>]/g, '')
            .substring(0, 1000); // Max length
    },

    sanitizeEmail: function(email) {
        return email.toLowerCase().trim();
    },

    sanitizePhone: function(phone) {
        return phone.replace(/\D/g, '').substring(0, 10);
    }
};

// ============================================
// RATE LIMITING
// ============================================
class RateLimiter {
    constructor(maxAttempts = 3, timeWindow = 60000) {
        this.maxAttempts = maxAttempts;
        this.timeWindow = timeWindow;
        this.attempts = [];
    }

    canProceed() {
        const now = Date.now();
        this.attempts = this.attempts.filter(time => now - time < this.timeWindow);

        if (this.attempts.length >= this.maxAttempts) {
            return false;
        }

        this.attempts.push(now);
        return true;
    }

    getRemainingTime() {
        if (this.attempts.length === 0) return 0;
        const oldestAttempt = Math.min(...this.attempts);
        const remaining = this.timeWindow - (Date.now() - oldestAttempt);
        return Math.max(0, Math.ceil(remaining / 1000));
    }
}

// ============================================
// BACK TO TOP BUTTON
// ============================================
class BackToTop {
    constructor() {
        this.createButton();
        this.init();
    }

    createButton() {
        this.button = document.createElement('button');
        this.button.className = 'back-to-top';
        this.button.innerHTML = '↑';
        this.button.setAttribute('aria-label', 'Back to top');

        // Styles
        Object.assign(this.button.style, {
            position: 'fixed',
            bottom: '2rem',
            right: '2rem',
            width: '50px',
            height: '50px',
            borderRadius: '50%',
            background: 'var(--primary-color)',
            color: 'var(--white)',
            fontSize: '1.5rem',
            cursor: 'pointer',
            opacity: '0',
            visibility: 'hidden',
            transition: 'all 0.3s ease',
            zIndex: '999',
            border: 'none',
            boxShadow: 'var(--shadow-lg)'
        });

        document.body.appendChild(this.button);
    }

    init() {
        // Show/hide button
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                this.button.style.opacity = '1';
                this.button.style.visibility = 'visible';
            } else {
                this.button.style.opacity = '0';
                this.button.style.visibility = 'hidden';
            }
        });

        // Scroll to top
        this.button.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    // Initialize all modules
    new MobileNavigation();
    new SmoothScroll();
    new LazyLoader();
    new StickyHeader();
    new BackToTop();

    // Initialize forms
    const contactForms = document.querySelectorAll('.contact-form form');
    contactForms.forEach(form => {
        new FormHandler(`#${form.id}`);
    });

    // Initialize rate limiter for forms
    window.formRateLimiter = new RateLimiter(3, 60000);

    console.log('Vishvavirat Security - Website initialized successfully');
});

// ============================================
// SERVICE WORKER REGISTRATION (PWA Ready)
// ============================================
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        // Uncomment when service worker is implemented
        // navigator.serviceWorker.register('/sw.js')
        //     .then(reg => console.log('Service Worker registered'))
        //     .catch(err => console.log('Service Worker registration failed'));
    });
}

// ============================================
// EXPORT FOR MODULE USAGE
// ============================================
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        CSRFToken,
        FormHandler,
        Sanitizer,
        RateLimiter
    };
}
