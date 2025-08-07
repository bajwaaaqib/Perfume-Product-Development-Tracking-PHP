<!DOCTYPE html>

<!--
 * Developed by Aaqib Bajwa
 * Website: www.aaqibbajwa.com
 * Email: info@aaqibbajwa.com
-->

<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>NPD Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
  
    body {
      background-color: #f8f9fa;
    }
    .navbar-brand {
      font-weight: bold;
      font-size: 1.5rem;
    }
    .nav-link {
      font-size: 1rem;
    }
    .todo-box {
      background: white;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 30px;
      border: 1px solid #ddd;
    }
    .todo-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: #343a40;
    }
    .list-group-item {
      font-size: 0.95rem;
    }
    @media (max-width: 576px) {
      .todo-title {
        font-size: 1rem;
      }
    }

    .drop {
      position: fixed; /* ✅ Use fixed so they don't push layout */
      top: -10px;
      width: 8px;
      height: 8px;
      background: rgba(173, 216, 230, 0.8);
      border-radius: 50%;
      animation: fall linear infinite;
      z-index: 0;
      pointer-events: none;
    }

    @keyframes fall {
      0% {
        transform: translateY(0);
        opacity: 0.7;
      }
      100% {
        transform: translateY(110vh); /* Slightly beyond view without triggering scroll */
        opacity: 0;
      }
    }

    /* FIX: Make mobile menu links clickable by raising z-index */
    .navbar-collapse {
      position: relative;
      z-index: 1050;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top mb-4">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">ARD PERFUMES</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <?php if (isset($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="add_product.php">Add Task</a></li>
          <li class="nav-item"><a class="nav-link" href="add_product_quality_check.php">Quality Check</a></li>
          <li class="nav-item"><a class="nav-link" href="add_printing_approval.php">Printing Approval</a></li>
          <li class="nav-item"><a class="nav-link" href="weekly_reports.php">Report</a></li>
          <li class="nav-item"><a class="nav-link" href="create_user.php">Add User</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
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

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
