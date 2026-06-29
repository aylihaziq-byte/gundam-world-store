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
  <title>Gundam World Store : Dashboard</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <style type="text/css">
    /* ==========================================================================
       THEME VARIABLES
       ========================================================================== */
    :root {
      --bg-main: #0f1115;
      --bg-panel: #161920;
      --bg-stripes-odd: #1c212c;
      --border-color: #2d3139;
      --text-main: #e2e8f0;
      --text-muted: #94a3b8;
      --text-bold: #ffffff;
      --terminal-bg: #05070a;
      --terminal-text: #00ffcc;
    }

    [data-theme="light"] {
      --bg-main: #f1f5f9;
      --bg-panel: #ffffff;
      --bg-stripes-odd: #f8fafc;
      --border-color: #cbd5e1;
      --text-main: #1e293b !important; 
      --text-muted: #334155 !important; 
      --text-bold: #0f172a !important; 
      --terminal-bg: #f8fafc !important;
      --terminal-text: #0f172a !important;
    }

    body {
      background-color: var(--bg-main) !important;
      color: var(--text-main) !important;
      font-family: 'SF Mono', SFMono-Regular, Consolas, 'Liberation Mono', Menlo, monospace;
      transition: background-color 0.3s ease, color 0.3s ease;
    }
    .container {
      padding-top: 40px;
    }
    
    /* Bento-Box / Wireframe Panel Container */
    .bento-panel {
      background-color: var(--bg-panel) !important;
      border: 2px solid var(--border-color) !important;
      border-radius: 0px !important;
      padding: 30px;
      margin-bottom: 25px;
      position: relative;
    }
    .bento-panel::before {
      content: ""; position: absolute; top: -2px; left: -2px; width: 12px; height: 12px;
      border-top: 2px solid #a855f7; border-left: 2px solid #a855f7;
    }
    .bento-panel::after {
      content: ""; position: absolute; bottom: -2px; right: -2px; width: 12px; height: 12px;
      border-bottom: 2px solid #a855f7; border-right: 2px solid #a855f7;
    }

    /* Welcome Banner Title */
    .welcome-header h1 {
      font-weight: 800;
      font-size: 28px;
      letter-spacing: 2px;
      margin-top: 0;
      margin-bottom: 10px;
      color: var(--text-bold) !important;
    }
    .welcome-header p {
      color: var(--text-muted) !important;
      font-size: 13px;
      letter-spacing: 1px;
      margin-bottom: 0;
    }

    /* System Status Line */
    .status-terminal {
      background-color: var(--terminal-bg) !important;
      border: 1px solid var(--border-color) !important;
      color: var(--terminal-text) !important;
      font-size: 12px;
      padding: 15px;
      line-height: 1.6;
      transition: background-color 0.3s ease, color 0.3s ease;
    }
    .pulse-indicator {
      display: inline-block;
      width: 8px;
      height: 8px;
      background-color: #22c55e;
      border-radius: 50%;
      margin-right: 6px;
    }
  </style>
</head>
<body>

    <?php include_once 'nav_bar.php'; ?>

    <div class="container-fluid" style="max-width: 1400px; margin: 0 auto;">
      
      <div class="row">
        <div class="col-xs-12">
          <div class="bento-panel welcome-header text-center" style="padding: 50px 20px; background-image: linear-gradient(to right, transparent 49%, var(--border-color) 49%, var(--border-color) 51%, transparent 51%), linear-gradient(to bottom, transparent 49%, var(--border-color) 49%, var(--border-color) 51%, transparent 51%); background-size: 30px 30px;">
            
            <div style="margin-bottom: 25px;">
              <img src="logo.png" class="img-responsive" style="max-height: 180px; width: auto; margin: 0 auto; filter: drop-shadow(0px 0px 12px rgba(168, 85, 247, 0.4));" alt="Gundam World Store Large Banner Logo">
            </div>
            
            <div style="max-width: 750px; margin: 0 auto; background-color: var(--bg-panel); padding: 12px 20px; display: inline-block; border: 1px dashed var(--border-color);">
              <h1 style="font-size: 18px; margin: 0 0 6px 0; font-weight: bold; letter-spacing: 2px;">// GUNDAM_WORLD_STORE_OS</h1>
              <p style="margin: 0; font-size: 12px; color: var(--text-muted);">Operational protocol active. Navigate using the system menu above.</p>
            </div>

          </div>
        </div>
      </div>

      <div class="row" style="margin-bottom: 40px;">
        <div class="col-xs-12">
          <div class="bento-panel">
            <div class="page-header" style="margin-bottom: 15px; padding-bottom: 8px; border-bottom: 1px dashed var(--border-color);">
              <h4 style="margin:0; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase; color: var(--text-bold);"><span class="pulse-indicator"></span> System_Diagnostics_Console</h4>
            </div>
            <pre class="status-terminal">
> Host connection established: <?php echo $servername; ?>

> Active database targeted: Database '<?php echo $dbname; ?>' loaded.
> Authenticated Operator: <?php echo $_SESSION['staff_name']; ?> [Clearance: <?php echo $_SESSION['staff_level']; ?>]
> Form Validation Subsystems: ONLINE [HTML5 Client-Side Context Intercept Active]
> UI Status: READY [Adaptive Light/Dark Theme Variables Registered]</pre>
          </div>
        </div>
      </div>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>