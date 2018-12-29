<?php
//账号控制器
class UserController extends BaseController{
	//默认
	public function indexAction(){
		$this->jump('index.php?c=index','页面访问错误');
	}
	
	//显示资料
	public function showUserInfoAction(){
		$user = $_SESSION['user'];
		//获取数据
		$id = $_GET['id'] + 0;
		$i = isset($_GET['i']) ? $_GET['i'] + 0 : 1;
		if ($i<1 || $i > 3){
			$i = 1;
		}
		//实例化对象
		$userInfoModel = new UserInfoModel('userinfo');
		$attentionModel = new AttentionModel('attention');
		$falg = 1;

		if ($user['username']!=$id){
			if ($attentionModel->getAllByWhere("username_ed = '{$id}' and username_att = '".$user['username']."'")){
				$falg = 2;
			}else{
				$falg = 3;
			}
		}
		
		if ($i == 1){
			//实例化对象
			$dynamicModel = new DynamicModel('dynamic');
			$imagesModel = new ImagesModel('images');
			$preModel = new PreModel('pre_dynam');
			$respondModel = new respondModel('respond_dyna');

			//分页获取数据
			$pagesize = 10; //页面大小
			$current = isset($_GET['page']) ? $_GET['page'] : 1; //当前页
			$offset = ($current - 1) * $pagesize;
			$ans = $dynamicModel -> getAllPage($offset,$pagesize,"username = ".$id);
			$total = $dynamicModel -> total("username = ".$id); //总共的数据数

			$this->library('Page');
			$page = new Page($total,$pagesize,$current,"index.php",array('p'=>'home','c'=>'user','act'=>'showUserInfo'));
			$pageinfo = $page -> showPage();

			$dynamics = array();

			foreach ($ans as $key => $value) {
				# code...
				$dynamic_id = $value['dynamic_id'];
				$dynamics[$key]['pre_num'] = $preModel->total("dynamic_id = $dynamic_id");
				$dynamics[$key]['dynamic'] = $value;
				$dynamics[$key]['dynamic']['content'] = explode(PHP_EOL, $value['content']);
				$dynamics[$key]['responds'] = $responds = $respondModel->getRespondsByWhere("dynamic_id = $dynamic_id");
				$dynamics[$key]['image'] = $imagesModel->getImagesByDynaiId($dynamic_id);
				$dynamics[$key]['responds_user'] = array();
				$dynamics[$key]['user'] = $userInfoModel-> selectByPk($value['username']);
				foreach ($responds as $v) {
					# code...
					$dynamics[$key]['responds_user'][] = $userInfoModel-> selectByPk($v['username']);
				}

			}
		}else if ($i == 2){
			$att_num = $attentionModel->total("username_att = '".$id."'");
			$fens_num = $attentionModel->total("username_ed = '".$id."'");
		}else if ($i == 3){
			$messageModel = new MessageModel('message');
			//分页获取数据
			$pagesize = 10; //页面大小
			$current = isset($_GET['page']) ? $_GET['page'] : 1; //当前页
			$offset = ($current - 1) * $pagesize;
			$ans = $messageModel -> getAllPage($offset,$pagesize,"username_ed = $id");
			$total = $messageModel -> total("username_ed = $id"); //总共的数据数

			$this->library('Page');
			$page = new Page($total,$pagesize,$current,"index.php",array('p'=>'home','c'=>'user','act'=>'showUserInfo','i' => 3));
			$pageinfo = $page -> showPage();

			$msginfo = array();
			foreach ($ans as $key => $value) {
				# code...
				$msginfo[$key]['message'] = $value;
				$msginfo[$key]['message']['content'] = explode(PHP_EOL, $value['content']);
				$msginfo[$key]['user'] = $userInfoModel->selectByPk($value['username_mes']);
			}
		}
		if ($id == $user['username']){
			$userinfo = $user;
			$title = "我";
		}else{
			$userinfo = $userInfoModel -> selectByPk($id);
			if ($userinfo == null){
				$this->jump('index.php?c=index','页面访问错误');
			}
			$title = "{$userinfo['nickname']}";
		}

		include CUR_VIEW_PATH . "userInfopage.html";
	}

	public function setInfoPageAction(){
		$user = $_SESSION['user'];

		include CUR_VIEW_PATH . "setuserinfopage.html";
	}

	public function updateInfoAction(){
		//获取数据
		$data['username'] = $_SESSION['user']['username'];
		$data['nickename'] = trim($_POST['nickename']);
		$data['sex'] = $_POST['sex'];
		$data['province'] = trim($_POST['province']);
		$data['area'] = trim($_POST['area']);
		$data['city'] = trim($_POST['city']);
		$data['email'] = trim($_POST['email']);
		$data['phone'] = trim($_POST['phone']);
		$data['brithday'] = trim($_POST['brithday']);
		$data['school'] = trim($_POST['school']);
		$data['token'] = trim($_POST['token']);

		//批量转移
		$this->helper("input");
		$data = deepSpecialChars($data);

		//实例化对象
		$userInfoModel = new UserInfoModel('userinfo');
		//更新数据
		if ($userInfoModel -> update($data)){
			$_SESSION['user'] = $userInfoModel -> selectByPk($data['username']);
			$this->jump('index.php?c=user&act=setInfoPage','修改成功！',1);
		}else{
			$this->jump('index.php?c=user&act=setInfoPage','修改失败！');
		}
	}

	public function updatePwdAction(){
		//获取数据
		$data['username'] = $_SESSION['user']['username'];
		$data['password'] = md5(trim($_POST['pwd']));
		$oldpwd = md5(trim($_POST['oldpwd']));

		//批量转移
		$this->helper("input");
		$data = deepSpecialChars($data);

		//实例化模型
		$userModel = new UserModel('user');

		if ($userModel -> checkUser($data['username'],$oldpwd)){
			if ($userModel -> update($data)){
				$this->jump('index.php?c=user&act=setInfoPage','修改成功！',1);
			}else{
				$this->jump('index.php?c=user&act=setInfoPage','修改失败！');
			}
		}else{
			$this->jump('index.php?c=user&act=setInfoPage','旧密码输入错误！');
		}
	}

	public function updatePotoAction(){
		//数据收集
		$data = $_SESSION['user'];

		//对上传的图片进行处理
		if ($_FILES['file']['error'] !== 4){
			//载入上传类
			$this->library('Upload');
			$upload = new Upload();
			if ($filename = $upload->up($_FILES['file'])){
				if ($data['poto'] != 'images/poto.jpg'){
					unlink(UPLOAD_PATH.$data['poto']);
				}
				$data['poto'] = $filename;
			} else {
				//失败
				$this -> jump('index.php?c=user&act=setInfoPage',$upload -> error());
			}
		}

		//实例化模型
		$userInfoModel = new UserInfoModel('userinfo');
		//更新数据
		if ($userInfoModel -> update($data)){
			$_SESSION['user'] = $userInfoModel -> selectByPk($data['username']);
			$this->jump('index.php?c=user&act=setInfoPage','修改成功！',1);
		}else{
			$this->jump('index.php?c=user&act=setInfoPage','修改失败！');
		}

	}

	public function regVipAction(){
		$user = $_SESSION['user'];

		$data['username'] = $user['username'];
		$data['identity'] = 2;

		//实例化模型
		$userInfoModel = new UserInfoModel('userinfo');

		if ($userInfoModel->update($data)){
			$_SESSION['user'] = $userInfoModel -> selectByPk($data['username']);
			$this->jump('index.php','注册成功！',1);
		}else{
			$this->jump('index.php','注册失败！');
		}
	}
}