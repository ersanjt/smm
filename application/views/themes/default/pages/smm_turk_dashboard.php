<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SMM Turk</title>
    
    <!-- Modern CSS Framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #e74c3c;
            --primary-dark: #c0392b;
            --secondary-color: #2c3e50;
            --accent-color: #f39c12;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --text-color: #2c3e50;
            --text-muted: #7f8c8d;
            --sidebar-width: 250px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--light-color);
            color: var(--text-color);
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: white;
            box-shadow: 2px 0 20px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }
        
        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            color: white;
            display: flex;
            align-items: center;
        }
        
        .sidebar-brand i {
            margin-right: 0.5rem;
            font-size: 1.8rem;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-item {
            margin: 0.25rem 0;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover {
            background: var(--light-color);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }
        
        .nav-link.active {
            background: rgba(231, 76, 60, 0.1);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }
        
        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        
        .nav-submenu {
            padding-left: 2rem;
            background: #f8f9fa;
        }
        
        .nav-submenu .nav-link {
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }
        
        .page-header {
            background: white;
            border-radius: 15px;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-color);
            margin: 0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .user-details h6 {
            margin: 0;
            font-weight: 600;
            color: var(--text-color);
        }
        
        .user-details small {
            color: var(--text-muted);
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-icon.primary {
            background: rgba(231, 76, 60, 0.1);
            color: var(--primary-color);
        }
        
        .stat-icon.success {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }
        
        .stat-icon.warning {
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }
        
        .stat-icon.info {
            background: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .stat-change {
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }
        
        .stat-change.positive {
            color: var(--success-color);
        }
        
        .stat-change.negative {
            color: var(--danger-color);
        }
        
        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-color);
            margin: 0;
        }
        
        .card-action {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .card-action:hover {
            text-decoration: underline;
        }
        
        /* Table */
        .table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .table thead {
            background: var(--light-color);
        }
        
        .table th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            color: var(--text-color);
            font-size: 0.9rem;
        }
        
        .table td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
            font-size: 0.9rem;
        }
        
        .table tbody tr {
            border-bottom: 1px solid #f1f3f4;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        /* Badges */
        .badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-success {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }
        
        .badge-warning {
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }
        
        .badge-danger {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }
        
        .badge-primary {
            background: rgba(231, 76, 60, 0.1);
            color: var(--primary-color);
        }
        
        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }
        
        .btn-success {
            background: var(--success-color);
            color: white;
        }
        
        .btn-warning {
            background: var(--warning-color);
            color: white;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Chart Container */
        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 1rem;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .action-card {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            text-decoration: none;
            transition: transform 0.3s ease;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
            color: white;
        }
        
        .action-card i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .action-card h6 {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .action-card p {
            font-size: 0.8rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="#" class="sidebar-brand">
                <i class="fas fa-rocket"></i>
                SMM Turk
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="<?php echo base_url('panel/dashboard'); ?>" class="nav-link active">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="<?php echo base_url('panel/order'); ?>" class="nav-link">
                    <i class="fas fa-shopping-cart"></i>
                    New Order
                </a>
            </div>
            <div class="nav-item">
                <a href="<?php echo base_url('panel/history'); ?>" class="nav-link">
                    <i class="fas fa-history"></i>
                    Order History
                </a>
            </div>
            <div class="nav-item">
                <a href="<?php echo base_url('panel/addbalance'); ?>" class="nav-link">
                    <i class="fas fa-credit-card"></i>
                    Add Balance
                </a>
            </div>
            <div class="nav-item">
                <a href="<?php echo base_url('panel/ticket'); ?>" class="nav-link">
                    <i class="fas fa-ticket-alt"></i>
                    Support Tickets
                </a>
            </div>
            <div class="nav-item">
                <a href="<?php echo base_url('panel/profile'); ?>" class="nav-link">
                    <i class="fas fa-user"></i>
                    Profile
                </a>
            </div>
            <div class="nav-item">
                <a href="<?php echo base_url('logout'); ?>" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Dashboard</h1>
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                </div>
                <div class="user-details">
                    <h6><?php echo $user['username']; ?></h6>
                    <small>Welcome back!</small>
                </div>
            </div>
        </div>
        
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="stat-value">$<?php echo number_format($user['balance'], 2); ?></div>
                <div class="stat-label">Current Balance</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +5.2% from last month
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value"><?php echo $stats['completed_orders']; ?></div>
                <div class="stat-label">Orders Completed</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +12.5% from last month
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value"><?php echo $stats['pending_orders']; ?></div>
                <div class="stat-label">Pending Orders</div>
                <div class="stat-change negative">
                    <i class="fas fa-arrow-down"></i> -2.1% from last month
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-value"><?php echo $stats['total_spent']; ?></div>
                <div class="stat-label">Total Spent</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +8.3% from last month
                </div>
            </div>
        </div>
        
        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Recent Orders -->
            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">Recent Orders</h3>
                    <a href="<?php echo base_url('panel/history'); ?>" class="card-action">View All</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recent_orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo $order['service_name']; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $order['status'] == 'completed' ? 'success' : ($order['status'] == 'pending' ? 'warning' : 'danger'); ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td>$<?php echo number_format($order['amount'], 2); ?></td>
                                <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                
                <div class="quick-actions">
                    <a href="<?php echo base_url('panel/order'); ?>" class="action-card">
                        <i class="fas fa-plus"></i>
                        <h6>New Order</h6>
                        <p>Place a new order</p>
                    </a>
                    
                    <a href="<?php echo base_url('panel/addbalance'); ?>" class="action-card">
                        <i class="fas fa-credit-card"></i>
                        <h6>Add Balance</h6>
                        <p>Top up your account</p>
                    </a>
                    
                    <a href="<?php echo base_url('panel/ticket'); ?>" class="action-card">
                        <i class="fas fa-headset"></i>
                        <h6>Get Support</h6>
                        <p>Contact support</p>
                    </a>
                    
                    <a href="<?php echo base_url('panel/profile'); ?>" class="action-card">
                        <i class="fas fa-user-cog"></i>
                        <h6>Profile</h6>
                        <p>Manage account</p>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Analytics Chart -->
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">Order Analytics</h3>
                <div>
                    <button class="btn btn-sm btn-primary">Last 7 Days</button>
                    <button class="btn btn-sm">Last 30 Days</button>
                </div>
            </div>
            
            <div class="chart-container">
                <canvas id="orderChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"></script>
    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }
        
        // Order Analytics Chart
        const ctx = document.getElementById('orderChart').getContext('2d');
        const orderChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Orders',
                    data: [12, 19, 3, 5, 2, 3, 8],
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231, 76, 60, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
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
                            color: '#f1f3f4'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Auto-refresh stats every 30 seconds
        setInterval(function() {
            // You can add AJAX call here to refresh stats
            console.log('Refreshing stats...');
        }, 30000);
    </script>
</body>
</html>
