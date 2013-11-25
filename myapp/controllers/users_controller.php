<?php 
class UsersController extends AppController{

	public $components = array('RequestHandler',  'Security');//, 'Auth');

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('add');
		$this->Auth->allow('login');
	}
	public function token(){
		$token = sha1(String::uuid());
		$this->User->id = $this->Auth->user('id');
		if(!$this->User->saveField('token', $token)){
			$token = null;
			$this->Session->setFlash('There was an error generating this token');
		}
		$this->set(compact('token'));
	}

	public function login(){
		/*if($this->_isJSON() && !$this->RequestHandler->isPost() ){
			$this->redirect(null, 400);
		}
		if($this->_isJSON() && $this->RequestHandler->isPost() ){

			$data['User']['username'] = $_POST['username'];
			$data['User']['password'] = $_POST['password'];
			
			$hashedpwd = $this->Auth->hashPasswords($data);
	        
	        $model = $this->Auth->getModel();
	        $user = $model->customAuthenicate($hashedpwd['User']['username'], $hashedpwd['User']['password']);

			if(!empty($user)){
				$this->Auth->user = $user['User'];
			}
			
			$this->User->id = $this->Auth->user['id'];
			
			$token = sha1(String::uuid());
			
			if(!$this->User->saveField('token', $token)){
				$token = null;
			}
			$this->set(compact('token')); 
		}*/
	}

	public function logout(){
		$this->redirect($this->Auth->logout());
	}

	public function dashboard(){

	}


	public function add(){
		if(!empty($this->data)){
			$this->User->Create();
			// password is hashed via security.salt in config/core.php
			if($this->User->save($this->data)){
				$this->Session->setFlash('User Created!');
				$this->redirect(array('action'=>'login'));
			}else{
				$this->Session->setFlash('Please correct the errors');
			}
		}

	}
	
}
?>