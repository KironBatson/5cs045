<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navigation</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Disabled</a>
        </li>
      </ul>
<!-- AJAX Live Search -->
<div class="position-relative">
  <input 
      type="text" 
      id="navSearchBox" 
      class="form-control" 
      placeholder="Search games..." 
      autocomplete="off"
      style="width: 200px;">
  
  <div id="navSearchResults" 
       class="list-group position-absolute w-100" 
       style="z-index: 2000;">
  </div>
</div>
<!-- End AJAX Live Search -->
    </div>
  </div>
</nav>

<!-- script for ajax, links to ajax.php -->
<script>
// Listen for typing in navbar search
document.getElementById("navSearchBox").addEventListener("keyup", function () {
    let keywords = this.value;

    // If empty, hide results
    if (keywords.length === 0) {
        document.getElementById("navSearchResults").style.display = "none";
        document.getElementById("navSearchResults").innerHTML = "";
        return;
    }

    fetch("ajax.php?search=" + keywords)
        .then(res => res.json())
        .then(data => {
            let resultsBox = document.getElementById("navSearchResults");
            resultsBox.innerHTML = "";

            if (data.length === 0) {
                resultsBox.style.display = "none";
                return;
            }

            data.forEach(game => {
                // Create a clickable result
                let row = document.createElement("a");
                row.classList.add("list-group-item", "list-group-item-action");
                row.textContent = game.game_name;

                // When clicked → go to game-details
                row.onclick = () => {
                    window.location.href = "game-details.php?id=" + game.game_id;
                };

                resultsBox.appendChild(row);
            });

            resultsBox.style.display = "block";
        });
});
</script>
