<?php
include("dbconnect.php");

// Prepare default SQL
if (isset($_GET['search']) && strlen($_GET['search']) > 0) {

    $search = $_GET['search'];

    // Prepared statement prevents SQLi
    $stmt = $mysqli->prepare("
        SELECT game_id, game_name
        FROM videogames
        WHERE game_name LIKE CONCAT('%', ?, '%')
        ORDER BY game_name
    ");

    $stmt->bind_param("s", $search);
    $stmt->execute();
    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

} else {
    // Fallback: still safe but no user input
    $stmt = $mysqli->prepare("
        SELECT game_id, game_name
        FROM videogames
        ORDER BY game_name
    ");

    $stmt->execute();
    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

header('Content-Type: application/json');
echo json_encode($results);
?>
