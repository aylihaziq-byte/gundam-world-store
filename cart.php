<?php
include_once 'session.php';
include_once 'database.php';

// Decode the cookie if it exists, otherwise start an empty array
$cart = isset($_COOKIE['gundam_cart']) ? json_decode($_COOKIE['gundam_cart'], true) : [];

// Handle all Cart Actions (Add, Update, Remove, Clear, Checkout)
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

if ($action == 'add') {
    $pid = $_POST['pid'];
    if (isset($cart[$pid])) {
        $cart[$pid]['qty'] += $_POST['pqty']; // Add to existing quantity
    } else {
        $cart[$pid] = [
            'name' => $_POST['pname'],
            'price' => $_POST['pprice'],
            'grade' => $_POST['pgrade'],
            'image' => $_POST['pimage'],
            'qty' => $_POST['pqty']
        ];
    }
    setcookie('gundam_cart', json_encode($cart), time() + (86400 * 30), "/"); // Save for 30 days
    header("Location: cart.php");
    exit();
} 
elseif ($action == 'update') {
    if (isset($_POST['qty'])) {
        foreach ($_POST['qty'] as $pid => $qty) {
            if ($qty > 0) {
                $cart[$pid]['qty'] = $qty;
            } else {
                unset($cart[$pid]); // Remove if qty set to 0
            }
        }
        setcookie('gundam_cart', json_encode($cart), time() + (86400 * 30), "/");
    }
    
    // Check if a customer was selected, and pass it back in the URL
    $redirect_url = "cart.php";
    if (isset($_POST['cid']) && !empty($_POST['cid'])) {
        $redirect_url .= "?cid=" . urlencode($_POST['cid']);
    }
    
    header("Location: " . $redirect_url);
    exit();
}
elseif ($action == 'remove') {
    $pid = $_GET['pid'];
    if (isset($cart[$pid])) unset($cart[$pid]);
    setcookie('gundam_cart', json_encode($cart), time() + (86400 * 30), "/");
    header("Location: cart.php");
    exit();
} 
elseif ($action == 'clear') {
    setcookie('gundam_cart', '', time() - 3600, "/"); // Destroy cookie
    header("Location: cart.php");
    exit();
} 
elseif ($action == 'checkout') {
    if (!empty($cart) && isset($_POST['cid'])) {
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 1. Create the Main Order
            $oid = "O_" . date('YmdHis');
            $orderdate = date('Y-m-d');
            $sid = $_SESSION['staff_id']; // Staff who is logged in
            $cid = $_POST['cid'];

            $stmt = $conn->prepare("INSERT INTO tbl_orders_a210683(fld_order_num, fld_order_date, fld_staff_num, fld_customer_num) VALUES(:oid, :orderdate, :sid, :cid)");
            $stmt->bindParam(':oid', $oid);
            $stmt->bindParam(':orderdate', $orderdate);
            $stmt->bindParam(':sid', $sid);
            $stmt->bindParam(':cid', $cid);
            $stmt->execute();

            // 2. Loop through Cart to Create Order Details
            $stmt_detail = $conn->prepare("INSERT INTO tbl_orders_details_a210683(fld_order_detail_num, fld_order_num, fld_product_num, fld_order_quantity) VALUES(:did, :oid, :pid, :qty)");
            foreach ($cart as $pid => $item) {
                $did = uniqid('D', true);
                $qty = $item['qty'];
                $stmt_detail->bindParam(':did', $did);
                $stmt_detail->bindParam(':oid', $oid);
                $stmt_detail->bindParam(':pid', $pid);
                $stmt_detail->bindParam(':qty', $qty);
                $stmt_detail->execute();
            }

            // 3. Clear the Cart Cookie and Redirect to Orders
            setcookie('gundam_cart', '', time() - 3600, "/");
            header("Location: orders.php");
            exit();

        } catch(PDOException $e) {
            echo "<script>alert('Error processing order: " . addslashes($e->getMessage()) . "');</script>";
        }
    } else {
        echo "<script>alert('Please select a customer before checking out.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gundam Inventory System : Cart</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <style type="text/css">
    /* ==========================================================================
       THEME VARIABLES (Injected for Cart Page)
       ========================================================================== */
    :root {
      --bg-main: #0f1115;
      --bg-panel: #161920;
      --bg-stripes-odd: #1c212c;
      --border-color: #2d3139;
      --text-main: #e2e8f0;
      --text-muted: #94a3b8;
      --text-bold: #ffffff;
    }

    [data-theme="light"] {
      --bg-main: #f1f5f9;
      --bg-panel: #ffffff;
      --bg-stripes-odd: #f8fafc;
      --border-color: #cbd5e1;
      --text-main: #1e293b !important; 
      --text-muted: #334155 !important; 
      --text-bold: #0f172a !important; 
    }

    body {
      background-color: var(--bg-main) !important;
      color: var(--text-main) !important;
      font-family: 'SF Mono', Consolas, monospace;
      transition: background-color 0.3s ease, color 0.3s ease;
    }
    .container-fluid { padding-top: 30px; }
    
    .bento-panel {
      background-color: var(--bg-panel) !important;
      border: 2px solid var(--border-color) !important;
      padding: 25px;
      margin-bottom: 25px;
      position: relative;
    }
    .bento-panel::before {
      content: ""; position: absolute; top: -2px; left: -2px; width: 10px; height: 10px;
      border-top: 2px solid #a855f7; border-left: 2px solid #a855f7;
    }
    .bento-panel::after {
      content: ""; position: absolute; bottom: -2px; right: -2px; width: 10px; height: 10px;
      border-bottom: 2px solid #a855f7; border-right: 2px solid #a855f7;
    }
    .page-header {
      margin-top: 0; border-bottom: 1px dashed var(--border-color); padding-bottom: 15px;
    }
    .page-header h2 {
      color: var(--text-bold) !important; font-weight: 700; font-size: 20px; letter-spacing: 1px; margin: 0; text-transform: uppercase;
    }
    .form-control {
      background-color: var(--bg-main) !important; border: 1px solid var(--border-color) !important; color: #00ffcc !important; border-radius: 0px !important;
    }
    .form-control:focus { border-color: #00ffcc !important; box-shadow: none !important; }
    
    /* Cart Specific Styles */
    .cart-item {
      border-bottom: 1px solid var(--border-color); padding: 15px 0; display: flex; align-items: center;
    }
    .cart-item:last-child { border-bottom: none; }
    .cart-img { width: 80px; height: 80px; object-fit: cover; border: 1px solid var(--border-color); background-color: var(--bg-main); }
    .cart-details { flex-grow: 1; padding-left: 15px; }
    .cart-price-block { display: flex; align-items: center; gap: 15px; }
    
    .btn-tech {
      background: transparent; border: 1px solid var(--text-main); color: var(--text-main); border-radius: 0px; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; transition: all 0.2s ease;
    }
    .btn-update { border-color: #3b82f6; color: #3b82f6; }
    .btn-update:hover { background-color: #3b82f6; color: #fff; }
    .btn-clear { border-color: #ef4444; color: #ef4444; }
    .btn-clear:hover { background-color: #ef4444; color: #fff; }
    .btn-checkout { background-color: #22c55e; border-color: #22c55e; color: #0f1115; font-weight: bold; padding: 10px 20px;}
    .btn-checkout:hover { background-color: transparent; color: #22c55e; }
  </style>
</head>
<body>

  <?php include_once 'nav_bar.php'; ?>

  <div class="container-fluid" style="max-width: 1400px; margin: 0 auto;">
    <div class="row">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        
        <div class="bento-panel">
          <div class="page-header d-flex" style="display: flex; justify-content: space-between; align-items: center;">
            <h2>// SYS_ORDER_CART</h2>
            <a href="products.php" class="btn btn-tech" style="border-color: #a855f7; color: #a855f7;"><span class="glyphicon glyphicon-th-list"></span> Products</a>
          </div>

          <?php if (empty($cart)) { ?>
            <div class="text-center" style="padding: 40px 0; color: var(--text-muted);">
              <span class="glyphicon glyphicon-shopping-cart" style="font-size: 40px; margin-bottom: 15px;"></span>
              <h4>YOUR CART IS EMPTY</h4>
              <p>Initialize a new build batch from the products matrix.</p>
            </div>
          <?php } else { ?>

            <form action="cart.php" method="post">
              
              <div class="form-group" style="background-color: var(--bg-main); padding: 15px; border: 1px solid var(--border-color); margin-bottom: 20px;">
                <label style="color: var(--text-muted); font-size: 11px; text-transform: uppercase;">Assign Collector (Buyer)</label>
                <select name="cid" class="form-control" required>
                  <option value="" disabled selected hidden>Select Assigned Collector</option>
                  <?php
                  try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $stmt = $conn->prepare("SELECT * FROM tbl_customers_a210683");
                    $stmt->execute();
                    
                    // Look for the customer ID in the URL to keep it selected
                    $retained_cid = isset($_GET['cid']) ? $_GET['cid'] : '';
                    
                    while ($custrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      $selected = ($retained_cid == $custrow['fld_customer_num']) ? "selected" : "";
                      echo "<option value='{$custrow['fld_customer_num']}' {$selected}>{$custrow['fld_customer_fname']} {$custrow['fld_customer_lname']}</option>";
                    }
                  } catch(PDOException $e) {}
                  ?>
                </select>
              </div>

              <div style="margin-bottom: 20px;">
                <?php 
                $grand_total = 0;
                foreach ($cart as $pid => $item) { 
                  $item_total = $item['price'] * $item['qty'];
                  $grand_total += $item_total;
                ?>
                  <div class="cart-item">
                    <?php if (empty($item['image'])) { ?>
                      <div class="cart-img text-center" style="line-height: 80px; color: var(--text-muted); font-size: 10px;">NO IMG</div>
                    <?php } else { ?>
                      <img src="products/<?php echo $item['image']; ?>" class="cart-img" alt="Kit Image">
                    <?php } ?>
                    
                    <div class="cart-details">
                      <h4 style="margin: 0 0 5px 0; color: var(--text-bold); font-weight: bold;"><?php echo $item['name']; ?></h4>
                      <p style="margin: 0; color: var(--text-muted); font-size: 12px;">Grade: [<?php echo $item['grade']; ?>] | Unit: RM <?php echo number_format($item['price'], 2); ?></p>
                    </div>
                    
                    <div class="cart-price-block">
                      <div style="color: #00ffcc; font-weight: bold;">RM <?php echo number_format($item_total, 2); ?></div>
                      <div>
                        <input type="number" name="qty[<?php echo $pid; ?>]" value="<?php echo $item['qty']; ?>" min="1" class="form-control" style="width: 70px; text-align: center;">
                      </div>
                      <a href="cart.php?action=remove&pid=<?php echo $pid; ?>" class="btn btn-tech btn-clear" style="padding: 6px 10px;" title="Remove Item"><span class="glyphicon glyphicon-trash"></span></a>
                    </div>
                  </div>
                <?php } ?>
              </div>

              <div style="border-top: 1px dashed var(--border-color); padding-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                  <span style="color: var(--text-muted); font-size: 11px; text-transform: uppercase;">Modify Batch?</span><br>
                  <a href="cart.php?action=clear" class="btn btn-tech btn-clear" onclick="return confirm('Wipe the entire cart memory?');">Delete Cart</a>
                  <button type="submit" name="action" value="update" class="btn btn-tech btn-update">Update Cart</button>
                </div>
                
                <div class="text-right">
                  <div style="margin-bottom: 10px; font-size: 16px;">
                    <span style="color: var(--text-muted); text-transform: uppercase; font-size: 12px;">Grand Total:</span> 
                    <strong style="color: #a855f7; font-size: 22px;">RM <?php echo number_format($grand_total, 2); ?></strong>
                  </div>
                  <button type="submit" name="action" value="checkout" class="btn btn-tech btn-checkout">Add Order <span class="glyphicon glyphicon-arrow-right"></span></button>
                </div>
              </div>

            </form>
          <?php } ?>

        </div>
      </div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</body>
</html>