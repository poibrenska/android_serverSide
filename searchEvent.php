<?php

/**
 * @author Eleonora Poibrenska
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if(isset($_POST['unique_code'])){

	$unique_code = $_POST['unique_code'];

    // create a new event
    $event = $db->searchEvent($unique_code);
	
	if ($event) {
        // user stored successfully
        $response["error"] = FALSE;
		$response["event"]["id"] = $event["id"];
		$response["event"]["unique_code"] = $event["unique_code"];
        $response["event"]["name"] = $event["name"];
        $response["event"]["description"] = $event["description"];
        $response["event"]["address"] = $event["address"];
		$response["event"]["user_id"] = $event["user_id"];

            echo json_encode($response);
    } else {
        // user failed to store
        $response["error"] = TRUE;
        $response["error_msg"] = "Event with that code do not exist!";
        echo json_encode($response);
        
    }

} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameter is missing!";
    echo json_encode($response);
}
?>

