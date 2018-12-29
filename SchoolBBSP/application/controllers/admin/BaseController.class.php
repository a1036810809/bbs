<?php
//后台基础控制器
Class BaseController extends Controller{
	//构造方法
	public function __construct(){
		$this->checkLogin();
	}

	//验证用户登陆
	public function checkLogin(){
		//使用session来判断
		if (!isset($_SESSION['admin'])){
			$this->jump('index.php?c=login&act=login','请先登录');
		}
	}
}