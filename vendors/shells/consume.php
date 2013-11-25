<?php
App::import('Core','HttpSocket');

class ConsumeShell extends shell{
	protected static $baseUrl;
	protected static $httpSocket;

	protected static $user;
	protected static $password;

	public function main(){
		if(empty($this->args) || count($this->args) !=3){
			$this->err('USAGE: cake consume <baseUrl> <user> <password>');
			$this->_stop();
		}
		list(self::$baseUrl, self::$user , self::$password ) = $this->args;

		/*$this->out(' base url : '. self::$baseUrl);
		$this->out(' user : '. self::$user);
		$this->out(' password : '. self::$password);*/
		$this->test();
	}

	protected function test(){

		/*$lastId = $this->listPosts();
		$this->hr();
*/
		
		date_default_timezone_set('UTC');
		$dttime = date("Y-m-d H:i:s");
		// 'Add' test
		$this->request('posts/add.json', 'POST', array(
			'title' => 'New Post SANKU ' .  $dttime,
			'body' => 'Body for my new post ' .  $dttime
		));
		//$this->out('Added/inserted new Post Successfully');
		$this->hr();
		
		$lastId = $this->listPosts();
		$this->hr();

		// 'Edit' test
		$dttime = date("Y-m-d H:i:s");
		$this->request('posts/edit/'.$lastId.'.json', 'POST', array(
			'title' => 'New Post title '.  $dttime,
			'body' => 'New Body for my new post '.  $dttime 
		));
		$this->out('Post '.$lastId.' updated successfully');
		$this->hr();

		$lastId = $this->listPosts();
		$this->hr();
		
		//$this->out('lastId '. $lastId);

		// 'View' test
		$this->displaypost($lastId);
		$this->out('GET '.$lastId.' fetched successfully');
		$this->hr();
		

		// 'delete' test
		$this->request('posts/delete/'.$lastId.'.json', 'DELETE');

		$this->out('Post '.$lastId.' deleted successfully');
		$this->hr();
		
		$lastId = $this->listPosts();
	}

	protected function request($url, $method='GET', $data=null){
		if(!isset(self::$httpSocket)){
			self::$httpSocket = new HttpSocket();
		}
		else{
			self::$httpSocket->reset();
		}

		$this->out('requesting : '. self::$baseUrl .'/'.$url );
		$body = self::$httpSocket->request( array(
			'method' => $method ,
			'uri' 	 => self::$baseUrl.'/'.$url ,
			'body' 	 => $data ,
			'auth'   => array(
				'user' => self::$user,
				'pass' => self::$password
			)
		));

		if($body === false || self::$httpSocket->response['status']['code'] !=200){
			$error = 'Error while performing '. $method.' to '.$url;
			if($body !== false){
				$error = '['.self::$httpSocket->response['status']['code'].']'.$error;
			}
			$this->err($error);
			$this->_stop();
		}
		return $body;
	}

	protected function displaypost($id){
		$response = json_decode( $this->request('posts/view/'.$id.'.json', 'GET') );
		if($response == null) $this->out(' its null');
		if($response != null){
			$this->out($response->Post->title . ' : ' . $response->Post->body );
		}
		
		return $response;
	}

	protected function listposts(){
		$response = json_decode($this->request('posts/index.json'));
		//if($response == null) $this->out(' its null');
		
		$lastId = null;
		foreach ($response as $item) {
			$lastId = $item->Post->id;
			$this->out($item->Post->title . ' : ' . $item->Post->url );
		}
		return $lastId;
	}

}
?>