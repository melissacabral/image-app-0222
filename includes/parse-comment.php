<?php
//if they submitted the comment, sanitize, validate and add to DB!
if( isset( $_POST['did_comment'] ) ){
	$allowed_tags = array( '<b>', '<i>', '<strong>', '<em>', '<del>' );
	$body = trim( strip_tags( $_POST['body'], $allowed_tags ) );
	//@TODO: change this to work with the logged in user
	$user_id = 1;

	$valid = true;
	//comment blank or too long
	if( strlen($body) == 0 OR strlen( $body ) > 1000 ){
		$valid = false;
		$errors['body'] = 'Please fill out the comment up to 1000 characters.'; 
	}
	//post id invalid
	if( 0 == $post_id ){
		$valid = false;
		$errors['post_id'] = 'Invalid Post';
	}
	//if valid, add a new comment to the DB
	if( $valid ){
		$result = $DB->prepare( 'INSERT INTO comments
								( user_id, body, date, post_id, is_approved )
								VALUES 
								( :user, :body, now(), :post, 1 )
								' );
		$data = array( 
					'body' => $body,
					'user' => $user_id,
					'post' => $post_id,
				 );
		$result->execute( $data );
		if( $result->rowCount() > 0 ){
			//success
			$feedback = 'Thanks for your comment';
			$feedback_class = 'success';
		}else{
			//error
			$feedback = 'Sorry, your comment could not be saved.';
			$feedback_class = 'error';
		}
	}else{
		$feedback = ' Fix the following:';
		$feedback_class = 'error';
	}
}//end parser