<?php
/*
 *userInfo
 */
class UserInfoModel extends Model{
	function getAllUserInfo(){
		$sql = "select * from {$this->table}";

		return $this->db->getAll($sql);
	}

}