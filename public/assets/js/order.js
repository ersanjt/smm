// Order Management JavaScript
class OrderManager {
    constructor() {
        this.selectedService = null;
        this.services = {
            'instagram-followers': {
                name: 'Instagram Followers',
                price: 0.50,
                min: 100,
                max: 100000
            },
            'instagram-likes': {
                name: 'Instagram Likes',
                price: 0.30,
                min: 100,
                max: 100000
            },
            'facebook-likes': {
                name: 'Facebook Page Likes',
                price: 0.80,
                min: 100,
                max: 100000
            },
            'youtube-views': {
                name: 'YouTube Views',
                price: 1.20,
                min: 100,
                max: 100000
            },
            'twitter-followers': {
                name: 'Twitter Followers',
                price: 0.40,
                min: 100,
                max: 100000
            },
            'tiktok-followers': {
                name: 'TikTok Followers',
                price: 0.60,
                min: 100,
                max: 100000
            }
        };
        this.init();
    }
    
    init() {
        this.initCategoryFilters();
        this.initOrderModal();
        this.initFormValidation();
        this.loadServices();
        console.log('Order Manager initialized');
    }
    
    // Load Services from API
    async loadServices() {
        try {
            console.log('Loading services from API...');
            const response = await window.apiManager.getServices('all');
            console.log('Services response:', response);
            
            if (response.success) {
                this.updateServicesGrid(response.data);
                console.log('Services loaded successfully');
            } else {
                console.error('Failed to load services:', response.message);
                this.showErrorMessage('Failed to load services: ' + response.message);
            }
        } catch (error) {
            console.error('Failed to load services:', error);
            this.showErrorMessage('Failed to load services. Please refresh the page.');
        }
    }
    
    // Update Services Grid
    updateServicesGrid(servicesData) {
        const servicesGrid = document.getElementById('servicesGrid');
        if (!servicesGrid) {
            console.error('Services grid not found');
            return;
        }
        
        console.log('Updating services grid with data:', servicesData);
        
        // Store services for later use
        this.loadedServices = servicesData;
        
        // Clear existing content
        servicesGrid.innerHTML = '';
        
        // Flatten all services from all categories
        const allServices = [];
        Object.values(servicesData).forEach(categoryServices => {
            if (Array.isArray(categoryServices)) {
                allServices.push(...categoryServices);
            }
        });
        
        console.log('All services:', allServices);
        
        if (allServices.length === 0) {
            servicesGrid.innerHTML = '<div class="no-services">No services available</div>';
            return;
        }
        
        // Add services from API
        allServices.forEach(service => {
            const serviceCard = this.createServiceCard(service);
            servicesGrid.appendChild(serviceCard);
        });
        
        console.log(`Added ${allServices.length} services to grid`);
    }
    
    // Create Service Card
    createServiceCard(service) {
        console.log('Creating service card for:', service);
        
        const card = document.createElement('div');
        card.className = 'service-card';
        card.dataset.category = service.category || 'other';
        
        // Get icon based on category
        const getCategoryIcon = (category) => {
            const icons = {
                'instagram': 'fab fa-instagram',
                'facebook': 'fab fa-facebook',
                'youtube': 'fab fa-youtube',
                'twitter': 'fab fa-twitter',
                'tiktok': 'fab fa-tiktok',
                'telegram': 'fab fa-telegram',
                'linkedin': 'fab fa-linkedin',
                'pinterest': 'fab fa-pinterest'
            };
            return icons[category] || 'fas fa-globe';
        };
        
        const providerBadge = service.provider ? `<span class="provider-badge ${service.provider.toLowerCase()}">${service.provider}</span>` : '';
        
        card.innerHTML = `
            <div class="service-header">
                <div class="service-icon ${service.category || 'other'}">
                    <i class="${getCategoryIcon(service.category)}"></i>
                </div>
                <div class="service-info">
                    <h3>${service.name} ${providerBadge}</h3>
                    <p>${service.description || 'High quality service'}</p>
                </div>
                <div class="service-price">
                    <span class="price">$${service.price || service.rate || '0.00'}</span>
                    <span class="per">per 1K</span>
                </div>
            </div>
            <div class="service-details">
                <div class="detail-item">
                    <i class="fas fa-clock"></i>
                    <span>Start Time: ${service.start_time || '0-1 hour'}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Speed: ${service.speed || '100-500 per day'}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Guarantee: ${service.guarantee || '30 days'}</span>
                </div>
                ${service.refill ? '<div class="detail-item"><i class="fas fa-sync-alt"></i><span>Refill Available</span></div>' : ''}
                ${service.cancel ? '<div class="detail-item"><i class="fas fa-times-circle"></i><span>Cancellation Available</span></div>' : ''}
            </div>
            <button class="btn-select-service" onclick="selectService('${service.id || service.service}')">
                Select Service
            </button>
        `;
        
        return card;
    }
    
