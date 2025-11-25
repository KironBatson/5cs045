<?php 
include("templates/header.php");
include("templates/nav.php");
include("dbconnect.php");

// Read the game_id from the URL
$game_id = $_GET['id'];

// Query the database for this game's details
$sql = "SELECT * FROM videogames WHERE game_id = {$game_id}";
$results = mysqli_query($mysqli, $sql);

// Convert result into an associative array
$game = mysqli_fetch_assoc($results);
?>

<h1>Edit <?=$game['game_name']?></h1>

<form action="edit-game-form.php" method="post">

<input type="hidden" name="game_id" value="<?=$game['game_id']?>">

<div class="mb-3">
    <label for="GameName" class="form-label">Game name</label>
    <input type="text" class="form-control" id="GameName" 
        name="GameName" value="<?=$game['game_name']?>">
</div>

<div class="mb-3">
    <label for="GameDescription" class="form-label">Description</label>
    <textarea class="form-control" id="GameDescription" 
        name="GameDescription" rows="5"><?=$game['game_description']?></textarea>
</div>

<div class="mb-3">
    <label for="DateReleased" class="form-label">Date released</label>
    <input type="date" class="form-control" id="DateReleased" 
        name="DateReleased" value="<?=$game['released_date']?>">
</div>

<div class="mb-3">
    <label for="GameRating" class="form-label">Rating</label>
    <input type="number" class="form-control" id="GameRating" 
        name="GameRating" value="<?=$game['rating']?>">
</div>

<div class="mb-3">
    <label for="GameGenre" class="form-label">Genre</label>
    <select name="GameGenre" id="GameGenre" class="form-select">
        <option value="">Select a genre</option>
        <?php
        $genreResults = $mysqli->query("SELECT * FROM genres ORDER BY genre_name");
        while ($genre = $genreResults->fetch_assoc()) {
            $selected = (isset($game['genre_id']) && $game['genre_id'] == $genre['genre_id']) ? "selected" : "";
            echo "<option value='{$genre['genre_id']}' $selected>{$genre['genre_name']}</option>";
        }
        ?>
    </select>
</div>


<input type="submit" class="btn btn-success" value="Save Changes"> <a href="frontpage.php" class="btn btn-danger">Cancel</a>

</form>
<script>
// Elements
const gameNameInput = document.getElementById("GameName");
const gameDescInput = document.getElementById("GameDescription");
const gameRatingInput = document.getElementById("GameRating");
const gameDateInput = document.getElementById("DateReleased");
const submitBtn = document.querySelector('input[type="submit"]');

// Message spans under each input (create if not already in HTML)
const nameMsg = document.createElement('div');
nameMsg.className = 'text-danger mt-1';
gameNameInput.parentNode.appendChild(nameMsg);

const descMsg = document.createElement('div');
descMsg.className = 'text-danger mt-1';
gameDescInput.parentNode.appendChild(descMsg);

const ratingMsg = document.createElement('div');
ratingMsg.className = 'text-danger mt-1';
gameRatingInput.parentNode.appendChild(ratingMsg);

const dateMsg = document.createElement('div');
dateMsg.className = 'text-danger mt-1';
gameDateInput.parentNode.appendChild(dateMsg);

// Disable submit initially if fields are blank
submitBtn.disabled = false;

// Validate fields function
function validateForm() {
    let valid = true;

    const name = gameNameInput.value.trim();
    const desc = gameDescInput.value.trim();
    const rating = gameRatingInput.value.trim();
    const date = gameDateInput.value;

    // Name
    if (name === '') {
        nameMsg.textContent = "Name cannot be blank.";
        valid = false;
    } else {
        nameMsg.textContent = '';
    }

    // Description
    if (desc === '') {
        descMsg.textContent = "Description cannot be blank.";
        valid = false;
    } else {
        descMsg.textContent = '';
    }

    // Rating
    if (rating === '') {
        ratingMsg.textContent = "Rating cannot be blank.";
        valid = false;
    } else {
        ratingMsg.textContent = '';
    }

    // Release date
    if (date === '') {
        dateMsg.textContent = "Release date cannot be blank.";
        valid = false;
    } else {
        dateMsg.textContent = '';
    }

    // Enable submit if all fields are valid
    submitBtn.disabled = !valid;
}

// AJAX duplicate name check (ignore current game_id)
const currentGameId = <?= $game['game_id'] ?>;

gameNameInput.addEventListener('input', function() {
    const name = this.value.trim();
    
    if (name.length === 0) {
        validateForm();
        return;
    }

    fetch("check-game.php?name=" + encodeURIComponent(name) + "&exclude=" + currentGameId)
        .then(res => res.json())
        .then(data => {
            if (data.exists) {
                nameMsg.textContent = "This game name already exists.";
                submitBtn.disabled = true;
            } else {
                validateForm();
            }
        });
});

// Validate other fields on input
[gameDescInput, gameRatingInput, gameDateInput].forEach(el => {
    el.addEventListener('input', validateForm);
});
</script>
<?php include("templates/footer.php");?>
