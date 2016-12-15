<?php
//登录控制器
class LoginController extends Controller {

	//显示登录页面
	public function loginAction(){
		include CUR_VIEW_PATH . "login.html";
	}

	//验证用户登录
	public function signinAction(){
		//1.收集用户名和密码 
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);

		//用户名转义
		$username = addslashes($username);
		$password = addslashes($password);
		//检查验证码操作
		$captcha = trim($_POST['captcha']); //表单提交过来
		if ($_SESSION['captcha'] != strtolower($captcha)) {
			$this->jump('index.php?p=admin&c=login&a=login','什么眼神！');
		}
		//2.验证和处理
		if ($username === '') {
			$this->jump('index.php?p=admin&c=login&a=login','用户名不能为空');
		}
		if ($password === '') {
			$this->jump('index.php?p=admin&c=login&a=login','密码不能为空');
		}
		//3.调用模型完成用户的检查并给出相应的提示
		$adminModel = new AdminModel('user');
		$user = $adminModel->checkUser($username,$password);
		if ($user) {
			//登录成功,先保存登录的标识符
			$_SESSION['admin'] = $user;
			$this->jump('index.php?p=admin&c=index&a=index','',0);
		} else {
			//失败
			$this->jump('index.php?p=admin&c=login&a=login','用户名或密码错误');
		}
	}

	//退出
	public function logoutAction(){
		unset($_SESSION['admin']);
		session_destroy();
		$this->jump('index.php?p=admin&c=login&a=login','退出成功');
	}

	//生成验证码
	public function captchaAction(){
		//载入验证码类
		$this->library('Captcha');
		//实例化对象
		$captcha = new Captcha();
		//调用方法生成验证码
		$captcha->generateCode();
		//将验证码保存到session中
		$_SESSION['captcha'] = $captcha->getCode();
	}
}