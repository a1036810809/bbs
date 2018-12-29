<?php
//帖子模型类
class ArticleModel extends Model{

	public function getAll($where = '1'){
		$sql = "select * from $this->table where $where";
		
		return $this->db->getAll($sql);
	}

	//获取全部信息
	public function getAllArticle(){
		$sql = "select * from {$this->table} order by endTime DESC";

		return $this->db->getAll($sql);
	}

	public function getAllArticleByPage($offset,$limit,$where = ''){
		if ($where == ''){
			$sql = "select * from {$this->table} order by endTime DESC limit $offset, $limit";
		}else{
			$sql = "select * from {$this->table} where $where order by endTime DESC limit $offset, $limit";
		}

		return $this->db->getAll($sql);
	}
}