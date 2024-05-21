<?php
$DBhostname = "localhost";
$DB_user = "aviles";
$DB_pass = "jerry8696";
$DB_name = "aviles_";

$conn = new mysqli($DBhostname, $DB_user, $DB_pass, $DB_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
