<?php include("templates/header.php");?>

<div class="modal fade" id="addgame" tabindex="-1" aria-labelledby="addgamelabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addgamelabel">Add a new game!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="add-game.php" method="post">
<div class="mb-3">
  <label for="GameName" class="form-label">Game name</label>
  <input type="text" class="form-control" id="GameName" name="GameName">
  <div id="gameNameMsg" class="text-danger mt-1" style="display:none;"></div>
</div>

<div class="mb-3">
  <label for="GameDescription" class="form-label">Description</label>
  <textarea class="form-control" id="GameDescription" name="GameDescription" rows="5"></textarea>
  <div id="gameDescMsg" class="text-danger mt-1" style="display:none;"></div>
</div>

<div class="mb-3">
  <label for="DateReleased" class="form-label">Date released</label>
  <input type="date" class="form-control" id="DateReleased" name="DateReleased">
  <div id="gameDateMsg" class="text-danger mt-1" style="display:none;"></div>
</div>

<div class="mb-3">
  <label for="GameRating" class="form-label">Rating</label>
  <input type="number" class="form-control" id="GameRating" name="GameRating">
  <div id="gameRatingMsg" class="text-danger mt-1" style="display:none;"></div>
</div>

<input type="submit" class="btn btn-secondary" id="submitBtn" value="Add game" disabled>

<script>
const gameNameInput = document.getElementById("GameName");
const gameDescInput = document.getElementById("GameDescription");
const gameRatingInput = document.getElementById("GameRating");
const gameDateInput = document.getElementById("DateReleased");
const submitBtn = document.getElementById("submitBtn");

// individual message boxes
const gameNameMsg = document.getElementById("gameNameMsg");
const gameDescMsg = document.getElementById("gameDescMsg");
const gameRatingMsg = document.getElementById("gameRatingMsg");
const gameDateMsg = document.getElementById("gameDateMsg");

function validateForm() {
    let valid = true;

    // ---- Game Name ----
    const name = gameNameInput.value.trim();
    if (name === "") {
        gameNameMsg.textContent = "Name cannot be blank.";
        gameNameMsg.style.display = "block";
        valid = false;
    } else {
        gameNameMsg.style.display = "none";
    }

    // ---- Description ----
    const desc = gameDescInput.value.trim();
    if (desc === "") {
        gameDescMsg.textContent = "Description cannot be blank.";
        gameDescMsg.style.display = "block";
        valid = false;
    } else {
        gameDescMsg.style.display = "none";
    }

    // ---- Rating ----
    const rating = gameRatingInput.value.trim();
    if (rating === "") {
        gameRatingMsg.textContent = "Rating cannot be blank.";
        gameRatingMsg.style.display = "block";
        valid = false;
    } else {
        gameRatingMsg.style.display = "none";
    }

    // ---- Release Date ----
    const date = gameDateInput.value.trim();
    if (date === "") {
        gameDateMsg.textContent = "Release date cannot be blank.";
        gameDateMsg.style.display = "block";
        valid = false;
    } else {
        gameDateMsg.style.display = "none";
    }

    // ---- AJAX check for duplicate game name ----
    if (name.length > 0) {
        fetch("check-game.php?name=" + encodeURIComponent(name))
            .then(res => res.json())
            .then(data => {
                if (data.exists) {
                    gameNameMsg.textContent = "This game is already in the system.";
                    gameNameMsg.style.display = "block";
                    submitBtn.disabled = true;
                    submitBtn.classList.remove("btn-primary");
                    submitBtn.classList.add("btn-secondary");
                } else {
                    if (valid) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove("btn-secondary");
                        submitBtn.classList.add("btn-primary");
                    }
                }
            });
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.remove("btn-primary");
        submitBtn.classList.add("btn-secondary");
    }

    if (!valid) {
        submitBtn.disabled = true;
        submitBtn.classList.remove("btn-primary");
        submitBtn.classList.add("btn-secondary");
    }
}

// Run validation on input change
gameNameInput.addEventListener("input", validateForm);
gameDescInput.addEventListener("input", validateForm);
gameRatingInput.addEventListener("input", validateForm);
gameDateInput.addEventListener("input", validateForm);
</script>
<?php include("templates/footer.php");?>