<?php
//后台首页控制器
class IndexController extends BaseController{
	public function indexAction(){
		// echo "admin...index...";
		// 载入视图
		include CUR_VIEW_PATH . "index.html";
	}

	public function topAction(){
		include CUR_VIEW_PATH . "top.html";
	}

	public function menuAction(){
		include CUR_VIEW_PATH . "menu.html";
	}

	public function dragAction(){
		include CUR_VIEW_PATH . "drag.html";
	}

	public function mainAction(){
		//实例化admin模型
//		$adminModel = new AdminModel('user'); //不带前缀的表名
//		$admins = $adminModel->getAdmins();
//		echo "<pre>";
//		var_dump($admins);
		include CUR_VIEW_PATH . "main.html";


	}

	//生成验证码
	public function codeAction(){
		//引入验证码类
		$this->library('Captcha');
		//实例化对象
		$captcha = new Captcha();
		//调用方法生成验证码
		$captcha->generateCode();
	}
}