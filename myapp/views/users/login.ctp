<?php
	echo $this->Form->create();
	echo $this->Form->inputs(
			array(	'legend' =>'Login',
					'username' =>  array('label' => 'Login Name'),
					'password' =>  array('label' => 'Secret Passcode')));

	echo $this->Form->end('Login');
?>