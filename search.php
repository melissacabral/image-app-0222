<?php 
$page = 'search';
require('config.php'); 
require_once('includes/functions.php');
require('includes/header.php');

//search configuration
$per_page = 1;
?> 
<main class="content">
	<?php
	//clean user input
	$phrase = trim( strip_tags( $_GET['phrase'] ) );
	$wildcard_phrase = "%$phrase%";
	//validate if phrase is not blank
	if( $phrase != '' ){ 
		//get the total published posts
		$query = 'SELECT post_id, title, body, image, date
					FROM posts
					WHERE 
						( title LIKE :phrase
						OR body LIKE :phrase )
					AND 
						is_published  = 1
					ORDER BY date DESC';
		$result = $DB->prepare( $query );
		$result->execute( array( 'phrase' => $wildcard_phrase ) );

		//total number of matching posts
		$total = $result->rowCount();

		//how many pages are needed? round up so we always get a full page
		$max_page = ceil( $total / $per_page );

		//what page are we on? url will look like search.php?phrase=bla&page=1
		if ( isset( $_GET['page'] ) ) {		
			$current_page = filter_var( $_GET['page'], FILTER_SANITIZE_NUMBER_INT );

			//validate the current page
			if( $current_page <= 0 OR $current_page > $max_page ){
				$current_page = 1;
			}
		}else{
			$current_page = 1;
		}
		//offset for our LIMIT
		$offset =  ( $current_page - 1 ) * $per_page ;
		//run the query again, but with the LIMIT added 
		$query .= ' LIMIT :offset, :per_page';
		$result = $DB->prepare( $query );
		//bind parameters so that LIMIT has integer values
		$result->bindParam( 'phrase', $wildcard_phrase, PDO::PARAM_STR );
		$result->bindParam( 'offset', $offset, 			PDO::PARAM_INT );
		$result->bindParam( 'per_page', $per_page, 		PDO::PARAM_INT );
		//run it
		$result->execute();
	?>

		<section class="title">
			<h2>Search Results for <?php echo $phrase; ?></h2>
			<h3>
				<?php echo $total; ?> posts found. 
				<?php 
				if( $total > 0 ){
					echo "Showing page $current_page of $max_page"; 
				}
				?>			
			</h3>
		</section>
		<?php if( $total > 0 ){ ?>
			<section class="grid">
				<?php 
				while( $row = $result->fetch() ){ 
					extract( $row );
				?>
				<div class="item">
					<a href="single.php?post_id=<?php echo $post_id; ?>">
						<img src="<?php echo $image; ?>" width="250" height="250" alt="<?php echo $title; ?>">
						<h3><?php echo $title; ?></h3>
						<span class="date"><?php echo time_ago($date); ?></span>
					</a>
				</div>
				<?php }//end while ?>
			</section>

			<?php 
			$prev = $current_page - 1;
			$next = $current_page + 1;
			?>
			<div class="pagination">
				<?php if( $current_page > 1 ){ ?>
				<a href="search.php?phrase=<?php echo $phrase; ?>&amp;page=<?php echo $prev; ?>" 
					class="button button-outline">
					&larr; Previous
				</a>
				<?php } ?>

				<?php if( $current_page < $max_page ){ ?>
				<a href="search.php?phrase=<?php echo $phrase; ?>&amp;page=<?php echo $next; ?>" 
					class="button button-outline">
					Next &rarr;
				</a>
				<?php } ?>
			</div>
		<?php 
		}//end if  total > 0
		?>

	<?php 
	}else{
		//@TODO: change this to generic "no posts" message
		echo 'Search is blank';
	} ?>
</main>

<?php require('includes/sidebar.php'); ?>
<?php require('includes/footer.php'); ?>