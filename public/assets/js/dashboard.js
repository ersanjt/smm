// Dashboard JavaScript
class DashboardManager {
    constructor() {
        this.charts = {};
        this.init();
    }
    
    init() {
        this.initCharts();
        this.initAnimations();
        this.initNotifications();
        this.initRealTimeUpdates();
        this.loadDashboardData();
        console.log('Dashboard Manager initialized');
    }
    
    // Load Dashboard Data from API
    async loadDashboardData() {
        try {
            // Load user data
            const userData = await window.apiManager.getUserData();
            if (userData.success) {
                this.updateUserStats(userData.data);
            }
            
            // Load statistics
            const statsData = await window.apiManager.getStats();
            if (statsData.success) {
                this.updateGlobalStats(statsData.data);
            }
            
            // Load recent orders
            const ordersData = await window.apiManager.getOrders();
            if (ordersData.success) {
                this.updateRecentOrders(ordersData.data);
            }
            
        } catch (error) {
            console.error('Failed to load dashboard data:', error);
            this.showErrorMessage('Failed to load dashboard data. Please refresh the page.');
        }
    }
    
    // Update User Stats
    updateUserStats(userData) {
        // Update balance
        const balanceElement = document.querySelector('.stat-value');
        if (balanceElement && userData.balance !== undefined) {
            balanceElement.textContent = `$${userData.balance.toFixed(2)}`;
        }
        
        // Update account info in header
        const accountItems = document.querySelectorAll('.account-item');
        accountItems.forEach(item => {
            const text = item.textContent;
            if (text.includes('Balance:')) {
                item.innerHTML = `<i class="fas fa-dollar-sign"></i><span>Balance: $${userData.balance.toFixed(2)}</span>`;
            }
        });
    }
    
