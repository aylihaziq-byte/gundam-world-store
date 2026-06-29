<?php
 
include_once 'database.php';
 
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
//Create
if (isset($_POST['create'])) {
  try {
    $stmt = $conn->prepare("INSERT INTO tbl_staffs_a210683(fld_staff_num, fld_staff_fname, fld_staff_lname,
      fld_staff_gender, fld_staff_position, fld_staff_phone, fld_staff_email, fld_staff_password, fld_staff_level) 
      VALUES(:sid, :fname, :lname, :gender, :position, :phone, :email, :password, :level)");
   
    $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
    $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
    $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
    $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
    $stmt->bindParam(':position', $position, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password_hashed, PDO::PARAM_STR);
    $stmt->bindParam(':level', $level, PDO::PARAM_STR); // Save their RBAC level
       
    $sid = $_POST['sid'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $gender =  $_POST['gender'];
    $position = $_POST['position']; 
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password_hashed = md5($_POST['password']); // HASH THE PASSWORD IN MD5
    
    // Determine user level based on position
    if ($position == 'Store Manager') {
        $level = 'Admin';
    } else {
        $level = 'Normal Staff';
    }
         
    $stmt->execute();
  } catch(PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
}
 
//Update
if (isset($_POST['update'])) {
  try {
    // If they typed a new password, update it. If left blank, keep the old one.
    if (!empty($_POST['password'])) {
        $stmt = $conn->prepare("UPDATE tbl_staffs_a210683 SET
          fld_staff_num = :sid, fld_staff_fname = :fname,
          fld_staff_lname = :lname, fld_staff_gender = :gender,
          fld_staff_position = :position, fld_staff_phone = :phone, 
          fld_staff_email = :email, fld_staff_password = :password, fld_staff_level = :level
          WHERE fld_staff_num = :oldsid");
        
        $password_hashed = md5($_POST['password']);
        $stmt->bindParam(':password', $password_hashed, PDO::PARAM_STR);
    } else {
        $stmt = $conn->prepare("UPDATE tbl_staffs_a210683 SET
          fld_staff_num = :sid, fld_staff_fname = :fname,
          fld_staff_lname = :lname, fld_staff_gender = :gender,
          fld_staff_position = :position, fld_staff_phone = :phone, 
          fld_staff_email = :email, fld_staff_level = :level
          WHERE fld_staff_num = :oldsid");
    }
   
    $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
    $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
    $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
    $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
    $stmt->bindParam(':position', $position, PDO::PARAM_STR); 
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':level', $level, PDO::PARAM_STR);
    $stmt->bindParam(':oldsid', $oldsid, PDO::PARAM_STR);
       
    $sid = $_POST['sid'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $position = $_POST['position']; 
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $oldsid = $_POST['oldsid'];

    if ($position == 'Store Manager') {
        $level = 'Admin';
    } else {
        $level = 'Normal Staff';
    }
         
    $stmt->execute();
    header("Location: staffs.php");
  } catch(PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
}
 
//Delete
if (isset($_GET['delete'])) {
 
  try {
    $stmt = $conn->prepare("DELETE FROM tbl_staffs_a210683 where fld_staff_num = :sid");
    $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
    $sid = $_GET['delete'];
    $stmt->execute();
 
    header("Location: staffs.php");
    }
 
  catch(PDOException $e)
  {
      echo "Error: " . $e->getMessage();
  }
}
 
//Edit
if (isset($_GET['edit'])) {
   
  try {
    $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a210683 where fld_staff_num = :sid");
    $stmt->bindParam(':sid', $sid, PDO::PARAM_STR);
    $sid = $_GET['edit'];
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