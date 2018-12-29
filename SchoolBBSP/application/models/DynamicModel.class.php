<?php
/*
 * 动态模型
 */
class DynamicModel extends Model{

	public function getAllPage($offset,$limit,$where = ''){
		if ($where == ''){
			$sql = "select * from {$this->table} order by starttime DESC limit $offset, $limit";
		}else{
			$sql = "select * from {$this->table} where $where order by starttime DESC limit $offset, $limit";
		}

		return $this->db->getAll($sql);
	}
}