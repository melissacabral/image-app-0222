<?php 
/**
 * Count the approved comments on any post
 * @return int number of comments
 */
function count_comments( $post_id = 0 ){
	global $DB;
	$result = $DB->prepare( 'SELECT COUNT(*) AS total
							FROM comments
							WHERE post_id = :id' );
	$result->execute( array( 'id' => $post_id ) );
	if( $result->rowCount() > 0 ){
		while( $row = $result->fetch() ){
			return $row['total'];
		}
	}elseif( DEBUG_MODE ){
		return $DB->errorInfo();
	}
}
/**
 * display comment_count on any post ID
 * @param  integer $post_id 
 * @param  boolean $long    whether or not to include the word "comment(s)" at the end
 * @return mixed          HTML output
 */
function display_comment_count( $post_id = 0, $long = true ){
	?>
	<span class="comment-count">
		<?php 
		$total = count_comments( $post_id ); 
		if( $long ) {
			echo $total == 1 ? 'One Comment' : "$total Comments"; 
		}else{
			echo $total;
		}
		?>			
	</span>
	<?php
}

/**
 * Convert any time stamp into a human friendly date
 * @param  string $timestamp any date or timestamp
 * @return string            displays the date
 */

function nice_date( $timestamp ){
	$date = new DateTime( $timestamp );
	echo  $date->format('l, F j, Y');
}

//no close php