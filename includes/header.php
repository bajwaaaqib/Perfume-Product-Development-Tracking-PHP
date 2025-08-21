<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>NPD Dashboard</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Navbar styling */
    .navbar {
      background: linear-gradient(90deg, #6f42c1, #6610f2);
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      min-height: 60px;
      position: relative;
    }
    
    /* Brand styling */
    .navbar-brand {
      font-weight: bold;
      font-size: 1.6rem;
      color: #fff;
      transition: all 0.3s ease;
      margin: 0 auto;
      display: block;
      text-align: center;
      width: 100%;
      padding: 0.5rem 0;
    }
    .navbar-brand:hover {
      color: #ffd700;
      cursor: pointer;
    }
    
    /* Desktop menu container - hidden initially */
    #desktopMenu {
      display: none;
      width: 100%;
    }
    
    /* When menu is expanded */
    .navbar-expanded .navbar-brand {
      display: none;
    }
    .navbar-expanded #desktopMenu {
      display: flex;
    }
    
    /* Menu items styling */
    .nav-link {
      color: #ffffff;
      font-weight: 500;
      font-size: 1rem;
      margin: 0 5px;
      position: relative;
      transition: all 0.3s ease;
      white-space: nowrap;
      padding: 0.5rem 1rem;
    }
    .nav-link:hover {
      color: #ffd700;
    }
    .nav-link::after {
      content: '';
      display: block;
      width: 0%;
      height: 2px;
      background: #ffd700;
      transition: width 0.3s;
      position: absolute;
      bottom: 0;
      left: 0;
    }
    .nav-link:hover::after {
      width: 100%;
    }

    /* Logout button styling */
    .nav-link.logout-link {
      background: #ff4b5c;
      color: #fff !important;
      border-radius: 5px;
      padding: 5px 10px;
      transition: all 0.3s ease;
    }
    .nav-link.logout-link:hover {
      background: #ff1f3a;
    }
    
    /* Mobile menu toggle button */
    .navbar-toggler {
      border: none;
      position: absolute;
      right: 1rem;
      top: 0.5rem;
    }
    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='white' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
    }
    
    /* Mobile menu styling */
    @media (max-width: 991.98px) {
      .navbar-brand {
        text-align: left;
        padding-left: 1rem;
        width: auto;
      }
      
      /* Hide desktop menu on mobile */
      #desktopMenu {
        display: none !important;
      }
      
      /* Show mobile menu when toggler is clicked */
      #mobileMenu.collapse:not(.show) {
        display: none;
      }
      #mobileMenu.collapse.show {
        display: block;
      }
    }
    
    /* Desktop menu styling */
    @media (min-width: 992px) {
      .navbar-toggler {
        display: none;
      }
      #mobileMenu {
        display: none !important;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top mb-4" id="mainNavbar">
  <div class="container-fluid">
    <!-- Brand/logo - shown by default -->
    <span class="navbar-brand" id="navBrand">
      ARD PERFUMES
    </span>
    
    <!-- Mobile menu toggle button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu" 
      aria-controls="mobileMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <!-- Desktop menu content (hidden initially) -->
    <div id="desktopMenu">
      <ul class="navbar-nav mx-auto">
        <?php if (isset($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="add_product.php"><i class="bi bi-plus-square me-1"></i>Add Task</a></li>
          <li class="nav-item"><a class="nav-link" href="add_product_quality_check.php"><i class="bi bi-check2-square me-1"></i>Quality Check</a></li>
          <li class="nav-item"><a class="nav-link" href="add_printing_approval.php"><i class="bi bi-printer me-1"></i>Printing Approval</a></li>
          <li class="nav-item"><a class="nav-link" href="weekly_reports.php"><i class="bi bi-file-earmark-text me-1"></i>Report</a></li>
          <li class="nav-item"><a class="nav-link" href="products.php"><i class="bi bi-box-seam me-1"></i>Products</a></li>
          <li class="nav-item"><a class="nav-link" href="state.php"><i class="bi bi-bar-chart-line me-1"></i>States</a></li>
          <li class="nav-item"><a class="nav-link" href="create_user.php"><i class="bi bi-person-plus me-1"></i>Users</a></li>
          <li class="nav-item"><a class="nav-link logout-link" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="index.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
    
    <!-- Mobile menu content (uses Bootstrap collapse) -->
    <div class="collapse navbar-collapse" id="mobileMenu">
      <ul class="navbar-nav">
        <?php if (isset($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="add_product.php"><i class="bi bi-plus-square me-1"></i>Add Task</a></li>
          <li class="nav-item"><a class="nav-link" href="add_product_quality_check.php"><i class="bi bi-check2-square me-1"></i>Quality Check</a></li>
          <li class="nav-item"><a class="nav-link" href="add_printing_approval.php"><i class="bi bi-printer me-1"></i>Printing Approval</a></li>
          <li class="nav-item"><a class="nav-link" href="weekly_reports.php"><i class="bi bi-file-earmark-text me-1"></i>Report</a></li>
          <li class="nav-item"><a class="nav-link" href="products.php"><i class="bi bi-box-seam me-1"></i>Products</a></li>
          <li class="nav-item"><a class="nav-link" href="state.php"><i class="bi bi-bar-chart-line me-1"></i>States</a></li>
          <li class="nav-item"><a class="nav-link" href="create_user.php"><i class="bi bi-person-plus me-1"></i>Users</a></li>
          <li class="nav-item"><a class="nav-link logout-link" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="index.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('mainNavbar');
    const brand = document.getElementById('navBrand');
    const desktopMenu = document.getElementById('desktopMenu');
    
    // Only add click behavior for desktop screens
    function checkScreenSize() {
      if (window.innerWidth >= 992) {
        // Desktop behavior
        brand.style.cursor = 'pointer';
        brand.addEventListener('click', toggleDesktopMenu);
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
          if (!navbar.contains(e.target) && !desktopMenu.contains(e.target)) {
            closeDesktopMenu();
          }
        });
      } else {
        // Mobile behavior - remove desktop click handler
        brand.removeEventListener('click', toggleDesktopMenu);
      }
    }
    
    function toggleDesktopMenu() {
      navbar.classList.toggle('navbar-expanded');
    }
    
    function closeDesktopMenu() {
      navbar.classList.remove('navbar-expanded');
    }
    
    // Initialize and add resize listener
    checkScreenSize();
    window.addEventListener('resize', checkScreenSize);
  });
</script>
</body>
</html>