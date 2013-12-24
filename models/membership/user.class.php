<?php
	/**
	 * Class User
	 *
	 * @author Antoine De Gieter
	 *
	 */
	class User {
		private $_id; # integer
		private $_username; # string
		private $_password; # string
		private $_firstName; # string
		private $_lastName; # string
		private $_email; # string
		private $_picture; # boolean

		/**
		 *
		 *
		 *
		 *
		 */
		public function _construct( $id, $username = "",
			$password = "", $firstName = "", $lastName = "",
			$email = "", $picture = 0 ) {
			if ( $id === 0 )
				$this->init( $username, $password, $firstName, 
					$lastName, $email, $picture );
			elseif ( User::checkId( $id ) )
				$this->fetchData( $id );
			else
				header( 'Location: 404' );
		}

		/**
		 *
		 *
		 *
		 *
		 *
		 */
		private function init( $username, $password, $firstName, 
			$lastName, $email, $picture ) {
			$this->_username = $username;
			$this->_password = $password;
			$this->_firstName = $firstName;
			$this->_lastName = $lastName;
			$this->_email = $email;
			$this->_picture = $picture;
			$this->create();
		}

		/**
		 * create a new user in the database
		 * from the instance attributes
		 *
		 */
		private function create() {
			$dbh->SPDO::getInstance();
			$stmt = $dbh->prepare( "insert into user(username, password, 
				firstName, lastName, email, picture) values (:username,
				sha1(:password), :firstName, :lastName, :picture);" );
			$stmt->execute( array(
				"username" => $this->_username,
				"password" => $this->_password,
				"firstName" => $this->_firstName,
				"lastName" => $this->_lastName,
				"picture" => $this->_picture
			) );
			$stmt->closeCursor();
			$this->_id = $dbh->lastInsertId();
		}
		/**
		 * @param $id
		 *
		 * get user data from the database
		 *
		 */
		private function fetchData( $id ) {
			$dbh = SPDO::getInstance();
			$stmt = $dbh->prepare( "select * from user where id = :id;" );
			$stmt->bindParam( "id", $id, PDO::PARAM_INT );
			$stmt->execute();
			$user = $stmt->fetch( PDO::FETCH_ASSOC );
			$stmt->closeCursor();
			
			$this->_id = $id;
			$this->_username = $user['username'];
			$this->_password = $user['password'];
			$this->_firstName = $user['firstName'];
			$this->_lastName = $user['lastName'];
			$this->_email = $user['email'];
			$this->_picture = $user['picture'];
		}

		# Static methods

		/**
		 * @param $id
		 *		id that needs to be checked
		 *
		 * return boolean
		 * 		true: id exists
		 * 		false: id doesn't exist
		 */
		public static checkId( $id ) {
			if ( !is_int( (int)$id ) )
				return false;
			
			$dbh = SPDO::getInstance();
			$stmt = $dbh->prepare( "select count(id) from user where id = :id" );
			$stmt->bindParam( "id", $id, PDO::PARAM_INT );
			$stmt->execute();
			$count = $stmt->fetch( PDO::FETCH_NUM );
			if ( (int)$count[0] === 0 )
				return false;
			return true;
		}

		/**
		 * @param $username
		 *		username that needs to be checked
		 *
		 * return integer
		 * 		!0: id that corresponds to the username
		 * 		0: username doesn't exist
		 */
		public static checkUsername( $username ) {
			$dbh = SPDO::getInstance();
			$stmt = $dbh->prepare( "select id from user 
			where username = :username;" );
			$stmt->bindParam( "username", $username, PDO::PARAM_STRING );
			$stmt->execute();
			if ( $id = $stmt->fetch( PDO::FETCH_NUM ) )
				return $id[0];
			return 0;
			
		}
	}
