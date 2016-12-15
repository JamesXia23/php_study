<?php
//后台商品类型管理
class TypeController extends BaseController {
	//显示类型
	public function indexAction(){

		//获取所有的商品类型
		$typeModel = new TypeModel('goods_type');

		// $types = $typeModel->getTypes();
		// 分页获取数据
		$pagesize = 5; //每页显示的记录数
		$current = isset($_GET['page']) ? $_GET['page'] : 1; //当前所在页数
		$offset = ($current - 1) * $pagesize;
		$types = $typeModel->getPageTypes($offset,$pagesize);

		//显示分页详情
		$this->library('Page');

		//获取总的记录数
		$where = "";
		$total = $typeModel->total($where);
		$page = new Page($total,$pagesize,$current,'index.php',
			array('p'=>'admin','c'=>'type','a'=>'index'));
		$pageinfo = $page->showPage();
		//载入视图
		include CUR_VIEW_PATH . "goods_type_list.html";
	}
	//显示添加类型页面
	public function addAction(){
		include CUR_VIEW_PATH . "goods_type_add.html";
	}
	//类型入库操作
	public function insertAction(){
		//1.收集表单数据，以关联数组的形式来收集 CTRL+SHIFT +D 复制一行
		$data['type_name'] = trim($_POST['type_name']);
		//2.验证和处理
		if ($data['type_name'] === '') {
			$this->jump('index.php?p=admin&c=type&a=add','商品类型名称不能为空');
		}
		//进行转义处理
		$this->helper('input');
		$data = deepspecialchars($data);
		$data = deepslashes($data);
		//3.调用模型完成入库操作并给出提示
		$typeModel = new TypeModel('goods_type');
		if ($typeModel->insert($data)) {
			$this->jump('index.php?p=admin&c=type&a=index','添加商品类型成功',1);
		} else {
			$this->jump('index.php?p=admin&c=type&a=add','添加商品类型失败');
		}
		
	}
	//显示编辑类型页面
	public function editAction(){
		$type_id = array();
		$type_id['type_id'] = $_GET['type_id'] + 0;
		$typeModel = new TypeModel('goods_type');
		$type = $typeModel->selectByPk($type_id);

		include CUR_VIEW_PATH . "goods_type_edit.html";
	}
	//类型更新操作
	public function updateAction(){
		//1.收集表单数据
		$data['type_id'] = $_POST['type_id'];
		$data['type_name'] = $_POST['type_name'];
		//实体转义
		$this->helper('input');
		$data = deepspecialchars($data);
		$data = deepslashes($data);

		//2.验证和处理
		if ($data['type_name'] === '') {
			$this->jump('index.php?p=admin&c=type&a=edit','商品类型名称不能为空');
		}

		$typeModel = new TypeModel('goods_type');

		if ($typeModel->update($data)) {
			$this->jump('index.php?p=admin&c=type&a=index','修改商品类型成功',1);
		} else {
			$this->jump("index.php?p=admin&c=type&a=edit&type_id={$data['type_id']}",'修改商品类型失败');
		}
	}
	//删除类型操作
	public function deleteAction(){
		//1.获取type_id和页码page
		$type_id = $_GET['type_id'];
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		//实体转义
		$this->helper('input');
		$type_id = deepspecialchars($type_id);
		$type_id = deepslashes($type_id);
		$page = deepspecialchars($page);
		$page = deepslashes($page);

		//2.获取当前类型的属性数
		$attrModel = new AttributeModel('attribute');
		$where = "type_id = $type_id";
		if($total = intval($attrModel->total($where))){
			$this->jump("index.php?p=admin&c=type&a=index&page=$page","当前类型下有{$total}个属性,不能删除");
		}
		//3.调用模型删除属性
		$typeModel = new TypeModel('goods_type');
		if($typeModel->delete(array('type_id' => $type_id))){
			$this->jump("index.php?p=admin&c=type&a=index","删除类型成功",1);
		}else {
			$this->jump("index.php?p=admin&c=type&a=index","删除类型失败");
		}
	}
}