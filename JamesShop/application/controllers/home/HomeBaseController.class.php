<?php
//前台基础控制器
class HomeBaseController extends Controller {
	protected $is_login;
	//构造方法
	public function __construct(){
		$this->is_login = false;
		$this->checkLogin();
	}

	//验证用户是否登录
	public function checkLogin(){
		//使用session来判断
		if (isset($_SESSION['user'])) {
			$this->is_login = true;
		}
	}
}