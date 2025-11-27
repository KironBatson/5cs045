<?php
// point to library and load Composer autoloader
require_once 'vendor/autoload.php';

// Tell Twig where templates are stored
$loader = new \Twig\Loader\FilesystemLoader('templates');
// Create Twig environment
$twig = new \Twig\Environment($loader);
?>
