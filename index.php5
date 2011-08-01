<?php 
/**
 * PHPCodesnipp.it-LIGHT
 * EXAMPLE REQUESTS
 * 
 * @author Sebastian Roming
 * @license CC-BY-SA 3.0
 */

require_once './PHPCodesnippit.class.php5';

// set your API Key (get it in your codesnipp.it profile settings)
define('API_KEY', 'PUT_YOUR_API_KEY_HERE');

$test = new PHPCodesnippit();

try {
	// authenticate against codesnipp.it server
	$authToken = $test->apiCall('auth', array(API_KEY));
} catch (Exception $ex) {
	// whoops, something failed while trying to authenticate
	echo 'ERROR: ' . $ex->getMessage();
}

// request API status
$status = $test->apiCall('status', 	null, 'POST');

// list of available methods
$methods = $test->apiCall('listMethods');

// list of available categories
$categories = $test->apiCall('categories');

// list of public timeline (limit set to 10)
$timeline_everyone = $test->apiCall('timeline/everyone', array('limit'=>10), 'POST');

// adding a snipp.it (private snipp.it, category: JavaScript)
$snippit_body = '<script>document.getElementById("test");</script>';
$snippit_add = $test->apiCall('snippit/add', array('snippit_name'=>'PHPCodesnipp.it API Test', 'snippit_type'=>8, 'snippit_body'=>$snippit_body, 'snippit_category'=>9, 'snippit_tags'=>'test,api,codesnipp.it'), 'POST');

// request user information
$user = $test->apiCall('view_user/sebastian');


// dump all responses
echo '<pre>';
var_dump($status);
var_dump($methods);
var_dump($categories);
var_dump($timeline_everyone);
var_dump($snippit_add);
var_dump($user);
echo '</pre>';
?>