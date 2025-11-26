<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kiron's Video Game Archive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <style>
/* CSS styles */
body {background-color: black;}
h1 , h4 , h5 , p   {color: white;}

/* navbar search results styling */
#navSearchResults {
    max-height: 250px;
    overflow-y: auto;
    display: none; /* hidden until results show */
}
#navSearchResults a {
    cursor: pointer;
}

/* Change navbar background and link colors */
.navbar {
    background-color: #333333ff; /* bgcolour */
}

.navbar .navbar-brand,
.navbar .nav-link {
    color: #ffffffff; /* text */
}
.navbar .nav-link:hover {
    color: #ff0000d5; /* button on hover */
}

/* search button */
.navbar .btn-primary {
    background-color: #ff0000d5; /* button bg */
    border-color: #ff0000d5;     /* button border */
    color: #ffffffff;             /* button text */
}
.navbar .btn-primary:hover {
    background-color: #cc0000ff; /*  button hover bg */
    border-color: #cc0000ff;     /* button hover border */
    color: #ffffffff;             /* button hover text */
}

/* Make all cards dark grey */
.card {
    background-color: #343a40; /* dark grey */
    color: white; /* text inside cards will be white by default */
    border: none; /* optional: remove borders */
}
/* Make all headings inside cards white (optional) */
.card h1, .card h2, .card h3, .card h4, .card h5, .card h6 {
    color: white;
}
/* Make all labels and form labels white */
label, form label {
    color: white;
}

.addgame-modal label {
    color: black !important;
}
.addgame-modal .form-control {
    background-color: #e9ecef;
    color: black;
}
</style>
  </head>

  <body>
	<div class="container">