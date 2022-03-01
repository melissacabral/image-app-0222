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

/**
 * Sanitizer functions
 */
function clean_string( &$dirty = '', $allowed_tags = array() ){
	return trim( strip_tags( $dirty, $allowed_tags ) );
}
function clean_int( &$dirty = 0 ){
	return filter_var( $dirty, FILTER_SANITIZE_NUMBER_INT );
}
function clean_boolean( &$dirty = 0 ){
	if(! isset($dirty) OR ! $dirty ){
		return 0;
	}else{
		return 1;
	}
}
function clean_email( &$dirty = '' ){
	return filter_var( $dirty,  FILTER_SANITIZE_EMAIL );
}

/**
 * Make an avatar image from a string
 * @param  string $string the string to put on the avatar
 * @param  int $size   size in pixels, square
 * @return [type]         [description]
 */
function make_letter_avatar($string, $size){
	//random pastel color
    $H =   mt_rand(0, 360);
    $S =   mt_rand(25, 50);
    $B =   mt_rand(90, 96);

    $RGB = get_RGB($H, $S, $B);
    $string = strtoupper($string);

    $imageFilePath = 'avatars/' . $string . '_' .  $H . '_' . $S . '_' . $B . '.png';

    //base avatar image that we use to center our text string on top of it.
    $avatar = imagecreatetruecolor($size, $size);  
    //make and fill the BG color
    $bg_color = imagecolorallocate($avatar, $RGB['red'], $RGB['green'], $RGB['blue']);
    imagefill( $avatar, 0, 0, $bg_color );
    //white text
    $avatar_text_color = imagecolorallocate($avatar, 255, 255, 255);
	// Load the gd font and write 
    //$font = imageloadfont('gd-files/gd-font.gdf');
    ///imagestring($avatar, $font, 10, 10, $string, $avatar_text_color);
    
    $font = 'fonts/font.ttf';
    $font_size = $size/2;
    $x = ($size/2) - ($size/5);
    $y = $size/2 + ($size/4);
    imagettftext($avatar, $font_size, 0, $x, $y, $avatar_text_color, $font, $string);


    imagepng($avatar, $imageFilePath);

    imagedestroy($avatar);

    return $imageFilePath;
}


/*
*  Converts HSV to RGB values
*  Input:     Hue        (H) Integer 0-360
*             Saturation (S) Integer 0-100
*             Lightness  (V) Integer 0-100
*  Output:    Array red, green, blue
*/
function get_RGB($iH, $iS, $iV) {
    if($iH < 0)   $iH = 0;   // Hue:
    if($iH > 360) $iH = 360; //   0-360
    if($iS < 0)   $iS = 0;   // Saturation:
    if($iS > 100) $iS = 100; //   0-100
    if($iV < 0)   $iV = 0;   // Lightness:
    if($iV > 100) $iV = 100; //   0-100

    $dS = $iS/100.0; // Saturation: 0.0-1.0
    $dV = $iV/100.0; // Lightness:  0.0-1.0
    $dC = $dV*$dS;   // Chroma:     0.0-1.0
    $dH = $iH/60.0;  // H-Prime:    0.0-6.0
    $dT = $dH;       // Temp variable

    while($dT >= 2.0) $dT -= 2.0; // php modulus does not work with float
    $dX = $dC*(1-abs($dT-1));     // as used in the Wikipedia link

    switch(floor($dH)) {
        case 0:
        $dR = $dC; $dG = $dX; $dB = 0.0; break;
        case 1:
        $dR = $dX; $dG = $dC; $dB = 0.0; break;
        case 2:
        $dR = 0.0; $dG = $dC; $dB = $dX; break;
        case 3:
        $dR = 0.0; $dG = $dX; $dB = $dC; break;
        case 4:
        $dR = $dX; $dG = 0.0; $dB = $dC; break;
        case 5:
        $dR = $dC; $dG = 0.0; $dB = $dX; break;
        default:
        $dR = 0.0; $dG = 0.0; $dB = 0.0; break;
    }

    $dM  = $dV - $dC;
    $dR += $dM; $dG += $dM; $dB += $dM;
    $dR *= 255; $dG *= 255; $dB *= 255;

    return  array(
        'red' =>  round($dR),
        'green'=> round($dG),
        'blue' => round($dB)
    );
}

/**
 * check to see if the viewer is logged in
 * @return array|bool false if not logged in, array of all user data if they are logged in
 */

