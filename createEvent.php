<?php

/**
 * @author Eleonora Poibrenska
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if(isset($_POST['name']) && isset($_POST['description']) && isset($_POST['address']) && isset($_POST['user_id'])){

    // receiving the post params
    $name = $_POST['name'];
    $description = $_POST['description'];
    $address = $_POST['address'];
	$user_id = $_POST['user_id'];

    // create a new event
    $event = $db->storeEvent($name, $description, $address, $user_id);
	
	if ($event) {
        // user stored successfully
        $response["error"] = FALSE;
		$response["event"]["id"] = $event["id"];
		$response["event"]["code"] = $event["unique_code"];
        $response["event"]["name"] = $event["name"];
        $response["event"]["description"] = $event["description"];
        $response["event"]["address"] = $event["address"];
		$response["event"]["user_id"] = $event["user_id"];

            echo json_encode($response);
    } else {
        // user failed to store
        $response["error"] = TRUE;
        $response["error_msg"] = "Unknown error occurred in creating event!";
        echo json_encode($response);
        
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters is missing!";
    echo json_encode($response);
}
?>

