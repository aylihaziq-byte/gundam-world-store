<?php
  include_once 'session.php';
  include_once 'products_crud.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gundam Inventory System : Products</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <style type="text/css">
    body {
      background-color: #0f1115; /* Absolute deep slate space background */
      color: #e2e8f0;
      font-family: 'SF Mono', SFMono-Regular, Consolas, 'Liberation Mono', Menlo, monospace; /* Tech Monospace font style */
    }
    .container-fluid {
      padding-top: 30px;
    }
    
    /* Bento-Box / Wireframe Panel Container */
    .bento-panel {
      background-color: var(--bg-panel);
      border: 2px solid var(--border-color); /* Bound dynamically by active theme values */
      border-radius: 0px !important;
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
      border-bottom: 1px dashed #2d3139;
      padding-bottom: 15px;
    }
    .page-header h2 {
      color: #ffffff;
      font-weight: 700;
      font-size: 20px;
      letter-spacing: 1px;
      margin: 0;
      text-transform: uppercase;
    }

    /* High-contrast form elements */
    .control-label {
      color: #94a3b8;
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 1px;
      text-align: left !important;
    }
    .form-control {
      background-color: var(--bg-main) !important;
      border: 1px solid var(--border-color) !important;
      color: var(--text-bold) !important;
      border-radius: 0px !important;
      font-size: 13px;
    }
    .form-control:focus {
      border-color: #00ffcc !important;
      box-shadow: none !important; /* Avoid muddy glows; crisp lines instead */
    }
    .radio label {
      color: #e2e8f0;
      font-size: 12px;
    }
    .radio label, 
    .radio, 
    input[type="radio"] + span,
    label {
      color: var(--text-main) !important;
      opacity: 1 !important; /* Prevents Bootstrap from washing out the text */
    }
    [data-theme="light"] .form-control::placeholder {
      color: #64748b !important; /* Clearer, darker gray for placeholders */
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
      border-color: #00ffcc !important;
      color: #00ffcc !important;
    }
    .btn-tech-submit:hover {
      background-color: #00ffcc !important;
      color: #0f1115 !important;
    }

    /* Fix: Assign an explicit neon-coral border and text to Wipe_Form so it stands out sharply */
    .btn-tech-clear {
      border-color: #ffb300 !important; /* Tech Amber/Orange alert wireframe */
      color: #ffb300 !important;
    }
    .btn-tech-clear:hover {
      background-color: #ffb300 !important;
      color: #0f1115 !important;
    }

    /* Grid Layout Table */
    .table-responsive {
      border: none;
    }
    .table-tech {
      background-color: #161920;
      border: 2px solid #2d3139 !important;
    }
    .table-tech > tfoot > tr > td, 
    .table-tech > tbody > tr > td {
      border: 1px solid #2d3139 !important;
      vertical-align: middle !important;
      font-size: 12px;
    }
    .table-tech th {
      background-color: #1c212c;
      border: 1px solid #2d3139 !important;
      color: #94a3b8;
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 1px;
    }
    /* Fix: Hard override Bootstrap's default white/light-grey striping */
    .table-striped > tbody > tr:nth-of-type(odd) {
      background-color: #1c212c !important; /* Fixed dark gray */
    }
    .table-striped > tbody > tr:nth-of-type(even) {
      background-color: #161920 !important; /* Even deeper dark gray */
    }
    
    /* Make sure all data cell text defaults to high-contrast white */
    .table-tech tbody tr td {
      color: #ffffff !important; 
    }
    
    /* Give subtext labels a readable silver hue instead of fading out */
    .table-tech tbody tr td strong {
      color: #e2e8f0 !important;
    }
    
    /* Tactile Status indicators */
    .tech-id { color: #a855f7; font-weight: bold; }
    .tech-price { color: #f43f5e; } /* Neon coral red for prices */
    
    /* Brutalist Sharp Badges */
    .brutalist-badge {
      display: inline-block;
      padding: 2px 6px;
      font-size: 10px;
      font-weight: bold;
      text-transform: uppercase;
      border: 1px solid;
    }

    /* Custom Tech Pagination */
    .pagination { margin: 0; }
    .pagination > li > a, .pagination > li > span {
      background-color: #161920;
      border: 1px solid #2d3139;
      color: #e2e8f0;
      border-radius: 0px !important;
      margin: 0 2px;
    }
    .pagination > .active > a, .pagination > .active > span {
      background-color: #a855f7 !important;
      border-color: #a855f7 !important;
      color: #fff;
    }
    .pagination > li > a:hover {
      background-color: #2d3139;
      color: #00ffcc;
    }
    .table-responsive {
      width: 100%;
      overflow-x: auto;
    }

    /* Ensure the scrollbar inside the table is visible on mobile */
    .dataTables_wrapper .dataTables_scroll {
      overflow-x: auto;
    }

    @media (max-width: 767px) {
    .dt-buttons {
      display: flex;
      justify-content: center;
      margin: 10px 0;
    }
    
    /* Give the search and length boxes some breathing room */
    .dataTables_length, .dataTables_filter {
      text-align: center;
      margin-bottom: 10px;
    }
  }
  </style>
</head>
<body>
  
    <?php include_once 'nav_bar.php'; ?>

    <div class="container-fluid" style="max-width: 1400px; margin: 0 auto;">
      <div class="row">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
          
          <?php if ($_SESSION['staff_level'] == 'Admin') { ?>
          <div class="bento-panel">
            <div class="page-header">
              <h2>// <?php echo isset($_GET['edit']) ? 'SYS_EDIT_PRODUCT' : 'SYS_CREATE_PRODUCT'; ?></h2>
            </div>
            
            <form action="products.php" method="post" class="form-horizontal" enctype="multipart/form-data">
              
              <div class="form-group">
                <label for="productid" class="col-sm-3 control-label">Product ID</label>
                <div class="col-sm-9">
                  <input name="pid" type="text" class="form-control" id="productid" placeholder="e.g. G-001" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_num']; ?>" required>
                </div>
              </div>
              
              <div class="form-group">
                <label for="productname" class="col-sm-3 control-label">Model Name</label>
                <div class="col-sm-9">
                  <input name="name" type="text" class="form-control" id="productname" placeholder="e.g. RX-78-2 Gundam" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_name']; ?>" required>
                </div>
              </div>

              <div class="form-group">
                <label for="fileToUpload" class="col-sm-3 control-label">Kit Image</label>
                <div class="col-sm-9">
                  <input name="fileToUpload" type="file" class="form-control" id="fileToUpload" accept="image/*" <?php if(!isset($_GET['edit'])) echo 'required'; ?>>
                  <?php if(isset($_GET['edit'])) echo '<span class="help-block" style="font-size:10px;color:#94a3b8;">Leave blank to keep existing image.</span>'; ?>
                </div>
              </div>
              
              <div class="form-group">
                <label for="productprice" class="col-sm-3 control-label">Price (RM)</label>
                <div class="col-sm-9">
                  <input name="price" type="number" step="0.01" min="0" class="form-control" id="productprice" placeholder="0.00" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_price']; ?>" required>
                </div>
              </div>
              
              <div class="form-group">
                <label for="productgrade" class="col-sm-3 control-label">Grade Scale</label>
                <div class="col-sm-9">
                  <select name="grade" class="form-control" id="productgrade" required>
                    <option value="" disabled selected hidden>Select Grade</option>
                    <option value="HG" <?php if(isset($_GET['edit'])) if($editrow['fld_product_grade']=="HG") echo "selected"; ?>>HG (High Grade)</option>
                    <option value="RG" <?php if(isset($_GET['edit'])) if($editrow['fld_product_grade']=="RG") echo "selected"; ?>>RG (Real Grade)</option>
                    <option value="MG" <?php if(isset($_GET['edit'])) if($editrow['fld_product_grade']=="MG") echo "selected"; ?>>MG (Master Grade)</option>
                    <option value="PG" <?php if(isset($_GET['edit'])) if($editrow['fld_product_grade']=="PG") echo "selected"; ?>>PG (Perfect Grade)</option>
                    <option value="FM" <?php if(isset($_GET['edit'])) if($editrow['fld_product_grade']=="FM") echo "selected"; ?>>FM (Full Mechanics)</option>
                    <option value="MGSD" <?php if(isset($_GET['edit'])) if($editrow['fld_product_grade']=="MGSD") echo "selected"; ?>>MGSD (Master Grade SD)</option>
                  </select>
                </div>
              </div>    
              
              <div class="form-group">
                <label for="productcond" class="col-sm-3 control-label">Condition</label>
                <div class="col-sm-9" id="productcond">
                  <div class="radio">
                    <label>
                      <input name="condition" type="radio" value="New" <?php if(isset($_GET['edit'])) if($editrow['fld_product_condition']=="New") echo "checked"; ?> required> [UNBUILT] NEW STOCK
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input name="condition" type="radio" value="Used" <?php if(isset($_GET['edit'])) if($editrow['fld_product_condition']=="Used") echo "checked"; ?> required> [BACKLOG] USED/BUILT
                    </label>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label for="productyear" class="col-sm-3 control-label">Release Year</label>
                <div class="col-sm-9">
                  <select name="year" class="form-control" id="productyear" required>
                    <?php for($y=2010; $y<=2026; $y++){ ?>
                      <option value="<?php echo $y; ?>" <?php if(isset($_GET['edit'])) if($editrow['fld_product_year']==$y) echo "selected"; ?>><?php echo $y; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>  
              
              <div class="form-group">
                <label for="productq" class="col-sm-3 control-label">Qty In Stock</label>
                <div class="col-sm-9">
                  <input name="quantity" type="number" min="0" class="form-control" id="productq" placeholder="Current Stock Quantity" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_product_quantity']; ?>" required>
                </div>
              </div>
              
              <div class="form-group" style="margin-bottom: 0;">
                <div class="col-sm-offset-3 col-sm-9">
                  <?php if (isset($_GET['edit'])) { ?>
                    <input type="hidden" name="oldpid" value="<?php echo $editrow['fld_product_num']; ?>">
                    <button class="btn btn-tech btn-tech-submit" type="submit" name="update"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Execute_Update</button>
                  <?php } else { ?>
                    <button class="btn btn-tech btn-tech-submit" type="submit" name="create"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Commit_Add</button>
                  <?php } ?>
                  <button class="btn btn-tech btn-tech-clear" type="reset"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Wipe_Form</button>
                </div>
              </div>
            </form>
          </div>
          <?php } else { ?>
            <div class="bento-panel text-center">
              <h4 style="color: #94a3b8;">// DATABASE_LOCKED: Admin clearance required to modify product inventory.</h4>
            </div>
          <?php } ?>
        </div>
      </div>
      
      <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
          <div class="bento-panel" style="padding: 15px;">
            <div class="table-responsive">
            <table id="productstable" class="table table-striped table-tech table-bordered" style="margin-bottom: 0; width:100%;">
              <thead>
                <tr>
                  <th>[ID]</th>
                  <th>[Model Name]</th>
                  <th>[Price]</th>
                  <th>[Grade]</th>
                  <th>[Condition]</th>
                  <th>[Actions]</th>
                </tr>
              </thead>
              <tbody>
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
                foreach($result as $readrow) {
                ?>
                <tr>
                  <td class="tech-id"><?php echo $readrow['fld_product_num']; ?></td>
                  <td><strong><?php echo $readrow['fld_product_name']; ?></strong></td>
                  <td style="font-weight: bold;">RM <?php echo number_format($readrow['fld_product_price'], 2); ?></td>
                  <td class="grade-cell"><?php echo $readrow['fld_product_grade']; ?></td>
                  <td>
                    <span class="brutalist-badge" style="<?php echo ($readrow['fld_product_condition'] == 'New') ? 'border-color:#00ffcc;color:#00ffcc;' : 'border-color:#94a3b8;color:#94a3b8;'; ?>">
                      <?php echo $readrow['fld_product_condition']; ?>
                    </span>
                  </td>
                  <td>
                    <a href="products_details.php?pid=<?php echo $readrow['fld_product_num']; ?>" class="btn btn-tech btn-xs" style="border-color:#eab308;color:#eab308;" role="button">Details</a>
                    <?php if ($_SESSION['staff_level'] == 'Admin') { ?>
                    <a href="products.php?edit=<?php echo $readrow['fld_product_num']; ?>" class="btn btn-tech btn-xs" style="border-color:#22c55e;color:#22c55e;" role="button">Edit</a>
                    <a href="products.php?delete=<?php echo $readrow['fld_product_num']; ?>" onclick="return confirm('Are you sure to delete this kit?');" class="btn btn-tech btn-xs" style="border-color:#ef4444;color:#ef4444;" role="button">Delete</a>
                    <?php } ?>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            </div>
          </div>
          
        </div>
      </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap.min.css"/>

    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        var table = $('#productstable').DataTable({
          "scrollX": true, // This enables horizontal scrolling on small screens
          "lengthMenu": [[5, 10, 22, 30, -1], [5, 10, 22, 30, "All"]],
          dom: "<'row'<'col-xs-12 col-sm-4'l><'col-xs-12 col-sm-4 text-center'B><'col-xs-12 col-sm-4'f>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row'<'col-sm-5'i><'col-sm-7'p>>",
          
          buttons: [
            {
              extend: 'excelHtml5',
              text: '<span class="glyphicon glyphicon-export"></span> Export Excel',
              // Ensure the button is block-level on mobile to look better
              className: 'btn btn-tech btn-tech-submit btn-sm', 
              exportOptions: {
                columns: [ 0, 1, 2, 3, 4 ]
              }
            }
          ]
        });
        $('.grade-cell').each(function() {
          var gradeText = $(this).text().trim();
          var badgeStyles = "";

          if(gradeText === "PG") {
            badgeStyles = "border-color: #eab308; color: #eab308;"; // Stark gold wire outline
          } else if(gradeText === "MG") {
            badgeStyles = "border-color: #ef4444; color: #ef4444;"; // Neon red wire outline
          } else if(gradeText === "RG") {
            badgeStyles = "border-color: #3b82f6; color: #3b82f6;"; // Cyan blue wire outline
          } else if(gradeText === "HG") {
            badgeStyles = "border-color: #22c55e; color: #22c55e;"; // Bright green wire outline
          } else {
            badgeStyles = "border-color: #a855f7; color: #a855f7;"; // Purple for others
          }

          $(this).html('<span class="brutalist-badge" style="' + badgeStyles + '">' + gradeText + '</span>');
        });
      });
    </script>
</body>
</html>