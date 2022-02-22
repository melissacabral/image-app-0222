<?php 
if( isset($_POST['did_login']) ){
	//sanitize everything
	$username = clean_string($_POST['username']);
	$password = clean_string($_POST['password']);
	
	//validate
	$valid = true;
	//--username wrong length
	if( strlen( $username ) < USERNAME_MIN OR strlen( $username ) > USERNAME_MAX ){
		$valid = false;
		//only show detailed errors in debug mode
		if( DEBUG_MODE ){
			$errors[] = 'username wrong length';
		}
	}
	//--password too short
	if( strlen($password) < PASSWORD_MIN ){
		$valid = false;
		if( DEBUG_MODE ){
			$errors[] = 'Password too short';
		}
	}
	//if valid, check if this combo is in the DB
	if( $valid ){
		//look up the username
		$result = $DB->prepare( 'SELECT user_id, password
								FROM users
								WHERE username = ?
								LIMIT 1' );
		$result->execute( array( $username ) );
		//if found, verify the hashed password
		if($result->rowCount() > 0){
			$row = $result->fetch();
			//verify
			if( password_verify( $password, $row['password']) ){
				//success, log them in for a week and redirect
				$feedback = 'Success';
				$feedback_class = 'success';

				//generate a random token (60 chars long)
				$access_token = bin2hex(random_bytes(30));
				//store it in the database for this user
				$result = $DB->prepare( 'UPDATE users
										SET access_token = :token
										WHERE user_id = :id
										LIMIT 1' );
				$result->execute( array(
									'token' => $access_token,
									'id' 	=> $row['user_id'],
								) );
				//if it worked, store a cookie and session
				if($result->rowCount() > 0){
					$expire = time() + 60 * 60 * 24 * 7;
					setcookie( 'access_token', $access_token, $expire );
					$_SESSION['access_token'] = $access_token;

					$hashed_id = password_hash( $row['user_id'], PASSWORD_DEFAULT );
					setcookie('user_id', $hashed_id, $expire);
					$_SESSION['user_id'] = $hashed_id;

					// redirect to another page
					header('Location:index.php');
				}else{
					$feedback = 'Login Failed.';
					$feedback_class = 'error';
				}
			}else{
				//error. bad password
				$feedback = 'Incorrect Login, Try again.';
				$feedback_class = 'error';
				if(DEBUG_MODE){
					$errors[] = 'incorrect password';
				}
			}
			
		}else{
			//error. nobody found with that username
			$feedback = 'Incorrect Login, Try again.';
			$feedback_class = 'error';
			if(DEBUG_MODE){
				$errors[] = 'username not found';
			}
		}
	}else{
		//invalid form submission
		$feedback = 'Incorrect login. Try again';
		$feedback_class = 'error';
	}	
}