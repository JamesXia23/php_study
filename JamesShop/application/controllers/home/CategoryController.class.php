<?php
//前台商品类型控制器
class CategoryController extends HomeBaseController {
	//显示指定分类下的所有商品
	public function listAction(){

		$cat_id = $_GET['cat_id'] + 0;
//		echo $cat_id;
//		if(isset($_GET['$cat_id'])){
//			$cat_id = $_GET['$cat_id'] + 0;
//		}

		//获取所有的分类
		$categoryModel = new CategoryModel('category');
		$cats = $categoryModel->frontCats();
//		//获取推荐商品
		$goodsModel = new GoodsModel('goods');
//		$bestGoods = $goodsModel->getBestGoods();
		$hotGoods = $goodsModel->getHotGoods();
//		$newGoods = $goodsModel->getNewGoods();

		//获取指定分类下的所有商品
		$catGoods = $goodsModel->select("cat_id = $cat_id");

//		// var_dump($cats);
		include  CUR_VIEW_PATH . "list.html";
	}
}