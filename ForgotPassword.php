<?php 
include("config.php");
if (isset($_POST['ForgotPassword'])){

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
	  header('location: Email_sent.php');
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
				<form class="login100-form validate-form" method = "post" action = "ForgotPassword.php">
					<span class="login100-form-title p-b-49">
						Write your email here
					</span>
	

					<div class="wrap-input100 validate-input m-b-23" data-validate = "Email is reauired">
						<span class="label-input100">Your Email:</span>
						<input class="input100" type="text" name="email" placeholder="Type your email">
						<span class="focus-input100" data-symbol="&#x40;"></span>
					</div>


					<div>
						<span class="label-input"></span>
						<input class="input" type="text">
					</div>
						
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn" type='submit' name="ForgotPassword">
								Retrieve Your password
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