    // Show Error Message
    showErrorMessage(message) {
        const notification = document.createElement('div');
        notification.className = 'error-notification';
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-exclamation-triangle"></i>
                <span>${message}</span>
            </div>
        `;
        
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #e53e3e;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            animation: slideInRight 0.3s ease;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    
    // Initialize Category Filters
    initCategoryFilters() {
        const categoryTabs = document.querySelectorAll('.category-tab');
        const serviceCards = document.querySelectorAll('.service-card');
        
        categoryTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs
                categoryTabs.forEach(t => t.classList.remove('active'));
                // Add active class to clicked tab
                tab.classList.add('active');
                
                const category = tab.dataset.category;
                this.filterServices(category, serviceCards);
            });
        });
    }
    
    // Filter Services
    filterServices(category, serviceCards) {
        serviceCards.forEach(card => {
            const cardCategory = card.dataset.category;
            
            if (category === 'all' || cardCategory === category) {
                card.classList.remove('hidden');
                card.classList.add('visible');
            } else {
                card.classList.add('hidden');
                card.classList.remove('visible');
            }
        });
    }
    
    // Initialize Order Modal
    initOrderModal() {
        const modal = document.getElementById('orderModal');
        const form = document.getElementById('orderForm');
        
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitOrder();
            });
        }
        
        // Close modal when clicking overlay
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal || e.target.classList.contains('modal-overlay')) {
                    this.closeOrderModal();
                }
            });
        }
    }
    
    // Initialize Form Validation
    initFormValidation() {
        const quantityInput = document.getElementById('quantity');
        const linkInput = document.getElementById('link');
        
        if (quantityInput) {
            quantityInput.addEventListener('input', () => {
                this.updateCharge();
                this.validateQuantity();
            });
        }
        
        if (linkInput) {
            linkInput.addEventListener('input', () => {
                this.validateLink();
            });
        }
    }
    
    // Select Service
    selectService(serviceId) {
        console.log('Selecting service:', serviceId);
        
        // Find service in loaded services
        const allServices = [];
        Object.values(this.loadedServices || {}).forEach(categoryServices => {
            if (Array.isArray(categoryServices)) {
                allServices.push(...categoryServices);
            }
        });
        
        this.selectedService = allServices.find(service => 
            service.id === serviceId || service.service === serviceId
        );
        
        console.log('Selected service:', this.selectedService);
        
        if (this.selectedService) {
            this.openOrderModal();
        } else {
            console.error('Service not found:', serviceId);
            this.showErrorMessage('Service not found. Please try again.');
        }
    }
    
    // Submit Order
    async submitOrder() {
        if (!this.selectedService) {
            this.showErrorMessage('Please select a service first!');
            return;
        }
        
        const link = document.getElementById('link').value.trim();
        const quantity = parseInt(document.getElementById('quantity').value);
        const runs = document.getElementById('runs').value;
        const interval = document.getElementById('interval').value;
        
        if (!link || !quantity) {
            this.showErrorMessage('Please fill in all required fields!');
            return;
        }
        
        if (quantity < this.selectedService.min || quantity > this.selectedService.max) {
            this.showErrorMessage(`Quantity must be between ${this.selectedService.min} and ${this.selectedService.max}!`);
            return;
        }
        
        try {
            const formData = new FormData();
            formData.append('service_id', this.selectedService.id);
            formData.append('link', link);
            formData.append('quantity', quantity);
            if (runs) formData.append('runs', runs);
            if (interval) formData.append('interval', interval);
            
            const response = await fetch(`${window.apiManager.baseURL}services.php?endpoint=add_order`, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccessMessage(`Order created successfully! Order ID: ${result.data.order_id}`);
                this.closeOrderModal();
            } else {
                this.showErrorMessage(result.message || 'Failed to create order');
            }
        } catch (error) {
            console.error('Order submission error:', error);
            this.showErrorMessage('Failed to submit order. Please try again.');
        }
    }
    
    // Open Order Modal
    openOrderModal() {
        const modal = document.getElementById('orderModal');
        const serviceName = document.getElementById('serviceName');
        const quantityInput = document.getElementById('quantity');
        const chargeInput = document.getElementById('charge');
        
        if (modal && this.selectedService) {
            console.log('Opening order modal for service:', this.selectedService);
            
            // Set service name
            if (serviceName) {
                serviceName.value = this.selectedService.name;
            }
            
            // Set quantity limits
            if (quantityInput) {
                quantityInput.min = this.selectedService.min || 100;
                quantityInput.max = this.selectedService.max || 100000;
                quantityInput.value = this.selectedService.min || 100;
            }
            
            // Update charge
            this.updateCharge();
            
            // Show modal
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            
            // Focus on first input
            const linkInput = document.getElementById('link');
            if (linkInput) {
                setTimeout(() => linkInput.focus(), 100);
            }
        }
    }
    
    // Close Order Modal
    closeOrderModal() {
        const modal = document.getElementById('orderModal');
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
            
            // Reset form
            const form = document.getElementById('orderForm');
            if (form) {
                form.reset();
            }
            
            this.selectedService = null;
        }
    }
    
    // Update Charge
    updateCharge() {
        const quantityInput = document.getElementById('quantity');
        const chargeInput = document.getElementById('charge');
        
        if (quantityInput && chargeInput && this.selectedService) {
            const quantity = parseInt(quantityInput.value) || 0;
            const price = this.selectedService.price || this.selectedService.rate || 0;
            const total = (quantity / 1000) * price;
            
            chargeInput.value = `$${total.toFixed(2)}`;
            console.log(`Updated charge: ${quantity} * ${price} = $${total.toFixed(2)}`);
        }
    }
    
    // Validate Quantity
    validateQuantity() {
        const quantityInput = document.getElementById('quantity');
        if (!quantityInput || !this.selectedService) return;
        
        const quantity = parseInt(quantityInput.value);
        const min = this.selectedService.min;
        const max = this.selectedService.max;
        
        if (quantity < min) {
            quantityInput.setCustomValidity(`Minimum quantity is ${min}`);
            quantityInput.style.borderColor = '#e53e3e';
        } else if (quantity > max) {
            quantityInput.setCustomValidity(`Maximum quantity is ${max}`);
            quantityInput.style.borderColor = '#e53e3e';
        } else {
            quantityInput.setCustomValidity('');
            quantityInput.style.borderColor = '#e2e8f0';
        }
    }
    
    // Validate Link
    validateLink() {
        const linkInput = document.getElementById('link');
        if (!linkInput) return;
        
        const link = linkInput.value.trim();
        const urlPattern = /^https?:\/\/.+/;
        
        if (link && !urlPattern.test(link)) {
            linkInput.setCustomValidity('Please enter a valid URL');
            linkInput.style.borderColor = '#e53e3e';
        } else {
            linkInput.setCustomValidity('');
            linkInput.style.borderColor = '#e2e8f0';
        }
    }
    
    // Submit Order
    submitOrder() {
        const form = document.getElementById('orderForm');
        if (!form || !this.selectedService) return;
        
        // Validate form
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Get form data
        const formData = new FormData(form);
        const orderData = {
            service: this.selectedService.name,
            link: formData.get('link') || document.getElementById('link').value,
            quantity: parseInt(formData.get('quantity') || document.getElementById('quantity').value),
            charge: formData.get('charge') || document.getElementById('charge').value,
            terms: formData.get('terms') === 'on'
        };
        
        // Show loading state
        this.showLoadingState();
        
        // Simulate order submission
        setTimeout(() => {
            this.hideLoadingState();
            this.showOrderSuccess(orderData);
            this.closeOrderModal();
        }, 2000);
    }
    
    // Show Loading State
    showLoadingState() {
        const submitBtn = document.querySelector('#orderForm button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Placing Order...';
            submitBtn.disabled = true;
        }
    }
    
    // Hide Loading State
    hideLoadingState() {
        const submitBtn = document.querySelector('#orderForm button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-shopping-cart"></i> Place Order';
            submitBtn.disabled = false;
        }
    }
    
    // Show Order Success
    showOrderSuccess(orderData) {
        const notification = document.createElement('div');
        notification.className = 'order-success-notification';
        notification.innerHTML = `
            <div class="notification-content">
                <div class="notification-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="notification-details">
                    <h4>Order Placed Successfully!</h4>
                    <p>Your order for ${orderData.service} has been placed and is being processed.</p>
                    <div class="order-summary">
                        <div class="summary-item">
                            <span>Service:</span>
                            <span>${orderData.service}</span>
                        </div>
                        <div class="summary-item">
                            <span>Quantity:</span>
                            <span>${orderData.quantity.toLocaleString()}</span>
                        </div>
                        <div class="summary-item">
                            <span>Total:</span>
                            <span>${orderData.charge}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            z-index: 10001;
            max-width: 400px;
            animation: slideInRight 0.3s ease;
        `;
        
        const style = document.createElement('style');
        style.textContent = `
            .order-success-notification .notification-content {
                display: flex;
                gap: 15px;
                padding: 20px;
            }
            
            .order-success-notification .notification-icon {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 20px;
                flex-shrink: 0;
            }
            
            .order-success-notification .notification-details h4 {
                margin: 0 0 8px 0;
                font-size: 1.1rem;
                font-weight: 600;
                color: #2d3748;
            }
            
            .order-success-notification .notification-details p {
                margin: 0 0 15px 0;
                font-size: 0.9rem;
                color: #718096;
            }
            
            .order-summary {
                background: #f7fafc;
                border-radius: 8px;
                padding: 15px;
            }
            
            .summary-item {
                display: flex;
                justify-content: space-between;
                margin-bottom: 8px;
                font-size: 0.9rem;
            }
            
            .summary-item:last-child {
                margin-bottom: 0;
                font-weight: 600;
                color: #2d3748;
            }
            
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        
        document.head.appendChild(style);
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                notification.remove();
                style.remove();
            }, 300);
        }, 5000);
    }
}

// Global functions for onclick handlers
function selectService(serviceId) {
    if (window.orderManager) {
        window.orderManager.selectService(serviceId);
    }
}

function closeOrderModal() {
    if (window.orderManager) {
        window.orderManager.closeOrderModal();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.orderManager = new OrderManager();
});
