<?php
//回复模型类
class RespondModel extends Model{
	//获取
	public function getRespondsByWhere($where = ''){
		$sql = "select * from {$this->table} where {$where}";

		return $this->db->getAll($sql);
	}

	public function getUsernameByWhere($where = ''){
		$sql = "select username from {$this->table} where {$where}";

		return $this->db->getAll($sql);
	}

	public function getColsByWhere($cols = '*',$where = ''){
		$sql = "select {$cols} from {$this->table} where {$where}";

		return $this->db->getAll($sql);
	}

	public function deleteByWhere($where){
		$sql = "DELETE FROM `{$this->table}` WHERE $where";

		if ($this->db->query($sql)) {
			# 成功，并判断受影响的记录数
			if ($rows = $this->db->getAffectedRows()) {
				# 有受影响的记录
				return $rows;
			} else {
				# 没有受影响的记录
				return false;
			}		
		} else {
			# 失败返回false
			return false;
		}
	}
}