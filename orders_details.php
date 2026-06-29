<?php
  include_once 'session.php';
  include_once 'orders_details_crud.php';
?>
 
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gundam Inventory System : Order Details</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <style type="text/css">
    body {
      font-family: 'SF Mono', SFMono-Regular, Consolas, 'Liberation Mono', Menlo, monospace; /* Tech Monospace font style */
    }
    .container-fluid {
      padding-top: 30px;
    }
    
    /* Bento-Box / Wireframe Panel Container */
    .bento-panel {
      background-color: var(--bg-panel);
      border: 2px solid var(--border-color); /* Stark sharp borders */
      border-radius: 0px !important; /* Trend: Zero rounded corners */
      padding: 25px;
      margin-bottom: 25px;
      position: relative;
    }
    
    /* Panel Structural Accents (Corner brackets look) */
    .bento-panel::before {
      content: "";
      position: absolute;
      top: -2px; left: -2px; width: 10px; height: 10px;
      border-top: 2px solid #a855f7; border-left: 2px solid #a855f7; /* Neon Purple Accent */
    }
    .bento-panel::after {
      content: "";
      position: absolute;
      bottom: -2px; right: -2px; width: 10px; height: 10px;
      border-bottom: 2px solid #a855f7; border-right: 2px solid #a855f7;
    }

    .panel-heading {
      background-color: var(--th-bg) !important;
      border-bottom: 1px dashed var(--border-color) !important;
      color: var(--th-text) !important;
      border-radius: 0px !important;
      padding: 15px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .page-header {
      margin-top: 0;
      border-bottom: 1px dashed var(--border-color);
      padding-bottom: 15px;
    }
    .page-header h3 {
      font-weight: 700;
      font-size: 18px;
      letter-spacing: 1px;
      margin: 0;
      text-transform: uppercase;
    }

    /* High-contrast form elements bound to theme variables */
    .control-label {
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 1px;
      text-align: left !important;
      color: var(--text-muted) !important;
    }
    .form-control {
      background-color: var(--bg-main) !important;
      border: 1px solid var(--border-color) !important;
      color: #00ffcc !important; /* Cyber terminal mint color */
      border-radius: 0px !important;
      font-size: 13px;
    }
    .form-control:focus {
      border-color: #00ffcc !important;
      box-shadow: none !important;
    }

    /* Force label elements inside form structures to remain highly visible */
    .radio label, .radio, label {
      color: var(--text-main) !important;
      opacity: 1 !important;
    }

    /* Custom Metadata table styling */
    .table-metadata {
      margin-bottom: 0;
    }
    .table-metadata td {
      border-color: var(--border-color) !important;
      color: var(--text-main) !important;
      font-size: 13px;
    }
    .meta-label {
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 1px;
      color: var(--text-muted);
      font-weight: normal;
    }

    /* Tactile Wireframe Buttons */
    .btn-tech {
      background: transparent;
      border: 1px solid var(--text-main);
      color: var(--text-main);
      border-radius: 0px;
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 1px;
      transition: all 0.2s ease;
    }
    .btn-tech-submit {
      border-color: #00ffcc;
      color: #00ffcc;
    }
    .btn-tech-submit:hover {
      background-color: #00ffcc;
      color: #0f1115;
    }
    .btn-tech-clear:hover {
      background-color: var(--text-main);
      color: var(--bg-main);
    }
    
    /* Giant Primary Action Button for Invoice Printing */
    .btn-tech-invoice {
      background-color: #a855f7;
      border: 2px solid #a855f7;
      color: #ffffff;
      border-radius: 0px;
      text-transform: uppercase;
      font-weight: bold;
      letter-spacing: 1px;
      transition: all 0.2s ease;
    }
    .btn-tech-invoice:hover, .btn-tech-invoice:focus {
      background-color: transparent;
      color: #a855f7;
    }

    /* Grid Layout Table */
    .table-tech {
      background-color: var(--bg-panel) !important;
      border: 2px solid var(--border-color) !important;
    }
    .tech-id { color: #a855f7; font-weight: bold; }
    
    /* Custom Brutalist Sharp Badges */
    .brutalist-badge {
      display: inline-block;
      padding: 2px 6px;
      font-size: 10px;
      font-weight: bold;
      text-transform: uppercase;
      border: 1px solid;
    }
  </style>
</head>
<body>

  <?php include_once 'nav_bar.php'; ?>
    
  <?php
  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
    // Fetch Header info: Order, Staff, and Collector details
    $stmt = $conn->prepare("SELECT * FROM tbl_orders_a210683, tbl_staffs_a210683,
      tbl_customers_a210683 WHERE
      tbl_orders_a210683.fld_staff_num = tbl_staffs_a210683.fld_staff_num AND
      tbl_orders_a210683.fld_customer_num = tbl_customers_a210683.fld_customer_num AND
      fld_order_num = :oid");
    $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
    $oid = $_GET['oid'];
    $stmt->execute();
    $readrow = $stmt->fetch(PDO::FETCH_ASSOC);
  }
  catch(PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
  $conn = null;
  ?>

  <div class="container-fluid" style="max-width: 1400px; margin: 0 auto;">
    
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-sm-offset-3">
        <div class="panel bento-panel" style="padding: 0; overflow: hidden;">
          <div class="panel-heading">
            <strong>[Registry_Header_Information]</strong>
          </div>
          <table class="table table-metadata">
            <tr>
              <td class="col-sm-4 meta-label">Order Reference</td>
              <td style="font-weight: bold; color: #a855f7;"><?php echo $readrow['fld_order_num'] ?></td>
            </tr>
            <tr>
              <td class="meta-label">Timestamp</td>
              <td><?php echo $readrow['fld_order_date'] ?></td>
            </tr>
            <tr>
              <td class="meta-label">Operator Staff</td>
              <td><?php echo $readrow['fld_staff_fname']." ".$readrow['fld_staff_lname'] ?></td>
            </tr>
            <tr>
              <td class="meta-label">Assigned Collector</td>
              <td><strong><?php echo $readrow['fld_customer_fname']." ".$readrow['fld_customer_lname'] ?></strong></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-sm-offset-3">
        <div class="bento-panel">
          <div class="page-header text-center">
            <h3>// ADD_PRODUCT_ITEM</h3>
          </div>
          
          <form action="orders_details.php?oid=<?php echo $_GET['oid']; ?>" method="post" class="form-horizontal">
            
            <div class="form-group">
              <label for="productid" class="col-sm-3 control-label">Select Model</label>
              <div class="col-sm-9">
                <select name="pid" class="form-control" id="productid" required>
                  <option value="" disabled selected hidden>Please select model kit</option>
                  <?php
                  try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $conn->prepare("SELECT * FROM tbl_products_a210683");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                  }
                  catch(PDOException $e){
                        echo "Error: " . $e->getMessage();
                  }
                  foreach($result as $productrow) {
                  ?>
                    <option value="<?php echo $productrow['fld_product_num']; ?>">
                      <?php echo "[".$productrow['fld_product_grade']."] " . $productrow['fld_product_name']; ?>
                    </option>
                  <?php } $conn = null; ?>
                </select>
              </div>
            </div>
            
            <div class="form-group">
              <label for="productqty" class="col-sm-3 control-label">Item Quantity</label>
              <div class="col-sm-9">
                <input name="quantity" type="number" min="1" class="form-control" id="productqty" placeholder="Quantity units" required>
                <input name="oid" type="hidden" value="<?php echo $readrow['fld_order_num'] ?>">
              </div>
            </div>
            
            <div class="form-group" style="margin-bottom: 0;">
              <div class="col-sm-offset-3 col-sm-9">
                <button class="btn btn-tech btn-tech-submit" type="submit" name="addproduct"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Insert_To_Cart</button>
                <button class="btn btn-tech btn-tech-clear" type="reset"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Clear_Fields</button>
              </div>
            </div>
            
          </form>
        </div>
      </div>
    </div>
    
    <hr style="width: 50%; border-top: 1px solid var(--border-color);">

    <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2">
        <div class="bento-panel" style="padding: 15px;">
          <div class="page-header text-center" style="margin-bottom: 15px; border-bottom: none;">
            <h3>// PRODUCTS_IN_THIS_ORDER</h3>
          </div>
          
          <table class="table table-striped table-tech table-bordered" style="margin-bottom: 0;">
            <thead>
              <tr>
                <th>[Detail ID]</th>
                <th>[Model Kit Item Description]</th>
                <th class="text-right">[Quantity]</th>
                <th>[Actions]</th>
              </tr>
            </thead>
            <tbody>
              <?php
              try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare("SELECT * FROM tbl_orders_details_a210683,
                  tbl_products_a210683 WHERE
                  tbl_orders_details_a210683.fld_product_num = tbl_products_a210683.fld_product_num AND
                  fld_order_num = :oid");
                $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
                $oid = $_GET['oid'];
                $stmt->execute();
                $result = $stmt->fetchAll();
              }
              catch(PDOException $e){
                    echo "Error: " . $e->getMessage();
              }
              foreach($result as $detailrow) {
                $qty = isset($detailrow['fld_order_quantity']) ? $detailrow['fld_order_quantity'] : $detailrow['fld_order_detail_quantity'];
              ?>
              <tr>
                <td class="tech-id"><?php echo $detailrow['fld_order_detail_num']; ?></td>
                <td>
                  <span class="brutalist-badge grade-cell" style="margin-right: 8px;"><?php echo $detailrow['fld_product_grade']; ?></span>
                  <strong><?php echo $detailrow['fld_product_name']; ?></strong>
                </td>
                <td class="text-right" style="font-weight: bold; color: #00ffcc;"><?php echo $qty; ?> units</td>
                <td class="text-center">
                  <a href="orders_details.php?delete=<?php echo $detailrow['fld_order_detail_num']; ?>&oid=<?php echo $_GET['oid']; ?>" onclick="return confirm('Remove this kit from order?');" class="btn btn-tech btn-xs" style="border-color:#ef4444;color:#ef4444;" role="button">Remove</a>
                </td>
              </tr>
              <?php } $conn = null; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
    <div class="row" style="margin-bottom: 40px; margin-top: 10px;">
      <div class="col-xs-12 text-center">
        <a href="invoice.php?oid=<?php echo $_GET['oid']; ?>" target="_blank" class="btn btn-tech-invoice btn-lg" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print_Official_Invoice</a>
      </div>
    </div>

  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('.grade-cell').each(function() {
        var gradeText = $(this).text().trim();
        var badgeStyles = "";

        if(gradeText === "PG") {
          badgeStyles = "border-color: #eab308; color: #eab308;";
        } else if(gradeText === "MG") {
          badgeStyles = "border-color: #ef4444; color: #ef4444;";
        } else if(gradeText === "RG") {
          badgeStyles = "border-color: #3b82f6; color: #3b82f6;";
        } else if(gradeText === "HG") {
          badgeStyles = "border-color: #22c55e; color: #22c55e;";
        } else {
          badgeStyles = "border-color: #a855f7; color: #a855f7;";
        }

        $(this).attr('style', badgeStyles);
      });
    });
  </script>
</body>
</html>