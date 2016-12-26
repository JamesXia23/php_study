<?php
//核心启动类
class Framework {
	//run方法
	public static function run(){
		self::init();		//初始化功能,主要是定义项目的路径常量
		self::autoload();	//自动载入
		self::dispatch();	//路由,分析url,确定哪个平台,哪个控制器,哪个方法
	}

	//初始化
	private static function init(){
		//路径的常量
		define("DS", DIRECTORY_SEPARATOR);//DIRECTORY_SEPARATOR:PHP中的目录分隔符,能自动适配windows和linux
		define("ROOT", getcwd() . DS); //根路径
		//根目录下的三个目录
		define("APP_PATH", ROOT . "application" . DS);
		define("FRAMEWORK_PATH",ROOT . "framework" .DS);
		define("PUBLIC_PATH", ROOT . "public" .DS);
		//application目录
		define("CONFIG_PATH", APP_PATH . "config" .DS);
		define("CONTROLLER_PATH", APP_PATH . "controllers" .DS);
		define("MODEL_PATH", APP_PATH . "models" .DS);
		define("VIEW_PATH", APP_PATH . "views" .DS);
		//framework目录
		define("CORE_PATH", FRAMEWORK_PATH . "core" .DS);
		define("DB_PATH", FRAMEWORK_PATH . "databases" .DS);
		define("LIB_PATH", FRAMEWORK_PATH . "libraries" .DS);
		define("HELPER_PATH", FRAMEWORK_PATH . "helpers" .DS);
		//public文件夹
		define("UPLOAD_PATH", PUBLIC_PATH . "uploads" .DS);
		//解析url,如:index.php?p=admin&c=goods&a=add--后台的GoodsController中的addAction
		//define("PLATFORM", isset($_GET['p']) ? $_GET['p'] : "home" );	//默认前台界面
		//解析url,如:index.php?c=goods&a=add--GoodsController中的addAction
		define("CONTROLLER", isset($_GET['c']) ? ucfirst($_GET['c']) : "Index");//默认IndexController,ucfirst,使字符串第一个字母大写
		define("ACTION", isset($_GET['a']) ? $_GET['a'] : "index" );	//默认调用index函数,加载首页视图
		define("CUR_CONTROLLER_PATH", CONTROLLER_PATH . DS );//当前控制器目录
		define("CUR_VIEW_PATH", VIEW_PATH . DS);				//当前视图目录
		//加载核心类
		include CORE_PATH . "Controller.class.php";
		include CORE_PATH . "Model.class.php";
		include DB_PATH . "Mysql.class.php";
		//载入配置文件
		$GLOBALS['config'] = include CONFIG_PATH. "config.php";
		//开启session
		session_start();
	}

	//路由分发,实例化对象调用方法
	//index.php?c=goods&a=add--后台的GoodsController中的addAction
	private static function dispatch(){
		$controller_name = CONTROLLER . "Controller";
		$action_name = ACTION . "Action";
		//实例化对象
		$controller = new $controller_name();

		//调用方法
		$controller->$action_name();
	}

	//自动载入
	//指定要自动加载的函数
	private static function autoload(){
		// spl_autoload_register(array(__CLASS__,'load'));
		spl_autoload_register('self::load');

	}

	//完成指定类的加载
	//只加载application中的controller和model,如GoodsController,BrandModel
	public static function load($classname){
		if (substr($classname, -10) == 'Controller') {	//取出最后十个字母,判断是否为Controller
			//控制器
			include CUR_CONTROLLER_PATH . "{$classname}.class.php";
		} elseif (substr($classname, -5) == 'Model') {	//取出最后五个字母,判断是否为Model
			//模型
			include MODEL_PATH . "{$classname}.class.php";
		} else {
			//暂略
		}
	}
}