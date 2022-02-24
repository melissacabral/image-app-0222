<?php 
require('config.php'); 
require_once('includes/functions.php');
require('includes/header.php');
//kill the page if not logged in as the author of this post
if( ! $logged_in_user ){
	exit('This page is for logged in users only');
}
?> 
<main class="content">

	<h2>Edit Post</h2>

	SHOW THE IMAGE HERE

	<?php show_feedback( $feedback, $feedback_class, $errors ); ?>

	<form action="" method="post">
		
	</form>
		
</main>

<?php 
require('includes/sidebar.php');
require('includes/footer.php'); 