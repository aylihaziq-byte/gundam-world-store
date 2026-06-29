<?php
  include_once 'session.php';
  if ($_SESSION['staff_level'] == 'Normal Staff') {
      echo "<script>alert('ACCESS DENIED: You do not have permission to view this module.'); window.location.href='index.php';</script>";
      exit();
  }
  include_once 'customers_crud.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gundam Inventory System : Collectors</title>
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
      box-shadow: none !important; /* Crisp wireframe tracking focus line */
    }
    .radio label {
      font-size: 12px;
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
    
    /* Custom Brutalist Sharp Badges */
    .brutalist-badge {
      display: inline-block;
      padding: 2px 6px;
      font-size: 10px;
      font-weight: bold;
      text-transform: uppercase;
      border: 1px solid;
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
              <h2>// <?php echo isset($_GET['edit']) ? 'SYS_EDIT_COLLECTOR' : 'SYS_REG_COLLECTOR'; ?></h2>
            </div>
            
            <form action="customers.php" method="post" class="form-horizontal">
              
              <div class="form-group">
                <label for="collectorid" class="col-sm-3 control-label">Collector ID</label>
                <div class="col-sm-9">
                  <input name="cid" type="text" class="form-control" id="collectorid" placeholder="e.g. C001" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_customer_num']; ?>" required>
                </div>
              </div>
              
              <div class="form-group">
                <label for="firstname" class="col-sm-3 control-label">First Name</label>
                <div class="col-sm-9">
                  <input name="fname" type="text" class="form-control" id="firstname" placeholder="First Name" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_customer_fname']; ?>" required>
                </div>
              </div>
              
              <div class="form-group">
                <label for="lastname" class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-9">
                  <input name="lname" type="text" class="form-control" id="lastname" placeholder="Last Name" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_customer_lname']; ?>" required>
                </div>
              </div>
              
              <div class="form-group">
                <label for="collectorgender" class="col-sm-3 control-label">Gender</label>
                <div class="col-sm-9" id="collectorgender">
                  <div class="radio">
                    <label>
                      <input name="gender" type="radio" value="Male" <?php if(isset($_GET['edit'])) if($editrow['fld_customer_gender']=="Male") echo "checked"; ?> required> MALE
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input name="gender" type="radio" value="Female" <?php if(isset($_GET['edit'])) if($editrow['fld_customer_gender']=="Female") echo "checked"; ?> required> FEMALE
                    </label>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label for="collectorphone" class="col-sm-3 control-label">Phone Number</label>
                <div class="col-sm-9">
                  <input name="phone" type="text" class="form-control" id="collectorphone" placeholder="01X-XXXXXXX" value="<?php if(isset($_GET['edit'])) echo $editrow['fld_customer_phone']; ?>" required>
                </div>
              </div>
              
              <div class="form-group">
                <label for="membershiplevel" class="col-sm-3 control-label">Membership Level</label>
                <div class="col-sm-9">
                  <select name="membership" class="form-control" id="membershiplevel" required>
                    <option value="" disabled selected hidden>Select Membership Level</option>
                    <option value="Newbie" <?php if(isset($_GET['edit'])) if($editrow['fld_customer_level']=="Newbie") echo "selected"; ?>>Newbie (Tier 1)</option>
                    <option value="Veteran" <?php if(isset($_GET['edit'])) if($editrow['fld_customer_level']=="Veteran") echo "selected"; ?>>Veteran (Tier 2)</option>
                    <option value="Pro Builder" <?php if(isset($_GET['edit'])) if($editrow['fld_customer_level']=="Pro Builder") echo "selected"; ?>>Pro Builder (Tier 3)</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="homeaddress" class="col-sm-3 control-label">Home Address</label>
                <div class="col-sm-9">
                  <textarea name="address" class="form-control" rows="3" id="homeaddress" placeholder="Full Postal Address" required><?php if(isset($_GET['edit'])) echo $editrow['fld_customer_address']; ?></textarea>
                </div>
              </div>
              
              <div class="form-group" style="margin-bottom: 0;">
                <div class="col-sm-offset-3 col-sm-9">
                  <?php if (isset($_GET['edit'])) { ?>
                    <input type="hidden" name="oldcid" value="<?php echo $editrow['fld_customer_num']; ?>">
                    <button class="btn btn-tech btn-tech-submit" type="submit" name="update"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Update_Collector</button>
                  <?php } else { ?>
                    <button class="btn btn-tech btn-tech-submit" type="submit" name="create"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Register_Profile</button>
                  <?php } ?>
                  <button class="btn btn-tech btn-tech-clear" type="reset"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Clear_Fields</button>
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
                  <th>[ID]</th>
                  <th>[Collector Name]</th>
                  <th>[Gender]</th>
                  <th>[Phone]</th>
                  <th>[Tier Level]</th>
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
                  $stmt = $conn->prepare("SELECT * FROM tbl_customers_a210683 LIMIT :start, :perpage");
                  $stmt->bindValue(':start', $start_from, PDO::PARAM_INT);
                  $stmt->bindValue(':perpage', $per_page, PDO::PARAM_INT);
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }
                catch(PDOException $e){
                  echo "Error: " . $e->getMessage();
                }
                foreach($result as $readrow) {
                ?>
                <tr>
                  <td class="tech-id"><?php echo $readrow['fld_customer_num']; ?></td>
                  <td><strong><?php echo $readrow['fld_customer_fname']." ".$readrow['fld_customer_lname']; ?></strong></td>
                  <td><?php echo $readrow['fld_customer_gender']; ?></td>
                  <td><?php echo $readrow['fld_customer_phone']; ?></td>
                  <td class="tier-cell"><?php echo $readrow['fld_customer_level']; ?></td>
                  <td>
                    <a href="customers.php?edit=<?php echo $readrow['fld_customer_num']; ?>" class="btn btn-tech btn-xs" style="border-color:#22c55e;color:#22c55e;" role="button">Edit</a>
                    <a href="customers.php?delete=<?php echo $readrow['fld_customer_num']; ?>" onclick="return confirm('Are you sure to delete this collector?');" class="btn btn-tech btn-xs" style="border-color:#ef4444;color:#ef4444;" role="button">Delete</a>
                  </td>
                </tr>
                <?php } ?>
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
                $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_customers_a210683");
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
                <li><a href="customers.php?page=<?php echo $page-1; ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
              <?php }
              
              for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                  echo "<li class=\"active\"><a href=\"customers.php?page=$i\">$i</a></li>";
                } else {
                  echo "<li><a href=\"customers.php?page=$i\">$i</a></li>";
                }
              }
              
              if ($page == $total_pages || $total_pages == 0) { ?>
                <li class="disabled"><span aria-hidden="true">&raquo;</span></li>
              <?php } else { ?>
                <li><a href="customers.php?page=<?php echo $page+1; ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
              <?php } ?>
            </ul>
          </nav>
        </div>
      </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript">
      $(document).ready(function() {
        $('.tier-cell').each(function() {
          var tierText = $(this).text().trim();
          var badgeStyles = "";

          if(tierText === "Pro Builder") {
            badgeStyles = "border-color: #ffd700; color: #eab308;"; // Gold for Pro Builders
          } else if(tierText === "Veteran") {
            badgeStyles = "border-color: #3b82f6; color: #3b82f6;"; // Cyan for Veterans
          } else {
            badgeStyles = "border-color: #94a3b8; color: #94a3b8;"; // Muted Grey for Newbies
          }

          $(this).html('<span class="brutalist-badge" style="' + badgeStyles + '">' + tierText + '</span>');
        });
      });
    </script>
</body>
</html>