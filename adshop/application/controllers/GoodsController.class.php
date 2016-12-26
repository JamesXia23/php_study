<?php
//商品管理
class GoodsController extends Controller {
	//后台商品列表
	public function listAction(){
		$goodModel = new GoodsModel('goods');
		$allGoods = $goodModel->select();
//		var_dump($allGoods);
		include CUR_VIEW_PATH . "goods_list.html";
	}
	//显示商品
	public function indexAction(){
		$goodsModel = new GoodsModel('goods');
		$allGoods = "";
		//根据品牌显示
		if(isset($_GET['brand_id'])){
			$brand_id = $_GET['brand_id'] + 0;
//			echo $brand_id;
			$allGoods = $goodsModel->selectGoods("brand_id = ".$brand_id);
//			var_dump($allGoods);
		}elseif(isset($_GET['cat_id'])){
			$cat_id = $_GET['cat_id'] + 0;
			$allGoods = $goodsModel->selectGoods("cat_id = ".$cat_id);
		}elseif(isset($_GET['keyword'])){
			$keyword = trim($_GET['keyword']);
			if($keyword == ''){
				echo "<script type='text/javascript'>window.parent.showDialog('关键字不能为空','');</script>";
				return;
			}
			$allGoods = $goodsModel->searchGoods($keyword);
		} else{
			$allGoods = $goodsModel->selectGoods();
		}
//		var_dump($allGoods);
		if(count($allGoods) == 0){
			echo "<script type='text/javascript'>window.parent.showDialog('无结果','');</script>";
			return;
		}

		include CUR_VIEW_PATH . "goods_show.html";
	}
	//商品详情页
	public function showOneAction(){
		$goods_id = $_GET['goods_id'] + 0;
//		商品
		$goodsModel = new GoodsModel('goods');
		$goods = $goodsModel->selectByPk(array('goods_id' => $goods_id));
//		var_dump($goods);
//		相册
		$galaryModel = new GalaryModel("galary");
		$galary = $galaryModel->select("goods_id=" . $goods_id);
//		属性以及值
		$attributeModel = new AttributeModel('attribute');
		$attrs = $attributeModel->getAttrByGoodsID($goods_id);
//		品牌
		$brandModel = new BrandModel('brand');
		$brand = $brandModel->select("brand_id=".$goods['brand_id']);
//		var_dump($brand);
//		类型
		$categoryModel = new CategoryModel('category');
		$category = $categoryModel->select("cat_id=".$goods['cat_id']);
		var_dump($attrs);
		include CUR_VIEW_PATH . 'goods_desc.html';
	}
	//显示添加商品页面
	public function addAction(){
		//获取所有的分类
		$categoryModel = new CategoryModel('category');
		$cats = $categoryModel->getCats();
		//获取所有级别
		$ranks = $categoryModel->getRanks();

//		//获取所有的的品牌
		$brandModel = new BrandModel("brand");
		$brands = $brandModel->getBrands();
		//获取所有的商品类型
//		$typeModel = new TypeModel('goods_type');
//		$types = $typeModel->getTypes();
		//载入视图页面
		include CUR_VIEW_PATH . "goods_add.html";
	}
	//商品入库操作
	public function insertAction(){
		//1.收集表单数据,并且校验,以关联数组的形式来收集 CTRL+SHIFT +D 复制一行
		$data['goods_name'] = trim($_POST['goods_name']);
		if($_POST['brand_id'] != '0')
			$data['brand_id'] = trim($_POST['brand_id']);
		if($_POST['cat_id'] != '0')
			$data['cat_id'] = $_POST['cat_id'];
		$data['shop_price'] = trim($_POST['shop_price']);
		$data['market_price'] = trim($_POST['market_price']);
		$data['goods_desc'] = trim($_POST['goods_desc']);
		$data['goods_number'] = trim($_POST['goods_number']);
		$data['add_time'] = time();
		$data['attr_name'] = $_POST['attr_name'];
		$data['attr_value'] = $_POST['attr_value'];

		if($data['goods_name'] === '')
			$this->jump('cframe=goods&aframe=add','商品名称不能为空',1);
		if($data['shop_price'] === '')
			$this->jump('cframe=goods&aframe=add','本店售价不能为空',1);
		if($data['market_price'] === '')
			$this->jump('cframe=goods&aframe=add','市场售价不能为空',1);
		if($data['goods_number'] === '')
			$this->jump('cframe=goods&aframe=add','商品库存不能为空',1);

		//判断商品属性数组是否为空,并且将不空的值存入$attr数组中
		$isEmpty = true;
		$attr = array();
		$attr['name'] = array();
		$attr['value'] = array();
		for($i = 0; $i < count($data['attr_name']); $i++){
			if($data['attr_name'][$i] != '') {
				$isEmpty = false;
				$attr['name'][] = $data['attr_name'][$i];
				$attr_values = $data['attr_value'][$i];
				$attr['value'][$i] = explode(",", $attr_values);
			}
		}
//		var_dump($attr);
//		die;
		if($isEmpty)
			$this->jump('cframe=goods&aframe=add','商品属性不能为空',1);

		$this->library('Upload');
		$upload = new Upload();
//		//相册上传
		$galary = $upload->multiUp($_FILES['img_url']/*,$_POST['img_desc']*/);
//		var_dump($galary);

		//2.验证和处理
		$this->helper('input');
		$data = deepspecialchars($data);
		$data = deepslashes($data);

		$data['goods_desc'] = trim($_POST['goods_desc']);
		$data['goods_desc'] = deepslashes($data['goods_desc']);

		//var_dump($data);

		//3.调用模型完成入库
		$goodsModel = new GoodsModel('goods');
		if ($goods_id = $goodsModel->insert($data)) {
			//相册的插入
			$galaryModel = new GalaryModel('galary');
			$galaryModel->insertGalary($goods_id,$galary);
//			//收集所有的扩展属性，然后完成goods_attr表的insert操作

//			商品属性及商品属性值插入
			$attrModel = new AttributeModel('attribute');
			$model = new Model('attr_value');
			for($i = 0; $i < count($attr['name']); $i++) {
//					商品属性插入
				$attr_data['goods_id'] = $goods_id;
				$attr_data['attr_name'] = $attr['name'][$i];
				if($attr_id = $attrModel->insert($attr_data)){
					//商品属性值插入
					for($j = 0; $j < count($attr['value'][$i]); $j++){
						$attr_value_data['attr_id'] = $attr_id;
						$attr_value_data['attr_value'] = $attr['value'][$i][$j];
						if(!$model->insert($attr_value_data))
							$this->jump('cframe=goods&aframe=add','商品属性值添加失败',2);
					}
				}else{
					$this->jump('cframe=goods&aframe=add','商品属性添加失败',2);
				}
			}
			$this->jump('cframe=goods&aframe=list','添加商品成功',1);
		} else {
			//失败
			$this->jump('cframe=goods&aframe=add','添加商品失败');
		}
	}
	//显示编辑商品页面
	public function editAction(){
		//获取goods_id
		$goods_id = trim($_GET['goods_id']);
		$goods_id = $goods_id + 0;
		//验证数据
		$this->helper('input');
		$goods_id = deepspecialchars($goods_id);
		$goods_id = deepslashes($goods_id);

		//创建对应模型
		//获取对应商品的信息
		$goodsModel = new GoodsModel('goods');
		$goods = $goodsModel->selectByPk(array('goods_id' => $goods_id));

		//获取所有的商品分类
		$categoryModel = new CategoryModel('category');
		$cats = $categoryModel->getCats();

		//获取所有的商品类型
		$typeModel = new TypeModel('goods_type');
		$types = $typeModel->getTypes();

		include CUR_VIEW_PATH . "goods_edit.html";
	}

