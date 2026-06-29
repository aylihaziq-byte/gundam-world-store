<?php
session_start();

// If the session variable is not set, redirect to login page
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}
?>