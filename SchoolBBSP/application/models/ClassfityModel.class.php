<?php
/**
 * 类别
 *
 */
class ClassfityModel extends Model{
	public function getAll(){
		$sql = "select * from $this->table";

		return $this->db->getAll($sql);
	}
}