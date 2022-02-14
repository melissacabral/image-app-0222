<aside class="sidebar">
	<?php //get the 5 most recently joined users 
	$result = $DB->prepare('SELECT username, profile_pic 
							FROM users
							ORDER BY join_date DESC
							LIMIT 5');
	$result->execute();		
	if( $result->rowCount() >= 1 ){						
	?>
	<section class="users">
		<h3>Newest Users</h3>
		<ul>
			<?php while( $row = $result->fetch() ):	 ?>
			<li class="user">
				<img 
					src="<?php echo $row['profile_pic'] ?>" 
					alt="<?php echo $row['username'] ?>" 
					width="40" height="40">
			</li>
		<?php endwhile;  ?>
		</ul>
	</section>
	<?php }//end users ?>

	<?php 
	//get up to  15 random categories 
	//get the 5 most recently joined users 
	$result = $DB->prepare('SELECT categories.*, COUNT(*) AS total 
							FROM categories, posts
							WHERE posts.category_id = categories.category_id
							GROUP BY posts.category_id
							ORDER BY RAND()
							LIMIT 15');
	$result->execute();		
	if( $result->rowCount() >= 1 ){		
	?>
	<section class="categories">
		<h3>Categories</h3>
		<ul>
			<?php while( $row = $result->fetch() ){ ?>
			<li>
				<?php echo $row['name']; ?> 
				(&nbsp;<?php echo $row['total']; ?>&nbsp;)
			</li>
			<?php } ?>
		</ul>		
	</section>
	<?php } ?>

	<?php 
	//get posts with recent comments
	$result = $DB->prepare('SELECT users.username, posts.title, posts.post_id
							FROM posts, users, comments
							WHERE posts.user_id = users.user_id
							AND comments.post_id = posts.post_id
							AND comments.is_approved = 1
							AND posts.is_published = 1
							ORDER BY comments.date DESC
							LIMIT 10');
	$result->execute();
	if($result->rowCount() > 0){
	 ?>
	<section class="recent-comments">
		<h3>Posts with recent comments</h3>
		<ul>
			<?php while( $row = $result->fetch() ){ ?>
			<li>
				<?php echo $row['username']; ?> commented on 
				<a href="single.php?post_id=<?php echo $row['post_id']; ?>">
					<?php echo $row['title']; ?>	
				</a>				
			</li>
			<?php } ?>
		</ul>
	</section>
	<?php } ?>

	<?php 
	//get up to  15 random tags 
	//get the 5 most recently joined users 
	$result = $DB->prepare('SELECT * 
							FROM tags
							ORDER BY RAND()
							LIMIT 15');
	$result->execute();		
	if( $result->rowCount() >= 1 ){		
	?>
	<section class="tags">
		<h3>Categories</h3>
		<ul>
			<?php while( $row = $result->fetch() ){ ?>
			<li><?php echo $row['name']; ?></li>
			<?php } ?>
		</ul>		
	</section>
	<?php } ?>
	
</aside>