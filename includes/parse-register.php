<?php
clean_boolean($_POST['did_register']);

if(  $_POST['did_register']  ){
	//sanitize everything
	$username = clean_string( $_POST['username'] );
	$email = clean_email( $_POST['email'] );
	$password = clean_string( $_POST['password'] );
	$policy = clean_boolean( $_POST['policy'] );
	
	//validate
	$valid = true;
	//-- username wrong length
	if( strlen($username) < USERNAME_MIN OR strlen($username) > USERNAME_MAX ){
		$valid = false;
		$errors['username'] = 'Choose a username between ' . USERNAME_MIN .  ' &ndash; ' . USERNAME_MAX . ' characters long';
	}else{
		//-- username already taken
		$query = 'SELECT username 
					FROM users 
					WHERE username = :username 
					LIMIT 1';
		$result = $DB->prepare($query);
		$result->execute( array( 'username' => $username ) );
		//if one row found, this name is already taken
		if( $result->rowCount() > 0 ){
			$valid = false;
			$errors['username'] = 'Sorry, that username is taken. Try another.';
		}
	}//end of username tests
	
	//-- email invalid
	if( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ){
		$valid = false;
		$errors['email'] = 'Provide a valid email address';
	}else{
		//-- email already taken
		$query = 'SELECT email 
					FROM users 
					WHERE email = :email 
					LIMIT 1';
		$result = $DB->prepare($query);
		$result->execute( array( 'email' => $email ) );
		//if one row found, this name is already taken
		if( $result->rowCount() > 0 ){
			$valid = false;
			$errors['email'] = 'That email is already in use. Try logging in.';
		}
	}//end email checks
	
	//-- password too short
	if( strlen( $password ) < PASSWORD_MIN ){
		$valid = false;
		$errors['password'] = 'Your password is too short';
	}
	
	//-- unchecked policy
	if( ! $policy ){
		$valid = false;
		$errors['policy'] = 'You must agree to the terms of service before registering.';
	}
	
	//if valid, add new user to DB
	if( $valid ){
		//make an avatar
		$avatar = make_letter_avatar( $username[0], 60 );
		$query = 'INSERT INTO users
					( email, username, password, profile_pic, bio, is_admin, join_date )
					VALUES 
					( :email, :username, :password, :image, "", 0, now() )';
		$result = $DB->prepare( $query );
		//make a uniquely salted, hashed password for storage
		$hashed_pass = password_hash( $password , PASSWORD_DEFAULT );
		$data = array(
				'username' 	=> $username,
				'email' 	=> $email,
				'password' 	=> $hashed_pass,
				'image' 	=> $avatar,
				);
		$result->execute( $data );
		//check if the row was added
		if( $result->rowCount() > 0 ){
			//success
			$feedback = 'Success! You can log in now';
			$feedback_class = 'success';
		}else{
			//error: DB issue
			$feedback = 'Insert failed';
			$feedback_class = 'error';
		}
	}else{
		//error: invalid submission
		$feedback = 'Fix these problems:';
		$feedback_class = 'error';
	}
}//end parser