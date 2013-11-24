<?php 
class UsersController extends AppController{

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('add');
		$this->Auth->allow('login');
	}

	public function login(){

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
		/*else{
			$this->Session->setFlash('Empty data!');
		}*/

	}
	/*
	public function isAuthorized(){
		return false;
	}*/

	

}
?>