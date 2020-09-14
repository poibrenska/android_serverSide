<?php

/**
 * @author Eleonora Poibrenska
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

class DB_Functions {

    private $conn;

    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    // destructor
    function __destruct() {
        
    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $password) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at) VALUES(?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $uuid, $name, $email, $encrypted_password, $salt);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $user;
        } else {
            return false;
        }
    }

    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");

        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $user;
            }
        } else {
            return NULL;
        }
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT email from users WHERE email = ?");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }
	
	    /**
     * Storing new event
     * returns event details
     */
    public function storeEvent($name, $description, $address, $user_id) {
        $unique_code = uniqid('');

        $stmt = $this->conn->prepare("INSERT INTO events(unique_code, name, description, address, user_id) VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $unique_code, $name, $description, $address, $user_id);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM events WHERE unique_code = ?");
            $stmt->bind_param("s", $unique_code);
            $stmt->execute();
            $event = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $event;
        } else {
            return false;
        }
    }
	
	public function getAllUserEvents($unique_id){
		    $stmt = $this->conn->prepare("SELECT * FROM events WHERE user_id = ?");
            $stmt->bind_param("s", $unique_id);
            $stmt->execute();
			$result = $stmt->get_result();
            $stmt->close();
			
			
			$rows = array();
			if($result){
				while($row = $result->fetch_assoc()){
					$rows[] = $row;
				}
			}
			return $rows;
	}
	
	public function searchEvent($unique_code){
		    $stmt = $this->conn->prepare("SELECT * FROM events WHERE unique_code = ?");
            $stmt->bind_param("s", $unique_code);
            $stmt->execute();
            $event = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $event;
	}
	
	public function deleteEvent($unique_code){
			$stmt = $this->conn->prepare("DELETE FROM presents WHERE event_id = ?");
            $stmt->bind_param("s", $unique_code);
            $stmt->execute();
            $stmt->close();
			
		    $stmt = $this->conn->prepare("DELETE FROM events WHERE unique_code = ?");
            $stmt->bind_param("s", $unique_code);
            $result = $stmt->execute();
            $stmt->close();

            return $result;
	}
	
	public function storeGift($name, $description, $event_id){
		$stmt = $this->conn->prepare("INSERT INTO gifts(name, description, event_id) VALUES(?, ?, ?)");
        $stmt->bind_param("sss",$name, $description, $event_id);
        $result = $stmt->execute();
        $stmt->close();

		return $result;
	}
	
	public function getGifts($event_id){
		$stmt = $this->conn->prepare("SELECT * FROM gifts WHERE event_id = ?");
        $stmt->bind_param("s", $event_id);
        $stmt->execute();
		$result = $stmt->get_result();
        $stmt->close();
				
		$rows = array();
			if($result){
				while($row = $result->fetch_assoc()){
					$rows[] = $row;
			}
		}
		return $rows;
	}
	
	public function bookGift($user_id, $id){
		$stmt = $this->conn->prepare("UPDATE gifts SET user_id = ? WHERE id = ?");
        $stmt->bind_param("si",$user_id, $id);
        $result = $stmt->execute();
		$stmt->close();

		return $result;
	}
	
	public function returnGift($id){
		$stmt = $this->conn->prepare("UPDATE gifts SET user_id = null WHERE id = ?");
        $stmt->bind_param("i",$id);
        $result = $stmt->execute();
		$stmt->close();

		return $result;
	}
	
	public function deleteGift($id){
		$stmt = $this->conn->prepare("DELETE FROM gifts WHERE id = ?");            
		$stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
	}
}

?>
