<?php 

class AppController extends Controller{
	
	public $components = array(
			'Auth' =>  array(
				'authorize' => 'controller',
				//,'allowedActions' =>  //list names of method thar needs to be public
				'loginRedirect' => array(
					'admin' => false,
					'controller' => 'users',
					//'action' => 'dashboard'
					'action' => 'login'
				 ),
				'loginError' => 'Invalid acoount specified',
				'authError' => 'You dont have the right permission'
			),
			'Session' 
	);
/*
	public function beforeFilter(){
		$user = $this->Auth->user();
		if(!empty($user)){
			Configure::write('User',$user[ $this->Auth->getModel()->alias] );
		}
		
		//if($this->Auth->getModel()->hasField('active')){
		//	$this->Auth->userScope =  array('active' => 1 );
		//}
	}*/
	
	protected function _isJSON(){
		return $this->RequestHandler->ext == 'json';
	}

	public function isAuthorized(){
		return true;
	}
/*
	public function beforeRender(){
		$user = $this->Auth->user();
		if(!empty($user)){
				$user = $user[ $this->Auth->getModel()->alias];
		}
		$this->set(compact('user'));
	}
*/
}
?>