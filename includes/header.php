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
    }
    .navbar-brand {
      font-weight: bold;
      font-size: 1.6rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: #fff;
      transition: all 0.3s ease;
      white-space: nowrap;
    }
    .navbar-brand:hover {
      color: #ffd700;
    }
    .navbar-brand img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      box-shadow: 0 2px 6px rgba(0,0,0,0.4);
      transition: transform 0.4s ease;
    }
    .navbar-brand img:hover {
      transform: scale(1.2) rotate(10deg);
    }

    .nav-link {
      color: #ffffff;
      font-weight: 500;
      font-size: 1rem;
      margin: 0 5px;
      position: relative;
      transition: all 0.3s ease;
      white-space: nowrap;
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

    .nav-item .active-link {
      color: #ffd700 !important;
      font-weight: bold;
    }

    /* Logout highlight */
    .nav-link.logout-link {
      background: #ff4b5c;
      color: #fff !important;
      border-radius: 5px;
      padding: 5px 10px;
      transition: all 0.3s ease;
    }
    .nav-link.logout-link:hover {
      background: #ff1f3a;
      color: #fff !important;
    }

    /* Drop animation 
    .drop {
      position: fixed;
      top: -10px;
      width: 8px;
      height: 8px;
      background: rgba(255, 255, 255, 0.8);
      border-radius: 50%;
      animation: fall linear infinite;
      z-index: 0;
      pointer-events: none;
    }
    @keyframes fall {
      0% { transform: translateY(0); opacity: 0.7; }
      100% { transform: translateY(110vh); opacity: 0; }
    }

    /* Responsive tweaks */
    @media (max-width: 768px) {
      .navbar-brand { font-size: 1.4rem; }
      .nav-link { font-size: 0.95rem; }
    }

    @media (max-width: 576px) {
      .navbar-brand img { width: 35px; height: 35px; }
      .nav-link { font-size: 0.9rem; }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top mb-4">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">
      ARD PERFUMES
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-left text-nowrap">
        <?php if (isset($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="add_product.php"><i class="bi bi-plus-square me-1"></i>Add Task</a></li>
          <li class="nav-item"><a class="nav-link" href="add_product_quality_check.php"><i class="bi bi-check2-square me-1"></i>Quality Check</a></li>
          <li class="nav-item"><a class="nav-link" href="add_printing_approval.php"><i class="bi bi-printer me-1"></i>Printing Approval</a></li>
          <li class="nav-item"><a class="nav-link" href="weekly_reports.php"><i class="bi bi-file-earmark-text me-1"></i>Report</a></li>
          <li class="nav-item"><a class="nav-link" href="products.php"><i class="bi bi-box-seam me-1"></i>Products</a></li>
          <li class="nav-item"><a class="nav-link" href="state.php"><i class="bi bi-bar-chart-line me-1"></i>States</a></li>
          <li class="nav-item"><a class="nav-link" href="create_user.php"><i class="bi bi-person-plus me-1"></i>Add User</a></li>
          <li class="nav-item"><a class="nav-link logout-link" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="index.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Drop Animation Script -->
<script>
  const dropCount = 50;
  for (let i = 0; i < dropCount; i++) {
    const drop = document.createElement('div');
    drop.classList.add('drop');
    drop.style.left = Math.random() * 100 + 'vw';
    drop.style.animationDuration = (Math.random() * 3 + 2) + 's';
    drop.style.animationDelay = Math.random() * 5 + 's';
    document.body.appendChild(drop);
  }
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
