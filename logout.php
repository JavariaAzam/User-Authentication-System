<?php
// logout.php — destroy session and go back to login
session_start();
session_unset();
session_destroy();
header('Location: login.php');
exit;
