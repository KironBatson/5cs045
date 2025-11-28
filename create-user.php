<?php
session_start();
include("dbconnect.php");
include("twig-init.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ---- reCAPTCHA verification ----
    $secret = '6Lf4eRksAAAAAMkxj_QGs1kDkQXn4ddKxW34wmc6';
    $response = $_POST['g-recaptcha-response'] ?? '';
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
    $captcha_success = json_decode($verify);

    if (!$captcha_success->success) {
        $error = "Captcha verification failed. Please try again.";
    } else {
        // ---- Normal user creation logic ----
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirm = $_POST['confirm'];
        
        // Check if passwords match
        if ($password !== $confirm) {
            $error = "Passwords do not match.";
        } else {
            // Check if username already exists
            $check = $mysqli->prepare("SELECT user_id FROM users WHERE username = ?");
            $check->bind_param("s", $username);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $error = "Username already exists. Please choose another.";
            } else {
                // Insert new user
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $role = 'user'; // default role

                $stmt = $mysqli->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $hash, $role);

                if ($stmt->execute()) {
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Database error. Please try again.";
                }
            }
            $check->close();
        }
    }
}

// Render Twig template
echo $twig->render('create-user.html', [
    'session' => $_SESSION,
    'error' => $error
]);
?>
