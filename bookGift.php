<?php

/**
 * @author Eleonora Poibrenska
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if(isset($_POST['user_id']) && isset($_POST['id'])){

    // receiving the post params
    $user_id = $_POST['user_id'];
	$id = $_POST['id'];

    $gift = $db->bookGift($user_id, $id);
	if ($gift) {

        $response["error"] = FALSE;
		$response["msg"] = "You book this gift successfully!";
		
		echo json_encode($response);
    } else {

        $response["error"] = TRUE;
        $response["error_msg"] = "Unknown error occurred in booking gift!";
        echo json_encode($response);
        
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters is missing!";
    echo json_encode($response);
}
?>