	//商品更新操作
	public function updateAction($galary){
		//1.收集表单数据,并且校验,以关联数组的形式来收集 CTRL+SHIFT +D 复制一行
		$data['goods_id'] = trim($_POST['goods_id']);
		$data['goods_name'] = trim($_POST['goods_name']);
		$data['type_id'] = $_POST['type_id'];
		$data['shop_price'] = trim($_POST['shop_price']);
		$data['market_price'] = trim($_POST['market_price']);
		$data['is_promote'] = isset($_POST['is_promote']) ? $_POST['is_promote'] : 0;
		$data['promote_price'] = trim($_POST['promote_price']);
		$data['promote_start_time'] = trim($_POST['promote_start_time']);
		$data['promote_end_time'] = trim($_POST['promote_end_time']);
		$data['goods_brief'] = trim($_POST['goods_brief']);
//		$data['goods_desc'] = trim($_POST['goods_desc']);
		$data['cat_id'] = $_POST['cat_id'];
		$data['goods_number'] = trim($_POST['goods_number']);
		$data['is_best'] = isset($_POST['is_best']) ? $_POST['is_best'] : 0;
		$data['is_hot'] = isset($_POST['is_hot']) ? $_POST['is_hot'] : 0;
		$data['is_new'] = isset($_POST['is_new']) ? $_POST['is_new'] : 0;
		$data['is_onsale'] = isset($_POST['is_onsale']) ? $_POST['is_onsale'] : 0;

		if($data['goods_name'] === '')
			$this->jump('index.php?p=admin&c=goods&a=add','商品名称不能为空',1);

		if($data['type_id'] === '0')
			$this->jump('index.php?p=admin&c=goods&a=add','请选择商品分类',1);
//		if(($data['brand_id'] = $_POST['brand_id']) === '0')
//			$this->jump('index.php?p=admin&c=goods&a=add','请选择商品品牌');
		if($data['shop_price'] === '')
			$this->jump('index.php?p=admin&c=goods&a=add','本店售价不能为空',1);

		if($data['market_price'] === '')
			$this->jump('index.php?p=admin&c=goods&a=add','市场售价不能为空',1);

		if($data['promote_price'] === '')
			$this->jump('index.php?p=admin&c=goods&a=add','促销价格不能为空',1);

		if($data['promote_start_time'] === '')
			$this->jump('index.php?p=admin&c=goods&a=add','促销开始日期不能为空',1);
		$data['promote_start_time'] = strtotime($data['promote_start_time']);

		if($data['promote_end_time'] === '')
			$this->jump('index.php?p=admin&c=goods&a=add','促销结束日期不能为空',1);
		$data['promote_end_time'] = strtotime($data['promote_end_time']);

		if($data['goods_number'] === '')
			$this->jump('index.php?p=admin&c=goods&a=add','商品库存不能为空',1);

		$this->library('Upload');
		$upload = new Upload();
		//封面图上传
		if ($_FILES['goods_img']['tmp_name'] !== '') {//默认$_FILES['goods_img']是不为空的,所以要判断他名字为空才算是没有上传
			//有上传
			if ($filename = $upload->up($_FILES['goods_img'])) {
				$data['goods_img'] = $filename; //成功
			} else {
				//失败
				$this->jump('index.php?p=admin&c=goods&a=add',$upload->error());
			}
		}
//		//相册上传
		$galary = $upload->multiUp($_FILES['img_url'],$_POST['img_desc']);
//		var_dump($galary);

		//2.验证和处理
		$this->helper('input');
		$data = deepspecialchars($data);
		$data = deepslashes($data);

		$data['goods_desc'] = trim($_POST['goods_desc']);
		$data['goods_desc'] = deepslashes($data['goods_desc']);
		//var_dump($data);

		//3.调用模型完成入库
		$goodsModel = new GoodsModel('goods');
		if ($goodsModel->update($data)) {
			//相册的插入
			$galaryModel = new GalaryModel('galary');
			$galaryModel->delete(array('goods_id' => $data['goods_id']));
			$galaryModel->insertGalary($data['goods_id'],$galary);
//			//收集所有的扩展属性，然后完成goods_attr表的insert操作

			if (isset($_POST['attr_id_list'])) {
				$ids = $_POST['attr_id_list'];
				$values = $_POST['attr_value_list'];
//				$prices = $_POST['attr_price_list'];
				//批量插入，一次性插入多条记录--循环
				$model = new Model('goods_attr');
				$model->delete(array('goods_id' => $data['goods_id']));

				foreach ($ids as $k => $v) {
					$list['goods_id'] = $data['goods_id'];
					$list['attr_id'] = $v;
					$list['attr_value'] = $values[$k];
//					$list['attr_price'] = $prices[$k];
					$model->insert($list);
				}
			}
			$this->jump('index.php?p=admin&c=goods&a=index','更新商品成功',1);
		} else {
			//失败
			$this->jump("index.php?p=admin&c=goods&a=edit&goods_id={$data['goods_id']}",'更新商品失败',11);
		}
	}
	//删除商品操作
	public function deleteAction(){
		//获取goods_id
		$goods_id = trim($_GET['goods_id']);
		$goods_id = $goods_id + 0;

		//验证数据
		$this->helper('input');
		$goods_id = deepspecialchars($goods_id);
		$goods_id = deepslashes($goods_id);

		//调用模型删除
		//删除商品时,对应的商品属性,商品属性值,商品的相册galary,购物车cart中的也要删除
		//删除商品属性及值goods_attr
		$attributeModel = new AttributeModel('attribute');
		if(!$attributeModel->deleteAttrByGoodsID($goods_id))
			$this->jump('cframe=goods&aframe=list',' 删除商品属性失败');

		//删除商品的相册galary
		$galaryModel = new GalaryModel('galary');
//		$galaryModel->delete(array('goods_id' => $goods_id));
		if($galaryModel->delete(array('goods_id' => $goods_id)) === false)
			$this->jump('cframe=goods&aframe=list','删除商品相册失败');

		//删除购物车cart中的商品
//		$cartModel = new Model('cart');
//		$cartModel->delete(array('goods_id' => $goods_id));

		//最后才是删除商品
		$goodsModel = new GoodsModel('goods');
		if($goodsModel->delete(array('goods_id' => $goods_id))) {
			$this->jump('cframe=goods&aframe=list','删除商品成功',1);
		} else {
			$this->jump('cframe=goods&aframe=list','删除商品失败');
		}

	}
}