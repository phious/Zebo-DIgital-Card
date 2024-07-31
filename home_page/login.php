<?php
session_start();
include("connection.php");

$error = ''; // Declare the $error variable

if (isset($_POST['login-btn'])) {
    $email = filter_input(INPUT_POST, 'useremail', FILTER_SANITIZE_EMAIL);
    $password = $_POST['userpassword'];
    $error = '';

    if ($email === false) {
        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Invalid email format</label>';
    } else {
        $stmt = $database->prepare("SELECT * FROM webuser WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $utype = $user['usertype'];
            $verified = $user['verified'];

            if ($utype == 'a') {
                $storedpass = $user['password'];

                if ($verified == '1') {
                    if (password_verify($password, $storedpass)) {
                        $_SESSION['user'] = $email;
                        $_SESSION['usertype'] = 'a';
                        header('location: Admin/index.php');
                        exit;
                    }
                }

                if ($verified != '1') {
                    header('location: verification.php');
                    exit;
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Invalid email or password</label>';
                }
            } else {
                $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Invalid email or password</label>';
            }
        } else {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Invalid email or password</label>';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <link
      href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="assets/css/app.css" />
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form class="sign-in-form" action="" method="POST">
            <h2 class="title">Sign In</h2>
            <div class="input-field">
              <i class="bx bxs-user"></i>
              <input name="useremail" type="email" placeholder="Email" required />
            </div>
            <div class="input-field">
              <i class="bx bxs-lock-alt"></i>
              <input name="userpassword" type="password" placeholder="Password" required />
            </div>
            <div>
              <?php echo $error ?>
            </div>
            <div class="forget-link">
              <a href="forgetPass.php">Forgot password?</a>
            </div>
            <input name="login-btn" type="submit" value="Login" class="btn solid" />
            <p class="social-text">Or sign in with social platforms</p>
            <div class="social-media">
              <a href="#" class="social-icon">
                <i class="bx bxl-instagram"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="bx bxl-twitter"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="bx bxl-google"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="bx bxl-linkedin"></i>
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script src="app.js"></script>
  </body>
</html>