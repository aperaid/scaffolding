<?php

session_start();

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($access)
{
	// For security, start by assuming the visitor is NOT authorized. 
	$isValid = False;

	// When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
	// Therefore, we know that a user is NOT logged in if that Session variable is blank. 
	if (!empty($access)) {
		$isValid= True;
	}
  
	return $isValid;
}

if ( !(isset($_SESSION['username'])) && !(isAuthorized($_SESSION['access'])) ) {
	header("Location: " . $ROOT . "pages/login/login.php");
}

// ** Logout the current user. **
//Link for logout
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";


if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
	
	//to fully log out a visitor we need to clear the session varialbles
	$_SESSION['username']  = NULL;
	$_SESSION['name'] = NULL;
	$_SESSION['access'] = NULL;
	unset($_SESSION['username']);
	unset($_SESSION['name']);
	unset($_SESSION['access']);
	
	//logout redirect
	header("Location: " . $ROOT. "pages/login/login.php");
	exit;
}

?>