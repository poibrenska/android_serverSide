<?php

/**
 * @author Eleonora Poibrenska
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if(isset($_POST['id'])){

    // receiving the post params
	$id = $_POST['id'];

    $gift = $db->returnGift($id);
	if ($gift) {

        $response["error"] = FALSE;
		$response["msg"] = "This gift is not booked from you anymore!";

		echo json_encode($response);
    } else {

        $response["error"] = TRUE;
        $response["error_msg"] = "Unknown error occurred in reurning gift!";
        echo json_encode($response);

    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters is missing!";
    echo json_encode($response);
}
?>