    // Update Global Stats
    updateGlobalStats(statsData) {
        // Update stats cards
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            const valueElement = card.querySelector('.stat-value');
            if (valueElement) {
                switch (index) {
                    case 0: // Balance
                        valueElement.textContent = `$${statsData.total_revenue.toFixed(2)}`;
                        break;
                    case 1: // Total Orders
                        valueElement.textContent = statsData.total_orders.toLocaleString();
                        break;
                    case 2: // Total Spending
                        valueElement.textContent = `$${statsData.total_revenue.toFixed(2)}`;
                        break;
                    case 3: // Open Tickets
                        valueElement.textContent = statsData.active_users.toLocaleString();
                        break;
                }
            }
        });
    }
    
    // Update Recent Orders
    updateRecentOrders(ordersData) {
        const activityList = document.querySelector('.activity-list');
        if (!activityList) return;
        
        // Clear existing content
        activityList.innerHTML = '';
        
        // Add new orders
        ordersData.slice(0, 4).forEach(order => {
            const orderElement = this.createOrderElement(order);
            activityList.appendChild(orderElement);
        });
    }
    
    // Create Order Element
    createOrderElement(order) {
        const orderElement = document.createElement('div');
        orderElement.className = 'activity-item';
        
        const statusIcon = this.getStatusIcon(order.status);
        const statusClass = this.getStatusClass(order.status);
        
        orderElement.innerHTML = `
            <div class="activity-icon ${statusClass}">
                <i class="fas fa-${statusIcon}"></i>
            </div>
            <div class="activity-content">
                <h4>Order #${order.id}</h4>
                <p>${order.service} - ${order.quantity.toLocaleString()} ${this.getServiceUnit(order.service)}</p>
                <span class="activity-time">${this.formatDate(order.date)}</span>
            </div>
            <div class="activity-status">
                <span class="status-badge ${statusClass}">${this.formatStatus(order.status)}</span>
            </div>
        `;
        
        return orderElement;
    }
    
    // Get Status Icon
    getStatusIcon(status) {
        const icons = {
            'completed': 'check-circle',
            'in_progress': 'clock',
            'pending': 'hourglass-half',
            'cancelled': 'times-circle',
            'failed': 'exclamation-circle'
        };
        return icons[status] || 'question-circle';
    }
    
    // Get Status Class
    getStatusClass(status) {
        const classes = {
            'completed': 'success',
            'in_progress': 'warning',
            'pending': 'info',
            'cancelled': 'error',
            'failed': 'error'
        };
        return classes[status] || 'info';
    }
    
    // Format Status
    formatStatus(status) {
        const statusMap = {
            'completed': 'Completed',
            'in_progress': 'In Progress',
            'pending': 'Pending',
            'cancelled': 'Cancelled',
            'failed': 'Failed'
        };
        return statusMap[status] || status;
    }
    
    // Get Service Unit
    getServiceUnit(service) {
        if (service.toLowerCase().includes('followers') || service.toLowerCase().includes('subscribers')) {
            return 'followers';
        } else if (service.toLowerCase().includes('likes')) {
            return 'likes';
        } else if (service.toLowerCase().includes('views')) {
            return 'views';
        }
        return 'items';
    }
    
    // Format Date
    formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const days = Math.floor(hours / 24);
        
        if (days > 0) {
            return `${days} day${days > 1 ? 's' : ''} ago`;
        } else if (hours > 0) {
            return `${hours} hour${hours > 1 ? 's' : ''} ago`;
        } else {
            return 'Just now';
        }
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
    
    // Initialize Charts
    initCharts() {
        this.createOrdersChart();
        this.createRevenueChart();
    }
    
    // Orders Chart
    createOrdersChart() {
        const ctx = document.getElementById('ordersChart');
        if (!ctx) return;
        
        this.charts.orders = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Orders',
                    data: [12, 19, 3, 5, 2, 3, 8, 15, 22, 18, 25, 30],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            borderColor: '#e2e8f0'
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        hoverBackgroundColor: '#667eea'
                    }
                }
            }
        });
    }
    
    // Revenue Chart
    createRevenueChart() {
        const ctx = document.getElementById('revenueChart');
        if (!ctx) return;
        
        this.charts.revenue = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: [120, 190, 300, 500, 200, 300, 800, 1500, 2200, 1800, 2500, 3000],
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderColor: '#667eea',
                    borderWidth: 0,
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            borderColor: '#e2e8f0'
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Initialize Animations
    initAnimations() {
        // Animate stats on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateStats(entry.target);
                }
            });
        }, observerOptions);
        
        // Observe stat cards
        document.querySelectorAll('.stat-card').forEach(card => {
            observer.observe(card);
        });
        
        // Animate activity items
        document.querySelectorAll('.activity-item').forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(20px)';
            item.style.transition = 'all 0.3s ease';
            
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, index * 100);
        });
    }
    
    // Animate Stats
    animateStats(element) {
        const valueElement = element.querySelector('.stat-value');
        if (!valueElement) return;
        
        const finalValue = valueElement.textContent;
        const isCurrency = finalValue.includes('$');
        const isPercentage = finalValue.includes('%');
        
        let numericValue = parseFloat(finalValue.replace(/[^0-9.]/g, ''));
        if (isNaN(numericValue)) return;
        
        let currentValue = 0;
        const increment = numericValue / 50;
        const duration = 1000;
        const stepTime = duration / 50;
        
        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= numericValue) {
                currentValue = numericValue;
                clearInterval(timer);
            }
            
            let displayValue = Math.floor(currentValue);
            if (isCurrency) {
                displayValue = '$' + displayValue.toLocaleString();
            } else if (isPercentage) {
                displayValue = displayValue + '%';
            } else {
                displayValue = displayValue.toLocaleString();
            }
            
            valueElement.textContent = displayValue;
        }, stepTime);
    }
    
    // Initialize Notifications
    initNotifications() {
        const notificationBell = document.querySelector('.notification-bell');
        if (notificationBell) {
            notificationBell.addEventListener('click', () => {
                this.showNotifications();
            });
        }
    }
    
    // Show Notifications
    showNotifications() {
        const notifications = [
            {
                title: 'Order Completed',
                message: 'Your Instagram followers order has been completed',
                time: '2 minutes ago',
                type: 'success'
            },
            {
                title: 'Payment Received',
                message: 'Funds added: $25.00 via PayPal',
                time: '1 hour ago',
                type: 'info'
            },
            {
                title: 'New Service Available',
                message: 'TikTok followers service is now available',
                time: '3 hours ago',
                type: 'warning'
            }
        ];
        
        this.createNotificationModal(notifications);
    }
    
    // Create Notification Modal
    createNotificationModal(notifications) {
        const modal = document.createElement('div');
        modal.className = 'notification-modal';
        modal.innerHTML = `
            <div class="notification-overlay">
                <div class="notification-content">
                    <div class="notification-header">
                        <h3>Notifications</h3>
                        <button class="close-notifications">&times;</button>
                    </div>
                    <div class="notification-list">
                        ${notifications.map(notif => `
                            <div class="notification-item ${notif.type}">
                                <div class="notification-icon">
                                    <i class="fas fa-${this.getNotificationIcon(notif.type)}"></i>
                                </div>
                                <div class="notification-details">
                                    <h4>${notif.title}</h4>
                                    <p>${notif.message}</p>
                                    <span class="notification-time">${notif.time}</span>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
        
        // Add styles
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
        `;
        
        const style = document.createElement('style');
        style.textContent = `
            .notification-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                animation: fadeIn 0.3s ease;
            }
            
            .notification-content {
                background: white;
                border-radius: 12px;
                width: 90%;
                max-width: 500px;
                max-height: 80vh;
                overflow: hidden;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                animation: slideUp 0.3s ease;
            }
            
            .notification-header {
                padding: 20px;
                border-bottom: 1px solid #e2e8f0;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .notification-header h3 {
                margin: 0;
                font-size: 1.2rem;
                font-weight: 600;
                color: #2d3748;
            }
            
            .close-notifications {
                background: none;
                border: none;
                font-size: 24px;
                color: #a0aec0;
                cursor: pointer;
                padding: 0;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .notification-list {
                max-height: 400px;
                overflow-y: auto;
            }
            
            .notification-item {
                display: flex;
                align-items: center;
                gap: 15px;
                padding: 15px 20px;
                border-bottom: 1px solid #f7fafc;
                transition: background-color 0.2s ease;
            }
            
            .notification-item:hover {
                background-color: #f7fafc;
            }
            
            .notification-icon {
                width: 40px;
                height: 40px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 16px;
            }
            
            .notification-item.success .notification-icon {
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            }
            
            .notification-item.info .notification-icon {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            }
            
            .notification-item.warning .notification-icon {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            }
            
            .notification-details h4 {
                margin: 0 0 5px 0;
                font-size: 1rem;
                font-weight: 600;
                color: #2d3748;
            }
            
            .notification-details p {
                margin: 0 0 5px 0;
                font-size: 0.9rem;
                color: #718096;
            }
            
            .notification-time {
                font-size: 0.8rem;
                color: #a0aec0;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            @keyframes slideUp {
                from { transform: translateY(30px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
        `;
        
        document.head.appendChild(style);
        document.body.appendChild(modal);
        
        // Close modal
        modal.querySelector('.close-notifications').addEventListener('click', () => {
            modal.remove();
            style.remove();
        });
        
        modal.querySelector('.notification-overlay').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) {
                modal.remove();
                style.remove();
            }
        });
    }
    
    // Get Notification Icon
    getNotificationIcon(type) {
        const icons = {
            success: 'check-circle',
            info: 'info-circle',
            warning: 'exclamation-triangle',
            error: 'times-circle'
        };
        return icons[type] || 'bell';
    }
    
    // Initialize Real-time Updates
    initRealTimeUpdates() {
        // Simulate real-time updates
        setInterval(() => {
            this.updateStats();
        }, 30000); // Update every 30 seconds
        
        // Simulate new notifications
        setInterval(() => {
            this.simulateNewNotification();
        }, 60000); // New notification every minute
    }
    
    // Update Stats
    updateStats() {
        // Simulate stat updates
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach(card => {
            const valueElement = card.querySelector('.stat-value');
            if (valueElement && Math.random() > 0.7) {
                // Add a subtle animation to indicate update
                valueElement.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    valueElement.style.transform = 'scale(1)';
                }, 200);
            }
        });
    }
    
    // Simulate New Notification
    simulateNewNotification() {
        const badge = document.querySelector('.notification-bell .badge');
        if (badge) {
            const currentCount = parseInt(badge.textContent) || 0;
            badge.textContent = currentCount + 1;
            
            // Add pulse animation
            badge.style.animation = 'pulse 0.5s ease';
            setTimeout(() => {
                badge.style.animation = '';
            }, 500);
        }
    }
    
    // Update Chart Data
    updateChartData(chartName, newData) {
        if (this.charts[chartName]) {
            this.charts[chartName].data.datasets[0].data = newData;
            this.charts[chartName].update();
        }
    }
    
    // Export Chart as Image
    exportChart(chartName) {
        if (this.charts[chartName]) {
            const link = document.createElement('a');
            link.download = `${chartName}-chart.png`;
            link.href = this.charts[chartName].toBase64Image();
            link.click();
        }
    }
}

// Initialize Dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new DashboardManager();
});

// Add pulse animation for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
`;
document.head.appendChild(style);
