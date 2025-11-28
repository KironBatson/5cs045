<?php
session_start();
include("dbconnect.php");

// Only admin can delete
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: frontpage.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: frontpage.php");
    exit();
}

$game_id = (int)$_GET['id'];

// Prepared statement prevents SQL injection
$stmt = $mysqli->prepare("DELETE FROM videogames WHERE game_id = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();

header("Location: frontpage.php");
exit();
?>
