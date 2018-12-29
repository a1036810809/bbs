<?php
/**
 * user模型
 */
class UserModel extends Model{
	function getAllUser(){
		$sql = "select * from {$this->table}";

		return $this->db->getAll($sql);
	}

	//检查用户
	function checkUser($username,$password){
		$users = $this->getAllUser();

		foreach ($users as $user) {
			if ($user['username'] == $username && $user['password'] == md5($password)){
				return $user;
			}
		}

		return ;
	}
	
}