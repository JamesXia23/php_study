<?php
//前台商品控制器
class GoodsController extends HomeBaseController {
	//显示商品详细信息
	public function indexAction(){
		if(!isset($_GET['goods_id'])){
			$this->jump('index.php','非法访问',1);
		}
		$goods_id = $_GET['goods_id'] + 0;

		//获取所有的分类
		$categoryModel = new CategoryModel('category');
		$cats = $categoryModel->frontCats();

		//获取热卖商品
		$goodsModel = new GoodsModel('goods');
		$hotGoods = $goodsModel->getHotGoods();

		$goods = $goodsModel->selectByPk(array('goods_id' => $goods_id));
//		$uniqueAttr = $goodsModel->getUniqueAttr($goods_id);
//		$radioAttr = $goodsModel->getRadioAttr($goods_id);
//		$checkAttr = $goodsModel->getCheckAttr($goods_id);
//		var_dump($uniqueAttr);
//		var_dump($radioAttr);
//		var_dump($checkAttr);

		$galaryModel = new GalaryModel('galary');
		$galary = $galaryModel->select("goods_id = $goods_id");

		include CUR_VIEW_PATH . 'goods.html';
	}
	public function searchAction(){
		$search_content = isset($_POST['search_content']) ? trim($_POST['search_content']) : "";

		if($search_content === ""){
			$this->jump("index.php","搜索内容为空");
		}

		$goodModel = new GoodsModel('goods');
		$where = "goods_name like '%{$search_content}%'";
		$results = $goodModel->select($where);

		if(count($results) === 0){
			$this->jump("index.php","搜索不到相关的内容");
		}

		//获取所有的分类
		$categoryModel = new CategoryModel('category');
		$cats = $categoryModel->frontCats();

		//获取热卖商品
		$goodsModel = new GoodsModel('goods');
		$hotGoods = $goodsModel->getHotGoods();

		include CUR_VIEW_PATH . 'searchresult.html';
	}
}