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

    // receiving the post params
    $unique_code = $_POST['unique_code'];


    $event = $db->deleteEvent($unique_code);
	
	if ($event) {

        $response["error"] = FALSE;
		$response["msg"] = "Event deleted successfully!";

            echo json_encode($response);
    } else {

        $response["error"] = TRUE;
        $response["error_msg"] = "Unknown error occurred in deleting event!";
        echo json_encode($response);
        
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters is missing!";
    echo json_encode($response);
}
?>

