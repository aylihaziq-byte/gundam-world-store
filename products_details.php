<?php
  include_once 'session.php';
  include_once 'database.php';
?>
 
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gundam Inventory System : Model Kit Details</title>
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
    
    /* Image Frame Modification */
    .tech-thumbnail {
      background-color: var(--bg-main) !important;
      border: 1px solid var(--border-color) !important;
      border-radius: 0px !important;
      padding: 15px;
    }

    /* Grid Layout Specification Table */
    .table-tech {
      background-color: transparent !important;
      border: none !important;
      margin-bottom: 0;
    }
    .table-tech tr {
      border-bottom: 1px solid var(--border-color);
    }
    .table-tech tr:last-child {
      border-bottom: none;
    }
    .table-tech td {
      border: none !important;
      padding: 14px 10px !important;
      font-size: 13px;
    }
    
    .spec-label {
      color: var(--text-muted);
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 1px;
      width: 35%;
    }
    .spec-value {
      font-weight: bold;
    }

    /* Custom Brutalist Sharp Badges */
    .brutalist-badge {
      display: inline-block;
      padding: 2px 6px;
      font-size: 11px;
      font-weight: bold;
      text-transform: uppercase;
      border: 1px solid;
    }
    
    /* Tech Back Navigation Button */
    .btn-tech-back {
      background: transparent;
      border: 1px solid var(--border-color);
      color: var(--text-main);
      border-radius: 0px;
      text-transform: uppercase;
      font-size: 12px;
      letter-spacing: 1px;
      padding: 10px 20px;
      margin-top: 15px;
      display: inline-block;
      transition: all 0.2s ease;
    }
    .btn-tech-back:hover {
      border-color: #00ffcc;
      color: #00ffcc;
      text-decoration: none;
    }
  </style>
</head>
<body>

  <?php include_once 'nav_bar.php'; ?>
    
  <?php
  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
    $stmt = $conn->prepare("SELECT * FROM tbl_products_a210683 WHERE fld_product_num = :pid");
    $stmt->bindParam(':pid', $pid, PDO::PARAM_STR);
    $pid = $_GET['pid'];
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
      
      <div class="col-xs-12 col-sm-5 col-sm-offset-1">
        <div class="bento-panel">
          <div class="tech-thumbnail">
            <?php if (empty($readrow['fld_product_image'])) { ?>
              <div class="text-center" style="padding: 100px 0; color: var(--text-muted);">
                <span class="glyphicon glyphicon-picture" aria-hidden="true" style="font-size: 48px;"></span><br><br>
                // NO_ASSET_AVAILABLE
              </div>
            <?php } else { ?>
              <img src="products/<?php echo $readrow['fld_product_image']; ?>" class="img-responsive" style="margin: 0 auto;" alt="Model Kit Image">
            <?php } ?>
          </div>
          <div class="text-center" style="display: flex; justify-content: center; gap: 10px; margin-top: 15px;">
            <a href="products.php" class="btn-tech-back" style="margin-top:0;"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Return</a>
            
            <form action="cart.php" method="post" style="display:inline;">
              <input type="hidden" name="action" value="add">
              <input type="hidden" name="pid" value="<?php echo $readrow['fld_product_num']; ?>">
              <input type="hidden" name="pname" value="<?php echo htmlspecialchars($readrow['fld_product_name'], ENT_QUOTES); ?>">
              <input type="hidden" name="pprice" value="<?php echo $readrow['fld_product_price']; ?>">
              <input type="hidden" name="pgrade" value="<?php echo $readrow['fld_product_grade']; ?>">
              <input type="hidden" name="pimage" value="<?php echo $readrow['fld_product_image']; ?>">
              <input type="hidden" name="pqty" value="1"> 
              
              <button type="submit" class="btn-tech-back" style="margin-top:0; border-color:#00ffcc; color:#00ffcc; font-weight:bold;">
                <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Add_To_Cart
              </button>
            </form>
          </div>
        </div>
      </div>
      
      <div class="col-xs-12 col-sm-5">
        <div class="panel bento-panel" style="padding: 0; overflow: hidden;">
          <div class="panel-heading">
            <strong>[Core_Specifications]</strong>
          </div>
          
          <table class="table table-tech">
            <tbody>
              <tr>
                <td class="spec-label">Registry ID</td>
                <td class="spec-value" style="color: #a855f7;"><?php echo $readrow['fld_product_num']; ?></td>
              </tr>
              <tr>
                <td class="spec-label">Model Name</td>
                <td class="spec-value"><?php echo $readrow['fld_product_name']; ?></td>
              </tr>
              <tr>
                <td class="spec-label">Market Value</td>
                <td class="spec-value" style="color: #ef4444;">RM <?php echo number_format($readrow['fld_product_price'], 2); ?></td>
              </tr>
              <tr>
                <td class="spec-label">Grade Classification</td>
                <td class="spec-value grade-cell"><?php echo $readrow['fld_product_grade']; ?></td>
              </tr>
              <tr>
                <td class="spec-label">Runner Condition</td>
                <td class="spec-value">
                  <span class="brutalist-badge" style="<?php echo ($readrow['fld_product_condition'] == 'New') ? 'border-color:#00ffcc;color:#00ffcc;' : 'border-color:var(--text-muted);color:var(--text-muted);'; ?>">
                    <?php echo ($readrow['fld_product_condition'] == 'New') ? 'UNBUILT / NEW' : 'BUILT / BACKLOG'; ?>
                  </span>
                </td>
              </tr>
              <tr>
                <td class="spec-label">Release Timeline</td>
                <td class="spec-value"><?php echo $readrow['fld_product_year']; ?></td>
              </tr>
              <tr>
                <td class="spec-label">Allocated Quantity</td>
                <td class="spec-value"><?php echo $readrow['fld_product_quantity']; ?> UNITS</td>
              </tr>
            </tbody>
          </table>
        </div>
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
          badgeStyles = "border-color: #eab308; color: #eab308;"; // Gold outline
        } else if(gradeText === "MG") {
          badgeStyles = "border-color: #ef4444; color: #ef4444;"; // Neon red outline
        } else if(gradeText === "RG") {
          badgeStyles = "border-color: #3b82f6; color: #3b82f6;"; // Cyan blue outline
        } else if(gradeText === "HG") {
          badgeStyles = "border-color: #22c55e; color: #22c55e;"; // Bright green outline
        } else {
          badgeStyles = "border-color: #a855f7; color: #a855f7;"; // Purple outline
        }

        $(this).html('<span class="brutalist-badge" style="' + badgeStyles + '">' + gradeText + '</span>');
      });
    });
  </script>
</body>
</html>