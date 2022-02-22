<?php 
//url will look like login.php?action=logout
if( isset( $_GET['action'] ) AND $_GET['action'] == 'logout' ){
	//TODO: remove the access_token from the DB

	//invalidate all cookies and session vars
	setcookie('access_token', 0, time() - 9999);
	setcookie('user_id', 0, time() - 9999);

	$_SESSION = array();

	//from php.net
	//php.net/manual/en/function.session-destroy.php
	if (ini_get("session.use_cookies")) {
	    $params = session_get_cookie_params();
	    setcookie(session_name(), '', time() - 42000,
	        $params["path"], $params["domain"],
	        $params["secure"], $params["httponly"]
	    );
	}

	session_destroy();
}//end logout logic