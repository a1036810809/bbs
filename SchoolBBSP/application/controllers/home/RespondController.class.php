<?php
/**
 * 回复控制类
 */
class RespondController extends BaseController
{
	//默认
	public function indexAction(){
		$this->jump('index.php?c=index','页面访问错误');
	}

	public function deleteRespondAction(){
		//获取数据
		$resp_id = $_GET['resp_id'] + 0;

		//实例化模型
		$respondModel = new RespondModel('respond_arti');
		$preModel = new PreModel('pre_resp');
		$pre_ids = $preModel -> getIdByRespId($resp_id);
		if ($respondModel -> delete($resp_id)){
			foreach ($pre_ids as $pre_id){
				$preModel -> delete($pre_id);
			}
			$this->jump("index.php?c=article&act=showPage&id={$data['arti_id']}",'删除成功！',2);
		}else{
			$this->jump("index.php?c=article&act=showPage&id={$data['arti_id']}",'删除失败！',2);
		}
	}

	//添加回复
	public function addRespondAction(){
		//收集数据
		$udata['arti_id'] = $data['arti_id'] = trim($_POST['arti_id']);
		$data['content'] = trim($_POST['content']);
		$udata['endTime'] = $data['starttime'] = date('Y-m-d H:i:s');
		$data['username'] = $_SESSION['user']['username'];

		//批量转移
		$this->helper("input");
		$data = deepSpecialChars($data);
		$udata = deepSpecialChars($udata);

		//实例化模型
		$respondModel = new RespondModel('respond_arti');
		$articleModel = new ArticleModel('article');


		if ($respondModel -> insert($data)){
			$articleModel -> update($udata);
			$this->jump("index.php?c=article&act=showPage&id={$data['arti_id']}",'回复成功！',2);
		}else{
			$this->jump("index.php?c=article&act=showPage&id={$data['arti_id']}",'回复失败！');
		}
	}

	public function delFDynAction(){
		//收集数据
		$username = $_SESSION['user']['username'];
		$id = $_GET['id'] + 0;

		//实例化对象
		$respondModel = new RespondModel('respond_dyna');
		if ($resp = $respondModel -> getRespondsByWhere("resp_id = $id and username = '{$username}'")){
			if ($respondModel->delete($id)){
				$this->jump("index.php?c=user&act=showUserInfo&id=$username",'删除成功！',1);
			}{
				$this->jump("index.php?c=user&act=showUserInfo&id=$username",'删除失败！');
			}
		}{
			$this->jump("index.php?c=user&act=showUserInfo&id=$username",'访问非法！');
		}
	}
}