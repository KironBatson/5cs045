<?php
include("dbconnect.php"); // your mysqli connection

if (!isset($_GET['name'])) {
    echo json_encode(["exists" => false]);
    exit;
}

$name = $mysqli->real_escape_string(trim($_GET['name']));

$sql = "SELECT * FROM videogames WHERE LOWER(game_name) = LOWER('$name')";
$result = $mysqli->query($sql);

echo json_encode(["exists" => $result->num_rows > 0]);
?>
