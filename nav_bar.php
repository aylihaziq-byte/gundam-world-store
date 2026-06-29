<?php
  // Dynamically calculate cart items for the navbar badge
  $cart_count = 0;
  if (isset($_COOKIE['gundam_cart'])) {
      $cart_data = json_decode($_COOKIE['gundam_cart'], true);
      if (is_array($cart_data)) {
          $cart_count = count($cart_data); // Counts unique kits in the cart
      }
  }
?>

<style type="text/css">
  /* ==========================================================================
     THEME VARIABLES (Enhanced for Maximum High-Contrast Visibility)
     ========================================================================== */
  :root {
    --bg-main: #0f1115;
    --bg-panel: #161920;
    --bg-stripes-odd: #1c212c;
    --border-color: #2d3139;
    --text-main: #e2e8f0;
    --text-muted: #94a3b8;
    --text-bold: #ffffff;
    --navbar-bg: #161920;
    --th-bg: #1c212c;
    --th-text: #94a3b8;
    --page-bg: #161920;
    --page-border: #2d3139;
    --page-text: #e2e8f0;
  }

  [data-theme="light"] {
    --bg-main: #f1f5f9;
    --bg-panel: #ffffff;
    --bg-stripes-odd: #f8fafc;
    --border-color: #cbd5e1;
    --text-main: #1e293b !important; 
    --text-muted: #334155 !important; 
    --text-bold: #0f172a !important; 
    --navbar-bg: #ffffff;
    --th-bg: #e2e8f0;
    --th-text: #334155;
    --page-bg: #ffffff;
    --page-border: #cbd5e1;
    --page-text: #0f172a;
  }

  /* ==========================================================================
     GLOBAL OVERRIDES USING CSS VARIABLES 
     ========================================================================== */
  body {
    background-color: var(--bg-main) !important;
    color: var(--text-main) !important;
    transition: background-color 0.3s ease, color 0.3s ease;
  }
  
  .bento-panel, .panel, .table-container {
    background-color: var(--bg-panel) !important;
    border-color: var(--border-color) !important;
    color: var(--text-main) !important;
  }
  
  .page-header h2 {
    color: var(--text-bold) !important;
  }
  
  .control-label {
    color: var(--text-muted) !important;
  }

  .form-control {
    background-color: var(--bg-main) !important;
    border-color: var(--border-color) !important;
    color: var(--text-bold) !important;
  }

  /* Strict Table Text & Structure Enforcement */
  .table-tech {
    background-color: var(--bg-panel) !important;
    border: 2px solid var(--border-color) !important;
  }
  
  .table-striped > tbody > tr:nth-of-type(odd) {
    background-color: var(--bg-stripes-odd) !important;
  }
  .table-striped > tbody > tr:nth-of-type(even) {
    background-color: var(--bg-panel) !important;
  }
  
  .table-tech td, 
  .table-tech tr td, 
  .table-tech tbody tr td {
    border-color: var(--border-color) !important;
    color: var(--text-main) !important;
  }
  
  .table-tech tbody tr td strong {
    color: var(--text-bold) !important;
  }

  .table-tech th,
  .table-tech tr th {
    background-color: var(--th-bg) !important;
    border-color: var(--border-color) !important;
    color: var(--th-text) !important;
  }

  /* ==========================================================================
     NAVBAR DESIGN COMPONENT
     ========================================================================== */
  .navbar-tech {
    background-color: var(--navbar-bg) !important;
    border: none !important;
    border-bottom: 2px solid var(--border-color) !important;
    border-radius: 0px !important;
    margin-bottom: 30px;
    transition: background-color 0.3s ease, border-color 0.3s ease;
  }
  
  .navbar-tech .navbar-brand {
    color: #a855f7 !important;
    font-family: 'SF Mono', SFMono-Regular, Consolas, monospace;
    font-weight: bold;
    letter-spacing: 1px;
  }
  
  .navbar-tech .navbar-nav > li > a {
    color: var(--text-muted) !important;
    font-family: 'SF Mono', SFMono-Regular, Consolas, monospace;
    text-transform: uppercase;
  }
  
  .navbar-tech .navbar-nav > li > a:hover {
    color: #00ffcc !important;
  }

  .theme-toggle-btn {
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-main);
    padding: 6px 12px;
    margin-top: 10px;
    font-family: 'SF Mono', SFMono-Regular, Consolas, monospace;
    font-size: 11px;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .theme-toggle-btn:hover {
    border-color: #00ffcc;
    color: #00ffcc;
  }

  /* Custom Cart Badge */
  .cart-badge {
    background-color: #ef4444;
    color: #ffffff;
    font-size: 10px;
    font-weight: bold;
    padding: 2px 6px;
    margin-left: 4px;
    vertical-align: top;
    border-radius: 0px;
    border: 1px solid #ef4444;
  }
  .cart-nav-link:hover .cart-badge {
    background-color: #0f1115;
    color: #ef4444;
  }

  /* Pagination */
  .pagination > li > a, 
  .pagination > li > span {
    background-color: var(--page-bg) !important;
    border: 1px solid var(--page-border) !important;
    color: var(--page-text) !important;
    border-radius: 0px !important;
    margin: 0 2px;
    transition: all 0.2s ease;
  }
  .pagination > .active > a, 
  .pagination > .active > span,
  .pagination > .active > a:hover,
  .pagination > .active > span:hover {
    background-color: #a855f7 !important;
    border-color: #a855f7 !important;
    color: #ffffff !important;
  }
  .pagination > li > a:hover {
    background-color: var(--bg-stripes-odd) !important;
    color: #00ffcc !important;
    border-color: #00ffcc !important;
  }
  .pagination > .disabled > span, 
  .pagination > .disabled > span:hover {
    background-color: var(--bg-stripes-odd) !important;
    color: var(--text-muted) !important;
    border-color: var(--border-color) !important;
    opacity: 0.5;
  }
