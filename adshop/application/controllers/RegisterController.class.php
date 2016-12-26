<?php
//前台用户控制器
class RegisterController extends Controller {
	/**
	 * 显示注册界面
	 */
	public function registerAction(){
		include CUR_VIEW_PATH . "register.html";
	}

	/**
	 * 判断用户名是否被注册
	 */
	public function usernameCheckAction(){
		$messsage = "";
		$divClassName = "form-group row has-feedback has-success";
		$spanClassName = "glyphicon glyphicon-ok form-control-feedback";
		if(!isset($_GET['username'])){
			$messsage = "用户名不能为空";
			$divClassName = "form-group row has-feedback has-error";
			$spanClassName = "glyphicon glyphicon-remove form-control-feedback";
		} else {
			$username = $_GET['username'];
			if($username == ''){
				$messsage = "用户名不能为空";
				$divClassName = "form-group row has-feedback has-error";
				$spanClassName = "glyphicon glyphicon-remove form-control-feedback";
			}elseif($this->checkUsername($username)){
				$messsage = "用户名已经被注册";
				$divClassName = "form-group row has-feedback has-error";
				$spanClassName = "glyphicon glyphicon-remove form-control-feedback";
			}
		}
		include CUR_VIEW_PATH . "username.html";
	}
	/**
	 * 使用Model查询用户名是否已经被注册,true表示已经被注册,false表示未被注册
	 * @param $username 用户名
	 * @return bool
	 */
	public function checkUsername($username){
		$userModel = new UserModel('user');
		return $userModel->checkUsername($username);
	}
	public function preCheckAction(){
		include CUR_VIEW_PATH . "checkregister.html";
	}
	public function toRegisterAction(){

		//1.收集用户名信息
		$user_name = trim($_POST['registerName']);
		$password = trim($_POST['registerPassword']);
		$repassword = trim($_POST['registerRepassword']);
		$email = trim($_POST['registerEmail']);

		//转义
		$user_name = addslashes($user_name);
		$password = addslashes($password);
		$repassword = addslashes($repassword);
		$email =  addslashes($email);

		//2.验证和处理
		if ($user_name === '') {
			$this->jump('cframe=register&aframe=register','用户名不能为空');
		}
		if ($password === '') {
			$this->jump('cframe=register&aframe=register','密码不能为空',2);
		}
		if ($password !== $repassword) {
			$this->jump('cframe=register&aframe=register','确认密码要与密码一致',2);
		}
		if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
			$this->jump('cframe=register&aframe=register',"非法邮箱$email",10);
		}
		//判断用户名和邮箱是否被注册过
		if($this->checkUsername($user_name)){
			$this->jump('cframe=register&aframe=register','用户名已被注册',2);
		}

		//3.调用模型完成用户的注册并给出相应的提示
		$userModel = new UserModel('user');
		$data['user_name'] = $user_name;
		$data['password'] = md5($password);
		$data['email'] = $email;
		$data['is_admin'] = '0';
		$data['reg_time'] = time();//获取当前时间戳

		if($userModel->insert($data)) {
			$this->jump('cframe=login&aframe=login','注册成功,将为您跳转到登录界面');
		}else{
			$this->jump('cframe=register&aframe=register','注册失败',1);
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