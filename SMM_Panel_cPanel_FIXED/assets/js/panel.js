/**
 * SMM Turk Panel JavaScript
 * Advanced functionality for the admin panel
 */

class PanelSystem {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupSidebar();
        this.setupNotifications();
        this.setupAnimations();
        this.setupFormHandlers();
        this.setupDataTables();
        this.setupModals();
    }
    
    setupSidebar() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const mainContent = document.querySelector('.main-content');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('open');
            }
        });
        
        // Set active menu item based on current page
        this.setActiveMenuItem();
    }
    
    setActiveMenuItem() {
        // Get current page filename
        const currentPage = window.location.pathname.split('/').pop() || 'index.html';
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            
            // Get the href from the link
            const href = link.getAttribute('href');
            
            if (href) {
                const linkPage = href.split('/').pop();
                
                // Check if this link matches current page
                if (linkPage === currentPage || (currentPage === 'index.html' && linkPage === 'index.html')) {
                    link.classList.add('active');
                }
            }
        });
    }
    
    setupNotifications() {
        const notificationBell = document.querySelector('.notification-bell');
        
        if (notificationBell) {
            notificationBell.addEventListener('click', () => {
                this.showNotifications();
            });
        }
        
        // Auto-update notification count
        this.updateNotificationCount();
        setInterval(() => this.updateNotificationCount(), 30000); // Update every 30 seconds
    }
    
    setupAnimations() {
        // Add entrance animations to cards
        const cards = document.querySelectorAll('.card, .stat-card, .service-card');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, { threshold: 0.1 });
        
        cards.forEach(card => observer.observe(card));
    }
    
    setupFormHandlers() {
        // Handle form submissions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', this.handleFormSubmit.bind(this));
        });
        
        // Real-time validation
        document.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('blur', this.validateField.bind(this));
            field.addEventListener('input', this.handleInputChange.bind(this));
        });
    }
    
    setupDataTables() {
        // Add sorting functionality to tables
        document.querySelectorAll('.data-table th').forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => this.sortTable(header));
        });
        
        // Add search functionality
        const searchInputs = document.querySelectorAll('input[type="search"], .search-box input');
        searchInputs.forEach(input => {
            input.addEventListener('input', this.handleSearch.bind(this));
        });
    }
    
    setupModals() {
        // Handle modal close buttons
        document.querySelectorAll('.modal-close').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const modal = e.target.closest('.modal');
                this.closeModal(modal);
            });
        });
        
        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeModal(modal);
                }
            });
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal[style*="display: flex"]');
                if (openModal) {
                    this.closeModal(openModal);
                }
            }
        });
    }
    
    handleFormSubmit(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        // Show loading state
        this.showLoadingState(form);
        
        // Simulate API call
        setTimeout(() => {
            this.hideLoadingState(form);
            this.showSuccessMessage('Operation completed successfully!');
            
            // Reset form if needed
            if (form.hasAttribute('data-reset')) {
                form.reset();
            }
        }, 2000);
    }
    
    validateField(event) {
        const field = event.target;
        const value = field.value.trim();
        
        // Clear previous errors
        this.clearFieldError(field);
        
        // Required field validation
        if (field.hasAttribute('required') && !value) {
            this.showFieldError(field, 'This field is required');
            return false;
        }
        
        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                this.showFieldError(field, 'Please enter a valid email address');
                return false;
            }
        }
        
        // URL validation
        if (field.type === 'url' && value) {
            try {
                new URL(value);
            } catch {
                this.showFieldError(field, 'Please enter a valid URL');
                return false;
            }
        }
        
        // Number validation
        if (field.type === 'number' && value) {
            const num = parseFloat(value);
            const min = field.getAttribute('min');
            const max = field.getAttribute('max');
            
            if (isNaN(num)) {
                this.showFieldError(field, 'Please enter a valid number');
                return false;
            }
            
            if (min && num < parseFloat(min)) {
                this.showFieldError(field, `Value must be at least ${min}`);
                return false;
            }
            
            if (max && num > parseFloat(max)) {
                this.showFieldError(field, `Value must be at most ${max}`);
                return false;
            }
        }
        
        return true;
    }
    
    handleInputChange(event) {
        const field = event.target;
        
        // Clear errors on input
        this.clearFieldError(field);
        
        // Real-time validation for specific fields
        if (field.name === 'quantity' && field.value) {
            this.validateQuantity(field);
        }
        
        if (field.name === 'link' && field.value) {
            this.validateLink(field);
        }
    }
    
    validateQuantity(field) {
        const value = parseFloat(field.value);
        const min = parseFloat(field.getAttribute('min')) || 1;
        const max = parseFloat(field.getAttribute('max')) || 100000;
        
        if (value < min) {
            this.showFieldError(field, `Minimum quantity is ${min}`);
        } else if (value > max) {
            this.showFieldError(field, `Maximum quantity is ${max}`);
        } else {
            this.showFieldSuccess(field, 'Valid quantity');
        }
    }
    
    validateLink(field) {
        const value = field.value;
        
        try {
            new URL(value);
            this.showFieldSuccess(field, 'Valid URL');
        } catch {
            this.showFieldError(field, 'Please enter a valid URL');
        }
    }
    
    showFieldError(field, message) {
        this.clearFieldError(field);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;
        errorDiv.style.cssText = `
            color: #e74c3c;
            font-size: 12px;
            margin-top: 4px;
        `;
        
        field.parentNode.appendChild(errorDiv);
        field.style.borderColor = '#e74c3c';
    }
    
    showFieldSuccess(field, message) {
        this.clearFieldError(field);
        
        const successDiv = document.createElement('div');
        successDiv.className = 'field-success';
        successDiv.textContent = message;
        successDiv.style.cssText = `
            color: #27ae60;
            font-size: 12px;
            margin-top: 4px;
        `;
        
        field.parentNode.appendChild(successDiv);
        field.style.borderColor = '#27ae60';
    }
    
    clearFieldError(field) {
        const existingError = field.parentNode.querySelector('.field-error');
        const existingSuccess = field.parentNode.querySelector('.field-success');
        
        if (existingError) existingError.remove();
        if (existingSuccess) existingSuccess.remove();
        
        field.style.borderColor = '#e5e7eb';
    }
    
    showLoadingState(form) {
        const submitBtn = form.querySelector('button[type="submit"], .btn-primary');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
        }
    }
    
    hideLoadingState(form) {
        const submitBtn = form.querySelector('button[type="submit"], .btn-primary');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('loading');
        }
    }
    
    showSuccessMessage(message) {
        this.showNotification(message, 'success');
    }
    
    showErrorMessage(message) {
        this.showNotification(message, 'error');
    }
    
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                <span>${message}</span>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        // Style the notification
        const colors = {
            success: '#27ae60',
            error: '#e74c3c',
            info: '#3498db',
            warning: '#f39c12'
        };
        
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${colors[type]};
            color: white;
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            max-width: 400px;
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    }
    
    showNotifications() {
        // Create notifications modal
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.style.display = 'flex';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Notifications</h3>
                    <button class="modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="notification-list">
                        <div class="notification-item">
                            <div class="notification-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="notification-content">
                                <h4>Welcome to SMM Turk</h4>
                                <p>Your account has been created successfully!</p>
                                <span class="notification-time">2 hours ago</span>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="notification-content">
                                <h4>Order Completed</h4>
                                <p>Your order #1001 has been completed successfully.</p>
                                <span class="notification-time">1 day ago</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Handle close
        modal.querySelector('.modal-close').addEventListener('click', () => {
            modal.remove();
        });
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }
    
    updateNotificationCount() {
        // Simulate notification count update
        const badge = document.querySelector('.notification-bell .badge');
        if (badge) {
            const count = Math.floor(Math.random() * 5);
            badge.textContent = count;
            badge.style.display = count > 0 ? 'block' : 'none';
        }
    }
    
    sortTable(header) {
        const table = header.closest('table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const columnIndex = Array.from(header.parentNode.children).indexOf(header);
        const isAscending = header.classList.contains('sort-asc');
        
        // Remove existing sort classes
        header.parentNode.querySelectorAll('th').forEach(th => {
            th.classList.remove('sort-asc', 'sort-desc');
        });
        
        // Add sort class
        header.classList.add(isAscending ? 'sort-desc' : 'sort-asc');
        
        // Sort rows
        rows.sort((a, b) => {
            const aValue = a.children[columnIndex].textContent.trim();
            const bValue = b.children[columnIndex].textContent.trim();
            
            if (isAscending) {
                return bValue.localeCompare(aValue);
            } else {
                return aValue.localeCompare(bValue);
            }
        });
        
        // Reorder rows
        rows.forEach(row => tbody.appendChild(row));
    }
    
    handleSearch(event) {
        const searchTerm = event.target.value.toLowerCase();
        const table = event.target.closest('.dashboard-content').querySelector('.data-table');
        
        if (table) {
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }
    }
    
    closeModal(modal) {
        modal.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => {
            modal.remove();
        }, 300);
    }
}

