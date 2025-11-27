<?php include("templates/header.php"); ?>

<?php
session_start();
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
    $password = $_POST['password']; // do NOT sanitize passwords; hashing breaks

    // --- reCAPTCHA ---
    $captcha = $_POST['g-recaptcha-response'];
    $secret = '6Lf4eRksAAAAAMkxj_QGs1kDkQXn4ddKxW34wmc6'; // replace with your key
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha");
    $captcha_success = json_decode($verify)->success;

    if(!$captcha_success){
        $error = "Captcha verification failed.";
    } else {
        // --- Check user in DB ---
        $stmt = $mysqli->prepare("SELECT user_id, password_hash, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows === 1){
            $stmt->bind_result($user_id, $password_hash, $role);
            $stmt->fetch();

            if(password_verify($password, $password_hash)){
                // Successful login
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
?>

<div class="d-flex flex-column justify-content-center align-items-center min-vh-100">
    <h1 class="mb-4 text-center">Login</h1>
    <form method="post" class="w-50">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="mb-3">
            <!-- Google reCAPTCHA v2 -->
            <div class="g-recaptcha" data-sitekey="6Lf4eRksAAAAAPwJCMsmDamWd0aNPpBPewthcEoR"></div>
        </div>
        <div class="d-flex justify-content-center gap-2">
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="create-user.php" class="btn btn-success">Create Account</a>
            <a href="frontpage.php" class="btn btn-secondary">Back to Homepage</a>
        </div>
        <?php if($error) echo "<p class='text-danger mt-2 text-center'>$error</p>"; ?>
    </form>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php include("templates/footer.php"); ?>