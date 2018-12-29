<?php
class AttentionModel extends Model{
	public function getAllByWhere($where = '1'){
		$sql = "select * from $this->table where $where";

		return $this->db->getAll($sql);
	}

	public function getAllPage($offset,$limit,$where = ''){
		if ($where == ''){
			$sql = "select * from {$this->table} limit $offset, $limit";
		}else{
			$sql = "select * from {$this->table} where $where limit $offset, $limit";
		}

		return $this->db->getAll($sql);
	}
}
