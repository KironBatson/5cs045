<!--include templates header and navigation bar, also this is how to make a note outside of php tags-->
<?php 
include("templates/header.php");
include("templates/nav.php");
?>
    
<h1>Kiron's Games Archive</h1> <!--main heading-->

<?php
include("dbconnect.php");
// Run SQL query, * does all field names, its better optimised to chose only what's needed - change it, select field from db order by field
$sql = "SELECT * FROM videogames ORDER BY game_name";
//run query, pass it 2 things, connection used declared in dbconnect.php, and which query being run - declared above
$results = mysqli_query($mysqli, $sql);
?>

<table class="table table-striped"> 
	<?php while($a_row = mysqli_fetch_assoc($results)):?>
		<tr>
		<td><a href="game-details.php?id=<?=$a_row['game_id']?>"><?=$a_row['game_name']?></a></td>
		<td><?=$a_row['released_date']?></td>
		<td><?=$a_row['rating']?></td>
		<td><a class="btn btn-outline-warning" href="edit-game.php?id=<?=$a_row['game_id']?>" role="button">Edit</a></td>
		<td><a class="btn btn-outline-danger" href="delete-game.php?id=<?=$a_row['game_id']?>" role="button">Delete</a></td>
		</tr>
	<?php endwhile;?>
</table>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addgame" >Add new game</button>

<?php include("add-game-form.php");?>
<?php include("templates/footer.php");?>