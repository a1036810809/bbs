<?php
//用户登陆
class LoginController extends Controller{
	//默认
	public function indexAction(){
		$this->jump('index.php?p=admin&c=login&act=login','页面访问错误');
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
		unset($_SESSION['admin']);
		$this->jump('index.php?p=admin&c=login&act=login','',0);
	}

		//验证身份信息
	public function signinAction(){
		//收集登陆信息
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		$captcha = trim($_POST['captcha']);

		//检查验证码
		if (strtolower($captcha) != $_SESSION['code']){
			$this->jump('index.php?p=admin&c=login&act=login',"验证码错误");
		}

		//调出模型完成用户的检查并给出相应的提升
		$adminModel = new AdminModel('admin');
		$admin = $adminModel->selectByPk($username);
		
		if (!empty($admin)){
			if(trim($admin['password']) == md5($password)){
				$_SESSION['admin'] = $admin;
				$this->jump('index.php?p=admin&c=index&act=index',"登陆成功");
			}else{
				$this->jump('index.php?p=admin&c=login&act=login','密码错误');
			}
		}else{
			$this->jump('index.php?p=admin&c=login&act=login','用户名或密码错误');
		}
	}
}