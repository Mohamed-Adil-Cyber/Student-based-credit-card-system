<?php 
include("config.php");
session_start();
$errors = [];
$user_id = "";
// connect to database
$db = mysqli_connect('localhost', 'root', '', 'root');
if (isset($_GET['token'])) {
  $_SESSION['token']=mysqli_real_escape_string($db,$_GET['token']);
  }
// LOG USER IN
if (isset($_POST['login_user'])) {
  // Get username and password from login form
  $user_id = mysqli_real_escape_string($db, $_POST['user_id']);
  $password = mysqli_real_escape_string($db, $_POST['password']);
  // validate form
  if (empty($user_id)) array_push($errors, "Username or Email is required");
  if (empty($password)) array_push($errors, "Password is required");

  // if no error in form, log user in
  if (count($errors) == 0) {
    $password = md5($password);
    $sql = "SELECT * FROM users WHERE username='$user_id' OR email='$user_id' AND passcode='$password'";
    $results = mysqli_query($db, $sql);

    if (mysqli_num_rows($results) == 1) {
      $_SESSION['username'] = $user_id;
      $_SESSION['success'] = "You are now logged in";
      header('location: index.php');
    }else {
      array_push($errors, "Wrong credentials");
    }
  }
}

/*
  Accept email of user whose password is to be reset
  Send email to user to reset their password
*/
if (isset($_POST['reset-password'])) {
  $email = mysqli_real_escape_string($db, $_POST['email']);
  // ensure that the user exists on our system
  $query = "SELECT email FROM users WHERE email='$email'";
  $results = mysqli_query($db, $query);

  if (empty($email)) {
    array_push($errors, "Your email is required");
  }else if(mysqli_num_rows($results) <= 0) {
    array_push($errors, "Sorry, no user exists on our system with that email");
  }
  // generate a unique random token of length 100
  $token = bin2hex(random_bytes(50));

  if (count($errors) == 0) {
    // store token in the password-reset database table against the user's email
    $sql = "INSERT INTO password_reset(email, token) VALUES ('$email', '$token')";
    $results = mysqli_query($db, $sql);

    // Send email to user with the token in a link they can click on
    $to = $email;
    $subject = "Reset your password on examplesite.com";
    $msg = "Hi there, click on this <a href=\"new_password.php?token=" . $token . "\">link</a> to reset your password on our site";
    $msg = wordwrap($msg,70);
    $headers = "From: info@examplesite.com";
    mail($to, $subject, $msg, $headers);
    header('location: pending.php?email=' . $email);
  }
}

// ENTER A NEW PASSWORD
if (isset($_POST['new_password'])) {
  $new_pass = mysqli_real_escape_string($db, $_POST['new_pass']);
  $new_pass_c = mysqli_real_escape_string($db, $_POST['new_pass_c']);

  // Grab to token that came from the email link
  $token = $_SESSION['token'];
  if (empty($new_pass) || empty($new_pass_c)) array_push($errors, "Password is required");
  if ($new_pass !== $new_pass_c) array_push($errors, "Password do not match");
  if (count($errors) == 0) {
    // select email address of user from the password_reset table 
    $sql = "SELECT email FROM password_reset WHERE token='$token' LIMIT 1";
    $results = mysqli_query($db, $sql);
    $email = mysqli_fetch_assoc($results)['email'];
    

    if ($email) {
      $sql = "UPDATE users SET passcode='$new_pass' WHERE email='$email'";
      $results = mysqli_query($db, $sql);
      header('location: index.php');
    }
    else{
      
      header('location: error.php');}
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>SBCS</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/card.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('images/bg-01.jpg');">
			<div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
				<form class="login100-form validate-form" method = "post" action = "new_password.php">
					<span class="login100-form-title p-b-49">
						Write your new password here
					</span>
	

					<div class="wrap-input100 validate-input m-b-23" data-validate = "password is reauired">
						<span class="label-input100">New Password:</span>
						<input class="input100" type="text" name="new_pass" placeholder="Type your password">
						<span class="focus-input100" data-symbol="&#x40;"></span>
					</div>


					<div>
						<span class="label-input"></span>
						<input class="input" type="text">
					</div>

                    <div class="wrap-input100 validate-input m-b-23" data-validate = "password retype is reauired">
						<span class="label-input100">Retype password:</span>
						<input class="input100" type="text" name="new_pass_c" placeholder="Type your password">
						<span class="focus-input100" data-symbol="&#x40;"></span>
					</div>


					<div>
						<span class="label-input"></span>
						<input class="input" type="text">
					</div>
						
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn" type='submit' name="new_password">
								Change Your password
							</button>
						</div>
					</div>
					&nbsp;
					
						&nbsp;
						<div class="flex-c-m">
						<a href="https://www.facebook.com/Student-based-credit-card-system-109988937348687/?modal=admin_todo_tour" class="login100-social-item bg1">
								<i class="fa fa-facebook"></i>
							</a>
	
							<a href="https://twitter.com/SBCS93424734" class="login100-social-item bg2">
								<i class="fa fa-twitter"></i>
							</a>
	
							<a href="https://www.upm.edu.sa/" class="login100-social-item bg3">
								<i class="fa fa-google"></i>
							</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>