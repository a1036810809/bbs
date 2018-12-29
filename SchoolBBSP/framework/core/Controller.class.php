<?php
//核心控制器
class Controller{
	//提示信息
	public function jump($url,$message,$wait = 2){
		if ($wait == 0){
			header("Location:$url");
		}else{
			include CUR_VIEW_PATH."message.html";
		}
		exit;//退出防止继续执行下面的代码
	}

	//加载工具类
	public function library($lib){
		include LIB_PATH . "{$lib}.class.php";
	}

	//加载辅助函数
	public function helper($helper){
		include HELPER_PATH . "{$helper}.php";
	}
}