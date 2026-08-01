<?php
require_once dirname(__DIR__) . '/includes/db.php';

if(isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: ../login.php");
    exit;
}
?>
