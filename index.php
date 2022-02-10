<?php 
require('config.php'); 
require('includes/header.php');
?> 
<main class="content">
		
		<?php 
		//"write it"
		//get up to 20 published posts, newest first
		$result = $DB->prepare('SELECT image, title, body, date
								FROM posts
								WHERE is_published = 1
								ORDER BY date DESC
								LIMIT 1');
		//"run it"
		$result->execute();
		//"check it" - are there at least 1 row (post) in the result
		if( $result->rowCount() >= 1 ){	
			//"loop it" - go through the result set one row at a time
			while( $row = $result->fetch() ){	
				//testing
				//print_r( $row );
		?>
		<div class="one-post">
			<img src="<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
			<h2><?php echo $row['title']; ?></h2>
			<p><?php echo $row['body']; ?></p>

			<span class="date"><?php echo $row['date']; ?></span>			
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
require('includes/footer.php'); ?>