<?php
//首页
class IndexController extends BaseController{
	public function indexAction(){
		$user = $_SESSION['user'];
		//模型
		$articleModel = new ArticleModel('article');
		$classfityModel = new ClassfityModel('classfity');
		
		//读取数据
		$classs = $classfityModel->getAll();
		$ans = array();

		foreach ($classs as $key => $value) {
			$num = $articleModel->total("classname = '".$value['classname']."'");
			$ans[$key]['classfity'] = $value;
			$ans[$key]['num'] = $num;
		}
		include CUR_VIEW_PATH."index.html";
	}
}