<?php
  include_once 'database.php';
?>
<?php
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  // Updated tables with your matric number: _a210683
  $stmt = $conn->prepare("SELECT * FROM tbl_orders_a210683, tbl_staffs_a210683,
    tbl_customers_a210683, tbl_orders_details_a210683 WHERE
    tbl_orders_a210683.fld_staff_num = tbl_staffs_a210683.fld_staff_num AND
    tbl_orders_a210683.fld_customer_num = tbl_customers_a210683.fld_customer_num AND
    tbl_orders_a210683.fld_order_num = tbl_orders_details_a210683.fld_order_num AND
    tbl_orders_a210683.fld_order_num = :oid");
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
 
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gundam World Store : Invoice</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
 
  </head>
<body>
 
<div class="container">
  <div class="row" style="margin-top: 20px;">
    <div class="col-xs-6">
      <br>
      <img src="logo.png" width="547" height="186" class="img-responsive" alt="Gundam World Store Logo">
    </div>
    <div class="col-xs-6 text-right">
      <h1 style="color: #6a1b9a; font-weight: bold;">INVOICE</h1>
      <h5><strong>Order ID:</strong> <?php echo $readrow['fld_order_num'] ?></h5>
      <h5><strong>Date:</strong> <?php echo $readrow['fld_order_date'] ?></h5>
    </div>
  </div>
  
  <hr>
  
  <div class="row">
    <div class="col-xs-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4><strong>From: Gundam World Store Sdn. Bhd.</strong></h4>
        </div>
        <div class="panel-body">
          <p>
            No. 45, Jalan Medan Pusat Bandar 1<br>
            Seksyen 9, Pusat Bandar Bangi<br>
            43650 Bandar Baru Bangi<br>
            Selangor Darul Ehsan<br>
          </p>
        </div>
      </div>
    </div>
    
    <div class="col-xs-5 col-xs-offset-2 text-right">
      <div class="panel panel-default">
        <div class="panel-heading text-left">
          <h4><strong>To: <?php echo $readrow['fld_customer_fname']." ".$readrow['fld_customer_lname'] ?></strong></h4>
        </div>
        <div class="panel-body text-left">
          <p>
            <?php echo nl2br($readrow['fld_customer_address']); ?>
          </p>
        </div>
      </div>
    </div>
  </div>
   
  <table class="table table-bordered table-striped">
    <thead>
      <tr style="background-color: #f2f2f2;">
        <th>No</th>
        <th>Model Kit Item</th>
        <th class="text-right">Quantity</th>
        <th class="text-right">Price(RM)/Unit</th>
        <th class="text-right">Total(RM)</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $grandtotal = 0;
      $counter = 1;
      try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Custom query matching your matric number table names
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
        // Checking column name to avoid calculation errors if it varies in your crud file
        $qty = isset($detailrow['fld_order_quantity']) ? $detailrow['fld_order_quantity'] : $detailrow['fld_order_detail_quantity'];
        $item_total = $detailrow['fld_product_price'] * $qty;
      ?>
      <tr>
        <td><?php echo $counter; ?></td>
        <td>[<?php echo $detailrow['fld_product_grade']; ?>] <?php echo $detailrow['fld_product_name']; ?></td>
        <td class="text-right"><?php echo $qty; ?></td>
        <td class="text-right"><?php echo number_format($detailrow['fld_product_price'], 2); ?></td>
        <td class="text-right"><?php echo number_format($item_total, 2); ?></td>
      </tr>
      <?php
        $grandtotal += $item_total;
        $counter++;
      } 
      ?>
      <tr>
        <td colspan="4" class="text-right"><strong>Grand Total</strong></td>
        <td class="text-right" style="color: #6a1b9a;"><strong>RM <?php echo number_format($grandtotal, 2); ?></strong></td>
      </tr>
    </tbody>
  </table>
   
  <div class="row" style="margin-top: 30px; margin-bottom: 30px;">
    <div class="col-xs-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4><strong>Bank Payment Gateway</strong></h4>
        </div>
        <div class="panel-body">
          <p><strong>Bank Name:</strong> Maybank Berhad</p>
          <p><strong>Account Holder:</strong> Gundam World Store Sdn. Bhd.</p>
          <p><strong>Account Number:</strong> 5123-4567-8901</p>
          <p><strong>SWIFT Code:</strong> MBBEMYKLXXX</p>
        </div>
      </div>
    </div>
    
    <div class="col-xs-7">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4><strong>Contact Support Details</strong></h4>
        </div>
        <div class="panel-body">
          <p><strong>Issued By Staff:</strong> <?php echo $readrow['fld_staff_fname']." ".$readrow['fld_staff_lname'] ?> </p>
          <p><strong>Official Email:</strong> <?php echo $readrow['fld_staff_email'] ?> </p>
          <hr style="margin: 10px 0;">
          <p class="text-muted text-center" style="margin-top: 15px;"><em>Computer-generated invoice. No physical signature is required.</em></p>
        </div>
      </div>
    </div>
  </div>
</div>
 
</body>
</html>