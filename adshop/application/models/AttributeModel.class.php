<?php
//商品属性模型
class AttributeModel extends Model{
	//删除指定商品下的所有属性(包括属性值)
	public function deleteAttrByGoodsID($goods_id){
//		删除属性值
		$sql = "DELETE FROM ad_attr_value
				WHERE ad_attr_value.attr_id
				IN (
					SELECT ad_attribute.attr_id FROM ad_attribute WHERE ad_attribute.goods_id = '{$goods_id}'
					)";
		if ($this->db->query($sql)) {
			# 成功,继续删除属性
			$sql = "DELETE FROM ad_attribute WHERE goods_id = '{$goods_id}'";
			if($this->db->query($sql)){
				return true;
			}else{
				return false;
			}
		} else {
			# 失败返回false
			return false;
		}
	}
	//获取指定商品下的所有属性(包括属性值)
	public function getAttrByGoodsID($goods_id){
		$attrs = $this->select("goods_id=".$goods_id);
		for($i = 0; $i < count($attrs); $i++){
			$sql = "SELECT attr_value FROM ad_attr_value WHERE attr_id = " . $attrs[$i]['attr_id'];
			$attrs[$i]['attr_values'] = $this->db->getCol($sql);
		}
		return $attrs;
	}
	//获取指定类型下的所有属性--v2
	public function getPageAttrs($type_id,$offset,$limit){
		$type_table = $GLOBALS['config']['prefix'] . "goods_type";
		if ($type_id == 0) {
			$sql = "SELECT * FROM {$this->table} as a INNER JOIN $type_table as b
			        ON  a.type_id = b.type_id
			        ORDER BY attr_id DESC
			        LIMIT $offset,$limit";
		} else {
			$sql = "SELECT * FROM {$this->table} as a INNER JOIN $type_table as b
			        ON  a.type_id = b.type_id
		        	WHERE a.type_id = $type_id
		        	ORDER BY attr_id DESC
			        LIMIT $offset,$limit";
		}	
		return $this->db->getAll($sql);
	}


	//获取指定类型下的所有属性并形成表格
	public function getAttrsTable($type_id){
		$sql = "SELECT * FROM {$this->table} WHERE type_id = $type_id";
		$attrs = $this->db->getAll($sql); //结果是二维数组
		$res = "<table width='100%' id='attrTable'>";

		foreach ($attrs as $attr) {
			$res .= "<tr>";
			$res .= "<input type='hidden' name='attr_id_list[]' value='{$attr['attr_id']}'>";
			switch ($attr['attr_input_type']) {
				case 0: #文本框
					$res .= "<td class='label'>{$attr['attr_name']}</td>";
					$res .= "<td>";
					$res .= "<input name='attr_value_list[]' type='text' size='40'>";
					break;
				case 1: #下拉列表
//					//创建attr_value表模型来获取属性值
//					$attrValueModel = new Model('attr_value');
//					$attrValues = $attrValueModel->select($attr['attr_id']);
//					$res .= "<select name='attr_value_list[]'>";
//					$res .= "<option value=''>请选择...</option>";
//					$opts = explode(PHP_EOL, $attr['attr_value']);
//					foreach ($opts as $opt) {
//						$res .= "<option value='$opt'>$opt</option>";
//					}
//					$res .= "</select>";
//					$res .= "<input type='hidden' name='attr_price_list[]' value='0'>";
					$res .= "<input name='attr_value_list[]' value='' type='hidden' size='40'>";
					break;
				case 2: #多行文本
					$res .= "<td class='label'>{$attr['attr_name']}</td>";
					$res .= "<td>";
					$res .= "<textarea name='attr_value_list[]' cols='30' rows='10'></textarea>";
					break;
			}

			$res .= "</td>";
			$res .="</tr>";
		}
		$res .= "</table>";
		return $res;
	}
	//获取指定类型下的所有属性并形成表格
	public function getEditAttrsTable($type_id,$goods_id){

//		select attr_name,attr_value
//		FROM js_attribute a,js_goods_attr b
//		where type_id = 8 and a.attr_id = b.attr_id and goods_id = 11;
		$sql = "select attr_name,attr_value,attr_input_type,a.attr_id as attr_id
  				from js_attribute a,js_goods_attr b
  				where type_id = {$type_id} and a.attr_id = b.attr_id and goods_id = {$goods_id}";
//		$sql = "SELECT * FROM {$this->table} WHERE type_id = $type_id";
		$attrs = $this->db->getAll($sql); //结果是二维数组

		$res = "<table width='100%' id='attrTable'>";

		foreach ($attrs as $attr) {
			$res .= "<tr>";
			$res .= "<input type='hidden' name='attr_id_list[]' value='{$attr['attr_id']}'>";
			switch ($attr['attr_input_type']) {
				case 0: #文本框
					$res .= "<td class='label'>{$attr['attr_name']}</td>";
					$res .= "<td>";
					$res .= "<input name='attr_value_list[]' type='text' size='40' value='{$attr['attr_value']}'>";
					break;
				case 1: #下拉列表
//					//创建attr_value表模型来获取属性值
//					$attrValueModel = new Model('attr_value');
//					$attrValues = $attrValueModel->select($attr['attr_id']);
//					$res .= "<select name='attr_value_list[]'>";
//					$res .= "<option value=''>请选择...</option>";
//					$opts = explode(PHP_EOL, $attr['attr_value']);
//					foreach ($opts as $opt) {
//						$res .= "<option value='$opt'>$opt</option>";
//					}
//					$res .= "</select>";
//					$res .= "<input type='hidden' name='attr_price_list[]' value='0'>";
					$res .= "<input name='attr_value_list[]' value='' type='hidden' size='40'>";
					break;
				case 2: #多行文本
					$res .= "<td class='label'>{$attr['attr_name']}</td>";
					$res .= "<td>";
					$res .= "<textarea name='attr_value_list[]' cols='30' rows='10'>{$attr['attr_value']}</textarea>";
					break;
			}

			$res .= "</td>";
			$res .="</tr>";
		}
		$res .= "</table>";
		return $res;
		//var_dump($attrs);
//		return $attrs;
	}
}