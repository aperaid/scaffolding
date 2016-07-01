<?php require_once('../../connections/Connection.php'); ?>
<?php include_once('../../pages/functionphp.php'); ?>

<?php
$ROOT = "../../";

/*Clear All
$_SESSION['username']  = NULL;
$_SESSION['name'] = NULL;
$_SESSION['access'] = NULL;
unset($_SESSION['username']);
unset($_SESSION['name']);
unset($_SESSION['access']);

if (!isset($_SESSION)) {
	session_start();
}
		*/
session_start();
$loginFormAction = $_SERVER['PHP_SELF'];

if (isset($_POST['Username']) && ($_POST['Username'] != "" && $_POST['Password'] != "")) {
	
	//Get the typed in Username and Password
	$username=$_POST['Username'];
	$password=$_POST['Password'];
	
	//Query check the username, password and access
	mysql_select_db($database_Connection, $Connection);
	$query_login=sprintf("SELECT Username AS Username, Password, Name AS Name, Access AS Access FROM users WHERE Username='$username' AND Password='$password'") or die(mysql_error());; 
	$login = mysql_query($query_login, $Connection) or die(mysql_error());
	$row_login = mysql_fetch_assoc($login);
	$loginFoundUser = mysql_num_rows($login);
	
	
	// If username and password is found/correct then
	if ($loginFoundUser == 1) {
		
		if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
		
		//declare two session variables and assign them
		$_SESSION['username'] = $row_login['Username'];
		$_SESSION['name'] = $row_login['Name'];
		$_SESSION['access'] = $row_login['Access'];
		
		header("Location: ". $ROOT . "index.php" );
	}
	else
	{
		header("Location: ");
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Xana | Login</title>
		
		<?php 
			include_once($ROOT . 'pages/cssinclude.php');
			include_once($ROOT . 'pages/jsinclude.php');
		?>

	</head>
	
	<body class="hold-transition login-page">
		<div class="login-box">
			
			<!-- login-logo -->
			<div class="login-logo">
				<a href="../../../../index.php"><b>Xana</b> erp</a>
			</div>
			<!-- login-logo -->
			
			<div class="login-box-body">
				<p class="login-box-msg">Sign in</p>
				<form ACTION="<?php echo $loginFormAction; ?>" id="fm_login_form1" name="fm_login_form1" method="POST">
					<!-- Username Box -->
					<div class="form-group has-feedback">
						<input name="Username" id="Username" type="text" class="form-control" placeholder="Username">
						<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					</div>
					<!-- Password Box -->
					<div class="form-group has-feedback">
						<input name="Password" id="Password" type="password" class="form-control" placeholder="Password">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<!-- Checkbox & Login Button Box -->
					<div class="row">
						<div class="col-xs-8">
							<div class="checkbox">
								<label>
									<input type="checkbox"> Remember Me
								</label>
							</div>
						</div>
						<div class="col-xs-4">
							<button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>

