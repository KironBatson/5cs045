<!--include templates header and navigation bar, also this is how to make a note outside of php tags-->
<?php include("templates/header.php");
include("templates/nav.php");?>
    
<h1>Games Archive</h1> <!--main heading-->

<?php
include("dbconnect.php");
// Run SQL query, * does all field names, its better optimised to chose only what's needed - change it, select field from db order by field
$sql = "SELECT * FROM videogames ORDER BY released_date";
//run query, pass it 2 things, connection used declared in dbconnect.php, and which query being run - declared above
$results = mysqli_query($mysqli, $sql);
?>

<table class="table table-striped"> 
	<?php while($a_row = mysqli_fetch_assoc($results)):?>
		<tr>
		<td><a href="game-details.php?id=<?=$a_row['game_id']?>"><?=$a_row['game_name']?></a></td>
		<td><?=$a_row['released_date']?></td>
		<td><?=$a_row['rating']?></td>
		</tr>
	<?php endwhile;?>
</table>

<?php include("templates/footer.php");?>