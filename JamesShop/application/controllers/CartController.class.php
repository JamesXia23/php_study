<?php
//前台购物车控制器
class CartController extends Controller {
	public function __construct()
	{
		parent::__construct();
		if($this->login_status != 1)
			$this->jump("cframe=login&aframe=login","请先登录");
	}
	//显示购物车
	public function indexAction(){
		$user = $_SESSION['user'];
		$cartModel = new CartModel('cart');
		$carts = $cartModel->getCart();

		include  CUR_VIEW_PATH . "cart.html";
	}

	public function insertAction()
	{
		//1.收集表单数据
		$data['user_id'] = $_SESSION['user']['user_id'];
		if(!isset($_GET['goods_id']))
			$this->jump('index.php','加入购物车失败');
		$data['goods_id'] = $_GET['goods_id'] + 0;
		$data['goods_number'] = 1;

		//2.验证与转义
		$this->helper('input');
		$data = deepslashes($data);
		$data = deepspecialchars($data);

		//3.调用模型插入数据
		$cartModel = new Model('cart');

		if(($cart = $cartModel->selectByPk(array('user_id' => $data['user_id'],'goods_id' => $data['goods_id'])))){
			$cart['goods_number'] += $data['goods_number'];

			if($cartModel->update($cart)){
				$this->jump("",'加入购物车成功',1);
			}else{
				$this->jump("",'加入购物车失败');
			}
		}else{
			$cartModel->insert($data);
			$this->jump("",'加入购物车成功',1);
		}
	}
	public function updateAction(){
		//1.收集表单数据
		$data['user_id'] = $_SESSION['user']['user_id'];
		if(!isset($_GET['goods_id']))
			$this->jump('cframe=cart&aframe=index','更新购物车失败');
		$data['goods_id'] = $_GET['goods_id'] + 0;
		if(!isset($_GET['goods_number']))
			$this->jump('cframe=cart&aframe=index','更新购物车失败');
		$data['goods_number'] = $_GET['goods_number'] + 0;
		$cartModel = new Model('cart');

		if($cartModel->update($data)){
			$this->jump("cframe=cart&aframe=index",'修改购物车成功',1);
		}else{
			$this->jump("cframe=cart&aframe=index",'修改购物车失败');
		}
	}
	public function deleteAction(){
		//1.收集数据
		$data['user_id'] = $_SESSION['user']['user_id'];
		$data['goods_id'] = isset($_GET['goods_id']) ? $_GET['goods_id'] + 0 : "";

		//2.判断和转义
		if($data['goods_id'] === ""){
			$this->jump("cframe=cart&aframe=index",'移除失败');
		}
		$this->helper('input');
		$data = deepslashes($data);
		$data = deepspecialchars($data);

		//3.调用模型删除数据
		$cartModel = new Model('cart');
		if($cartModel->delete($data) === false){
			$this->jump("cframe=cart&aframe=index",'移除失败');
		}
		$this->jump("cframe=cart&aframe=index",'移除成功');
	}
}