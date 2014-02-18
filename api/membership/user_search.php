<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['username'] ) && !empty( $_GET['username'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT * FROM user WHERE username like :username;");
		$username = "%".$_GET['username']."%";
		$stmt->bindParam( "username", $username, PDO::PARAM_STR );
		$stmt->execute();
		if ( $users = $stmt->fetchAll( PDO::FETCH_ASSOC ) ):
			$response = array( "error" => false );
			foreach ( $users as &$user ):
				if ( (int)$user["picture"] === 0 ):
					$user["picture"] = "http://" . $_SERVER['HTTP_HOST'] .
						relPathToUri( "../../global/img/upload/users/no.jpg" );
				else:
					$user["picture"] = "http://" . $_SERVER['HTTP_HOST'] .
						relPathToUri( "../../global/img/upload/users/" . $user["id"] . ".jpg" );
				endif;
			endforeach;
			$response["users"] = $users;
			array_walk_recursive( $response, 'conv' );
		else:
			$response = array( "error" => true, "stack_trace" => "no match" );
		endif;
		$stmt->closeCursor();
	else:
		$response = array( "error" => true, "stack_trace" => "empty" );
	endif;
	print json_encode( $response );