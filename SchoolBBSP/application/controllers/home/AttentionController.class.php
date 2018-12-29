<?php

/**
 * 
 */
class AttentionController extends Controller
{
	public function closeAction(){
		$user = $_SESSION['user'];
		//收集数据
		$username = $_SESSION['user']['username'];
		$id = $_GET['id'] + 0;

		//实例化
		$attentionModel = new AttentionModel('attention');

		if ($att = $attentionModel->getAllByWhere("username_ed = '{$id}' and username_att = '{$username}'")){
			if ($attentionModel->delete($att['atten_id'])){
				$this -> jump('index.php?c=user&act=showUserInfo&id='.$id,"取关成功",1);
			}else{
				$this -> jump('index.php?c=user&act=showUserInfo&id='.$id,"取关失败");
			}
		}else{
			$this -> jump('index.php?c=user&act=showUserInfo&id='.$id,"你未关注该用户");
		}
	}

	public function addAction(){
		$user = $_SESSION['user'];
		//收集数据
		$data['username_att'] = $_SESSION['user']['username'];
		$data['username_ed'] = $_GET['id'] + 0;

		//实例化
		$attentionModel = new AttentionModel('attention');

		if ($att = $attentionModel->getAllByWhere("username_ed = '".$data['username_ed']."' and username_att = '".$data['username_att']."'")){
			$this -> jump('index.php?c=user&act=showUserInfo&id='.$data['username_ed'],"你已关注该用户");
		}else if ($attentionModel->insert($data)){
			$this -> jump('index.php?c=user&act=showUserInfo&id='.$data['username_ed'],"关注成功",1);
		}else{
			$this -> jump('index.php?c=user&act=showUserInfo&id='.$data['username_ed'],"关注失败");
		}
	}

	public function showAttAction(){
		$user = $_SESSION['user'];
		$username = $_SESSION['user']['username'];
		$id = $_GET['id'] + 0;

		//实例化
		$attentionModel = new AttentionModel('attention');
		$userInfoModel = new UserInfoModel('userinfo');

		if ($user = $userInfoModel->selectByPk($id)){
			if ($username == $id){
				$title = "我的关注";
			}else{
				$title = $user['nickname']."的关注";
			}
			//分页获取数据
			$pagesize = 10; //页面大小
			$current = isset($_GET['page']) ? $_GET['page'] : 1; //当前页
			$offset = ($current - 1) * $pagesize;
			$ans = $attentionModel -> getAllPage($offset,$pagesize,"username_att = '{$id}'");
			$total = $attentionModel -> total("username_att = '{$id}'"); //总共的数据数

			$this->library('Page');
			$page = new Page($total,$pagesize,$current,"index.php",array('p'=>'home','c'=>'attention','act'=>'showAtt'));
			$pageinfo = $page -> showPage();

			$users = array();

			foreach ($ans as $key => $value) {
				# code...
				$users[] = $userInfoModel->selectByPk($value['username_ed']);
			}

			include CUR_VIEW_PATH.'showatten.html';
		}else{
			$this -> jump('index.php',"非法访问");
		}
	}

	public function showFensAction(){
		$user = $_SESSION['user'];
		$username = $_SESSION['user']['username'];
		$id = $_GET['id'] + 0;

		//实例化
		$attentionModel = new AttentionModel('attention');
		$userInfoModel = new UserInfoModel('userinfo');

		if ($user = $userInfoModel->selectByPk($id)){
			if ($username == $id){
				$title = "我的粉丝";
			}else{
				$title = $user['nickname']."的粉丝";
			}
			//分页获取数据
			$pagesize = 10; //页面大小
			$current = isset($_GET['page']) ? $_GET['page'] : 1; //当前页
			$offset = ($current - 1) * $pagesize;
			$ans = $attentionModel -> getAllPage($offset,$pagesize,"username_ed = '{$id}'");
			$total = $attentionModel -> total("username_ed = '{$id}'"); //总共的数据数

			$this->library('Page');
			$page = new Page($total,$pagesize,$current,"index.php",array('p'=>'home','c'=>'attention','act'=>'showFnes'));
			$pageinfo = $page -> showPage();

			$users = array();

			foreach ($ans as $key => $value) {
				# code...
				$users[] = $userInfoModel->selectByPk($value['username_att']);
			}

			include CUR_VIEW_PATH.'showatten.html';
		}else{
			$this -> jump('index.php',"非法访问");
		}
	}
}