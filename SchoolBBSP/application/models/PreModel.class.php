<?php
/**
 *  点赞模型类
 */
class PreModel extends Model
{
	public function getUserByRespId($Resp_id){
		$sql = "select username from {$this->table} where resp_id = {$Resp_id}";

		return $this->db->getAll($sql);
	}

	public function getIdByRespId($Resp_id){
		$sql = "select pre_id from {$this->table} where resp_id = {$Resp_id}";

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