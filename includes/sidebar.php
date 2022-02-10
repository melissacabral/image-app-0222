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
	$result = $DB->prepare('SELECT * 
							FROM categories
							ORDER BY RAND()
							LIMIT 15');
	$result->execute();		
	if( $result->rowCount() >= 1 ){		
	?>
	<section class="categories">
		<h3>Categories</h3>
		<ul>
			<?php while( $row = $result->fetch() ){ ?>
			<li><?php echo $row['name']; ?></li>
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