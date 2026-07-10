<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
    <!-- Sidebar -->
    <nav id="admin-sidebar">
        <div class="sidebar-brand text-center p-3">
            <img src="../assets/images/logo.jpg" alt="Owl Cafe Logo" style="height: 80px; width: 80px; object-fit: contain;" class="rounded-circle shadow-sm">
        </div>
        
        <div class="d-flex flex-column h-100 pb-5">
            <ul class="nav flex-column mb-auto mt-3">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="orders.php" class="nav-link <?= ($current_page == 'orders.php') ? 'active' : '' ?>">
                        <i class="fas fa-shopping-bag"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a href="bookings.php" class="nav-link <?= ($current_page == 'bookings.php') ? 'active' : '' ?>">
                        <i class="fas fa-calendar-check"></i> Bookings
                    </a>
                </li>
                <li class="nav-item">
                    <a href="customers.php" class="nav-link <?= ($current_page == 'customers.php') ? 'active' : '' ?>">
                        <i class="fas fa-users"></i> Customers
                    </a>
                </li>
                <li class="nav-item">
                    <a href="menu.php" class="nav-link <?= ($current_page == 'menu.php') ? 'active' : '' ?>">
                        <i class="fas fa-utensils"></i> Menu Management
                    </a>
                </li>
                <li class="nav-item">
                    <a href="gallery.php" class="nav-link <?= ($current_page == 'gallery.php') ? 'active' : '' ?>">
                        <i class="fas fa-images"></i> Gallery
                    </a>
                </li>
                <li class="nav-item">
                    <a href="messages.php" class="nav-link <?= ($current_page == 'messages.php') ? 'active' : '' ?>">
                        <i class="fas fa-envelope"></i> Messages
                    </a>
                </li>
                <li class="nav-item">
                    <a href="settings.php" class="nav-link <?= ($current_page == 'settings.php') ? 'active' : '' ?>">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
            </ul>

            <ul class="nav flex-column mt-4 pt-4 border-top" style="border-color: var(--border-color) !important;">
                <li class="nav-item">
                    <a href="../index.php" class="nav-link" target="_blank">
                        <i class="fas fa-external-link-alt"></i> View Website
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <a href="auth.php?action=logout" class="nav-link text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>
