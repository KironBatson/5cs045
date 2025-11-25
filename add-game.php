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
$thumbnail_path = 'uploads/placeholder.jpg'; // default

if (isset($_FILES['GameThumbnail']) && $_FILES['GameThumbnail']['error'] === UPLOAD_ERR_OK) {

    $fileTmpPath = $_FILES['GameThumbnail']['tmp_name'];
    $fileName = basename($_FILES['GameThumbnail']['name']);
    $fileSize = $_FILES['GameThumbnail']['size'];
    $fileType = mime_content_type($fileTmpPath);

    // Allowed types
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($fileType, $allowedTypes)) {
        echo "<h4>Invalid file type. Only JPG, PNG, GIF allowed. Using placeholder.</h4>";
    } elseif ($fileSize > 2 * 1024 * 1024) { // 2MB max
        echo "<h4>File too large (max 2MB). Using placeholder.</h4>";
    } else {
        // Sanitize filename
        $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", $fileName);
        $destPath = 'uploads/' . $fileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {

            // Resize image to 300x400 for consistency
            list($originalWidth, $originalHeight, $type) = getimagesize($destPath);
            $newWidth = 300;
            $newHeight = 400;
            $dstImg = imagecreatetruecolor($newWidth, $newHeight);

            // Create source image
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $srcImg = imagecreatefromjpeg($destPath);
                    break;
                case IMAGETYPE_PNG:
                    $srcImg = imagecreatefrompng($destPath);
                    imagealphablending($dstImg, false);
                    imagesavealpha($dstImg, true);
                    break;
                case IMAGETYPE_GIF:
                    $srcImg = imagecreatefromgif($destPath);
                    break;
                default:
                    $srcImg = null;
            }

            if ($srcImg) {
                imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

                // Save resized image
                switch ($type) {
                    case IMAGETYPE_JPEG: imagejpeg($dstImg, $destPath, 90); break;
                    case IMAGETYPE_PNG: imagepng($dstImg, $destPath); break;
                    case IMAGETYPE_GIF: imagegif($dstImg, $destPath); break;
                }

                imagedestroy($srcImg);
                imagedestroy($dstImg);
            }

            $thumbnail_path = $destPath; // success
        } else {
            echo "<h4>Thumbnail upload failed, using placeholder</h4>";
        }
    }
}

// Create SQL statement to insert new game into database
$sql = "INSERT INTO videogames(game_name, game_description, released_date, rating, genre_id, thumbnail_path)
        VALUES('{$game_name}', '{$game_description}', '{$game_release_date}', '{$game_rating}', $game_genre, '{$thumbnail_path}')";

// Run SQL statement and report errors
if(!$mysqli -> query($sql)) {
echo("<h4>SQL error description: " . $mysqli -> error . "</h4>");
}

// Redirect to list
header("location: frontpage.php");
?>