<?php include("dbconnect.php");

// Read updated values from the form, uses escape string to prevent SQL injection and allow use of special characters
$game_id = $_POST['game_id'];
$game_name = $mysqli->real_escape_string(trim($_POST['GameName']));
$game_description = $mysqli->real_escape_string(trim($_POST['GameDescription']));
$game_release_date = $_POST['DateReleased'];
$game_rating = $_POST['GameRating'];
$game_genre = $_POST['GameGenre'] ? (int)$_POST['GameGenre'] : "NULL";

// Keep current thumbnail as default
$thumbnail_path = null;

// Handle (optional) thumbnail upload
if (isset($_FILES['GameThumbnail']) && $_FILES['GameThumbnail']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['GameThumbnail']['tmp_name'];
    $fileName = basename($_FILES['GameThumbnail']['name']);
    $fileSize = $_FILES['GameThumbnail']['size'];
    $fileType = mime_content_type($fileTmpPath);
    $allowedTypes = ['image/jpeg','image/png','image/gif'];

    if (!in_array($fileType, $allowedTypes)) {
        echo "<h4>Invalid file type. Keeping existing thumbnail.</h4>";
    } elseif ($fileSize > 2*1024*1024) {
        echo "<h4>File too large (max 2MB). Keeping existing thumbnail.</h4>";
    } else {
        $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/","",$fileName);
        $destPath = 'uploads/' . $fileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Resize for consistency
            list($origW,$origH,$type) = getimagesize($destPath);
            $newW = 300; $newH = 400;
            $dstImg = imagecreatetruecolor($newW,$newH);

            switch($type){
                case IMAGETYPE_JPEG: $srcImg = imagecreatefromjpeg($destPath); break;
                case IMAGETYPE_PNG: $srcImg = imagecreatefrompng($destPath); imagealphablending($dstImg,false); imagesavealpha($dstImg,true); break;
                case IMAGETYPE_GIF: $srcImg = imagecreatefromgif($destPath); break;
                default: $srcImg = null;
            }

            if ($srcImg) {
                imagecopyresampled($dstImg, $srcImg,0,0,0,0,$newW,$newH,$origW,$origH);
                switch($type){
                    case IMAGETYPE_JPEG: imagejpeg($dstImg,$destPath,90); break;
                    case IMAGETYPE_PNG: imagepng($dstImg,$destPath); break;
                    case IMAGETYPE_GIF: imagegif($dstImg,$destPath); break;
                }
                imagedestroy($srcImg); imagedestroy($dstImg);
            }

            $thumbnail_path = $destPath;
        } else {
            echo "<h4>Thumbnail upload failed, keeping existing thumbnail</h4>";
        }
    }
}

// Prepare SQL
$thumbnail_sql = $thumbnail_path ? ", thumbnail_path = '{$thumbnail_path}'" : "";

// Create SQL statement to update game details in database
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
