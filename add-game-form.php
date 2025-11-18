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
<div id="gameExistsMsg" class="text-danger mt-1" style="display:none;"></div>
</div>
<div class="mb-3">
<label for="GameDescription" class="form-label">Description</label>
<textarea class="form-control" id="GameDescription" name="GameDescription" rows="5"></textarea>
</div>
<div class="mb-3">
<label for="DateReleased" class="form-label">Date released</label>
<input type="date" class="form-control" id="DateReleased" name="DateReleased">
</div>
<div class="mb-3">
<label for="GameRating" class="form-label">Rating</label>
<input type="number" class="form-control" id="GameRating" name="GameRating">
</div>
<input type="submit" id="submitBtn" class="btn btn-primary" value="Add game">
</form>
</div>
      </div>
    </div>
  </div>
</div>
<script>
// AJAX check for duplicate game names
document.getElementById("GameName").addEventListener("input", function () {
    const gameName = this.value.trim();
    const msgBox = document.getElementById("gameExistsMsg");
    const submitBtn = document.getElementById("submitBtn");

    if (gameName.length < 2) {
        msgBox.style.display = "none";
        submitBtn.disabled = false;
        return;
    }

    fetch("check-game.php?name=" + encodeURIComponent(gameName))
        .then(res => res.json())
        .then(data => {
            if (data.exists) {
                msgBox.textContent = "This game is already in the system.";
                msgBox.style.display = "block";
                submitBtn.disabled = true; // prevent form submission
            } else {
                msgBox.style.display = "none";
                submitBtn.disabled = false;
            }
        });
});
</script>
<?php include("templates/footer.php");?>