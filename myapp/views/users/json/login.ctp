<?php
if(!empty($token)){
	$arrayResponse = array('success' =>  true, 'token' => $token );
	echo json_encode($arrayResponse);
}else{
	$arrayName = array('success' => false, 'message' => 'Invalid username/password' );
	echo json_encode($arrayName);
}
?>