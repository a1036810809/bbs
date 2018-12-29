<?php 

class IndexController extends BaseController{
	public function indexAction(){
		$userInfoModel = new UserInfoModel('userinfo');
		$classfityModel = new ClassfityModel('classfity');

		$classs = $classfityModel->getAll();
		//分页获取数据
		$pagesize = 10; //页面大小
		$current = isset($_GET['page']) ? $_GET['page'] : 1; //当前页
		$offset = ($current - 1) * $pagesize;
		$users = $userInfoModel -> pageRows($offset,$pagesize);
		$total = $userInfoModel -> total(); //总共的数据数

		$this->library('Page');
		$page = new Page($total,$pagesize,$current,"index.php",array('p'=>'admin','c'=>'index','act'=>'index'));
		$pageinfo = $page -> showPage();

		include CUR_VIEW_PATH."index.html";
	}

	public function changeAction(){
		$data['username'] = $_GET['id'] + 0;
		$data['identity'] = 3;

		$userInfoModel = new UserInfoModel('userinfo');

		if ($userInfoModel->update($data)){
			$this->jump('index.php?p=admin','调整成功');
		}else{
			$this->jump('index.php?p=admin','调整失败');
		}
	}

	public function clearAction(){
		$data['username'] = $_GET['id'] + 0;
		$data['identity'] = 1;

		$userInfoModel = new UserInfoModel('userinfo');

		if ($userInfoModel->update($data)){
			$this->jump('index.php?p=admin','调整成功');
		}else{
			$this->jump('index.php?p=admin','调整失败');
		}
	}

	public function addcAction(){
		$data['classname'] = $_POST['classname'];

		$classfityModel = new ClassfityModel('classfity');

		if ($classfityModel->insert($data)){
			$this->jump('index.php?p=admin','添加成功');
		}else{
			$this->jump('index.php?p=admin','添加成功');
		}
	}
}