function check_login(){
    global $DB;
    //if the cookie is valid, turn it into session data
    if(isset($_COOKIE['access_token']) AND isset($_COOKIE['user_id'])){
        $_SESSION['access_token'] = $_COOKIE['access_token'];
        $_SESSION['user_id'] = $_COOKIE['user_id'];
    }

   //if the session is valid, check their credentials
   if( isset($_SESSION['access_token']) AND isset($_SESSION['user_id']) ){
        //check to see if these keys match the DB     

       $data = array(
        'access_token' =>$_SESSION['access_token'],
       );

        $result = $DB->prepare(
            "SELECT * FROM users
                WHERE  access_token = :access_token
                LIMIT 1");
        $result->execute( $data );
       
        if($result->rowCount() > 0){
            //token found. confirm the user_id
            $row = $result->fetch();
            if( password_verify( $row['user_id'], $_SESSION['user_id'] ) ){
                //success! return all the info about the logged in user
                return $row;
            }else{
                return false;
            }
          
        }else{
            return false;
        }
    }else{
        //not logged in
        return false;
    }
}

/**
 * Display any size image from it's identifying string
 * @param  string $image unique string from the image file name
 * @param  string $size  small, medium (default) or large
 * @param  string $alt   alt text for the img tag
 * @return mixed        html output
 */
function show_post_image( $image, $size = 'medium', $alt ='' ){
    $url = 'uploads/' . $image . '_' . $size . '.jpg';   
    echo "<img src='$url' alt='$alt'>";
}

/**
 * Show a <select> element containing all categories
 * @return mixed html
 */
function show_category_dropdown( $default = 0 ){
    global $DB;
    $result = $DB->prepare('SELECT * FROM categories ORDER BY name ASC');
    $result->execute();
    if($result->rowCount() > 0){
    ?>
    <select name="category_id">
        <?php
        while( $row = $result->fetch() ){ 
            extract($row);
            if( $category_id == $default ){
                $atts = 'selected';
            }
            echo "<option value='$category_id' $atts>$name</option>";
        } 
        ?>
    </select>
    <?php
    }//end if categories
}

/**
* displays sql query information including the computed parameters.
* Silent unless DEBUG MODE is set to 1 in config.php
* @param [statement handler] $sth -  any PDO statement handler that needs troubleshooting
*/
function debug_statement($sth){
    if( DEBUG_MODE ){
        echo '<pre>';
        $info = debug_backtrace();
        echo '<b>Debugger ran from ' . $info[0]['file'] . ' on line ' . $info[0]['line'] . '</b><br><br>';
        $sth->debugDumpParams();
        echo '</pre>';
    }
}
/**
 * Show an edit button when logged in as the author of the post shown
 * @param  int  $post_id   the post id
 * @param  int  $author_id the user_id of the author of the post
 * @param  boolean|array $user      the logged in user array
 * @return mixed             html
 */
function show_edit_button($post_id, $author_id, $user = false){
    //if the user is logged in and is the user of this post
    if($user AND $author_id == $user['user_id'] ){
        echo "<a href='edit-post.php?post_id=$post_id' class='button button-outline'>Edit</a>";
    }
}

 
/**
 * LIKE BUTTON ADDITIONS
 * Count the likes on any post
 */

function count_likes( $post_id ){
  global $DB;
  $result = $DB->prepare( "SELECT COUNT(*) AS total_likes
            FROM likes
            WHERE post_id = ?" );
  $result->execute( array($post_id) );
  if( $result->rowCount() >= 1 ){
    $row = $result->fetch();
    $total = $row['total_likes'];

    //display it with correct grammar (ternary operator example)
    return $total == 1 ? '1 Like' : "$total Likes" ;

  }
}
/**
 * Interface for "like" button and count
 * works on any post
 */
function like_interface( $post_id, $user_id = 0 ){
  global $DB;
  //is the viewer logged in?
    if( $user_id ){
    //does the viewer "like" this post?
     $result = $DB->prepare( "SELECT * FROM likes
              WHERE user_id = ?
              AND post_id = ?
              LIMIT 1" );
      $result->execute(array($user_id, $post_id));
   if( $result->rowCount() >= 1 ){
      //they like it
      $class = 'you-like';
    }else{
      //they don't like
      $class = 'not-liked';
    }
  } //end if logged in
  

  ?>
  <span class="like-interface">
    <span class="<?php echo $class; ?>">
      
      <?php 
      //logged in?
      if( $user_id ){ ?>
      <span class="heart-button" data-postid="<?php echo $post_id; ?>">❤</span>
      <?php 
      } //end if logged in
      ?>

      <?php echo count_likes( $post_id ); ?>
    </span>
  </span>
  <?php
}
//no close php