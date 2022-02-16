<?php 
$page = 'home';
require('config.php'); 
require_once('includes/functions.php');
require('includes/header.php');
?> 
<main class="content">
		
		<?php 
		//"write it"
		//get up to 20 published posts, newest first
		$result = $DB->prepare('SELECT p.*, u.username, u.profile_pic, u.user_id, cat.name
								FROM posts AS p, users AS u, categories AS cat
								WHERE p.user_id = u.user_id
								AND cat.category_id = p.category_id
								AND p.is_published = 1
								ORDER BY p.date DESC
								LIMIT 20');
		//"run it"
		$result->execute();
		//"check it" - are there at least 1 row (post) in the result
		if( $result->rowCount() >= 1 ){	
			//"loop it" - go through the result set one row at a time
			while( $row = $result->fetch() ){	
				//testing the array
				//print_r( $row );
				//make nice pretty vars from the assoc array
				extract($row);
		?>
		<div class="one-post">
			<a href="single.php?post_id=<?php echo urlencode($post_id); ?>">
				<img src="<?php echo $image; ?>" alt="<?php echo $title; ?>">
			</a>
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
		}else{
			//empty state
			echo '<h1 class="empty">No Posts Found</h1>';
		} 
		?>

	</main>

<?php 
require('includes/sidebar.php');
?>
<?php
require('includes/footer.php'); ?>