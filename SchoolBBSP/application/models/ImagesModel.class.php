<?php
class ImagesModel extends Model{
	public function getImagesByArtiId($id){
		$sql = "select * from $this->table where arti_id = $id";

		return $this->db->getAll($sql);
	}
	public function getImagesByDynaiId($id){
		$sql = "select * from $this->table where dynamic_id = $id";

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