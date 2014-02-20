<?php
	header( "Content-Type: text/plain" );
	require_once "../../lib/spdo.class.php";

	$uploadDirectory = '../../global/img/upload/circuses/';

	if ( is_uploaded_file( $_FILES['circusPicture']['tmp_name'] ) ):

		if ( isset( $_POST['id'] ) && !empty( $_POST['id'] ) 
		&& isset( $_POST['key'] ) && $_POST['key'] === sha1( "odyssee" ) ):

			$id = $_POST['id'];

			$dbh = SPDO::getInstance();

			$stmt = $dbh->prepare( "SELECT picture FROM circus WHERE id = :id;" );
			$stmt->bindParam( ":id", $id, PDO::PARAM_INT );
			$stmt->execute();
			$picture = current( $stmt->fetch( PDO::FETCH_NUM ) );
			$stmt->closeCursor();

			if ( !$picture ):
				$stmt->prepare( "UPDATE circus SET picture = 1; WHERE id = :id" );
				$stmt->bindParam( ":id", $id, PDO::PARAM_INT );
				$stmt->execute();
				$stmt->closeCursor();
			endif;

			$fileName = $uploadDirectory . $id . ".jpg";
			
			if ( move_uploaded_file( $_FILES['circusPicture']['tmp_name'], $fileName ) ):
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