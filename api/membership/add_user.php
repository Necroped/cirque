<?php
	header( "Content-Type: text/plain" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['username'] ) 
	&& !empty( $_GET['username'] )
	&& isset( $_GET['password'] ) 
	&& !empty( $_GET['password'] )
	&& isset( $_GET['email'] ) 
	&& !empty( $_GET['email'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):
		$dbh = SPDO::getInstance();
		$username = $_GET['username'];
		$password = $_GET['password'];
		$email = $_GET['email'];

		$stmt = $dbh->prepare( "INSERT INTO user (username, password, email) VALUES (:username, sha1(:password), :email);" );
		$stmt->bindParam( ":username", $username, PDO::PARAM_STR );
		$stmt->bindParam( ":password", $password, PDO::PARAM_STR );
		$stmt->bindParam( ":email", $email, PDO::PARAM_STR );
		if ( $stmt->execute() ):
			$result = array(
				"error" => false,
				"id" => $dbh->lastInsertId(),
			);
		else:
			$result = array(
				"error" => true,
				"stack_trace" => "wrong params"
			);
		endif;
		$stmt->closeCursor();
	else:
		$result = array(
				"error" => true,
				"stack_trace" => "bad key / wrong params"
			);
	endif;
	print json_encode( $result );