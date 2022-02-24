<?php 
require('config.php'); 
require_once('includes/functions.php');

//log out parser
require('includes/parse-logout.php');
//register form parser 
require('includes/parse-login.php');

//doctype and visible header
require('includes/header-no-nav.php');
?>
<main class="content">
	<h1>Log In to GramOG</h1>

	<?php show_feedback( $feedback, $feedback_class, $errors ); ?>

		<form method="post" action="login.php">
			<label>Username</label>
			<input type="text" name="username">

			<label>Password</label>
			<input type="password" name="password">

			<input type="submit" value="Log In">
			<input type="hidden" name="did_login" value="1">
		</form>
</main>

<?php include('includes/footer.php'); ?>