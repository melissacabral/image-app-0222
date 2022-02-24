<?php 
require('config.php'); 
require_once('includes/functions.php');
require('includes/header.php');

//which post are we viewing? sanitize the query string var
//url: single.php?post_id=x
if( isset( $_GET['post_id'] ) ){
	//sanitize and validate
	$post_id = filter_var( $_GET['post_id'], FILTER_SANITIZE_NUMBER_INT );
	//make sure it isn't blank
	if( '' == $post_id ){
		$post_id = 0;
	}
}else{
	$post_id = 0;
}

require('includes/parse-comment.php');

?> 
<main class="content">
		
		<?php 
		//"write it"
		//get the published post, newest first
		$query = 'SELECT p.*, u.username, u.profile_pic, u.user_id, cat.name, p.allow_comments
							FROM posts AS p, users AS u, categories AS cat
							WHERE p.user_id = u.user_id
							AND cat.category_id = p.category_id
							AND p.is_published = 1
							AND p.post_id = :id
							LIMIT 1';
		$result = $DB->prepare( $query );
		//"run it"
		$result->execute( array( 'id' => $post_id ) );
		//"check it" - are there at least 1 row (post) in the result
		if( $result->rowCount() >= 1 ){	
			//"loop it" - go through the result set one row at a time
			while( $row = $result->fetch() ){	
				//testing
				//print_r( $row );
				//make nice pretty vars from the assoc array
				extract($row);
		?>
		<div class="one-post">
			<?php show_post_image($image, 'large', $title); ?>

			<span class="author">
				<img src="<?php echo $profile_pic; ?>" width="40" height="40">
				<?php echo $username; ?>
			</span>
			<h2><?php echo $title; ?></h2>
			<p><?php echo $body; ?></p>

			<?php display_comment_count( $post_id ); ?>
			<span class="date"><?php nice_date( $date ); ?></span>	
			<span class="category"><?php echo $name; ?></span>		
		</div>
		<?php 
			} //end while

			require('includes/comments.php');
			
			if( $allow_comments AND $logged_in_user ){
				require('includes/comment-form.php');
			}

		}else{
			//empty state
			echo '<h1 class="empty">No Posts Found</h1>';
		} 
		?>
	</main>

<?php 
require('includes/sidebar.php');
require('includes/footer.php'); ?>