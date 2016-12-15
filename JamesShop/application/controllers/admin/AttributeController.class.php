<?php
//后台商品属性管理
class AttributeController extends BaseController {
	//显示属性
	public function indexAction(){

		//获取所有的商品类型
		$typeModel = new TypeModel('goods_type');
		$types = $typeModel->getTypes();
		$type_id = isset($_GET['type_id']) ? $_GET['type_id'] : 0 ;
		$attrModel = new AttributeModel('attribute');
		$attrValueModel = new Model('attr_value');

		$pagesize = 5;
		$current = isset($_GET['page']) ?  $_GET['page'] : 1;
		$offset = ($current - 1) * $pagesize;
		$attrs = $attrModel->getPageAttrs($type_id,$offset,$pagesize);
//		var_dump($attrs);
		$tempArray = array();
		foreach($attrs as $attr) {
			$where = "attr_id = {$attr['attr_id']}";
			$attrValues = $attrValueModel->select($where);
			//var_dump($attrValues);
			$values = "";
			foreach($attrValues as $attrValue){
				$values .= "{$attrValue['attr_value']}_{$attrValue['attr_price']}" . " ";
			}
			$attr['attr_value'] = $values;
			$tempArray[] = $attr;
		}
//		var_dump($tempArray);
		$attrs = $tempArray;
		//分页获取
		$this->library('Page');
		if ($type_id == 0) {
			$where = "";
		} else {
			$where = "type_id = $type_id";
		}
		$total = $attrModel->total($where);
		$page = new Page($total,$pagesize,$current,'index.php',array(
			'p'=>'admin','c'=>'attribute','a'=>'index','type_id'=>$type_id));
		$pageinfo = $page->showPage();
		// $attrs = $attrModel->getAttrs($type_id);
		include CUR_VIEW_PATH . "attribute_list.html";
	}
	//显示添加属性页面
	public function addAction(){
		//获取所有的商品类型
		$typeModel = new TypeModel('goods_type');
		$types = $typeModel->getTypes();
		include CUR_VIEW_PATH . "attribute_add.html";
	}
	//属性入库操作
	public function insertAction(){
		//1.收集表单数据，以关联数组的形式来收集 CTRL+SHIFT +D 复制一行
		$data['attr_name'] = trim($_POST['attr_name']);
		$data['attr_type'] = $_POST['attr_type'];
		$data['attr_input_type'] = $_POST['attr_input_type'];
		$data['type_id'] = $_POST['type_id'];

		$attr_value = split(PHP_EOL,(isset($_POST['attr_value']) ? trim($_POST['attr_value']) : ""));

		//2.验证和处理
		if ($data['attr_name'] === '') {
			$this->jump('index.php?p=admin&c=attribute&a=add','属性名称不能为空');
		}
		if ($data['type_id'] == 0) {
			$this->jump('index.php?p=admin&c=attribute&a=add','必须要选择商品类型');
		}
		if (($data['attr_type'] === '1' || $data['attr_type'] === '2') && $data['attr_input_type'] !== '1') {
			$this->jump('index.php?p=admin&c=attribute&a=add',"选择'单选/复选属性'时，录入方式只能是'从下面的列表中选择'",5);
		}
		if ($data['attr_type'] === '3' && $data['attr_input_type'] === '1') {
			$this->jump('index.php?p=admin&c=attribute&a=add',"选择'唯一属性'时，录入方式不能是'从下面的列表中选择'",5);
		}
		//进行转义处理
		$this->helper('input');
		$data = deepspecialchars($data);
		$data = deepslashes($data);
		$attr_value = deepspecialchars($attr_value);
		$attr_value = deepslashes($attr_value);

		//3.调用模型完成入库操作并给出提示
		$attrModel = new AttributeModel('attribute');
		$attrValueModel = new Model('attr_value');

		if ($attr_id = $attrModel->insert($data)) {
			$attrValue['attr_id'] = $attr_id;
			foreach($attr_value as $v) {
				$attrValue['attr_price'] = '0';
				//判断_是否存在,如果不存在,那就不用截取了
				$v = trim($v);
				if(strpos($v,'_') === false) {
					if(trim($v) !== '') {
						$attrValue['attr_value'] = $v;
						$attrValueModel->insert($attrValue);
					}
				} else {
					$v = split('_',$v);//只要_存在,结果就肯定>=2
					if(count($v) === 2 && ($attrValue['attr_value'] = $v[0]) !== ''){
						if($v[1] !== '')
							$attrValue['attr_price'] = $v[1];
						$attrValueModel->insert($attrValue);
					}
				}
			}
			$this->jump("index.php?p=admin&c=attribute&a=index&type_id={$data['type_id']}",'添加属性成功');
		} else {
			$this->jump('index.php?p=admin&c=attribute&a=add','添加属性失败');
		}
	}
	//显示编辑属性页面
	public function editAction(){

		$attrModel = new AttributeModel("attribute");
		$attrValueModel = new Model("attr_value");
		$typeModel = new TypeModel("goods_type");
		//获取attr_id
		$attr_id = $_GET['attr_id'] + 0;
		$this->helper('input');
		$attr_id = deepspecialchars($attr_id);
		$attr_id = deepslashes($attr_id);
		//获取attr_value
		$where = "attr_id = $attr_id";
		$attr_values = $attrValueModel->select($where);
		//构造attr_value字符串
		$values = "";
		foreach($attr_values as $attr_value){
			$values .= "{$attr_value['attr_value']}_{$attr_value['attr_price']}" . PHP_EOL;
		}
		$values = rtrim($values,PHP_EOL);

		//获取属性记录
		$pk = array();
		$pk['attr_id'] = $attr_id;
		$attr = $attrModel->selectByPk($pk);
		$attr['attr_value'] = $values;

		//获取所有分类
		$types = $typeModel->select();
		foreach($types as $type){
			if($type['type_id'] == $attr['type_id'])
				$attr['type_name'] = $type['type_name'];
		}

		include CUR_VIEW_PATH . "attribute_edit.html";
	}
	//属性更新操作
	public function updateAction(){
		//1.收集表单数据
		$data['attr_id'] = $_POST['attr_id'];
		$data['attr_name'] = $_POST['attr_name'];
		$data['type_id'] = $_POST['type_id'];
		$data['attr_type'] = $_POST['attr_type'];
		$data['attr_input_type'] = $_POST['attr_input_type'];
		$attr_value = split(PHP_EOL,(isset($_POST['attr_value']) ? $_POST['attr_value'] : ""));

		//实体转义
		$this->helper('input');
		$data = deepspecialchars($data);
		$data = deepslashes($data);
		$attr_value = deepspecialchars($attr_value);
		$attr_value = deepslashes($attr_value);

		//2.验证和处理
		if ($data['attr_name'] === '') {
			$this->jump("index.php?p=admin&c=attribute&a=edit&attr_id=".$data['attr_id'],'属性名称不能为空');
		}
		if (($data['attr_type'] === '1' || $data['attr_type'] === '2') && $data['attr_input_type'] !== '1') {
			$this->jump('index.php?p=admin&c=attribute&a=add',"选择'单选/复选属性'时，录入方式只能是'从下面的列表中选择'",5);
		}
		if ($data['attr_type'] === '3' && $data['attr_input_type'] === '1') {
			$this->jump('index.php?p=admin&c=attribute&a=add',"选择'唯一属性'时，录入方式不能是'从下面的列表中选择'",5);
		}

		//3.调用模型完成更新并给出提示
		$attrModel = new AttributeModel('attribute');
		$attrValueModel = new Model('attr_value');

		$attrValueModel->delete(array('attr_id' => $data['attr_id']));
		$attrValue['attr_id'] = $data['attr_id'];

		foreach($attr_value as $v) {
			//判断_是否存在,如果不存在,那就不用截取了
			$v = trim($v);
			$attrValue['attr_price'] = '0';
			if(strpos($v,'_') === false) {
				if(trim($v) !== '') {
					$attrValue['attr_value'] = $v;
					$attrValueModel->insert($attrValue);
				}
			} else {
				$v = split('_',$v);//只要_存在,结果就肯定>=2
				if(count($v) === 2 && ($attrValue['attr_value'] = $v[0]) !== ''){
					if($v[1] !== '')
						$attrValue['attr_price'] = $v[1];
					$attrValueModel->insert($attrValue);
				}
			}
		}
		if($attrModel->update($data)){
			$this->jump("index.php?p=admin&c=attribute&a=index&type_id={$data['type_id']}",'修改属性成功',1);
		} else {
			$this->jump("index.php?p=admin&c=attribute&a=index&type_id={$data['type_id']}",'修改属性值成功',1);
		}
	}
	//删除属性操作
	public function deleteAction(){
		//1.获取attr_id
		$attr_id = $_GET['attr_id'] + 0;
		$type_id = $_GET['type_id'] + 0;

		//2.数据验证
		$this->helper('input');
		$attr_id = deepslashes($attr_id);
		$attr_id = deepspecialchars($attr_id);
		$type_id = deepslashes($type_id);
		$type_id = deepspecialchars($type_id);

		//3.调用模型删除对应属性
		$attrValueModel = new Model('attr_value');
		$attrModel = new AttributeModel('attribute');
		$goodsAttrModel = new Model('goods_attr');


		//删除
		if($goodsAttrModel->delete(array('attr_id' => $attr_id)) === false)
			$this->jump("index.php?p=admin&c=attribute&a=index&type_id=$type_id",'删除属性失败',3);

		$where = "attr_id = $attr_id";
		if(!$attrValueModel->total($where)=='0' && !$attrValueModel->delete(array('attr_id' => $attr_id))){
			$this->jump("index.php?p=admin&c=attribute&a=index&type_id=$type_id",'删除属性值失败',3);
		} elseif($attrModel->delete(array('attr_id' => $attr_id))){
			$this->jump("index.php?p=admin&c=attribute&a=index&type_id=$type_id",'删除属性成功',1);
		} else {
			$this->jump("index.php?p=admin&c=attribute&a=index&type_id=$type_id",'删除属性失败',3);
		}

	}
	//获取指定类型下的属性
	public function getAttrsAction(){
		$type_id = $_GET['type_id'] + 0;
		//调用模型完成具体的操作
		$attrModel = new AttributeModel('attribute');
		// $attrs = $type_id;
		$attrs = $attrModel->getAttrsTable($type_id);
		echo <<<STR
		<script type="text/javascript">
			window.parent.document.getElementById("tbody-goodsAttr").innerHTML = "$attrs";
		</script>
STR;
	}
	//获取指定类型下的属性,并且按照商品id填充表单
	public function getEditAttrsAction(){
		$type_id = $_GET['type_id'] + 0;
		$goods_id = $_GET['goods_id'] + 0;
		//调用模型完成具体的操作
		$attrModel = new AttributeModel('attribute');
		// $attrs = $type_id;
		$attrs = $attrModel->getEditAttrsTable($type_id,$goods_id);

//		var_dump($attrs);
//		$count = count($attrs);
		echo <<<STR
		<script type="text/javascript">
			window.parent.document.getElementById("tbody-goodsAttr").innerHTML = "{$attrs}";
		</script>
STR;
	}
}