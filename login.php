<?php
session_start();
include("twig-init.php");
include("dbconnect.php");

$error = '';

// Redirect if already logged in
if(isset($_SESSION['user_id'])){
    header("Location: frontpage.php");
    exit();
}

// Handle login submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    // --- reCAPTCHA ---
    $captcha = $_POST['g-recaptcha-response'] ?? '';
    $secret = '6Lf4eRksAAAAAMkxj_QGs1kDkQXn4ddKxW34wmc6';
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha");
    $captcha_success = json_decode($verify)->success ?? false;

    if(!$captcha_success){
        $error = "Captcha verification failed.";
    } else {
        $stmt = $mysqli->prepare("SELECT user_id, password_hash, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows === 1){
            $stmt->bind_result($user_id, $password_hash, $role);
            $stmt->fetch();

            if(password_verify($password, $password_hash)){
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                header("Location: frontpage.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
        $stmt->close();
    }
}

// Render Twig template
echo $twig->render('login.html', [
    'session' => $_SESSION,
    'error' => $error
]);
?>