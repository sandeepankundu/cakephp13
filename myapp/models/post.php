<?php

class Post extends AppModel{
	public $validate  = array(
		'title' =>  array('required' => true, 'rule'=> 'notEmpty' ),
		'body' =>  array('required' => true, 'rule'=> 'notEmpty' )
	);
}

?>