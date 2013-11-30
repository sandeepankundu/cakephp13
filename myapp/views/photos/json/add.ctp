<?php

if(!empty($success) && $success== true){
	$arrayResponse = array('success' =>  true);//, 'token' => $token );
	echo json_encode($arrayResponse);
}else{
	$arrayName = array('success' => false, 
		'message' => !empty($message) ? $message: 'File not uploaded.',
		'errors' => !empty($errors) ? $errors :null
	 );
	echo json_encode($arrayName);
}
/*
if(!empty($myresponse){
	echo json_encode($myresponse);
}
else{
	$arrayName = array('respone' => 'no response');
	echo json_encode($arrayName);
}*/
?>