<?php
//核心控制器
class Controller {
	//0代表管理员,1代表用户,2代表未登录
	protected $login_status;
	protected $login_username;
	protected $login_userid;
	//构造方法
	public function __construct(){
		$this->checkUser();
	}

	//验证用户登录
	public function checkUser(){
		//使用session来判断
		if (!isset($_SESSION['user'])) {
			$this->login_status = 2;
			$this->login_username = '更多';
		} elseif ($_SESSION['user']['is_admin'] == 1) {
			$this->login_status = 0;
			$this->login_username = $_SESSION['user']['user_name'];
			$this->login_userid = $_SESSION['user']['user_id'];
		} else {
			$this->login_status = 1;
			$this->login_username = $_SESSION['user']['user_name'];
			$this->login_userid = $_SESSION['user']['user_id'];
		}

//		echo $login_status;
	}

	//提示信息并跳转
	/**
	 * @param string $attr 为iframe设置参数
	 * @param string $message 提示信息
	 * @param int $wait 等待时间
	 */
	public function jump($attr,$message,$wait = 2){
		if ($wait == 0) {
			header("Location:index.php?$attr");

		} else {
			include CUR_VIEW_PATH . "message.html";
		}
		//一定要退出
		exit; //die
	}

	//加载工具类
	public function library($lib){
		include LIB_PATH . "{$lib}.class.php";
	}

	//加载辅助函数文件
	public function helper($helper){
		include HELPER_PATH . "{$helper}.php";
	}
}