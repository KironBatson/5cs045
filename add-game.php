<?php

// Connect to database
include("dbconnect.php");

// Read values from the form, uses escape string to prevent SQL injection and allow use of special characters
$game_name = $mysqli->real_escape_string(trim($_POST['GameName']));
$game_description = $mysqli->real_escape_string(trim($_POST['GameDescription']));
$game_release_date = $_POST['DateReleased'];
$game_rating = $_POST['GameRating'];
$game_genre = $_POST['GameGenre'] ? (int)$_POST['GameGenre'] : "NULL";

// Handle thumbnail upload
$thumbnail_path = 'uploads/placeholder.jpg'; // default placeholder
if (isset($_FILES['GameThumbnail']) && $_FILES['GameThumbnail']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['GameThumbnail']['tmp_name'];
    $fileName = basename($_FILES['GameThumbnail']['name']);
    $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", $fileName); // sanitize and add timestamp
    $destPath = 'uploads/' . $fileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $thumbnail_path = $destPath; // successful upload
    } else {
        echo "<h4>Thumbnail upload failed, using placeholder</h4>";
    }
}

// Build SQL statement
$sql = "INSERT INTO videogames(game_name, game_description, released_date, rating, genre_id, thumbnail_path)
        VALUES('{$game_name}', '{$game_description}', '{$game_release_date}', '{$game_rating}', $game_genre, '{$thumbnail_path}')";

// Run SQL statement and report errors
if(!$mysqli -> query($sql)) {
echo("<h4>SQL error description: " . $mysqli -> error . "</h4>");
}

// Redirect to list
header("location: frontpage.php");
?>