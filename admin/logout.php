<?php
require_once __DIR__ . '/../php/config.php';
session_destroy();
header('Location: ../php/login.php');
exit;
?>
