<?php
//品牌控制器
class BrandController extends Controller{
	
	//显示品牌列表
	public function indexAction(){
		//先获取品牌信息
		$brandModel = new BrandModel("brand");
		$brands = $brandModel->getBrands();
//		$pageinfo = $page->showPage();
		include CUR_VIEW_PATH . "brand_list.html";
	}

	//载入添加品牌页面
	public function addAction(){
		include CUR_VIEW_PATH . "brand_add.html";
	}

	//载入编辑品牌页面
	public function editAction(){
		//获取该品牌信息
		$brandModel = new BrandModel("brand");
		//条件
		$brand_id = $_GET['brand_id'] + 0; //出于考虑
//		echo $brand_id;
		//使用模型获取
		$brand = $brandModel->selectByPk(array("brand_id" => $brand_id));
//		 var_dump($brand);
		include CUR_VIEW_PATH . "brand_edit.html";
	}

	//定义insert方法，完成品牌的插入
	public function insertAction(){
		//接受表单提交过来的数据
		$data['brand_name'] = trim($_POST['brandName']);
		if($data['brand_name'] == "")
			$this->jump("cframe=brand&aframe=add","品牌名称不能为空");
		//对提交过来的数据需要做一些验证、过滤等一些处理(此处忽略)
		$this->helper("input");
		$data = deepspecialchars($data); //实体转义处理
		//处理文件上传,需要使用到Upload.class.php
		$this->library("Upload"); //载入文件上传类
		$upload = new Upload(); //实例化上传对象
		if ($filename = $upload->up($_FILES["logo"])){
			//成功
			$data['logo_url'] = $filename;
			//调用模型完成入库操作，并给出相应的提示
			$brandModel = new BrandModel("brand");
			if ($brandModel->insert($data)){//添加成功
				$this->jump("cframe=brand&aframe=index","添加商品品牌成功");
			}else {//添加失败
				$this->jump("cframe=brand&aframe=add","添加商品品牌失败");
			}
		}else {//失败
			$this->jump("cframe=brand&aframe=add", $upload->error(), 3);
		}
	}

	//定义update方法，完成品牌的更新
	public function updateAction(){
		//获取条件及数据
		$data['brand_id'] = $_POST['brand_id'];
		$data['brand_name'] = trim($_POST['brand_name']);

		if($data['brand_name'] == "")
			$this->jump("cframe=brand&aframe=edit&brand_id=".$data['brand_id'],"品牌名称不能为空");
		//对提交过来的数据需要做一些验证、过滤等一些处理(此处忽略)
		$this->helper("input");
		$data = deepspecialchars($data); //实体转义处理
		//处理文件上传,需要使用到Upload.class.php
		$this->library("Upload"); //载入文件上传类
		$upload = new Upload(); //实例化上传对象

		if($_FILES['logo']['name'] != ""){
			if ($filename = $upload->up($_FILES["logo"])){
				$data['logo_url'] = $filename;
			}else {//失败
				$this->jump("cframe=brand&aframe=edit&brand_id=".$data['brand_id'], $upload->error(), 3);
			}
		}

		//调用模型完成更新
		$brandModel = new BrandModel("brand");
		if($brandModel->update($data)){
			$this->jump("cframe=brand&aframe=index","更新成功",2);
		}else{
			$this->jump("cframe=brand&aframe=edit&brand_id=".$data['brand_id'],"更新失败",2);
		}
	}

	//定义delete方法，完成品牌的删除
	public function deleteAction(){
		//获取brand_id
		$brand_id = $_GET['brand_id'] + 0;
		$brandModel = new BrandModel("brand");
		$brand = $brandModel->selectByPk(array("brand_id" => $brand_id));
		//得到图片的全路径
		$img = UPLOAD_PATH . $brand['logo_url'];
		if ($brandModel->delete(array("brand_id" => $brand_id))){
			//成功的同时删除对应的图片
			@unlink($img);
			$this->jump("cframe=brand&aframe=index","删除成功",2);
		}else{
			$this->jump("cframe=brand&aframe=index","删除失败",3);
		}
	}
}