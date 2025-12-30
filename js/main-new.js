/**
 * VISHVAVIRAT SECURITY & FACILITY INDIA PVT. LTD.
 * Updated JavaScript - Matching American Guard Services Style
 */

'use strict';

// ============================================
// HERO SLIDER
// ============================================
class HeroSlider {
    constructor() {
        this.slides = document.querySelectorAll('.hero-slide');
        this.dots = document.querySelectorAll('.slider-dot');
        this.prevArrow = document.querySelector('.slider-arrow-prev');
        this.nextArrow = document.querySelector('.slider-arrow-next');
        this.currentSlide = 0;
        this.slideInterval = null;

        if (this.slides.length > 0) {
            this.init();
        }
    }

    init() {
        // Dot click handlers
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                this.goToSlide(index);
                this.resetInterval();
            });
        });

        // Arrow click handlers
        if (this.prevArrow) {
            this.prevArrow.addEventListener('click', () => {
                this.prevSlide();
                this.resetInterval();
            });
        }

        if (this.nextArrow) {
            this.nextArrow.addEventListener('click', () => {
                this.nextSlide();
                this.resetInterval();
            });
        }

        // Start auto-slide
        this.startAutoSlide();

        // Pause on hover
        const sliderContainer = document.querySelector('.hero-slider');
        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', () => this.stopAutoSlide());
            sliderContainer.addEventListener('mouseleave', () => this.startAutoSlide());
        }
    }

    goToSlide(index) {
        // Remove active class from current slide and dot
        this.slides[this.currentSlide].classList.remove('active');
        this.dots[this.currentSlide].classList.remove('active');

        // Set new current slide
        this.currentSlide = index;

        // Add active class to new slide and dot
        this.slides[this.currentSlide].classList.add('active');
        this.dots[this.currentSlide].classList.add('active');
    }

    nextSlide() {
        const next = (this.currentSlide + 1) % this.slides.length;
        this.goToSlide(next);
    }

    prevSlide() {
        const prev = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.goToSlide(prev);
    }

    startAutoSlide() {
        this.slideInterval = setInterval(() => {
            this.nextSlide();
        }, 5000); // Change slide every 5 seconds
    }

    stopAutoSlide() {
        if (this.slideInterval) {
            clearInterval(this.slideInterval);
        }
    }

    resetInterval() {
        this.stopAutoSlide();
        this.startAutoSlide();
    }
}

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
// FORM HANDLER (Same as before with updates)
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

        // Name validation
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
            field.style.borderColor = '#e53e3e';

            if (!existingError) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'field-error';
                errorDiv.style.color = '#e53e3e';
                errorDiv.style.fontSize = '0.875rem';
                errorDiv.style.marginTop = '0.25rem';
                errorDiv.textContent = message;
                field.parentElement.appendChild(errorDiv);
            } else {
                existingError.textContent = message;
            }
        } else {
            field.style.borderColor = '#ddd';
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

        // Add metadata
        formData.append('csrf_token', this.generateCSRFToken());
        formData.append('source_page', window.location.pathname);
        formData.append('timestamp', new Date().toISOString());

        // Show loading state
        this.setLoading(true);

        try {
            const response = await fetch('/api/contact.php', {
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
            this.submitBtn.disabled = true;
            this.submitBtn.textContent = 'Sending...';
        } else {
            this.submitBtn.disabled = false;
            this.submitBtn.textContent = 'Send Message';
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

    generateCSRFToken() {
        // Generate simple token for client-side
        return Array.from(crypto.getRandomValues(new Uint8Array(32)))
            .map(b => b.toString(16).padStart(2, '0'))
            .join('');
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
                    const offsetTop = target.offsetTop - 80;
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
            background: '#c9a961',
            color: '#1a1a1a',
            fontSize: '1.5rem',
            cursor: 'pointer',
            opacity: '0',
            visibility: 'hidden',
            transition: 'all 0.3s ease',
            zIndex: '999',
            border: 'none',
            boxShadow: '0 4px 20px rgba(0, 0, 0, 0.2)',
            fontWeight: 'bold'
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

        // Hover effect
        this.button.addEventListener('mouseenter', () => {
            this.button.style.background = '#1a1a1a';
            this.button.style.color = '#c9a961';
        });

        this.button.addEventListener('mouseleave', () => {
            this.button.style.background = '#c9a961';
            this.button.style.color = '#1a1a1a';
        });
    }
}

// ============================================
// SCROLL ANIMATIONS
// ============================================
class ScrollAnimations {
    constructor() {
        this.init();
    }

    init() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-up');
                }
            });
        }, observerOptions);

        // Observe service cards
        document.querySelectorAll('.service-card').forEach(card => {
            observer.observe(card);
        });

        // Observe stat items
        document.querySelectorAll('.stat-item').forEach(stat => {
            observer.observe(stat);
        });
    }
}

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    // Initialize all modules
    new HeroSlider();
    new MobileNavigation();
    new SmoothScroll();
    new BackToTop();
    new ScrollAnimations();

    // Initialize forms
    const contactForm = document.querySelector('#contactForm');
    if (contactForm) {
        new FormHandler('#contactForm');
    }

    const serviceInquiryForm = document.querySelector('#serviceInquiryForm');
    if (serviceInquiryForm) {
        new FormHandler('#serviceInquiryForm');
    }

    console.log('Vishvavirat Security - Website initialized successfully');
});
