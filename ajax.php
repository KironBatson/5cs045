<?php include("dbconnect.php");
// Is a keyword provided in the URL?
if(isset($_GET['search']))
$sql = "SELECT * FROM videogames WHERE game_name LIKE '%{$_GET['search']}%'
ORDER BY game_name";
else
$sql = "SELECT * FROM videogames ORDER BY game_name";
// Fetch all record, convert to JSON and return
$results = $mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);
print(json_encode($results));
?>