// Global functions for backward compatibility
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('open');
}

function copyApiKey() {
    const apiKey = document.getElementById('api-key');
    if (apiKey) {
        navigator.clipboard.writeText(apiKey.textContent).then(() => {
            alert('API Key copied to clipboard!');
        });
    }
}

function copyReferralLink() {
    const referralLink = document.querySelector('.referral-link input');
    if (referralLink) {
        navigator.clipboard.writeText(referralLink.value).then(() => {
            alert('Referral link copied to clipboard!');
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new PanelSystem();
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
    
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        margin-left: auto;
    }
    
    .notification-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    .notification-item {
        display: flex;
        gap: 12px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 8px;
    }
    
    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
    }
    
    .notification-icon i {
        color: #3498db;
    }
    
    .notification-content h4 {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 4px;
    }
    
    .notification-content p {
        color: #7f8c8d;
        font-size: 14px;
        margin-bottom: 8px;
    }
    
    .notification-time {
        font-size: 12px;
        color: #95a5a6;
    }
    
    .sort-asc::after {
        content: ' ↑';
        color: #dc2626;
    }
    
    .sort-desc::after {
        content: ' ↓';
        color: #dc2626;
    }
`;
document.head.appendChild(style);

// Global function for sidebar toggle
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        sidebar.classList.toggle('open');
    }
}

// Handle Add Funds
function handleAddFunds() {
    // Redirect to Add Funds page
    window.location.href = 'add-funds.html';
}

// Handle Settings
function handleSettings() {
    // Redirect to Settings page
    window.location.href = 'settings.html';
}

// Toggle Theme - Enhanced with localStorage
let isDarkMode = localStorage.getItem('darkMode') === 'true';

// Apply saved theme on page load
if (isDarkMode) {
    document.body.classList.add('dark-mode');
    updateThemeIcon();
}

function updateThemeIcon() {
    const themeIcon = document.querySelector('[title="Theme"] i');
    if (themeIcon) {
        if (isDarkMode) {
            themeIcon.className = 'fas fa-moon';
        } else {
            themeIcon.className = 'fas fa-sun';
        }
    }
}

function toggleTheme() {
    isDarkMode = !isDarkMode;
    localStorage.setItem('darkMode', isDarkMode);
    
    if (isDarkMode) {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }
    
    updateThemeIcon();
    
    // Add smooth transition
    document.body.style.transition = 'background 0.3s ease, color 0.3s ease';
}

// Handle Settings
function handleSettings() {
    window.location.href = 'settings.html';
}

// Handle Logout
function handleLogout() {
    if (confirm('Are you sure you want to logout?')) {
        // Clear session
        sessionStorage.clear();
        localStorage.clear();
        
        // Redirect to login
        window.location.href = '../login.html';
    }
}

// Initialize Panel System
document.addEventListener('DOMContentLoaded', () => {
    window.panelSystem = new PanelSystem();
});