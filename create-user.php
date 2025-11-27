<?php include("templates/header.php"); ?>
<?php
session_start();
include("dbconnect.php");

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

        if($password !== $confirm){
            $error = "Passwords do not match.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user'; // default role

            $stmt = $mysqli->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hash, $role);

            if($stmt->execute()){
                header("Location: login.php");
                exit();
            } else {
                $error = "Username already exists or database error.";
            }
        }
    }
}
?>

<div class="d-flex flex-column justify-content-center align-items-center min-vh-100">
    <h2 class="mb-4 text-center text-white">Create User</h2>

    <form method="post" class="w-50">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="mb-3">
            <input type="password" name="confirm" class="form-control" placeholder="Confirm Password" required>
        </div>
        <!-- Google reCAPTCHA -->
        <div class="mb-3">
            <div class="g-recaptcha" data-sitekey="6Lf4eRksAAAAAPwJCMsmDamWd0aNPpBPewthcEoR"></div>
        </div>

        <div class="d-flex justify-content-center gap-2">
            <button type="submit" class="btn btn-success">Create User</button>
            <a href="login.php" class="btn btn-secondary">Back to Login</a>
        </div>

        <?php if($error) echo "<p class='text-danger mt-2 text-center'>$error</p>"; ?>
    </form>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php include("templates/footer.php"); ?>