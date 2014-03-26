<?php
	header( "Content-Type: text/plain" );
	require_once "../../lib/spdo.class.php";

	$uploadDirectory = '../../global/img/upload/pictures/';

	if ( is_uploaded_file( $_FILES['eventPicture']['tmp_name'] ) ):

		if ( isset( $_POST['user'] ) && !empty( $_POST['user'] ) 
		&& isset( $_POST['event'] ) && !empty( $_POST['event'] ) 
		&& isset( $_POST['key'] ) && $_POST['key'] === sha1( "odyssee" ) ):

			$user = $_POST['user'];
			$event = $_POST['event'];

			$dbh = SPDO::getInstance();

			$stmt = $dbh->prepare( "INSERT INTO picture (event, user) VALUES (:event, :user);" );
			$stmt->bindParam( ":event", $event, PDO::PARAM_INT );
			$stmt->bindParam( ":user", $user, PDO::PARAM_INT );
			$stmt->execute();
			$stmt->closeCursor();

			$fileName = $uploadDirectory . $id . ".jpg";
			
			if ( move_uploaded_file( $_FILES['eventPicture']['tmp_name'], $fileName ) ):
				echo true;
			else:
				echo false . PHP_EOL;
				echo "Couldn't move file";
			
			endif;
		else:
			echo false . PHP_EOL;
			echo "No id";
		endif;

	else:
		echo false . PHP_EOL;
		echo "No file";
	endif;