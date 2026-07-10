<?php
session_start();
require_once '../includes/db.php';

// Admin auth check
if(!isset($_SESSION['admin_id'])) { 
    header("Location: login.php"); 
    exit; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | OWL CAFE</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Main Site CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">

    <!-- Admin Specific CSS -->
    <style>
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            overflow-x: hidden;
        }
        
        /* Sidebar Styling */
        #admin-sidebar {
            width: 280px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            transition: all 0.3s;
            background-color: var(--bg-secondary);
            border-right: 1px solid var(--border-color);
            overflow-y: auto;
        }
        
        #admin-sidebar .sidebar-brand {
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            border-bottom: 1px solid var(--border-color);
        }
        
        #admin-sidebar .nav-link {
            color: var(--text-muted);
            padding: 15px 25px;
            font-weight: 500;
            transition: 0.3s;
        }
        
        #admin-sidebar .nav-link i {
            width: 25px;
            text-align: center;
            margin-right: 10px;
        }
        
        #admin-sidebar .nav-link:hover, 
        #admin-sidebar .nav-link.active {
            background-color: rgba(212, 175, 55, 0.1);
            color: var(--golden-accent);
            border-left: 4px solid var(--golden-accent);
        }
        
        /* Main Content Styling */
        #main-content {
            margin-left: 280px;
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        /* Topbar Mobile Toggle */
        #mobile-topbar {
            display: none;
            background-color: var(--bg-secondary);
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            #admin-sidebar {
                transform: translateX(-100%);
            }
            #admin-sidebar.show {
                transform: translateX(0);
            }
            #main-content {
                margin-left: 0;
            }
            #mobile-topbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            /* Overlay */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            .sidebar-overlay.show {
                display: block;
            }
        }
        
        /* Table overrides for admin */
        .table {
            color: var(--text-primary);
        }
        .table th {
            color: var(--golden-accent);
            border-bottom-color: var(--border-color);
        }
        .table td {
            border-bottom-color: var(--border-color);
            vertical-align: middle;
        }
        .admin-card {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body>

    <!-- Mobile Topbar -->
    <div id="mobile-topbar">
        <div>
            <img src="../assets/images/logo.jpg" alt="Owl Cafe Logo" style="height: 40px; width: 40px; object-fit: contain;" class="rounded-circle shadow-sm">
        </div>
        <button class="btn theme-text fs-4 p-0 border-0" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
