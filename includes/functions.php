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
/**
 * Get the Amount of time that has passed since any date (i.e. 2 days ago)
 * @param  string  $datetime timestamp
 * @param  boolean $full     whether to be really really specific with the time ago
 * @return string            the amount of time ago
 */
function time_ago($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

/**
 * Display the feedback after a typical form submission
 * @param  string $message   the message to display
 * @param  string $css_class the class to apply to the container - use 'error' or 'success'. default value 'info'
 * @param  array $list 		The bullet list of issues to show
 * @return mixed            HTML output
 */
function show_feedback( &$message, &$css_class = 'info', &$list = array() ){
	if(  isset( $message )  ){
		echo "<div class='feedback $css_class'>";
		echo "<h3>$message</h3>";
		//if the list isn't empty, show it 
		if(! empty( $list )){
			echo '<ul>';
			foreach( $list as $item ){
				echo "<li>$item</li>";
			}
			echo '</ul>';
		}
		echo '</div>';
	}
}
/**
 * make <select> dropdowns sticky
 * @param  mixed $a First thing
 * @param  mixed $b Second thing
 * @return string    The "selected" attribute for HTML
 */
function selected( $a, $b ){
	if( $a == $b ){
		echo 'selected';
	}
}
/**
 * make checkboxes and radio buttons sticky
 * @param  mixed $a First thing
 * @param  mixed $b Second thing
 * @return string    The "checked" attribute for HTML
 */
function checked( $a, $b ){
	if( $a == $b ){
		echo 'checked';
	}
}
/**
 * Hilight an HTML input if it encounters an error
 * @param  string $key   the name of the field that caused the issue
 * @param  array $array the collection of errors from the form submission
 * @return string        a css class for the input
 */
function field_error( $key, $array ){
	if( array_key_exists( $key, $array ) ){
		echo 'field-error';
	}
}

//no close php