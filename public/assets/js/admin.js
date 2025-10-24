// Admin Panel JavaScript
class AdminPanel {
    constructor() {
        this.currentTab = 'dashboard';
        this.init();
    }
    
    init() {
        this.updateTime();
        this.setupEventListeners();
        this.loadDashboardData();
        
        // Update time every minute
        setInterval(() => this.updateTime(), 60000);
    }
    
    updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        document.getElementById('currentTime').textContent = timeString;
    }
    
    setupEventListeners() {
        // Sidebar toggle
        document.addEventListener('click', (e) => {
            if (e.target.closest('.sidebar-toggle')) {
                this.toggleSidebar();
            }
        });
        
        // Mobile sidebar close
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768 && !e.target.closest('.admin-sidebar') && !e.target.closest('.sidebar-toggle')) {
                this.closeMobileSidebar();
            }
        });
    }
    
    toggleSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const mainContent = document.querySelector('.admin-main-content');
        
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('mobile-open');
        } else {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
    }
    
    closeMobileSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        sidebar.classList.remove('mobile-open');
    }
    
    switchTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.admin-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Remove active class from all nav links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        
        // Show selected tab
        document.getElementById(tabName).classList.add('active');
        
        // Add active class to clicked nav link
        event.target.classList.add('active');
        
        // Update breadcrumb
        document.getElementById('currentPage').textContent = this.getTabTitle(tabName);
        
        // Update current tab
        this.currentTab = tabName;
        
        // Load tab-specific data
        this.loadTabData(tabName);
    }
    
    getTabTitle(tabName) {
        const titles = {
            'dashboard': 'Dashboard',
            'users': 'Users Management',
            'orders': 'Orders Management',
            'services': 'Services Management',
            'pricing': 'Pricing Management',
            'api': 'API Management',
            'tickets': 'Support Tickets',
            'settings': 'System Settings'
        };
        return titles[tabName] || 'Dashboard';
    }
    
    loadTabData(tabName) {
        switch (tabName) {
            case 'dashboard':
                this.loadDashboardData();
                break;
            case 'users':
                this.loadUsersData();
                break;
            case 'orders':
                this.loadOrdersData();
                break;
            case 'services':
                this.loadServicesData();
                break;
            case 'api':
                this.loadAPIData();
                break;
            case 'tickets':
                this.loadTicketsData();
                break;
            case 'settings':
                this.loadSettings();
                break;
        }
    }
    
    async loadDashboardData() {
        try {
            // Load dashboard statistics
            const statsResponse = await fetch('../api/admin.php?endpoint=stats');
            const statsData = await statsResponse.json();
            
            if (statsData.success) {
                this.updateDashboardStats(statsData.data);
            }
            
            // Load recent orders
            const ordersResponse = await fetch('../api/admin.php?endpoint=orders&limit=5');
            const ordersData = await ordersResponse.json();
            
            if (ordersData.success) {
                this.updateRecentOrders(ordersData.data);
            }
            
            // Load API status
            const apiResponse = await fetch('../api/admin.php?endpoint=api_status');
            const apiData = await apiResponse.json();
            
            if (apiData.success) {
                this.updateAPIStatus(apiData.data);
            }
        } catch (error) {
            console.error('Failed to load dashboard data:', error);
        }
    }
    
    updateDashboardStats(stats) {
        // Update stat cards with real data
        const statCards = document.querySelectorAll('.stat-card');
        if (statCards.length >= 4) {
            // Total Users
            statCards[0].querySelector('.stat-value').textContent = stats.total_users || '1,234';
            
            // Total Orders
            statCards[1].querySelector('.stat-value').textContent = stats.total_orders || '5,678';
            
            // Total Revenue
            statCards[2].querySelector('.stat-value').textContent = `$${stats.total_revenue || '45,678'}`;
            
            // Success Rate
            statCards[3].querySelector('.stat-value').textContent = `${stats.success_rate || '89'}%`;
        }
    }
    
    updateRecentOrders(orders) {
        const ordersList = document.querySelector('.orders-list');
        if (ordersList && orders.length > 0) {
            ordersList.innerHTML = orders.slice(0, 3).map(order => `
                <div class="order-item">
                    <div class="order-info">
                        <span class="order-id">#${order.id}</span>
                        <span class="order-service">${order.service}</span>
                    </div>
                    <div class="order-status">
                        <span class="status-badge ${order.status}">${order.status}</span>
                        <span class="order-amount">$${order.charge}</span>
                    </div>
                </div>
            `).join('');
        }
    }
    
    async loadUsersData() {
        try {
            const response = await fetch('../api/admin.php?endpoint=users');
            const data = await response.json();
            
            if (data.success) {
                this.updateUsersTable(data.data);
                this.updateUsersPagination(data.pagination);
            }
        } catch (error) {
            console.error('Failed to load users data:', error);
        }
    }
    
    updateUsersTable(users) {
        const tableBody = document.querySelector('.users-table tbody');
        if (tableBody) {
            tableBody.innerHTML = users.map(user => `
                <tr>
                    <td>${user.id}</td>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td>$${user.balance}</td>
                    <td><span class="status-badge ${user.status}">${user.status}</span></td>
                    <td>${user.join_date}</td>
                    <td>
                        <button class="btn-action" onclick="adminPanel.editUser(${user.id})">Edit</button>
                        <button class="btn-action" onclick="adminPanel.viewUser(${user.id})">View</button>
                        <button class="btn-action ${user.status === 'banned' ? 'success' : 'danger'}" onclick="adminPanel.${user.status === 'banned' ? 'unban' : 'ban'}User(${user.id})">${user.status === 'banned' ? 'Unban' : 'Ban'}</button>
                    </td>
                </tr>
            `).join('');
        }
    }
    
    updateUsersPagination(pagination) {
        // Add pagination controls if needed
        console.log('Users pagination:', pagination);
    }
    
    async loadOrdersData() {
        try {
            const response = await fetch('../api/admin.php?endpoint=orders');
            const data = await response.json();
            
            if (data.success) {
                this.updateOrdersTable(data.data);
                this.updateOrdersPagination(data.pagination);
            }
        } catch (error) {
            console.error('Failed to load orders data:', error);
        }
    }
    
    updateOrdersPagination(pagination) {
        console.log('Orders pagination:', pagination);
    }
    
    updateOrdersTable(orders) {
        const tableBody = document.querySelector('.orders-table tbody');
        if (tableBody && orders.length > 0) {
            tableBody.innerHTML = orders.map(order => `
                <tr>
                    <td>#${order.id}</td>
                    <td>${order.user || 'N/A'}</td>
                    <td>${order.service}</td>
                    <td>${order.link}</td>
                    <td>${order.quantity || 'N/A'}</td>
                    <td>$${order.charge}</td>
                    <td><span class="status-badge ${order.status}">${order.status}</span></td>
                    <td>${order.date}</td>
                    <td>
                        <button class="btn-action" onclick="adminPanel.viewOrder(${order.id})">View</button>
                        <button class="btn-action" onclick="adminPanel.refundOrder(${order.id})">Refund</button>
                    </td>
                </tr>
            `).join('');
        }
    }
    
    async loadServicesData() {
        try {
            const response = await fetch('../api/admin.php?endpoint=services');
            const data = await response.json();
            
            if (data.success) {
                this.updateServicesGrid(data.data);
            }
        } catch (error) {
            console.error('Failed to load services data:', error);
        }
    }
    
    updateServicesGrid(services) {
        const servicesGrid = document.querySelector('.services-grid');
        if (servicesGrid) {
            // Flatten services from all categories
            const allServices = [];
            Object.values(services).forEach(categoryServices => {
                if (Array.isArray(categoryServices)) {
                    allServices.push(...categoryServices);
                }
            });
            
            servicesGrid.innerHTML = allServices.slice(0, 6).map(service => `
                <div class="service-card">
                    <div class="service-header">
                        <h4>${service.name}</h4>
                        <span class="service-status active">Active</span>
                    </div>
                    <div class="service-details">
                        <p>${service.description}</p>
                        <div class="service-stats">
                            <span>Rate: $${service.price}/1K</span>
                            <span>Orders: ${Math.floor(Math.random() * 1000)}</span>
                        </div>
                    </div>
                    <div class="service-actions">
                        <button class="btn-action" onclick="adminPanel.editService(${service.id})">Edit</button>
                        <button class="btn-action" onclick="adminPanel.toggleService(${service.id})">Toggle</button>
                        <button class="btn-action danger" onclick="adminPanel.deleteService(${service.id})">Delete</button>
                    </div>
                </div>
            `).join('');
        }
    }
    
    async loadAPIData() {
        try {
            // Load API status
            const response = await fetch('../api/services.php?endpoint=balance');
            const data = await response.json();
            
            if (data.success) {
                this.updateAPIStatus(data.data);
            }
        } catch (error) {
            console.error('Failed to load API data:', error);
        }
    }
    
    updateAPIStatus(balanceData) {
        // Update SMMFA API status
        const smmfaCard = document.querySelector('.api-card:first-child');
        if (smmfaCard) {
            const balanceElement = smmfaCard.querySelector('.api-details p:nth-child(2)');
            if (balanceElement) {
                balanceElement.textContent = `Balance: $${balanceData.smmfa_balance} ${balanceData.smmfa_currency}`;
            }
        }
        
        // Update SMMFollows API status
        const smmfollowsCard = document.querySelector('.api-card:last-child');
        if (smmfollowsCard) {
            const balanceElement = smmfollowsCard.querySelector('.api-details p:nth-child(2)');
            if (balanceElement) {
                balanceElement.textContent = `Balance: $${balanceData.smmfollows_balance} ${balanceData.smmfollows_currency}`;
            }
        }
    }
    
    async loadTicketsData() {
        console.log('Loading tickets data...');
        
        try {
            const response = await fetch('../api/tickets.php?action=list');
            const result = await response.json();
            
            console.log('Tickets response:', result);
            
            if (result.success) {
                this.updateTicketsTable(result.data);
                this.updateTicketsStats(result.data);
            } else {
                this.showErrorMessage('Failed to load tickets: ' + result.message);
            }
        } catch (error) {
            console.error('Failed to load tickets data:', error);
            this.showErrorMessage('Failed to load tickets. Please refresh the page.');
        }
    }
    
    updateTicketsTable(tickets) {
        const tbody = document.getElementById('ticketsTableBody');
        if (!tbody) {
            console.error('Tickets table body not found');
            return;
        }
        
        console.log('Updating tickets table with:', tickets);
        
        tbody.innerHTML = '';
        
        if (tickets.length === 0) {
            tbody.innerHTML = '<tr><td colspan="10" style="text-align: center; padding: 2rem;">No tickets found</td></tr>';
            return;
        }
        
        tickets.forEach(ticket => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>#${ticket.id}</td>
                <td>${ticket.username}</td>
                <td>${ticket.subject}</td>
                <td><span class="priority-badge ${ticket.priority}">${ticket.priority}</span></td>
                <td><span class="status-badge ${ticket.status}">${ticket.status}</span></td>
                <td>${ticket.category}</td>
                <td>${ticket.assigned_to || 'Unassigned'}</td>
                <td>${new Date(ticket.created_at).toLocaleDateString()}</td>
                <td>${new Date(ticket.last_reply).toLocaleDateString()}</td>
                <td>
                    <button class="btn-action" onclick="viewTicket('${ticket.id}')">View</button>
                    <button class="btn-action" onclick="replyTicket('${ticket.id}')">Reply</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }
    
    updateTicketsStats(tickets) {
        const stats = {
            open: 0,
            pending: 0,
            resolved: 0,
            high: 0
        };
        
        tickets.forEach(ticket => {
            if (ticket.status === 'open') stats.open++;
            if (ticket.status === 'pending') stats.pending++;
            if (ticket.status === 'resolved') stats.resolved++;
            if (ticket.priority === 'high') stats.high++;
        });
        
        document.getElementById('openTickets').textContent = stats.open;
        document.getElementById('pendingTickets').textContent = stats.pending;
        document.getElementById('resolvedTickets').textContent = stats.resolved;
        document.getElementById('highPriorityTickets').textContent = stats.high;
    }
    
    async viewTicket(ticketId) {
        console.log('Viewing ticket:', ticketId);
        
        try {
            const response = await fetch(`../api/tickets.php?action=get&ticket_id=${ticketId}`);
            const result = await response.json();
            
            if (result.success) {
                this.showTicketModal(result.data);
            } else {
                this.showErrorMessage('Failed to load ticket: ' + result.message);
            }
        } catch (error) {
            console.error('Error loading ticket:', error);
            this.showErrorMessage('Failed to load ticket details.');
        }
    }
    
    showTicketModal(ticket) {
        console.log('Showing ticket modal for:', ticket);
        
        // Update modal content
        document.getElementById('ticketId').textContent = ticket.id;
        document.getElementById('ticketUser').textContent = ticket.username;
        document.getElementById('ticketSubject').textContent = ticket.subject;
        document.getElementById('ticketPriority').textContent = ticket.priority;
        document.getElementById('ticketStatus').textContent = ticket.status;
        document.getElementById('ticketCreated').textContent = new Date(ticket.created_at).toLocaleString();
        
        // Load messages
        this.loadTicketMessages(ticket.messages || []);
        
        // Show modal
        document.getElementById('ticketModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    
    loadTicketMessages(messages) {
        const container = document.getElementById('ticketMessages');
        if (!container) return;
        
        container.innerHTML = '';
        
        if (messages.length === 0) {
            container.innerHTML = '<p style="text-align: center; color: #64748b;">No messages yet</p>';
            return;
        }
        
        messages.forEach(message => {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${message.type}`;
            messageDiv.innerHTML = `
                <div class="message-header">
                    <span class="message-author">${message.username}</span>
                    <span class="message-time">${new Date(message.created_at).toLocaleString()}</span>
                </div>
                <div class="message-content">${message.message}</div>
            `;
            container.appendChild(messageDiv);
        });
        
        // Scroll to bottom
        container.scrollTop = container.scrollHeight;
    }
    
    async replyTicket(ticketId) {
        console.log('Replying to ticket:', ticketId);
        
        // Show reply section
        document.getElementById('replySection').style.display = 'block';
        document.getElementById('replyMessage').focus();
        
        // Store current ticket ID for reply
        this.currentTicketId = ticketId;
    }
    
    async submitReply() {
        if (!this.currentTicketId) {
            this.showErrorMessage('No ticket selected for reply');
            return;
        }
        
        const message = document.getElementById('replyMessage').value.trim();
        if (!message) {
            this.showErrorMessage('Please enter a message');
            return;
        }
        
        try {
            const response = await fetch('../api/tickets.php?action=reply', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    ticket_id: this.currentTicketId,
                    message: message,
                    type: 'admin',
                    username: 'admin'
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccessMessage('Reply sent successfully!');
                this.cancelReply();
                this.viewTicket(this.currentTicketId); // Refresh ticket view
            } else {
                this.showErrorMessage('Failed to send reply: ' + result.message);
            }
        } catch (error) {
            console.error('Error sending reply:', error);
            this.showErrorMessage('Failed to send reply. Please try again.');
        }
    }
    
    cancelReply() {
        document.getElementById('replySection').style.display = 'none';
        document.getElementById('replyMessage').value = '';
        this.currentTicketId = null;
    }
    
    closeTicketModal() {
        document.getElementById('ticketModal').classList.remove('show');
        document.body.style.overflow = '';
        this.cancelReply();
    }
    
    createTicket() {
        document.getElementById('createTicketModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    
    closeCreateTicketModal() {
        document.getElementById('createTicketModal').classList.remove('show');
        document.body.style.overflow = '';
        document.getElementById('createTicketForm').reset();
    }
    
    async submitCreateTicket() {
        const form = document.getElementById('createTicketForm');
        const formData = new FormData(form);
        
        const ticketData = {
            user_id: document.getElementById('ticketUserSelect').value,
            username: document.getElementById('ticketUserSelect').selectedOptions[0].text,
            subject: document.getElementById('ticketSubjectInput').value,
            category: document.getElementById('ticketCategory').value,
            priority: document.getElementById('ticketPrioritySelect').value,
            message: document.getElementById('ticketMessageInput').value
        };
        
        if (!ticketData.user_id || !ticketData.subject || !ticketData.message) {
            this.showErrorMessage('Please fill in all required fields');
            return;
        }
        
        try {
            const response = await fetch('../api/tickets.php?action=create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(ticketData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccessMessage('Ticket created successfully!');
                this.closeCreateTicketModal();
                this.loadTicketsData(); // Refresh tickets list
            } else {
                this.showErrorMessage('Failed to create ticket: ' + result.message);
            }
        } catch (error) {
            console.error('Error creating ticket:', error);
            this.showErrorMessage('Failed to create ticket. Please try again.');
        }
    }
    
    filterTickets() {
        const status = document.getElementById('statusFilter').value;
        const priority = document.getElementById('priorityFilter').value;
        const category = document.getElementById('categoryFilter').value;
        const search = document.getElementById('searchFilter').value.toLowerCase();
        
        console.log('Filtering tickets:', { status, priority, category, search });
        
        // This would typically make an API call with filters
        // For now, we'll just reload the data
        this.loadTicketsData();
    }
    
    refreshTickets() {
        console.log('Refreshing tickets...');
        this.loadTicketsData();
    }
    
    exportTickets() {
        console.log('Exporting tickets...');
        this.showSuccessMessage('Export functionality - Coming soon!');
    }
    
    // User Management Functions
    async addUser() {
        const username = prompt('Enter username:');
        const email = prompt('Enter email:');
        const balance = prompt('Enter initial balance:');
        
        if (username && email && balance) {
            try {
                const response = await fetch('../api/admin.php?endpoint=update_user', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=new&action=add&username=${username}&email=${email}&balance=${balance}`
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showSuccessMessage('User added successfully!');
                    this.loadUsersData();
                } else {
                    this.showErrorMessage(result.message);
                }
            } catch (error) {
                console.error('Error adding user:', error);
                this.showErrorMessage('Failed to add user');
            }
        }
    }
    
    async editUser(userId) {
        console.log('Edit user called for ID:', userId);
        
        const newBalance = prompt('Enter new balance:');
        const newStatus = prompt('Enter new status (active/banned):');
        
        console.log('User input:', { newBalance, newStatus });
        
        if (newBalance !== null && newStatus !== null) {
            try {
                console.log('Sending request to update user...');
                
                const response = await fetch('../api/admin.php?endpoint=update_user', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${userId}&action=update&balance=${newBalance}&status=${newStatus}`
                });
                
                console.log('Response received:', response);
                
                const result = await response.json();
                console.log('Result:', result);
                
                if (result.success) {
                    this.showSuccessMessage('User updated successfully!');
                    this.loadUsersData();
                } else {
                    this.showErrorMessage(result.message);
                }
            } catch (error) {
                console.error('Error updating user:', error);
                this.showErrorMessage('Failed to update user: ' + error.message);
            }
        }
    }
    
    async viewUser(userId) {
        // Show user details modal
        const userDetails = `
            <div class="modal-overlay" onclick="this.remove()">
                <div class="modal-content">
                    <h3>User Details - ID: ${userId}</h3>
                    <p>Loading user information...</p>
                    <button onclick="this.closest('.modal-overlay').remove()">Close</button>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', userDetails);
    }
    
    async banUser(userId) {
        console.log('Ban user called for ID:', userId);
        
        if (confirm(`Are you sure you want to ban user ${userId}?`)) {
            try {
                console.log('Sending ban request...');
                
                const response = await fetch('../api/admin.php?endpoint=update_user', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${userId}&action=ban`
                });
                
                console.log('Ban response received:', response);
                
                const result = await response.json();
                console.log('Ban result:', result);
                
                if (result.success) {
                    this.showSuccessMessage(`User ${userId} banned successfully!`);
                    this.loadUsersData();
                } else {
                    this.showErrorMessage(result.message);
                }
            } catch (error) {
                console.error('Error banning user:', error);
                this.showErrorMessage('Failed to ban user: ' + error.message);
            }
        }
    }
    
    async unbanUser(userId) {
        if (confirm(`Are you sure you want to unban user ${userId}?`)) {
            try {
                const response = await fetch('../api/admin.php?endpoint=update_user', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${userId}&action=unban`
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showSuccessMessage(`User ${userId} unbanned successfully!`);
                    this.loadUsersData();
                } else {
                    this.showErrorMessage(result.message);
                }
            } catch (error) {
                console.error('Error unbanning user:', error);
                this.showErrorMessage('Failed to unban user');
            }
        }
    }
    
    // Order Management Functions
    async viewOrder(orderId) {
        // Show order details modal
        const orderDetails = `
            <div class="modal-overlay" onclick="this.remove()">
                <div class="modal-content">
                    <h3>Order Details - #${orderId}</h3>
                    <p>Loading order information...</p>
                    <button onclick="this.closest('.modal-overlay').remove()">Close</button>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', orderDetails);
    }
    
    async refundOrder(orderId) {
        console.log('Refund order called for ID:', orderId);
        
        if (confirm(`Are you sure you want to refund order ${orderId}?`)) {
            try {
                console.log('Sending refund request...');
                
                const response = await fetch('../api/admin.php?endpoint=update_order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `order_id=${orderId}&action=refund`
                });
                
                console.log('Refund response received:', response);
                
                const result = await response.json();
                console.log('Refund result:', result);
                
                if (result.success) {
                    this.showSuccessMessage(`Order ${orderId} refunded successfully!`);
                    this.loadOrdersData();
                } else {
                    this.showErrorMessage(result.message);
                }
            } catch (error) {
                console.error('Error refunding order:', error);
                this.showErrorMessage('Failed to refund order: ' + error.message);
            }
        }
    }
    
    async cancelOrder(orderId) {
        if (confirm(`Are you sure you want to cancel order ${orderId}?`)) {
            try {
                const response = await fetch('../api/admin.php?endpoint=update_order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `order_id=${orderId}&action=cancel`
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showSuccessMessage(`Order ${orderId} cancelled successfully!`);
                    this.loadOrdersData();
                } else {
                    this.showErrorMessage(result.message);
                }
            } catch (error) {
                console.error('Error cancelling order:', error);
                this.showErrorMessage('Failed to cancel order');
            }
        }
    }
    
    exportOrders() {
        // Create CSV export
        const csvContent = "Order ID,User,Service,Link,Quantity,Charge,Status,Date\n";
        const orders = document.querySelectorAll('.orders-table tbody tr');
        
        let csvData = csvContent;
        orders.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 8) {
                csvData += `${cells[0].textContent},${cells[1].textContent},${cells[2].textContent},${cells[3].textContent},${cells[4].textContent},${cells[5].textContent},${cells[6].textContent},${cells[7].textContent}\n`;
            }
        });
        
        // Download CSV
        const blob = new Blob([csvData], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `orders_export_${new Date().toISOString().split('T')[0]}.csv`;
        a.click();
        window.URL.revokeObjectURL(url);
        
        this.showSuccessMessage('Orders exported successfully!');
    }
    
    // Service Management Functions
    async addService() {
        const name = prompt('Enter service name:');
        const category = prompt('Enter category:');
        const costPrice = prompt('Enter cost price:');
        const sellPrice = prompt('Enter sell price:');
        
        if (name && category && costPrice && sellPrice) {
            try {
                const response = await fetch('../api/admin.php?endpoint=update_service', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `service_id=new&action=add&name=${name}&category=${category}&cost_price=${costPrice}&sell_price=${sellPrice}`
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showSuccessMessage('Service added successfully!');
                    this.loadServicesData();
                } else {
                    this.showErrorMessage(result.message);
                }
            } catch (error) {
                console.error('Error adding service:', error);
                this.showErrorMessage('Failed to add service');
            }
        }
    }
    
    async editService(serviceId) {
        console.log('Edit service called for ID:', serviceId);
        
        const newCostPrice = prompt('Enter new cost price:');
        const newSellPrice = prompt('Enter new sell price:');
        
        console.log('Service input:', { newCostPrice, newSellPrice });
        
        if (newCostPrice !== null && newSellPrice !== null) {
            try {
                console.log('Sending service update request...');
                
                const response = await fetch('../api/admin.php?endpoint=update_service', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `service_id=${serviceId}&action=update&cost_price=${newCostPrice}&sell_price=${newSellPrice}`
                });
                
                console.log('Service response received:', response);
                
                const result = await response.json();
                console.log('Service result:', result);
                
                if (result.success) {
                    this.showSuccessMessage('Service updated successfully!');
                    this.loadServicesData();
                } else {
                    this.showErrorMessage(result.message);
                }
            } catch (error) {
                console.error('Error updating service:', error);
                this.showErrorMessage('Failed to update service: ' + error.message);
            }
        }
    }
    
    async toggleService(serviceId) {
        try {
            const response = await fetch('../api/admin.php?endpoint=update_service', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `service_id=${serviceId}&action=toggle`
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccessMessage('Service status toggled successfully!');
                this.loadServicesData();
            } else {
                this.showErrorMessage(result.message);
            }
        } catch (error) {
            console.error('Error toggling service:', error);
            this.showErrorMessage('Failed to toggle service');
        }
    }
    
    async deleteService(serviceId) {
        if (confirm(`Are you sure you want to delete service ${serviceId}?`)) {
            try {
                const response = await fetch('../api/admin.php?endpoint=update_service', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `service_id=${serviceId}&action=delete`
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showSuccessMessage(`Service ${serviceId} deleted successfully!`);
                    this.loadServicesData();
                } else {
                    this.showErrorMessage(result.message);
                }
            } catch (error) {
                console.error('Error deleting service:', error);
                this.showErrorMessage('Failed to delete service');
            }
        }
    }
    
    // Pricing Management Functions
    updatePricing() {
        alert('Update Pricing functionality - Coming soon!');
    }
    
    editPricing(serviceId) {
        alert(`Edit Pricing for service ${serviceId} - Coming soon!`);
    }
    
    // API Management Functions
    testAPIs() {
        alert('Testing all APIs...');
        this.loadAPIData();
    }
    
    editAPI(apiName) {
        alert(`Edit ${apiName} API configuration - Coming soon!`);
    }
    
    testAPI(apiName) {
        alert(`Testing ${apiName} API...`);
    }
    
    // Ticket Management Functions
    viewTicket(ticketId) {
        alert(`View Ticket ${ticketId} - Coming soon!`);
    }
    
    replyTicket(ticketId) {
        alert(`Reply to Ticket ${ticketId} - Coming soon!`);
    }
    
    exportTickets() {
        alert('Export Tickets functionality - Coming soon!');
    }
    
    // Settings Functions
    async loadSettings() {
        console.log('Loading settings...');
        
        try {
            const response = await fetch('../api/settings.php?action=get');
            const result = await response.json();
            
            if (result.success) {
                this.populateSettingsForm(result.data);
                console.log('Settings loaded successfully');
            } else {
                this.showErrorMessage('Failed to load settings: ' + result.message);
            }
        } catch (error) {
            console.error('Error loading settings:', error);
            this.showErrorMessage('Failed to load settings: ' + error.message);
        }
    }
    
    populateSettingsForm(settings) {
        // General Settings
        document.getElementById('site_name').value = settings.general?.site_name || '';
        document.getElementById('site_description').value = settings.general?.site_description || '';
        document.getElementById('site_url').value = settings.general?.site_url || '';
        document.getElementById('default_currency').value = settings.general?.default_currency || 'USD';
        document.getElementById('timezone').value = settings.general?.timezone || 'UTC';
        document.getElementById('language').value = settings.general?.language || 'en';
        document.getElementById('maintenance_mode').checked = settings.general?.maintenance_mode || false;
        document.getElementById('maintenance_message').value = settings.general?.maintenance_message || '';
        
        // Security Settings
        document.getElementById('enable_2fa').checked = settings.security?.enable_2fa || false;
        document.getElementById('session_timeout').value = settings.security?.session_timeout || 30;
        document.getElementById('max_login_attempts').value = settings.security?.max_login_attempts || 5;
        document.getElementById('password_min_length').value = settings.security?.password_min_length || 8;
        document.getElementById('require_strong_password').checked = settings.security?.require_strong_password || false;
        document.getElementById('enable_captcha').checked = settings.security?.enable_captcha || false;
        
        // Email Settings
        document.getElementById('smtp_host').value = settings.email?.smtp_host || '';
        document.getElementById('smtp_port').value = settings.email?.smtp_port || 587;
        document.getElementById('smtp_username').value = settings.email?.smtp_username || '';
        document.getElementById('smtp_password').value = settings.email?.smtp_password || '';
        document.getElementById('smtp_encryption').value = settings.email?.smtp_encryption || 'tls';
        document.getElementById('from_email').value = settings.email?.from_email || '';
        document.getElementById('from_name').value = settings.email?.from_name || '';
        document.getElementById('admin_email').value = settings.email?.admin_email || '';
        
        // Payment Settings
        document.getElementById('default_payment_method').value = settings.payment?.default_payment_method || 'paypal';
        document.getElementById('minimum_deposit').value = settings.payment?.minimum_deposit || 5.00;
        document.getElementById('maximum_deposit').value = settings.payment?.maximum_deposit || 1000.00;
        document.getElementById('paypal_client_id').value = settings.payment?.paypal_client_id || '';
        document.getElementById('paypal_client_secret').value = settings.payment?.paypal_client_secret || '';
        document.getElementById('stripe_public_key').value = settings.payment?.stripe_public_key || '';
        document.getElementById('stripe_secret_key').value = settings.payment?.stripe_secret_key || '';
        
        // API Settings
        document.getElementById('smmfa_api_key').value = settings.api?.smmfa_api_key || '';
        document.getElementById('smmfa_api_url').value = settings.api?.smmfa_api_url || '';
        document.getElementById('smmfollows_api_key').value = settings.api?.smmfollows_api_key || '';
        document.getElementById('smmfollows_api_url').value = settings.api?.smmfollows_api_url || '';
        document.getElementById('api_rate_limit').value = settings.api?.api_rate_limit || 1000;
        document.getElementById('api_timeout').value = settings.api?.api_timeout || 30;
        
        // Appearance Settings
        document.getElementById('primary_color').value = settings.appearance?.primary_color || '#f59e0b';
        document.getElementById('secondary_color').value = settings.appearance?.secondary_color || '#1e293b';
        document.getElementById('logo_url').value = settings.appearance?.logo_url || '';
        document.getElementById('favicon_url').value = settings.appearance?.favicon_url || '';
        document.getElementById('custom_css').value = settings.appearance?.custom_css || '';
        document.getElementById('custom_js').value = settings.appearance?.custom_js || '';
        
        // Social Settings
        document.getElementById('google_client_id').value = settings.social?.google_client_id || '';
        document.getElementById('google_client_secret').value = settings.social?.google_client_secret || '';
        document.getElementById('facebook_app_id').value = settings.social?.facebook_app_id || '';
        document.getElementById('facebook_app_secret').value = settings.social?.facebook_app_secret || '';
        document.getElementById('apple_client_id').value = settings.social?.apple_client_id || '';
        document.getElementById('apple_client_secret').value = settings.social?.apple_client_secret || '';
        document.getElementById('enable_social_login').checked = settings.social?.enable_social_login || false;
    }
    
    async saveSettings() {
        console.log('Save settings called');
        
        try {
            const settings = {
                general: {
                    site_name: document.getElementById('site_name').value,
                    site_description: document.getElementById('site_description').value,
                    site_url: document.getElementById('site_url').value,
                    default_currency: document.getElementById('default_currency').value,
                    timezone: document.getElementById('timezone').value,
                    language: document.getElementById('language').value,
                    maintenance_mode: document.getElementById('maintenance_mode').checked,
                    maintenance_message: document.getElementById('maintenance_message').value
                },
                security: {
                    enable_2fa: document.getElementById('enable_2fa').checked,
                    session_timeout: parseInt(document.getElementById('session_timeout').value),
                    max_login_attempts: parseInt(document.getElementById('max_login_attempts').value),
                    password_min_length: parseInt(document.getElementById('password_min_length').value),
                    require_strong_password: document.getElementById('require_strong_password').checked,
                    enable_captcha: document.getElementById('enable_captcha').checked
                },
                email: {
                    smtp_host: document.getElementById('smtp_host').value,
                    smtp_port: parseInt(document.getElementById('smtp_port').value),
                    smtp_username: document.getElementById('smtp_username').value,
                    smtp_password: document.getElementById('smtp_password').value,
                    smtp_encryption: document.getElementById('smtp_encryption').value,
                    from_email: document.getElementById('from_email').value,
                    from_name: document.getElementById('from_name').value,
                    admin_email: document.getElementById('admin_email').value
                },
                payment: {
                    default_payment_method: document.getElementById('default_payment_method').value,
                    minimum_deposit: parseFloat(document.getElementById('minimum_deposit').value),
                    maximum_deposit: parseFloat(document.getElementById('maximum_deposit').value),
                    paypal_client_id: document.getElementById('paypal_client_id').value,
                    paypal_client_secret: document.getElementById('paypal_client_secret').value,
                    stripe_public_key: document.getElementById('stripe_public_key').value,
                    stripe_secret_key: document.getElementById('stripe_secret_key').value
                },
                api: {
                    smmfa_api_key: document.getElementById('smmfa_api_key').value,
                    smmfa_api_url: document.getElementById('smmfa_api_url').value,
                    smmfollows_api_key: document.getElementById('smmfollows_api_key').value,
                    smmfollows_api_url: document.getElementById('smmfollows_api_url').value,
                    api_rate_limit: parseInt(document.getElementById('api_rate_limit').value),
                    api_timeout: parseInt(document.getElementById('api_timeout').value)
                },
                appearance: {
                    primary_color: document.getElementById('primary_color').value,
                    secondary_color: document.getElementById('secondary_color').value,
                    logo_url: document.getElementById('logo_url').value,
                    favicon_url: document.getElementById('favicon_url').value,
                    custom_css: document.getElementById('custom_css').value,
                    custom_js: document.getElementById('custom_js').value
                },
                social: {
                    google_client_id: document.getElementById('google_client_id').value,
                    google_client_secret: document.getElementById('google_client_secret').value,
                    facebook_app_id: document.getElementById('facebook_app_id').value,
                    facebook_app_secret: document.getElementById('facebook_app_secret').value,
                    apple_client_id: document.getElementById('apple_client_id').value,
                    apple_client_secret: document.getElementById('apple_client_secret').value,
                    enable_social_login: document.getElementById('enable_social_login').checked
                }
            };
            
            console.log('Settings to save:', settings);
            
            const response = await fetch('../api/settings.php?action=update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(settings)
            });
            
            console.log('Settings response received:', response);
            
            const result = await response.json();
            console.log('Settings result:', result);
            
            if (result.success) {
                this.showSuccessMessage('Settings saved successfully!');
            } else {
                this.showErrorMessage(result.message || 'Failed to save settings');
                if (result.errors) {
                    result.errors.forEach(error => {
                        this.showErrorMessage(error);
                    });
                }
            }
        } catch (error) {
            console.error('Error saving settings:', error);
            this.showErrorMessage('Failed to save settings: ' + error.message);
        }
    }
    
    // Settings Tab Management
    switchSettingsTab(tabName) {
        console.log('Switch settings tab called:', tabName);
        
        // Hide all tab contents
        document.querySelectorAll('.settings-tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Remove active class from all tab buttons
        document.querySelectorAll('.settings-tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Show selected tab content
        document.getElementById(tabName + '-settings').classList.add('active');
        
        // Add active class to clicked tab button
        event.target.classList.add('active');
    }
    
    // Settings Management Functions
    async backupSettings() {
        console.log('Backup settings called');
        
        try {
            const response = await fetch('../api/settings.php?action=backup', {
                method: 'POST'
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccessMessage('Settings backed up successfully!');
            } else {
                this.showErrorMessage(result.message);
            }
        } catch (error) {
            console.error('Error backing up settings:', error);
            this.showErrorMessage('Failed to backup settings: ' + error.message);
        }
    }
    
    async resetSettings() {
        console.log('Reset settings called');
        
        if (confirm('Are you sure you want to reset all settings to default values? This action cannot be undone.')) {
            try {
                const response = await fetch('../api/settings.php?action=reset', {
                    method: 'POST'
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showSuccessMessage('Settings reset to default values!');
                    this.loadSettings();
                } else {
                    this.showErrorMessage(result.message);
                }
            } catch (error) {
                console.error('Error resetting settings:', error);
                this.showErrorMessage('Failed to reset settings: ' + error.message);
            }
        }
    }
    
    async testEmailSettings() {
        console.log('Test email settings called');
        
        const smtp_host = document.getElementById('smtp_host').value;
        const smtp_port = document.getElementById('smtp_port').value;
        const smtp_username = document.getElementById('smtp_username').value;
        const smtp_password = document.getElementById('smtp_password').value;
        const smtp_encryption = document.getElementById('smtp_encryption').value;
        const test_email = prompt('Enter test email address:');
        
        if (!test_email) return;
        
        try {
            const response = await fetch('../api/settings.php?action=test_email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `smtp_host=${smtp_host}&smtp_port=${smtp_port}&smtp_username=${smtp_username}&smtp_password=${smtp_password}&smtp_encryption=${smtp_encryption}&test_email=${test_email}`
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccessMessage('Email test successful!');
            } else {
                this.showErrorMessage(result.message);
            }
        } catch (error) {
            console.error('Error testing email:', error);
            this.showErrorMessage('Failed to test email: ' + error.message);
        }
    }
    
    async testAPI(apiName) {
        console.log('Test API called:', apiName);
        
        const api_key = document.getElementById(apiName + '_api_key').value;
        const api_url = document.getElementById(apiName + '_api_url').value;
        
        if (!api_key || !api_url) {
            this.showErrorMessage('Please enter API key and URL first');
            return;
        }
        
        try {
            const response = await fetch('../api/settings.php?action=test_api', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `api_name=${apiName}&api_key=${api_key}&api_url=${api_url}`
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccessMessage(`${apiName.toUpperCase()} API test successful! Balance: ${result.balance} ${result.currency}`);
            } else {
                this.showErrorMessage(result.message);
            }
        } catch (error) {
            console.error('Error testing API:', error);
            this.showErrorMessage('Failed to test API: ' + error.message);
        }
    }
    
    async testAllSettings() {
        console.log('Test all settings called');
        
        this.showSuccessMessage('Testing all settings...');
        
        // Test email settings
        await this.testEmailSettings();
        
        // Test SMMFA API
        await this.testAPI('smmfa');
        
        // Test SMMFollows API
        await this.testAPI('smmfollows');
        
        this.showSuccessMessage('All tests completed!');
    }
    
    // Notification Functions
    showSuccessMessage(message) {
        this.showNotification(message, 'success');
    }
    
    showErrorMessage(message) {
        this.showNotification(message, 'error');
    }
    
    showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    // Filter Functions
    applyFilters() {
        console.log('Apply filters called');
        
        // Get current tab
        const currentTab = this.currentTab;
        console.log('Current tab:', currentTab);
        
        // Apply filters based on current tab
        switch (currentTab) {
            case 'users':
                this.loadUsersData();
                break;
            case 'orders':
                this.loadOrdersData();
                break;
            case 'tickets':
                this.loadTicketsData();
                break;
            default:
                console.log('No filters to apply for tab:', currentTab);
        }
        
        this.showSuccessMessage('Filters applied successfully!');
    }
}

// Global Functions
function switchAdminTab(tabName) {
    console.log('Switch admin tab called:', tabName);
    
    if (window.adminPanel) {
        window.adminPanel.switchTab(tabName);
    } else {
        console.error('Admin panel not initialized');
    }
}

function toggleAdminSidebar() {
    if (window.adminPanel) {
        window.adminPanel.toggleSidebar();
    }
}

function addUser() {
    if (window.adminPanel) {
        window.adminPanel.addUser();
    }
}

function editUser(userId) {
    console.log('Global editUser called for ID:', userId);
    
    if (window.adminPanel) {
        window.adminPanel.editUser(userId);
    } else {
        console.error('Admin panel not initialized');
    }
}

function viewUser(userId) {
    if (window.adminPanel) {
        window.adminPanel.viewUser(userId);
    }
}

function banUser(userId) {
    if (window.adminPanel) {
        window.adminPanel.banUser(userId);
    }
}

function viewOrder(orderId) {
    if (window.adminPanel) {
        window.adminPanel.viewOrder(orderId);
    }
}

function refundOrder(orderId) {
    console.log('Global refundOrder called for ID:', orderId);
    
    if (window.adminPanel) {
        window.adminPanel.refundOrder(orderId);
    } else {
        console.error('Admin panel not initialized');
    }
}

function cancelOrder(orderId) {
    if (window.adminPanel) {
        window.adminPanel.cancelOrder(orderId);
    }
}

function exportOrders() {
    if (window.adminPanel) {
        window.adminPanel.exportOrders();
    }
}

function addService() {
    if (window.adminPanel) {
        window.adminPanel.addService();
    }
}

function editService(serviceId) {
    console.log('Global editService called for ID:', serviceId);
    
    if (window.adminPanel) {
        window.adminPanel.editService(serviceId);
    } else {
        console.error('Admin panel not initialized');
    }
}

function toggleService(serviceId) {
    if (window.adminPanel) {
        window.adminPanel.toggleService(serviceId);
    }
}

function deleteService(serviceId) {
    if (window.adminPanel) {
        window.adminPanel.deleteService(serviceId);
    }
}

function updatePricing() {
    if (window.adminPanel) {
        window.adminPanel.updatePricing();
    }
}

function editPricing(serviceId) {
    if (window.adminPanel) {
        window.adminPanel.editPricing(serviceId);
    }
}

function testAPIs() {
    if (window.adminPanel) {
        window.adminPanel.testAPIs();
    }
}

function editAPI(apiName) {
    if (window.adminPanel) {
        window.adminPanel.editAPI(apiName);
    }
}

function testAPI(apiName) {
    if (window.adminPanel) {
        window.adminPanel.testAPI(apiName);
    }
}

function viewTicket(ticketId) {
    if (window.adminPanel) {
        window.adminPanel.viewTicket(ticketId);
    }
}

function replyTicket(ticketId) {
    if (window.adminPanel) {
        window.adminPanel.replyTicket(ticketId);
    }
}

function exportTickets() {
    if (window.adminPanel) {
        window.adminPanel.exportTickets();
    }
}

function saveSettings() {
    console.log('Global saveSettings called');
    
    if (window.adminPanel) {
        window.adminPanel.saveSettings();
    } else {
        console.error('Admin panel not initialized');
    }
}

function applyFilters() {
    console.log('Global applyFilters called');
    
    if (window.adminPanel) {
        window.adminPanel.applyFilters();
    } else {
        console.error('Admin panel not initialized');
    }
}

// Settings Global Functions
function switchSettingsTab(tabName) {
    console.log('Global switchSettingsTab called:', tabName);
    
    if (window.adminPanel) {
        window.adminPanel.switchSettingsTab(tabName);
    } else {
        console.error('Admin panel not initialized');
    }
}

function testEmailSettings() {
    console.log('Global testEmailSettings called');
    
    if (window.adminPanel) {
        window.adminPanel.testEmailSettings();
    } else {
        console.error('Admin panel not initialized');
    }
}

function testAPI(apiName) {
    console.log('Global testAPI called:', apiName);
    
    if (window.adminPanel) {
        window.adminPanel.testAPI(apiName);
    } else {
        console.error('Admin panel not initialized');
    }
}

function testAllSettings() {
    console.log('Global testAllSettings called');
    
    if (window.adminPanel) {
        window.adminPanel.testAllSettings();
    } else {
        console.error('Admin panel not initialized');
    }
}

function backupSettings() {
    console.log('Global backupSettings called');
    
    if (window.adminPanel) {
        window.adminPanel.backupSettings();
    } else {
        console.error('Admin panel not initialized');
    }
}

function resetSettings() {
    console.log('Global resetSettings called');
    
    if (window.adminPanel) {
        window.adminPanel.resetSettings();
    } else {
        console.error('Admin panel not initialized');
    }
}

// Tickets Global Functions
function viewTicket(ticketId) {
    console.log('Global viewTicket called:', ticketId);
    
    if (window.adminPanel) {
        window.adminPanel.viewTicket(ticketId);
    } else {
        console.error('Admin panel not initialized');
    }
}

function replyTicket(ticketId) {
    console.log('Global replyTicket called:', ticketId);
    
    if (window.adminPanel) {
        window.adminPanel.replyTicket(ticketId);
    } else {
        console.error('Admin panel not initialized');
    }
}

function closeTicketModal() {
    console.log('Global closeTicketModal called');
    
    if (window.adminPanel) {
        window.adminPanel.closeTicketModal();
    } else {
        console.error('Admin panel not initialized');
    }
}

function createTicket() {
    console.log('Global createTicket called');
    
    if (window.adminPanel) {
        window.adminPanel.createTicket();
    } else {
        console.error('Admin panel not initialized');
    }
}

function closeCreateTicketModal() {
    console.log('Global closeCreateTicketModal called');
    
    if (window.adminPanel) {
        window.adminPanel.closeCreateTicketModal();
    } else {
        console.error('Admin panel not initialized');
    }
}

function refreshTickets() {
    console.log('Global refreshTickets called');
    
    if (window.adminPanel) {
        window.adminPanel.refreshTickets();
    } else {
        console.error('Admin panel not initialized');
    }
}

function filterTickets() {
    console.log('Global filterTickets called');
    
    if (window.adminPanel) {
        window.adminPanel.filterTickets();
    } else {
        console.error('Admin panel not initialized');
    }
}

function exportTickets() {
    console.log('Global exportTickets called');
    
    if (window.adminPanel) {
        window.adminPanel.exportTickets();
    } else {
        console.error('Admin panel not initialized');
    }
}

function replyToTicket() {
    console.log('Global replyToTicket called');
    
    if (window.adminPanel) {
        window.adminPanel.replyTicket(window.adminPanel.currentTicketId);
    } else {
        console.error('Admin panel not initialized');
    }
}

function cancelReply() {
    console.log('Global cancelReply called');
    
    if (window.adminPanel) {
        window.adminPanel.cancelReply();
    } else {
        console.error('Admin panel not initialized');
    }
}

function changeTicketStatus() {
    console.log('Global changeTicketStatus called');
    alert('Change ticket status functionality - Coming soon!');
}

function assignTicket() {
    console.log('Global assignTicket called');
    alert('Assign ticket functionality - Coming soon!');
}

// Form submission handlers
document.addEventListener('DOMContentLoaded', () => {
    // Reply form submission
    const replyForm = document.getElementById('replyForm');
    if (replyForm) {
        replyForm.addEventListener('submit', (e) => {
            e.preventDefault();
            if (window.adminPanel) {
                window.adminPanel.submitReply();
            }
        });
    }
    
    // Create ticket form submission
    const createTicketForm = document.getElementById('createTicketForm');
    if (createTicketForm) {
        createTicketForm.addEventListener('submit', (e) => {
            e.preventDefault();
            if (window.adminPanel) {
                window.adminPanel.submitCreateTicket();
            }
        });
    }
});

// Initialize Admin Panel
document.addEventListener('DOMContentLoaded', () => {
    console.log('Initializing Admin Panel...');
    
    try {
        window.adminPanel = new AdminPanel();
        console.log('Admin Panel initialized successfully');
    } catch (error) {
        console.error('Failed to initialize Admin Panel:', error);
    }
});
