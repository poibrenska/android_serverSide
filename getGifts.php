<?php

/**
 * @author Eleonora Poibrenska
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if(isset($_POST['event_id'])){

	$event_id = $_POST['event_id'];

    // create a new event
    $gifts = $db->getGifts($event_id);
	
	$response["gifts"] = $gifts;
	echo json_encode($response);

} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameter is missing!";
    echo json_encode($response);
}
?>

