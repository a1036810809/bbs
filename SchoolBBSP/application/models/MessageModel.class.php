<?php

class MessageModel extends Model{
	public function getAllByWhere($where = '1'){
		$sql = "select * from $this->table where $where";

		return $this->db->getAll($sql);
	}

	public function getAllPage($offset,$limit,$where = ''){
		if ($where == ''){
			$sql = "select * from {$this->table} order by starttime DESC limit $offset, $limit";
		}else{
			$sql = "select * from {$this->table} where $where order by starttime DESC limit $offset, $limit";
		}

		return $this->db->getAll($sql);
	}
}