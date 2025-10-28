/**
 * SMM Turk Authentication System
 * Advanced JavaScript for Login/Register functionality
 */

class AuthSystem {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.setupAnimations();
        this.setupValidation();
    }
    
    setupEventListeners() {
        // Form submission
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', this.handleFormSubmit.bind(this));
        });
        
        // Real-time validation
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('blur', this.validateField.bind(this));
            input.addEventListener('input', this.handleInputChange.bind(this));
        });
        
        // Password strength
        const passwordInputs = document.querySelectorAll('input[type="password"]');
        passwordInputs.forEach(input => {
            if (input.name === 'password') {
                input.addEventListener('input', this.checkPasswordStrength.bind(this));
            }
        });
        
        // Social login buttons
        document.querySelectorAll('.btn-social').forEach(btn => {
            btn.addEventListener('click', this.handleSocialLogin.bind(this));
        });
    }
    
    setupAnimations() {
        // Add entrance animations
        const elements = document.querySelectorAll('.auth-card, .benefits-section, .security-features');
        elements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                element.style.transition = 'all 0.6s ease';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }
    
    setupValidation() {
        // Email validation
        const emailInputs = document.querySelectorAll('input[type="email"]');
        emailInputs.forEach(input => {
            input.addEventListener('input', this.validateEmail.bind(this));
        });
        
        // Password confirmation
        const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');
        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', this.validatePasswordMatch.bind(this));
        }
    }
    
    handleFormSubmit(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        // Validate form
        if (!this.validateForm(form)) {
            return;
        }
        
        // Show loading state
        this.showLoadingState(form);
        
        // Simulate API call
        this.simulateApiCall(form, data);
    }
    
    validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!this.validateField({ target: field })) {
                isValid = false;
            }
        });
        
        // Check password match for registration
        if (form.querySelector('input[name="confirm_password"]')) {
            const password = form.querySelector('input[name="password"]').value;
            const confirmPassword = form.querySelector('input[name="confirm_password"]').value;
            
            if (password !== confirmPassword) {
                this.showError(form.querySelector('input[name="confirm_password"]'), 'Passwords do not match');
                isValid = false;
            }
        }
        
        return isValid;
    }
    
    validateField(event) {
        const field = event.target;
        const value = field.value.trim();
        
        // Clear previous errors
        this.clearError(field);
        
        // Required field validation
        if (field.hasAttribute('required') && !value) {
            this.showError(field, 'This field is required');
            return false;
        }
        
        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                this.showError(field, 'Please enter a valid email address');
                return false;
            }
        }
        
        // Username validation
        if (field.name === 'username' && value) {
            const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
            if (!usernameRegex.test(value)) {
                this.showError(field, 'Username must be 3-20 characters, letters, numbers, and underscores only');
                return false;
            }
        }
        
        // Phone validation
        if (field.type === 'tel' && value) {
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            if (!phoneRegex.test(value.replace(/[\s\-\(\)]/g, ''))) {
                this.showError(field, 'Please enter a valid phone number');
                return false;
            }
        }
        
        return true;
    }
    
    validateEmail(event) {
        const field = event.target;
        const value = field.value.trim();
        
        if (value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(value)) {
                this.showSuccess(field, 'Valid email address');
            } else {
                this.showError(field, 'Please enter a valid email address');
            }
        }
    }
    
    checkPasswordStrength(event) {
        const password = event.target.value;
        const strengthFill = document.getElementById('strength-fill');
        const strengthText = document.getElementById('strength-text');
        
        if (!strengthFill || !strengthText) return;
        
        let strength = 0;
        let strengthLabel = 'Weak';
        let strengthColor = '#e74c3c';
        
        // Length check
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        
        // Character variety checks
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        // Determine strength level
        if (strength >= 5) {
            strengthLabel = 'Very Strong';
            strengthColor = '#27ae60';
        } else if (strength >= 4) {
            strengthLabel = 'Strong';
            strengthColor = '#27ae60';
        } else if (strength >= 3) {
            strengthLabel = 'Medium';
            strengthColor = '#f39c12';
        } else if (strength >= 2) {
            strengthLabel = 'Weak';
            strengthColor = '#e74c3c';
        } else {
            strengthLabel = 'Very Weak';
            strengthColor = '#e74c3c';
        }
        
        // Update UI
        strengthFill.style.width = (strength * 16.67) + '%';
        strengthFill.style.backgroundColor = strengthColor;
        strengthText.textContent = strengthLabel;
        strengthText.style.color = strengthColor;
    }
    
    validatePasswordMatch(event) {
        const confirmPassword = event.target;
        const password = document.querySelector('input[name="password"]');
        
        if (password && confirmPassword.value) {
            if (password.value !== confirmPassword.value) {
                this.showError(confirmPassword, 'Passwords do not match');
            } else {
                this.showSuccess(confirmPassword, 'Passwords match');
            }
        }
    }
    
    handleInputChange(event) {
        const field = event.target;
        
        // Clear errors on input
        this.clearError(field);
        
        // Real-time validation for specific fields
        if (field.name === 'username' && field.value) {
            this.validateUsername(field);
        }
    }
    
    validateUsername(field) {
        const value = field.value;
        const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
        
        if (usernameRegex.test(value)) {
            this.showSuccess(field, 'Username is available');
        } else {
            this.showError(field, 'Username must be 3-20 characters, letters, numbers, and underscores only');
        }
    }
    
    showError(field, message) {
        this.clearError(field);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        errorDiv.style.color = '#e74c3c';
        errorDiv.style.fontSize = '12px';
        errorDiv.style.marginTop = '5px';
        
        field.parentNode.appendChild(errorDiv);
        field.style.borderColor = '#e74c3c';
    }
    
    showSuccess(field, message) {
        this.clearError(field);
        
        const successDiv = document.createElement('div');
        successDiv.className = 'success-message';
        successDiv.textContent = message;
        successDiv.style.color = '#27ae60';
        successDiv.style.fontSize = '12px';
        successDiv.style.marginTop = '5px';
        
        field.parentNode.appendChild(successDiv);
        field.style.borderColor = '#27ae60';
    }
    
    clearError(field) {
        const existingError = field.parentNode.querySelector('.error-message');
        const existingSuccess = field.parentNode.querySelector('.success-message');
        
        if (existingError) existingError.remove();
        if (existingSuccess) existingSuccess.remove();
        
        field.style.borderColor = '#e5e7eb';
    }
    
    showLoadingState(form) {
        const submitBtn = form.querySelector('.btn-primary');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        if (btnText && btnLoading) {
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-flex';
            submitBtn.disabled = true;
        }
    }
    
    hideLoadingState(form) {
        const submitBtn = form.querySelector('.btn-primary');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        if (btnText && btnLoading) {
            btnText.style.display = 'inline';
            btnLoading.style.display = 'none';
            submitBtn.disabled = false;
        }
    }
    
    simulateApiCall(form, data) {
        // Simulate network delay
        setTimeout(() => {
            // Simulate success
            this.showSuccessMessage('Account created successfully!');
            
            // Hide loading state
            this.hideLoadingState(form);
            
            // Redirect after delay
            setTimeout(() => {
                if (form.querySelector('input[name="email"]')) {
                    // Registration form
                    window.location.href = 'login.html';
                } else {
                    // Login form
                    window.location.href = 'panel/';
                }
            }, 1500);
            
        }, 2000);
    }
    
    showSuccessMessage(message) {
        // Create success notification
        const notification = document.createElement('div');
        notification.className = 'success-notification';
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-check-circle"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Style the notification
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #27ae60;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            animation: slideInRight 0.3s ease;
        `;
        
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    handleSocialLogin(event) {
        const btn = event.target.closest('.btn-social');
        let provider = 'Unknown';
        
        if (btn.classList.contains('google')) {
            provider = 'Google';
        } else if (btn.classList.contains('facebook')) {
            provider = 'Facebook';
        } else if (btn.classList.contains('apple')) {
            provider = 'Apple';
        }
        
        // Show loading state
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connecting...';
        btn.disabled = true;
        
        // Simulate social login
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            this.showSuccessMessage(`Login with ${provider} - Feature coming soon!`);
        }, 1500);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new AuthSystem();
});

// Mobile Menu Toggle Function
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    if (mobileMenu) {
        mobileMenu.classList.toggle('active');
    }
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const mobileMenu = document.getElementById('mobileMenu');
    const toggleButton = document.querySelector('.mobile-menu-toggle');
    
    if (mobileMenu && 
        !mobileMenu.contains(event.target) && 
        !toggleButton.contains(event.target) &&
        mobileMenu.classList.contains('active')) {
        mobileMenu.classList.remove('active');
    }
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .success-notification .notification-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .success-notification i {
        font-size: 18px;
    }
    
    @media (max-width: 768px) {
        .mobile-nav-menu {
            display: block !important;
        }
    }
`;
document.head.appendChild(style);
