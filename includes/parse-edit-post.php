<?php
//which post are we trying to edit?
$post_id = clean_int($_GET['post_id']);

//parse on submit
if( isset( $_POST['did_edit'] ) ){
	//sanitize everything
	$title 			= clean_string( $_POST['title'] );
	$body 			= clean_string( $_POST['body'] );
	$category_id 	= clean_int( $_POST['category_id'] );
	$allow_comments = clean_boolean( $_POST['allow_comments'] );
	$is_published 	= clean_boolean( $_POST['is_published'] );
	//validate the data
	$valid = true;
	//--title too long or blank
	if( strlen($title) > 50 OR $title == '' ){
		$valid = false;
		$errors['title'] = 'Create a title between 1 &ndash; 50 characters long';
	}
	//--body too long
	if( strlen($body) > 2000 ){
		$valid = false;
		$errors['body'] = 'Post caption must be shorter than 2000 characters';
	}
	//invalid category
	if($category_id < 1){
		$valid = false;
		$errors['category_id'] = 'Invalid Category';
	}
	//if valid, update the DB
	if($valid){
		$result = $DB->prepare('
					UPDATE posts
					SET
					title 			= :title,
					body 			= :body,
					category_id 	= :category_id,
					allow_comments 	= :allow_comments,
					is_published 	= :is_published

					WHERE post_id 	= :post_id
					AND user_id 	= :user_id
					LIMIT 1
					');
		$data = array(
					'title' 		=> $title,
					'body'			=> $body,
					'category_id' 	=> $category_id,
					'allow_comments' => $allow_comments,
					'is_published' 	=> $is_published,
					'post_id'		=> $post_id,
					'user_id'		=> $logged_in_user['user_id'],
		);
		$result->execute($data);
		//tricky query! debug it
		//debug_statement($result);

		if($result->rowCount() > 0){
			//success
			$feedback = 'Changes successfully saved';
			$feedback_class = 'success';
		}else{
			//db error
			$feedback = 'No changes were made to your post';
			$feedback_class = 'info';
		}
	}else{
		//not valid submission
		$feedback = 'Error. Fix the following:';
		$feedback_class = 'error';
	}
}//end if did edit


//make sure the viewer is the author of this post (use this info later to pre-fill the form)
$result = $DB->prepare('SELECT * FROM posts
						WHERE post_id = :post_id
						AND user_id = :user_id 
						LIMIT 1');
$result->execute(array(
					'post_id' => $post_id,
					'user_id' => $logged_in_user['user_id'],
				));
if($result->rowCount() > 0){
	$row = $result->fetch();
	//set up these variables for later $title, $body, etc
	extract($row);
}else{
	//security! not logged in as the author
	exit('You are not allowed to edit this post');
}