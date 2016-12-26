<?php
//登录控制器
class LoginController extends Controller {

	//显示登录页面
	public function loginAction(){
		include CUR_VIEW_PATH . "login.html";
	}
	//验证验证码
	public function captchaCheckAction(){
		$divClassName = "form-group row has-feedback has-error";
		$spanClassName = "glyphicon glyphicon-remove form-control-feedback";
		if($_SESSION['captcha'] == $_GET['captcha']){
			$divClassName = "form-group row has-feedback has-success";
			$spanClassName = "glyphicon glyphicon-ok form-control-feedback";
		}
		include CUR_VIEW_PATH . "captcha.html";
	}
	//验证用户登录
	public function toLoginAction(){
		//1.收集用户名和密码 
		$username = trim($_POST['loginName']);
		$password = trim($_POST['loginPassword']);

		//用户名转义
		$username = addslashes($username);
		$password = addslashes($password);
		//检查验证码操作
		$captcha = trim($_POST['loginCaptcha']); //表单提交过来
		if ($_SESSION['captcha'] != strtolower($captcha)) {
			$this->jump('cframe=login&aframe=login','验证码错误');
		}
		//2.验证和处理
		if ($username === '') {
			$this->jump('cframe=login&aframe=login','用户名不能为空');

		}
		if ($password === '') {
			$this->jump('cframe=login&aframe=login','密码不能为空');
		}
		//3.调用模型完成用户的检查并给出相应的提示
		$userModel = new UserModel('user');
		$user = $userModel->checkUser($username,$password);
		if ($user) {
			//登录成功,先保存登录的标识符
			$message = $user['is_admin'] == '1' ? "admin" : "user";
			$_SESSION['user'] = $user;
			$this->jump('','登陆成功'.$message);
		} else {
			//失败
			$this->jump('cframe=login&aframe=login','用户名或密码错误');
		}
	}

	//退出
	public function logoutAction(){
		unset($_SESSION['user']);
		session_destroy();
		$this->jump('','登出成功');
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