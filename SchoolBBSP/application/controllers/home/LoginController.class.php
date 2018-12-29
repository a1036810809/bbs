<?php
//用户登陆
class LoginController extends Controller{
	//默认
	public function indexAction(){
		$this->jump('index.php?c=login&act=login','页面访问错误');
	}

	//登陆界面
	public function loginAction(){
		include CUR_VIEW_PATH."login.html";
	}

	//获取验证码
	public function captchaAction(){
		//引入验证码类
		$this->library('Captcha');
		//实例化对象
		$captcha = new Captcha();
		//调用方法生成验证码
		$captcha->generateCode();
		//获取验证码值
		$_SESSION['code'] = $captcha->getCode();
	}

	//退出登陆
	public function logoutAction(){
		unset($_SESSION['user']);
		$this->jump('index.php?c=login&act=login','',0);
	}

	//验证身份信息
	public function signinAction(){
		//收集登陆信息
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		$captcha = trim($_POST['captcha']);

		//检查验证码
		if (strtolower($captcha) != $_SESSION['code']){
			$this->jump('index.php?c=login&act=login',"验证码错误");
		}

		//调出模型完成用户的检查并给出相应的提升
		$userModel = new UserModel('user');
		$user = $userModel->checkUser($username,$password);
		
		if (!empty($user)){
			//获取头像
			$userinfoModel = new UserInfoModel('userinfo');
			$userinfo = $userinfoModel -> selectByPk($username);
			$_SESSION['user'] = $userinfo;
			$this->jump('index.php?c=index&act=index',"登陆成功");
		}else{
			$this->jump('index.php?c=login&act=login','用户名或密码错误');
		}
	}

	//注册界面
	public function registerAction(){
		include CUR_VIEW_PATH . "register.html";
	}

	//注册验证
	public function regiSubAction(){
		//收集表单
		$idata['username'] = $udata['username'] = trim($_POST['username']);
		$udata['password'] = md5(trim($_POST['pwd']));
		$idata['nickname'] = trim($_POST['nickname']);
		$idata['poto'] = "images/poto.jpg";
		$idata['sex'] = $_POST['sex'];
		$idata['province'] = trim($_POST['province']);
		$idata['city'] = trim($_POST['city']);
		$idata['area'] = trim($_POST['area']);
		$idata['brithday'] = trim($_POST['brithday']);
		$idata['email'] = trim($_POST['email']);
		$idata['token'] = trim($_POST['token']);
		$idata['school'] = trim($_POST['school']);
		$idata['phone'] = trim($_POST['tell']);
		$idata['identity'] = 1;
		
		//批量转移
		$this->helper("input");
		$idata = deepSpecialChars($idata);
		$udata = deepSpecialChars($udata);
		//验证是否存在
		$userModel = new UserModel('user');
		$users = $userModel->getAllUser();
		if ($users != null){
			foreach ($users as $user) {
				if ($user['username'] == $udata['username']){
					$this->jump('index.php?c=login&act=register','当前用户名已存在');
				}
			}
		}

		//插入
		$userinfoModel = new UserInfoModel('userinfo');

		if ($userModel -> insert($udata)){
			if ($userinfoModel -> insert($idata)){
				$this->jump('index.php?c=login&act=login','注册成功');
			}else{
				$userModel -> delete($udata['username']);
				$this->jump('index.php?c=login&act=register','注册失败');
			}
		}else{
			$this->jump('index.php?c=login&act=register','注册失败');
		}
	}
	//找回密码
	public function losepwdAction(){
		include CUR_VIEW_PATH . "retrievePwd.html";
	}
	//设置密码
	public function resetpwdAction(){
		$udata['username'] = $data['username'] = trim($_POST['username']);
		$data['email'] = trim($_POST['email']);
		$data['phone'] = trim($_POST['tell']);
		$udata['password'] = $data['password'] = md5(trim($_POST['pwd']));

		//批量转移
		$this->helper("input");
		$data = deepSpecialChars($data);

		//验证
		$userinfoModel = new UserInfoModel('userinfo');
		$userModel = new UserModel('user');
		if ($userinfo = $userinfoModel -> selectByPk($data['username'])){
			if ($userinfo['email'] === $data['email'] && $userinfo['phone'] === $data['phone']){
				if ($update = $userModel -> update($udata)){
					$this->jump('index.php?c=login&act=login','更改成功');
				}else{
					$this->jump('index.php?c=login&act=losepwd','更改失败');
				}
			}else{
				$this->jump('index.php?c=login&act=losepwd','信息输入错误');
			}
		}else{
			$this->jump('index.php?c=login&act=losepwd','用户不存在');
		}
	}
}