<?php

/**
 * @author Eleonora Poibrenska
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if(isset($_POST['name']) && isset($_POST['description']) && isset($_POST['event_id'])){

    // receiving the post params
    $name = $_POST['name'];
    $description = $_POST['description'];
	$event_id = $_POST['event_id'];

    // create a new event
    $gift = $db->storeGift($name, $description, $event_id);
	
	if ($gift) {
// user failed to store
        $response["error"] = FALSE;
        $response["msg"] = "Gift stored successfully!";
        echo json_encode($response);
    } else {
        // user failed to store
        $response["error"] = TRUE;
        $response["error_msg"] = "Unknown error occurred in creating gift!";
        echo json_encode($response);
        
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters is missing!";
    echo json_encode($response);
}
?>

