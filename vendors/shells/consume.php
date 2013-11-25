<?php
App::import('Core','HttpSocket');

class ConsumeShell extends shell{
	protected static $baseUrl;
	protected static $httpSocket;

	public function main(){
		if(empty($this->args) || count($this->args) !=1){
			$this->err('USAGE: cake consume <baseUrl>');
			$this->_stop();
		}
		self::$baseUrl = $this->args[0];
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
			'title' => 'New Post AA3 ' .  $dttime,
			'body' => 'Body for my new post ' .  $dttime
		));
		$this->out('Added/inserted new Post Successfully');
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

		// 'delete' test
		$this->request('posts/delete/'.$lastId.'add.json', 'DELETE');

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
			'uri' => self::$baseUrl.'/'.$url ,
			'body' => $data 
		));

		if($body ===false || self::$httpSocket->response['status']['code'] !=200){
			$error = 'Error while performing '. $method.' to '.$url;
			if($body !== false){
				$error = '['.self::$httpSocket->response['status']['code'].']'.$error;
			}
			$this->err($error);
			$this->_stop();
		}
		return $body;
	}

	protected function listposts(){
		$response = json_decode(($this->request('posts/index.json')));
		//$this->out('2. listPosts -> '.$response);
		if($response == null) $this->out(' its null');
		
		$lastId = null;
		foreach ($response as $item) {
			$lastId = $item->Post->id;
			$this->out($item->Post->title . ' : ' . $item->Post->url );
		}
		return $lastId;
	}

}
?>