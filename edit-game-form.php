<?php include("dbconnect.php");

// Read updated values from the form, uses escape string to prevent SQL injection and allow use of special characters
$game_id = $_POST['game_id'];
$game_name = $mysqli->real_escape_string(trim($_POST['GameName']));
$game_description = $mysqli->real_escape_string(trim($_POST['GameDescription']));
$game_release_date = $_POST['DateReleased'];
$game_rating = $_POST['GameRating'];
$game_genre = $_POST['GameGenre'] ? (int)$_POST['GameGenre'] : "NULL";

// Handle thumbnail upload (optional)
$thumbnail_sql = "";
if (isset($_FILES['GameThumbnail']) && $_FILES['GameThumbnail']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['GameThumbnail']['tmp_name'];
    $fileName = basename($_FILES['GameThumbnail']['name']);
    $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", $fileName); // sanitize and add timestamp
    $destPath = 'uploads/' . $fileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $thumbnail_sql = ", thumbnail_path = '{$destPath}'";
    } else {
        echo "<h4>Thumbnail upload failed, keeping existing thumbnail</h4>";
    }
}

// Build SQL UPDATE statement
$sql = "UPDATE videogames 
        SET game_name = '{$game_name}',
            game_description = '{$game_description}',
            released_date = '{$game_release_date}',
            rating = '{$game_rating}',
            genre_id = $game_genre
            {$thumbnail_sql}
        WHERE game_id = {$game_id}";

// Run SQL statement and report errors
if(!$mysqli -> query($sql)) {
    echo("<h4>SQL error description: " . $mysqli -> error . "</h4>");
    exit();
}

// Redirect back to front page
header("location: frontpage.php");

?>
