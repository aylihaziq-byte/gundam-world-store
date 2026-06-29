<?php
 
include_once 'database.php';
 
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// The directory where your images will be saved. Make sure this folder exists!
$target_dir = "products/"; 
 
// Create
if (isset($_POST['create'])) {
  $uploadOk = 1;
  $image_name = basename($_FILES["fileToUpload"]["name"]);
  $target_file = $target_dir . $image_name;
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  // Check if image file is a actual image or fake image
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    $uploadOk = 1;
  } else {
    echo "<script>alert('ERROR: File is not an image. Please upload a valid image file.'); window.location.href='products.php';</script>";
    $uploadOk = 0;
    exit(); // Stop execution
  }

  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "webp") {
    echo "<script>alert('ERROR: Sorry, only JPG, JPEG, PNG, WEBP & GIF files are allowed.'); window.location.href='products.php';</script>";
    $uploadOk = 0;
    exit();
  }

  // If everything is ok, try to upload file and insert data
  if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      try {
        $stmt = $conn->prepare("INSERT INTO tbl_products_a210683(fld_product_num, fld_product_name, fld_product_price, fld_product_grade, fld_product_condition, fld_product_year, fld_product_quantity, fld_product_image) VALUES(:pid, :name, :price, :grade, :condition, :year, :quantity, :image)");
       
        $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':grade', $grade, PDO::PARAM_STR);
        $stmt->bindParam(':condition', $condition, PDO::PARAM_STR);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':image', $image_name, PDO::PARAM_STR); // Bind the image name
           
        $pid = $_POST['pid'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $grade =  $_POST['grade'];
        $condition = $_POST['condition'];
        $year = $_POST['year'];
        $quantity = $_POST['quantity'];
             
        $stmt->execute();
        header("Location: products.php");
      } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
      }
    } else {
      echo "<script>alert('ERROR: There was an error uploading your file to the server.'); window.location.href='products.php';</script>";
      exit();
    }
  }
}
 
// Update
if (isset($_POST['update'])) {
  try {
    $image_name = basename($_FILES["fileToUpload"]["name"]);
    
    // Check if the user actually uploaded a NEW image
    if (!empty($image_name)) {
      $target_file = $target_dir . $image_name;
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
      
      // Verify the new file is an image
      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if($check !== false && in_array($imageFileType, ["jpg", "png", "jpeg", "gif", "webp"])) {
        
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
          // Prepare SQL with the image update
          $stmt = $conn->prepare("UPDATE tbl_products_a210683 SET fld_product_num = :pid, fld_product_name = :name, fld_product_price = :price, fld_product_grade = :grade, fld_product_condition = :condition, fld_product_year = :year, fld_product_quantity = :quantity, fld_product_image = :image WHERE fld_product_num = :oldpid");
          $stmt->bindParam(':image', $image_name, PDO::PARAM_STR);
        } else {
          echo "<script>alert('ERROR: Could not move the uploaded file to the server folder.'); window.location.href='products.php';</script>";
          exit();
        }
      } else {
        echo "<script>alert('ERROR: Invalid image file type uploaded during update.'); window.location.href='products.php';</script>";
        exit();
      }
    } else {
      // No new image uploaded, so update everything ELSE, but leave the image field alone in the DB
      $stmt = $conn->prepare("UPDATE tbl_products_a210683 SET fld_product_num = :pid, fld_product_name = :name, fld_product_price = :price, fld_product_grade = :grade, fld_product_condition = :condition, fld_product_year = :year, fld_product_quantity = :quantity WHERE fld_product_num = :oldpid");
    }

    $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price, PDO::PARAM_STR);
    $stmt->bindParam(':grade', $grade, PDO::PARAM_STR);
    $stmt->bindParam(':condition', $condition, PDO::PARAM_STR);
    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':oldpid', $oldpid, PDO::PARAM_STR);
       
    $pid = $_POST['pid'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $grade = $_POST['grade'];
    $condition = $_POST['condition'];
    $year = $_POST['year'];
    $quantity = $_POST['quantity'];
    $oldpid = $_POST['oldpid'];
         
    $stmt->execute();
 
    header("Location: products.php");
  } catch(PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
}
 
// Delete
if (isset($_GET['delete'])) {
  try {
    $stmt = $conn->prepare("DELETE FROM tbl_products_a210683 WHERE fld_product_num = :pid");
    $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
    $pid = $_GET['delete'];
    $stmt->execute();
 
    header("Location: products.php");
  } catch(PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
}

// Edit
if (isset($_GET['edit'])) {
  try {
    $stmt = $conn->prepare("SELECT * FROM tbl_products_a210683 WHERE fld_product_num = :pid");
    $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
    $pid = $_GET['edit'];
    $stmt->execute();
 
    $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
  } catch(PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
}
 
$conn = null;
?>