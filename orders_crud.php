<?php
 
include_once 'database.php';
 
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
// Create
if (isset($_POST['create'])) {
 
  try {
    // We include fld_order_date to capture when the kit was purchased
    $stmt = $conn->prepare("INSERT INTO tbl_orders_a210683(fld_order_num, fld_order_date, fld_staff_num,
      fld_customer_num) VALUES(:oid, :orderdate, :sid, :cid)");
   
    $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
    $stmt->bindParam(':orderdate', $orderdate, PDO::PARAM_STR);
    $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
    $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
       
    // Generating ID in your project format: O_YYYYMMDD_Unique
    $oid = "O_" . date('YmdHis'); 
    $orderdate = date('Y-m-d'); // Current date
    $sid = $_POST['sid'];
    $cid = $_POST['cid'];
     
    $stmt->execute();
    }
 
  catch(PDOException $e)
  {
      echo "Error: " . $e->getMessage();
  }
}
 
// Update
if (isset($_POST['update'])) {
   
  try {
    $stmt = $conn->prepare("UPDATE tbl_orders_a210683 SET fld_staff_num = :sid,
      fld_customer_num = :cid WHERE fld_order_num = :oid");
   
    $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
    $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
    $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
       
    $oid = $_POST['oid'];
    $sid = $_POST['sid'];
    $cid = $_POST['cid'];
     
    $stmt->execute();
 
    header("Location: orders.php");
    }
 
  catch(PDOException $e)
  {
      echo "Error: " . $e->getMessage();
  }
}
 
// Delete
if (isset($_GET['delete'])) {
 
  try {
    $stmt = $conn->prepare("DELETE FROM tbl_orders_a210683 WHERE fld_order_num = :oid");
    $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
    $oid = $_GET['delete'];
    $stmt->execute();
 
    header("Location: orders.php");
    }
 
  catch(PDOException $e)
  {
      echo "Error: " . $e->getMessage();
  }
}
 
// Edit
if (isset($_GET['edit'])) {
   
    try {
    $stmt = $conn->prepare("SELECT * FROM tbl_orders_a210683 WHERE fld_order_num = :oid");
    $stmt->bindParam(':oid', $oid, PDO::PARAM_STR);
    $oid = $_GET['edit'];
    $stmt->execute();
 
    $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
    }
 
  catch(PDOException $e)
  {
      echo "Error: " . $e->getMessage();
  }
}
 
  $conn = null;
?>