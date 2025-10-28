<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'SMM Turk Panel'; ?></title>
    
    <!-- Modern CSS Framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #f8fafc;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f9fafb;
            --border-color: #e5e7eb;
            --text-color: #374151;
            --text-muted: #6b7280;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--text-color);
        }
        
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
        }
        
        .auth-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .auth-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .auth-header p {
            opacity: 0.9;
            font-size: 0.9rem;
        }
        
        .auth-body {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-color);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: var(--light-color);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background: white;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 10px;
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
            width: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }
        
        .btn-secondary {
            background: var(--secondary-color);
            color: var(--text-color);
            border: 2px solid var(--border-color);
        }
        
        .btn-secondary:hover {
            background: var(--border-color);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border: none;
        }
        
        .alert-danger {
            background: #fef2f2;
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }
        
        .alert-success {
            background: #f0fdf4;
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .auth-footer {
            text-align: center;
            padding: 1rem 2rem;
            background: var(--light-color);
            color: var(--text-muted);
            font-size: 0.85rem;
        }
        
        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .auth-footer a:hover {
            text-decoration: underline;
        }
        
        /* Dashboard Styles */
        .dashboard-container {
            background: var(--light-color);
            min-height: 100vh;
        }
        
        .sidebar {
            background: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
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
        }
        
        .nav-link:hover {
            background: var(--light-color);
            color: var(--primary-color);
        }
        
        .nav-link.active {
            background: var(--primary-color);
            color: white;
        }
        
        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        
        .page-header {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-color);
            margin: 0;
        }
        
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }
        
        .stat-icon.primary {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }
        
        .stat-icon.success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }
        
        .stat-icon.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }
        
        .stat-icon.danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .table thead {
            background: var(--light-color);
        }
        
        .table th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            color: var(--text-color);
        }
        
        .table td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
        }
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }
        
        .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }
        
        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }
        
        .badge-primary {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
