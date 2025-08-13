<?php
// db.php — creates a single mysqli connection you can reuse everywhere
$DB_HOST = 'localhost';
$DB_USER = 'root';      // change if needed
$DB_PASS = 'Javairia2005';          // change if needed
$DB_NAME = 'auth_app';  // must match schema.sql

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    error_log('MySQL connect error: ' . $mysqli->connect_error);
    die('Database connection failed.');
}
$mysqli->set_charset('utf8mb4');
