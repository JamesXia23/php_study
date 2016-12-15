<?php
//前台用户控制器
class UserController extends HomeBaseController {
	public function toRegisterAction(){
		include CUR_VIEW_PATH . "register.html";
	}
	public function toLoginAction(){
		include CUR_VIEW_PATH . "login.html";
	}
	public function registerAction(){
		//1.收集用户名信息
		$user_name = trim($_POST['user_name']);
		$password = trim($_POST['password']);
		$repassword = trim($_POST['repassword']);
		$email = trim($_POST['email']);
		$captcha = trim($_POST['captcha']);

		//转义
		$user_name = addslashes($user_name);
		$password = addslashes($password);
		$repassword = addslashes($repassword);
		$email = trim($_POST['email']);

		//2.验证和处理
		if ($user_name === '') {
			$this->jump('index.php?p=home&c=user&a=toRegister','用户名不能为空',1);
		}
		if ($password === '') {
			$this->jump('index.php?p=home&c=user&a=toRegister','密码不能为空',1);
		}
		if ($password !== $repassword) {
			$this->jump('index.php?p=home&c=user&a=toRegister','确认密码要与密码一致',2);
		}
		if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
			$this->jump('index.php?p=home&c=user&a=toRegister','非法邮箱',1);
		}
		if ($_SESSION['captcha'] != strtolower($captcha)) {
			$this->jump('index.php?p=home&c=user&a=toRegister','什么眼神！',1);
		}
		//判断用户名和邮箱是否被注册过
		$this->checkUsernameAndEmail($user_name,$email);

		//3.调用模型完成用户的注册并给出相应的提示
		$userModel = new UserModel('user');
		$data['user_name'] = $user_name;
		$data['password'] = md5($password);
		$data['email'] = $email;
		$data['is_admin'] = '0';
		$data['reg_time'] = time();//获取当前时间戳

		if($userModel->insert($data)) {
			$this->jump('index.php?p=home&c=user&a=toLogin','注册成功,将为您跳转到登录界面');
		}else{
			$this->jump('index.php?p=home&c=user&a=toRegister','注册失败',1);
		}
	}
	public function loginAction(){
		//1.收集用户名和密码
		$user_name = trim($_POST['user_name']);
		$password = trim($_POST['password']);

		//用户名转义
		$user_name = addslashes($user_name);
		$password = addslashes($password);
		//检查验证码操作
		$captcha = trim($_POST['captcha']); //表单提交过来
		if ($_SESSION['captcha'] != strtolower($captcha)) {
			$this->jump('index.php?p=home&c=user&a=toLogin','什么眼神！',1);
		}
		//2.验证和处理
		if ($user_name === '') {
			$this->jump('index.php?p=home&c=user&a=toLogin','用户名不能为空');
		}
		if ($password === '') {
			$this->jump('index.php?p=home&c=user&a=toLogin','密码不能为空');
		}
		//3.调用模型完成用户的检查并给出相应的提示
		$userModel = new UserModel('user');
		$user = $userModel->checkUser($user_name,$password);
		if ($user) {
			//登录成功,先保存登录的标识符
			$_SESSION['user'] = $user;
			$this->jump('index.php','登录成功',1);
		} else {
			//失败
			$this->jump('index.php?p=home&c=user&a=toLogin','用户名或密码错误');
		}
	}

	//退出
	public function logoutAction(){
		unset($_SESSION['user']);
		session_destroy();
		$this->jump('index.php','退出成功');
	}

	public function checkUsernameAndEmail($username,$email){

		$userModel = new UserModel('user');
		if($userModel->checkUsername($username)){
			$this->jump('index.php?p=home&c=user&a=toRegister','该用户名已被注册');
		}
		if($userModel->checkEmail($email)){
			$this->jump('index.php?p=home&c=user&a=toRegister','该邮箱已被注册');
		}
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