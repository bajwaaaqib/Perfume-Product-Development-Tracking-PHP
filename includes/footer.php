<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sticky Footer Example</title>
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .content-wrap {
      flex: 1;
      padding: 20px;
      /* Your page content styles here */
    }
    footer {
      background-color: #f8f9fa; /* light gray */
      border-top: 1px solid #dee2e6; /* subtle border */
      text-align: center;
      padding: 15px 10px;
    }
    footer a {
      color: #007bff;
      text-decoration: none;
      font-weight: bold;
    }
    footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <footer>
    <p class="mb-0">
      Developed by <a href="https://aaqibbajwa.com" target="_blank" rel="noopener noreferrer">Aaqib</a><br />
      <small>For Perfume Product Development Tracking</small>
    </p>
  </footer>

</body>
</html>
