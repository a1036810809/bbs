<?php
//后台基础控制器
Class BaseController extends Controller{
	//构造方法
	public function __construct(){
		$this->checkLogin();
		$this->updateState(); 
	}


	public function updateState(){
		$user = $_SESSION['user'];

		$userinfoModel = new UserInfoModel('userinfo');
		$_SESSION['user'] = $userinfoModel->selectByPk($user['username']);
	}
	//验证用户登陆
	public function checkLogin(){
		//使用session来判断
		if (!isset($_SESSION['user'])){
			$this->jump('index.php?c=login&act=login','请先登录');
		}
	}
}