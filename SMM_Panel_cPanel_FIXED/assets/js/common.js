// Common Header and Footer Function
function loadCommonElements() {
    // Create common header
    const headerHTML = `
        <header class="header">
            <div class="container">
                <a href="/" class="logo" aria-label="SMM Turk Homepage">
                    <i class="fas fa-rocket" aria-hidden="true"></i>
                    SMM Turk
                </a>
                <nav class="nav" role="navigation" aria-label="Main Navigation">
                    <a href="index.html#home" title="Home Page">Home</a>
                    <a href="index.html#services" title="SMM Services">Services</a>
                    <a href="index.html#why-choose" title="Why Choose SMM Turk">Why Choose Us</a>
                    <a href="index.html#how-it-works" title="How Our SMM Panel Works">How It Works</a>
                    <a href="index.html#reseller" title="SMM Reseller Panel">Reseller</a>
                    <a href="index.html#faq" title="Frequently Asked Questions">FAQ</a>
                    <a href="index.html#contact" title="Contact Us">Contact</a>
                </nav>
                <div class="auth-buttons">
                    <a href="login.html" class="btn-auth" title="Login to SMM Turk">Sign In</a>
                    <a href="register.html" class="btn-auth primary" title="Create SMM Turk Account">Sign Up</a>
                </div>
                <button class="mobile-menu-btn" aria-label="Toggle Mobile Menu" aria-expanded="false">
                    <i class="fas fa-bars" aria-hidden="true"></i>
                </button>
            </div>
        </header>

        <!-- Mobile Menu -->
        <div class="mobile-menu">
            <a href="index.html#home">Home</a>
            <a href="index.html#services">Services</a>
            <a href="index.html#why-choose">Why Choose Us</a>
            <a href="index.html#how-it-works">How It Works</a>
            <a href="index.html#reseller">Reseller</a>
            <a href="index.html#faq">FAQ</a>
            <a href="index.html#contact">Contact</a>
            <div class="mobile-auth-buttons">
                <a href="login.html" class="btn-auth">Sign In</a>
                <a href="register.html" class="btn-auth primary">Sign Up</a>
            </div>
        </div>
    `;

    // Create common footer
    const footerHTML = `
        <footer class="footer" role="contentinfo">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <h4>SMM Turk</h4>
                        <p>World's Best Cheap & Easy SMM Panel. Your partner in business expansion for the last 8 years.</p>
                        <div class="footer-social">
                            <a href="https://twitter.com/smmturk" rel="noopener noreferrer" aria-label="Follow us on Twitter" title="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="https://facebook.com/smmturk" rel="noopener noreferrer" aria-label="Follow us on Facebook" title="Facebook"><i class="fab fa-facebook"></i></a>
                            <a href="https://instagram.com/smmturk" rel="noopener noreferrer" aria-label="Follow us on Instagram" title="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="https://telegram.org/smmturk" rel="noopener noreferrer" aria-label="Contact us on Telegram" title="Telegram"><i class="fab fa-telegram"></i></a>
                        </div>
                    </div>
                    <div class="footer-section">
                        <h4>Services</h4>
                        <ul>
                            <li><a href="index.html#services" title="Facebook SMM Services">Facebook Services</a></li>
                            <li><a href="index.html#services" title="Instagram Followers and Likes">Instagram Services</a></li>
                            <li><a href="index.html#services" title="YouTube Views and Subscribers">YouTube Services</a></li>
                            <li><a href="index.html#services" title="Twitter Followers and Retweets">Twitter Services</a></li>
                            <li><a href="index.html#services" title="TikTok Services">TikTok Services</a></li>
                            <li><a href="index.html#services" title="Telegram Services">Telegram Services</a></li>
                            <li><a href="index.html#services" title="LinkedIn Services">LinkedIn Services</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h4>Support</h4>
                        <ul>
                            <li><a href="index.html#contact" title="24/7 Live Chat Support">Live Chat 24/7</a></li>
                            <li><a href="index.html#faq" title="Frequently Asked Questions">FAQ</a></li>
                            <li><a href="index.html#contact" title="Contact SMM Turk">Contact Us</a></li>
                            <li><a href="panel/tickets.html" title="Support Tickets">Help Center</a></li>
                            <li><a href="index.html#reseller" title="SMM Reseller Panel">Reseller Panel</a></li>
                            <li><a href="panel/api.html" title="API Documentation">API Documentation</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h4>Contact</h4>
                        <ul>
                            <li><i class="fas fa-envelope"></i> <a href="mailto:contact@smmturk.com" title="Email SMM Turk">contact@smmturk.com</a></li>
                            <li><i class="fas fa-headset"></i> <a href="mailto:support@smmturk.com" title="Support Email">support@smmturk.com</a></li>
                            <li><i class="fab fa-telegram"></i> <a href="https://t.me/smmturk_support" rel="noopener noreferrer" title="Telegram Support">@smmturk_support</a></li>
                            <li><i class="fas fa-clock"></i> 24/7 Support Available</li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; Copyright 2025. All Rights Reserved by SMM Turk.</p>
                    <p>
                        <a href="/terms.html" title="Terms of Service">Terms of Service</a> | 
                        <a href="/privacy.html" title="Privacy Policy">Privacy Policy</a> | 
                        <a href="/refund.html" title="Refund Policy">Refund Policy</a>
                    </p>
                </div>
            </div>
        </footer>
    `;

    // Insert header if placeholder exists
    const headerPlaceholder = document.getElementById('header-placeholder');
    if (headerPlaceholder) {
        headerPlaceholder.innerHTML = headerHTML;
    }

    // Insert footer if placeholder exists
    const footerPlaceholder = document.getElementById('footer-placeholder');
    if (footerPlaceholder) {
        footerPlaceholder.innerHTML = footerHTML;
    }

    // Initialize mobile menu
    initMobileMenu();
}

// Initialize mobile menu functionality
function initMobileMenu() {
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
            const icon = mobileMenuBtn.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            }
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                mobileMenu.classList.remove('active');
                const icon = mobileMenuBtn.querySelector('i');
                if (icon) {
                    icon.classList.add('fa-bars');
                    icon.classList.remove('fa-times');
                }
            }
        });
    }
}

// Run on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCommonElements();
});

