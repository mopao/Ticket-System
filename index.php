<?php
session_start();
use XMLManagers\UserManager;
require __DIR__ . '/vendor/autoload.php';

$error_incorrect_credentials = "";
$error_username = "";
$error_password = "";
$username="";
$password="";

if (isset($_GET['page']) && $_GET['page'] === "logout" ) {

	unset($_SESSION['user']);
}

if (isset($_POST['logIn'])) {

	//retrieve user's inputs
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

	if($username === ""){
		$error_username = "Please enter an username!";
	}

	if($password === ""){
		$error_password = "Please enter a password!";
	}

	if($username !== "" && $password !== ""){

		$userManager = new UserManager();
		$result = $userManager->checkLogin($username,$password);
		if (!$result) {
			$error_incorrect_credentials = "incorrect username or password";
		}
		else{
			$user = array('username' => $username, 'password' => $password);
			$_SESSION['user'] = $user;

			header('Location: ticket_list.php');
			exit;
		}


	}



}

?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="imgs/favicon-3.ico" >
		<title> Ticket system - Login </title>
		<link rel="stylesheet" type="text/css" href="css/login.css">
		<link rel="stylesheet" type="text/css" href="css/general.css">

	</head>
	<body>
		<main class="fluid-container">
			<h1> Welcome to our XML Ticket system </h1>
			<div class="login__form_wrapper">
				<form action="" method="post" name="login_form">
					<h2 class="hide"> login form </h2>
					<fieldset>
						<legend>user authentication</legend>
						<div>
							<label class="login__field_error"><?= $error_incorrect_credentials ?></label>
						</div>
						<label class="login__field_error"><?= $error_username ?></label>
						<div class="form-row">
							<label for="username" class="col-md-4">Username:</label>
							<input type="text" id="username" class="form-control" name="username" value="<?= $username ?>">

						</div>
						<label class="login__field_error"><?= $error_password ?></label>
						<div class="form-row">
							<label for="password" class="col-md-4">Password:</label>
							<input type="password" id="password" name="password" class="form-control" value="<?= $password ?>">
						</div>
						<div>
							<button type="submit" name="logIn">Log in</button>
						</div>
					</fieldset>

				</form>
			</div>

		</main>


	</body>
</html>
