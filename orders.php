<?php
  include_once 'session.php';
  include_once 'orders_crud.php';
?>
 
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gundam Inventory System : Orders</title>
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

    .page-header {
      margin-top: 0;
      border-bottom: 1px dashed var(--border-color);
      padding-bottom: 15px;
    }
    .page-header h2 {
      font-weight: 700;
      font-size: 20px;
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

    /* Grid Layout Table */
    .table-responsive {
      border: none;
    }
    .tech-id { color: #a855f7; font-weight: bold; }

    /* Custom Tech Pagination overrides */
    .pagination { margin: 0; }
    .pagination > li > a, .pagination > li > span {
      background-color: var(--bg-panel);
      border: 1px solid var(--border-color);
      color: var(--text-main);
      border-radius: 0px !important;
      margin: 0 2px;
    }
    .pagination > .active > a, .pagination > .active > span {
      background-color: #a855f7 !important;
      border-color: #a855f7 !important;
      color: #fff;
    }
    .pagination > li > a:hover {
      background-color: var(--bg-stripes-odd);
      color: #00ffcc;
    }
  </style>
</head>
<body>
  
    <?php include_once 'nav_bar.php'; ?>

    <div class="container-fluid" style="max-width: 1400px; margin: 0 auto;">
      <div class="row">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
          
          <div class="bento-panel">
            <div class="page-header">
              <h2>// <?php echo isset($_GET['edit']) ? 'SYS_EDIT_ORDER' : 'SYS_CREATE_ORDER'; ?></h2>
            </div>
            
            <form action="orders.php" method="post" class="form-horizontal">
              
              <div class="form-group">
                <label for="orderid" class="col-sm-3 control-label">Order ID</label>
                <div class="col-sm-9">
                  <input name="oid" type="text" class="form-control" id="orderid" readonly placeholder="Auto-generated" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_order_num']; ?>">
                </div>
              </div>
              
              <div class="form-group">
                <label for="orderdate" class="col-sm-3 control-label">Order Date</label>
                <div class="col-sm-9">
                  <input name="orderdate" type="text" class="form-control" id="orderdate" readonly value="<?php if(isset($_GET['edit'])) echo $editrow['fld_order_date']; else echo date('Y-m-d'); ?>">
                </div>
              </div>
              
              <div class="form-group">
                <label for="handlingstaff" class="col-sm-3 control-label">Handling Staff</label>
                <div class="col-sm-9">
                  <select name="sid" class="form-control" id="handlingstaff" required>
                    <option value="" disabled selected hidden>Select Handling Staff</option>
                    <?php
                    try {
                      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                      $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a210683");
                      $stmt->execute();
                      $result = $stmt->fetchAll();
                    }
                    catch(PDOException $e){
                          echo "Error: " . $e->getMessage();
                    }
                    foreach($result as $staffrow) {
                    ?>
                      <option value="<?php echo $staffrow['fld_staff_num']; ?>" <?php if((isset($_GET['edit'])) && ($editrow['fld_staff_num']==$staffrow['fld_staff_num'])) echo "selected"; ?>>
                        <?php echo $staffrow['fld_staff_fname']." ".$staffrow['fld_staff_lname'];?>
                      </option>
                    <?php } $conn = null; ?> 
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="collectorselect" class="col-sm-3 control-label">Collector</label>
                <div class="col-sm-9">
                  <select name="cid" class="form-control" id="collectorselect" required>
                    <option value="" disabled selected hidden>Select Collector (Customer)</option>
                    <?php
                    try {
                      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                      $stmt = $conn->prepare("SELECT * FROM tbl_customers_a210683");
                      $stmt->execute();
                      $result = $stmt->fetchAll();
                    }
                    catch(PDOException $e){
                          echo "Error: " . $e->getMessage();
                    }
                    foreach($result as $custrow) {
                    ?>
                      <option value="<?php echo $custrow['fld_customer_num']; ?>" <?php if((isset($_GET['edit'])) && ($editrow['fld_customer_num']==$custrow['fld_customer_num'])) echo "selected"; ?>>
                        <?php echo $custrow['fld_customer_fname']." ".$custrow['fld_customer_lname']?>
                      </option>
                    <?php } $conn = null; ?> 
                  </select>
                </div>
              </div>
              
              <div class="form-group" style="margin-bottom: 0;">
                <div class="col-sm-offset-3 col-sm-9">
                  <?php if (isset($_GET['edit'])) { ?>
                    <button class="btn btn-tech btn-tech-submit" type="submit" name="update"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Update_Order</button>
                  <?php } else { ?>
                    <button class="btn btn-tech btn-tech-submit" type="submit" name="create"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Open_New_Order</button>
                  <?php } ?>
                  <button class="btn btn-tech btn-tech-clear" type="reset"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Reset_Selections</button>
                </div>
              </div>
            </form>
          </div>

        </div>
      </div>
      
      <hr style="border-top: 1px solid var(--border-color);">

      <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
          <div class="bento-panel" style="padding: 15px;">
            <table class="table table-striped table-tech table-bordered" style="margin-bottom: 0;">
              <thead>
                <tr>
                  <th>[Order ID]</th>
                  <th>[Order Date]</th>
                  <th>[Staff Name]</th>
                  <th>[Collector Name]</th>
                  <th>[Actions]</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $per_page = 5;
                $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
                $start_from = ($page - 1) * $per_page;

                try {
                  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  
                  $sql = "SELECT * FROM tbl_orders_a210683, tbl_staffs_a210683, tbl_customers_a210683 WHERE ";
                  $sql .= "tbl_orders_a210683.fld_staff_num = tbl_staffs_a210683.fld_staff_num AND ";
                  $sql .= "tbl_orders_a210683.fld_customer_num = tbl_customers_a210683.fld_customer_num ";
                  $sql .= "LIMIT :start, :perpage";
                  
                  $stmt = $conn->prepare($sql);
                  $stmt->bindValue(':start', $start_from, PDO::PARAM_INT);
                  $stmt->bindValue(':perpage', $per_page, PDO::PARAM_INT);
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }
                catch(PDOException $e){
                      echo "Error: " . $e->getMessage();
                }
                foreach($result as $orderrow) {
                ?>
                <tr>
                  <td class="tech-id"><?php echo $orderrow['fld_order_num']; ?></td>
                  <td><?php echo $orderrow['fld_order_date']; ?></td>
                  <td><?php echo $orderrow['fld_staff_fname']." ".$orderrow['fld_staff_lname'] ?></td>
                  <td><strong><?php echo $orderrow['fld_customer_fname']." ".$orderrow['fld_customer_lname'] ?></strong></td>
                  <td>
                    <a href="orders_details.php?oid=<?php echo $orderrow['fld_order_num']; ?>" class="btn btn-tech btn-xs" style="border-color:#eab308;color:#eab308;" role="button">Details</a>
                    <?php if ($_SESSION['staff_level'] == 'Admin') { ?>
                    <a href="orders.php?edit=<?php echo $orderrow['fld_order_num']; ?>" class="btn btn-tech btn-xs" style="border-color:#22c55e;color:#22c55e;" role="button">Edit</a>
                    <a href="orders.php?delete=<?php echo $orderrow['fld_order_num']; ?>" onclick="return confirm('Are you sure you want to delete this order?');" class="btn btn-tech btn-xs" style="border-color:#ef4444;color:#ef4444;" role="button">Delete</a>
                    <?php } ?>
                  </td>
                </tr>
                <?php } $conn = null; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="row" style="margin-top: 5px; margin-bottom: 40px;">
        <div class="col-xs-12 text-center">
          <nav>
            <ul class="pagination">
              <?php
              try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_orders_a210683");
                $stmt->execute();
                $total_records = $stmt->fetchColumn();
              }
              catch(PDOException $e){
                echo "Error: " . $e->getMessage();
              }
              $total_pages = ceil($total_records / $per_page);
              
              if ($page == 1) { ?>
                <li class="disabled"><span aria-hidden="true">&laquo;</span></li>
              <?php } else { ?>
                <li><a href="orders.php?page=<?php echo $page-1; ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
              <?php }
              
              for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                  echo "<li class=\"active\"><a href=\"orders.php?page=$i\">$i</a></li>";
                } else {
                  echo "<li><a href=\"orders.php?page=$i\">$i</a></li>";
                }
              }
              
              if ($page == $total_pages || $total_pages == 0) { ?>
                <li class="disabled"><span aria-hidden="true">&raquo;</span></li>
              <?php } else { ?>
                <li><a href="orders.php?page=<?php echo $page+1; ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
              <?php } ?>
            </ul>
          </nav>
        </div>
      </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>