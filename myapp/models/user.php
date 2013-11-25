<?php
class User extends AppModel{
	
	public static function get($field = null){
		$user = Configure::read('User');
		if(empty($user) || ( !empty($field) && !array_key_exists($field, $user)) ){
			return false;
		}
		return !empty($field) ? $user[$field] : $user;
	}

	public function customAuthenicate($username, $password){
		$user = $this->find('first', array(
			'conditions' => array( $this->alias.'.username' => $username, $this->alias.'.password' => $password ),
			'recursive' => -1
		));
		return $user;
	}

	public function useToken($token){
		$user = $this->find('first', array(
			'conditions' => array( $this->alias.'.token' => $token ),
			'recursive' => -1
		));
		echo 'Token '.$token;

		if(empty($user)){
			throw new Exception("Token is not valid");
		}
		$apiSettings = Configure::read('API');
		$tokenUsed = !empty($user[$this->alias]['token_used']) ? $user[$this->alias]['token_used'] : null;
		$tokenUses = $user[$this->alias]['token_uses'];

		if(!empty($tokenUsed)){
			$tokenTimeThreshold = strtotime ('+'.
				$apiSettings['time'], strtotime($tokenUsed));
		}
		$now = time();
		if(!empty($tokenUsed) && $now <= $tokenTimeThreshold && $tokenUses>= $apiSettings['maximum']){
			return false;
		}
		$id= $user[$this->alias][$this->primaryKey];
		if(!empty($tokenUsed) && $now <= $tokenTimeThreshold){
			$this->id = $id;
			$this->saveField('token_uses', $token_uses + 1);
		}else{
			$this->id = $id;
			$this->save(
				array('token_used' => date('Y-m-d H:i:s') , 'token_uses'=>1 ),
				false,
				array('token_used', 'token_uses')
			);
		}
		return $id;
	}

}
?>