<?php

class PostsController extends AppController{

	public $components = array('RequestHandler', 'Security');

	protected function _isJSON(){
		return $this->RequestHandler->ext == 'json';
	}

	public function beforeFilter(){
		parent::beforeFilter();

		if($this->_isJSON()){
			$this->Auth->allow($this->action);
			$this->Security->loginOptions  = array(
				'type'  => 'basic',
				'realm' => 'My rest services',
				'login' => '_restLogin'
			);
			$this->Security->requireLogin();//$this->action);
			$this->Security->validatePost = false;
		}

		if($this->_isJSON() && !$this->RequestHandler->isGet()){
			if( empty($this->data) && !empty($_POST)){
				$this->data[$this->modelClass] = $_POST;
			}
		}
	}

	public function _restLogin($credentials){
		$login = array();
		foreach ( array('username' ,'password' ) as $field ) {
			$value = $credentials[$field];
			if($field == 'password' && !empty($value)){
				$value = $this->Auth->password($value);
			}
			$login[ $this->Auth->fields[$field]] = $value;
		}
		if( !$this->Auth->login($login)){
			$this->Security->blackhole($this, 'login');
		}
	}

	public function beforeRender(){
		parent::beforeRender();
		if($this->_isJSON()){
			Configure::write('debug',0);
			//Configure::write('debug',2);
			$this->disableCache();
		}
	}

	public function index(){
		if($this->_isJSON() && !$this->RequestHandler->isGet() ){
			$this->redirect(null, 400);
		}
		$posts = $this->Post->find('all');
		$this->set(compact('posts'));
	}


	public function showme(){
		$posts = $this->Post->find('all');
		$this->set(compact('posts'));
	}

	public function listposts(){
		$posts = $this->Post->find('all');
		$this->set(compact('posts'));
	}

	public function add(){
		$this->setAction('edit');
	}
	public function view($id){
		if($this->_isJSON() && !$this->RequestHandler->isGet() ){
			$this->redirect(null, 400);
		}
		$post = $this->Post->find( 'first', array('conditions' =>  array('Post.id' => $id ) ));
		if(empty($post)){
			if($this->_isJSON() ){
				$this->redirect(null, 404);
			}
			$this->cakeError('error404');
		}
		//if(!empty($post)){
			$this->set(compact('post'));
		//}
	}

	public function edit($id=null){

		if($this->_isJSON() && !$this->RequestHandler->isPost() ){
			$this->redirect(null, 400);
		}

		if(!empty($this->data)){
			if(!empty($id)){
				$this->Post->id =  $id;
			}else{
				$this->Post->create();
			}
			if($this->Post->save($this->data)){
				$this->Session->setFlash('Post created successfully!!!');
				if($this->_isJSON() ){
					$this->redirect(null, 200);
				}
				else{
					$this->redirect(array('action' => 'index' ));
				}
			}else{
				if($this->_isJSON() ){
					$this->redirect(null, 403);
				}
				else{
					$this->Session->setFlash('Please correct the errors marked below!!!');
				}
				
			}
		}elseif(!empty($id)){
			$this->data = $this->Post->find( 'first', array('conditions' =>  array('Post.id' => $id ) ));
			if(empty($this->data)){
				if($this->_isJSON() ){
					$this->redirect(null, 404);
				}
				$this->cakeError('error404');
			}
		}
		$this->set(compact('id'));
	}

	public function delete($id){

		if($this->_isJSON() && !$this->RequestHandler->isDelete() ){
			$this->redirect(null, 400);
		}
		$post = $this->Post->find( 'first', array('conditions' =>  array('Post.id' => $id ) ));
		if(empty($post)){
			if($this->_isJSON() ){
				$this->redirect(null, 404);
			}
			$this->cakeError('error404');
		}
		if(!empty($post)){
			if($this->Post->delete($id)){
				$this->Session->setFlash('Post deleted succcessfully');
				
				if($this->_isJSON() ){
					$this->redirect(null, 200);
				}
				else{
					$this->redirect(array('action' => 'index' ));
				}
			}else{
				if($this->_isJSON() ){
					$this->redirect(null, 403);
				}
				else{
					$this->Session->setFlash('Could not delete post!!!');
				}
			}
		}
		$this->set(compact('post'));
	}
}

?>