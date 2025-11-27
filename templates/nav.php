<?php include("dbconnect.php"); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top w-100">
  <div class="container-fluid">
    <a class="navbar-brand" href="frontpage.php">Navigation</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="frontpage.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact-us.php">Contact Us</a>
        </li>
        <!-- login/logout -->
        <?php session_start(); // make sure session is started ?>
        <li class="nav-item">
        <?php if(isset($_SESSION['user_id'])): ?>
        <a class="nav-link" href="logout.php">Logout (<?=htmlspecialchars($_SESSION['username'])?>)</a>
        <?php else: ?>
          <a class="nav-link" href="login.php">Login</a>
        <?php endif; ?>
      </li>
      </ul>

      <!-- Search Form -->
      <form class="d-flex align-items-center" method="GET" action="search-games.php">
        <!-- Game Name (AJAX autocomplete) -->
        <div class="position-relative me-2">
          <input 
              type="text" 
              id="navSearchBox" 
              name="keywords"
              class="form-control" 
              placeholder="Search games..." 
              autocomplete="off"
              style="width: 200px;">
          
          <div id="navSearchResults" 
               class="list-group position-absolute w-100" 
               style="z-index: 2000;">
          </div>
        </div>

        <!-- Genre Dropdown -->
        <select class="form-select me-2" name="genre">
          <option value="">All Genres</option>
          <?php 
            $genres = $mysqli->query("SELECT * FROM genres ORDER BY genre_name");
            while($row = $genres->fetch_assoc()):
          ?>
            <option value="<?=$row['genre_id']?>"><?=$row['genre_name']?></option>
          <?php endwhile;?>
        </select>

        <!-- Release Year Dropdown -->
        <select class="form-select me-2" name="release_year">
          <option value="">Any Year</option>
          <?php 
            $years = $mysqli->query("SELECT DISTINCT YEAR(released_date) AS year FROM videogames ORDER BY year DESC");
            while($y = $years->fetch_assoc()):
          ?>
            <option value="<?=$y['year']?>"><?=$y['year']?></option>
          <?php endwhile;?>
        </select>

        <!-- Search Button -->
        <button class="btn btn-primary" type="submit">Search</button>
      </form>
      <!-- End Search Form -->

    </div>
  </div>
</nav>

<!-- AJAX Script for live autocomplete -->
<script>
document.getElementById("navSearchBox").addEventListener("keyup", function () {
    let keywords = this.value;

    if (keywords.length === 0) {
        document.getElementById("navSearchResults").style.display = "none";
        document.getElementById("navSearchResults").innerHTML = "";
        return;
    }

    fetch("ajax.php?search=" + encodeURIComponent(keywords))
        .then(res => res.json())
        .then(data => {
            let resultsBox = document.getElementById("navSearchResults");
            resultsBox.innerHTML = "";

            if (data.length === 0) {
                resultsBox.style.display = "none";
                return;
            }

            data.forEach(game => {
                let row = document.createElement("a");
                row.classList.add("list-group-item", "list-group-item-action");
                row.textContent = game.game_name;

                row.onclick = () => {
                    window.location.href = "game-details.php?id=" + game.game_id;
                };

                resultsBox.appendChild(row);
            });

            resultsBox.style.display = "block";
        });
});
</script>