</style>

<nav class="navbar navbar-tech">
  <div class="container-fluid" style="max-width: 1400px; margin: 0 auto;">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" style="border-color: var(--border-color);">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar" style="background-color: #00ffcc;"></span>
        <span class="icon-bar" style="background-color: #00ffcc;"></span>
        <span class="icon-bar" style="background-color: #00ffcc;"></span>
      </button>
      <a class="navbar-brand" href="index.php">// GUNDAM_WORLD_STORE</a>
    </div>
 
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="#" style="color: #00ffcc;">[ Welcome, <?php echo isset($_SESSION['staff_name']) ? $_SESSION['staff_name'] : 'User'; ?> ]</a></li>
        <li><a href="index.php">Home</a></li>
        <li><a href="products.php">Products</a></li>
  
        <?php if (isset($_SESSION['staff_level']) && $_SESSION['staff_level'] == 'Admin') { ?>
          <li><a href="customers.php">Collectors</a></li>
          <li><a href="staffs.php">Staff Logs</a></li>
        <?php } ?>
        
        <li><a href="orders.php">Orders</a></li>
      </ul>
      
      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="cart.php" class="cart-nav-link" style="color: #a855f7; font-weight: bold; letter-spacing: 1px;">
            <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> CART
            <?php if($cart_count > 0) { echo "<span class='cart-badge'>$cart_count</span>"; } ?>
          </a>
        </li>

        <li>
          <button id="themeToggle" class="theme-toggle-btn" style="margin-left: 10px;">
            <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Mode: <span id="themeLabel">DARK</span>
          </button>
        </li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        
      </ul>
    </div>
  </div>
</nav>

<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function() {
    var toggleBtn = document.getElementById("themeToggle");
    var themeLabel = document.getElementById("themeLabel");
    
    var currentTheme = localStorage.getItem("gundam-theme") || "dark";
    document.documentElement.setAttribute("data-theme", currentTheme);
    themeLabel.textContent = currentTheme.toUpperCase();

    toggleBtn.addEventListener("click", function() {
      var activeTheme = document.documentElement.getAttribute("data-theme");
      var newTheme = "dark";

      if (activeTheme === "dark") {
        newTheme = "light";
      }

      document.documentElement.setAttribute("data-theme", newTheme);
      themeLabel.textContent = newTheme.toUpperCase();
      localStorage.setItem("gundam-theme", newTheme);
    });
  });
</script>