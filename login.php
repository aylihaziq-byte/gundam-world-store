<?php
session_start();
include_once 'database.php';

$error = '';

if (isset($_POST['login'])) {
    // Check if Cloudflare CAPTCHA was completed on the frontend
    if (empty($_POST['cf-turnstile-response'])) {
        $error = "Please complete the CAPTCHA validation.";
    } else {
        // --- SECURE BACKEND VERIFICATION WITH CLOUDFLARE ---
        $turnstile_secret = "0x4AAAAAADp8rHXQ4hPppmIYzojy_gk74Rw"; 
        $turnstile_response = $_POST['cf-turnstile-response'];
        $user_ip = $_SERVER['REMOTE_ADDR'];

        $verify_url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
        $data = [
            'secret' => $turnstile_secret,
            'response' => $turnstile_response,
            'remoteip' => $user_ip
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context  = stream_context_create($options);
        $result = file_get_contents($verify_url, false, $context);
        $turnstile_data = json_decode($result);

        // If Cloudflare says they failed the check
        if (!$turnstile_data->success) {
            $error = "Security validation failed. Please try again.";
        } else {
            // --- VALID HUMAN DETECTED: PROCEED WITH DATABASE LOGIN ---
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $staff_id = $_POST['staffid'];
                $staff_pass = $_POST['password'];
                // Hash the input password to compare with the database
                $hashed_pass = md5($staff_pass); 

                $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a210683 WHERE fld_staff_num = :sid AND fld_staff_password = :pass");
                $stmt->bindParam(':sid', $staff_id, PDO::PARAM_STR);
                $stmt->bindParam(':pass', $hashed_pass, PDO::PARAM_STR);
                $stmt->execute();

                $readrow = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($readrow) {
                    // Login successful, set session variables
                    $_SESSION['staff_id'] = $readrow['fld_staff_num'];
                    $_SESSION['staff_name'] = $readrow['fld_staff_fname'] . " " . $readrow['fld_staff_lname'];
                    $_SESSION['staff_level'] = $readrow['fld_staff_level']; // Admin or Normal Staff
                    
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "ACCESS DENIED: Invalid Staff ID or Password.";
                }
            } catch(PDOException $e) {
                $error = "Error: " . $e->getMessage();
            }
            $conn = null;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gundam World Store : System Login</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

  <style type="text/css">
    body {
      background-color: #0f1115;
      color: #e2e8f0;
      font-family: 'SF Mono', Consolas, monospace;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }
    .bento-panel {
      background-color: #161920;
      border: 2px solid #2d3139;
      padding: 40px;
      position: relative;
      width: 100%;
      max-width: 400px;
    }
    .bento-panel::before {
      content: ""; position: absolute; top: -2px; left: -2px; width: 12px; height: 12px;
      border-top: 2px solid #a855f7; border-left: 2px solid #a855f7;
    }
    .bento-panel::after {
      content: ""; position: absolute; bottom: -2px; right: -2px; width: 12px; height: 12px;
      border-bottom: 2px solid #a855f7; border-right: 2px solid #a855f7;
    }
    .form-control {
      background-color: #05070a !important;
      border: 1px solid #2d3139 !important;
      color: #00ffcc !important;
      border-radius: 0px !important;
    }
    .form-control:focus { border-color: #00ffcc !important; box-shadow: none !important; }
    .btn-tech-submit {
      background: transparent; border: 1px solid #00ffcc; color: #00ffcc;
      border-radius: 0px; text-transform: uppercase; letter-spacing: 1px;
      width: 100%; transition: all 0.2s ease;
    }
    .btn-tech-submit:hover { background-color: #00ffcc; color: #0f1115; }
    .error-msg {
      color: #ef4444; font-size: 12px; border: 1px dashed #ef4444; padding: 10px; margin-bottom: 15px; text-align: center;
    }
  </style>
</head>
<body>

  <div class="bento-panel">
    <div class="text-center" style="margin-bottom: 20px;">
      <h2 style="margin: 0; color: #a855f7; font-weight: bold;">// SYS_LOGIN</h2>
      <p style="font-size: 11px; color: #94a3b8;">Gundam World Store OS</p>
    </div>

    <?php if ($error != '') { echo "<div class='error-msg'>$error</div>"; } ?>

    <form action="login.php" method="post">
      <div class="form-group">
        <label style="color: #94a3b8; font-size: 11px; text-transform: uppercase;">Staff ID</label>
        <input name="staffid" type="text" class="form-control" placeholder="e.g. S001" required>
      </div>
      <div class="form-group">
        <label style="color: #94a3b8; font-size: 11px; text-transform: uppercase;">Password</label>
        <input name="password" type="password" class="form-control" placeholder="••••••••" required>
      </div>
      
      <div class="form-group text-center">
        <!-- YOUR NEW LIVE SITE KEY HAS BEEN PLACED HERE -->
        <div class="cf-turnstile" data-sitekey="0x4AAAAAADp8rPH8AIrpVQTu" data-theme="dark"></div>
      </div>

      <button type="submit" name="login" class="btn btn-tech-submit" style="padding: 10px;">Authenticate <span class="glyphicon glyphicon-log-in"></span></button>
    </form>
  </div>

</body>
</html>