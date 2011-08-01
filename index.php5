<?php 
/**
 * PHPCodesnipp.it
 * EXAMPLE REQUESTS
 * 
 * @author Sebastian Roming
 */

require_once './PHPCodesnippit.class.php5';

define('API_KEY', 'PUT_YOUR_API_KEY_HERE');

$test = new PHPCodesnippit();

try {
	$authToken 			= $test->apiCall('auth', 	array(API_KEY));
} catch (Exception $ex) {
	echo 'ERROR: ' . $ex->getMessage();
}

$status 			= $test->apiCall('status', 	array('auth_token'=>$authToken), 'POST');
$methods 			= $test->apiCall('listMethods');
$categories 		= $test->apiCall('categories');
$timeline_everyone 	= $test->apiCall('timeline/everyone', array('limit'=>10), 'POST');

$snippit_body 		= '<script>document.getElementById("test");</script>';
$snippit_add 		= $test->apiCall('snippit/add', array('snippit_name'=>'PHPCodesnipp.it API Test', 'snippit_type'=>8, 'snippit_body'=>$snippit_body, 'snippit_category'=>9, 'snippit_tags'=>'test,api,codesnipp.it', 'auth_token'=>$authToken), 'POST');

$user				= $test->apiCall('view_user/sebastian');

echo '<pre>';
var_dump($status);
var_dump($methods);
var_dump($categories);
var_dump($timeline_everyone);
var_dump($snippit_add);
var_dump($user);
?>