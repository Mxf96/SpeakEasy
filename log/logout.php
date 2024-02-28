<?php
require_once '../includes/inc-db-connect.php';
unset($_SESSION['user']);
session_destroy();

header("Location: /index.php");
exit;