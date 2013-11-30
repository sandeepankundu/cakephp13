<?php 
// app/models/photo.php
class Photo extends AppModel {
    var $name = 'Photo';

    public $validate = array(
	    'type' => array(
	        'rule'    => array('inList', array(
	        									'image/gif', 
	        									'image/jpeg',
	        									'image/png', 
	        									'image/bmp',
	        									'image/tiff'
	        									)),
	        'message' => 'File format upload not supported.'
	    )/*,
	    'size' => array(
	        'rule' => array('fileSize', '<=', '1MB'),
	        'message' => 'Image must be less than 1MB'
    	)*/
	);
}
?>