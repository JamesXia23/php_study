<?php
//前台购物车控制器
class CartController extends HomeBaseController {
	public function __construct()
	{
		parent::__construct();
		if(!$this->is_login){
			$this->jump('index.php?p=home&c=user&a=toLogin','请先登录');
		}
	}
	//显示购物车
	public function indexAction(){
		//获取所有的分类
		$categoryModel = new CategoryModel('category');
		$cats = $categoryModel->frontCats();

		$user = $_SESSION['user'];
		$cartModel = new Model('cart');
		$carts = $cartModel->select("user_id = {$user['user_id']}");

		$goodsModel = new GoodsModel('goods');

		foreach($carts as $k => $cart){
			$goods = $goodsModel->selectByPk(array('goods_id' => $cart['goods_id']));
			$carts[$k]['goods_name'] = $goods['goods_name'];
			$carts[$k]['goods_img'] = $goods['goods_img'];
		}
//		var_dump($user);
		include  CUR_VIEW_PATH . "cart.html";
	}

	public function insertAction()
	{
		//1.收集表单数据
		$data['user_id'] = isset($_POST['user_id']) ? $_POST['user_id'] : "";
		$data['goods_id'] = isset($_POST['goods_id']) ? $_POST['goods_id'] : "";
		$data['goods_number'] = isset($_POST['goods_number']) ? $_POST['goods_number'] : "";
		$data['goods_price'] = isset($_POST['goods_price']) ? $_POST['goods_price'] : "";

		//2.验证与转义
		$this->helper('input');
		$data = deepslashes($data);
		$data = deepspecialchars($data);

		if($data['user_id'] === "" || $data['goods_id'] === "" || $data['goods_number'] === "" || $data['goods_price'] === ""){
			$this->jump('index.php','非法提交',1);
		}

		//3.调用模型插入数据
		$cartModel = new Model('cart');

		if(($cart = $cartModel->selectByPk(array('user_id' => $data['user_id'],'goods_id' => $data['goods_id'])))){
			$cart['goods_number'] += $data['goods_number'];

			if($cartModel->update($cart)){
				$this->jump("index.php?p=home&c=goods&a=index&goods_id={$data['goods_id']}",'加入购物车成功',1);
			}else{
				$this->jump("index.php?p=home&c=goods&a=index&goods_id={$data['goods_id']}",'加入购物车失败');
			}
		}else{
			$cartModel->insert($data);
			$this->jump("index.php?p=home&c=goods&a=index&goods_id={$data['goods_id']}",'加入购物车成功',1);
		}
	}
	public function deleteAction(){
		//1.收集数据
		$data['user_id'] = isset($_SESSION['user']) ? $_SESSION['user']['user_id'] : "";
		$data['goods_id'] = isset($_GET['goods_id']) ? $_GET['goods_id'] + 0 : "";

		//2.判断和转义
		if($data['user_id'] === "" || $data['goods_id'] === ""){
			$this->jump("index.php?p=home&c=cart&a=index",'移除失败');
		}
		$this->helper('input');
		$data = deepslashes($data);
		$data = deepspecialchars($data);

		//3.调用模型删除数据
		$cartModel = new Model('cart');
		if($cartModel->delete($data) === false){
			$this->jump("index.php?p=home&c=cart&a=index",'移除失败');
		}
		$this->jump("index.php?p=home&c=cart&a=index",'移除成功');
	}
}