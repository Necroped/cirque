<?php
	header( "Content-Type: text/plain" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['id'] )
	&& !empty( $_GET['id'] )
	&& isset( $_GET['firstName'] ) 
	&& !empty( $_GET['firstName'] )
	&& isset( $_GET['lastName'] ) 
	&& !empty( $_GET['lastName'] )
	&& isset( $_GET['password1'] ) 
	&& !empty( $_GET['password1'] )
	&& isset( $_GET['password2'] ) 
	&& !empty( $_GET['password2'] )
	&& $_GET['password1'] === $_GET['password2']
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):
		$dbh = SPDO::getInstance();
		$id = $_GET['id'];
		$firstName = $_GET['firstName'];
		$lastName = $_GET['lastName'];
		$password = $_GET['password1'];

		$stmt = $dbh->prepare( "UPDATE user SET firstName = :firstName, lastName = :lastName password = sha1(:password); WHERE id = :id" );
		$stmt->bindParam( ":firstName", $firstName, PDO::PARAM_STR );
		$stmt->bindParam( ":lastName", $lastName, PDO::PARAM_STR );
		$stmt->bindParam( ":password", $password, PDO::PARAM_STR );
		$stmt->bindParam( ":id", $id, PDO::PARAM_STR );
		if ( $stmt->execute() ):
			$result = array( "error" => false );
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
				"stack_trace" => "bad key / wrong params / passwords don't match"
			);
	endif;
	print json_encode( $result );