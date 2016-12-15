<?php
//商品模型
class GoodsModel extends Model {
	//获取推荐商品
	public function getBestGoods(){
		$sql = "SELECT goods_id,goods_name,goods_img,shop_price FROM {$this->table}
		        WHERE  is_best = 1 AND is_onsale = 1
				ORDER BY goods_id DESC
		        LIMIT 4";
		return $this->db->getAll($sql);
	}
	//获取热销商品
	public function getHotGoods(){
		$sql = "SELECT goods_id,goods_name,goods_img,shop_price FROM {$this->table}
		        WHERE  is_hot = 1 AND is_onsale = 1
				ORDER BY goods_id DESC
		        LIMIT 4";
		return $this->db->getAll($sql);
	}
	//获取最新商品
	public function getNewGoods(){
		$sql = "SELECT goods_id,goods_name,goods_img,shop_price
				FROM {$this->table}
		        WHERE  is_new = 1 AND is_onsale = 1
				ORDER BY goods_id DESC
		        LIMIT 4";
		return $this->db->getAll($sql);
	}
	//获取商品的唯一属性
	public function getUniqueAttr($goods_id){
		$sql = "SELECT attr_name,attr_value
				FROM js_attribute a,js_goods_attr b
		        WHERE  a.attr_id = b.attr_id AND goods_id = $goods_id AND attr_type = '0'";
		return $this->db->getAll($sql);
	}
	//获取商品的单值属性
	public function getRadioAttr($goods_id){
		$sql = "SELECT attr_name,c.attr_value AS attr_value,attr_price
				FROM js_attribute a,js_goods_attr b,js_attr_value c
		        WHERE attr_type = '1' AND goods_id = $goods_id AND a.attr_id = b.attr_id AND a.attr_id = c.attr_id
		        GROUP BY attr_name";
		//var_dump($this->db->getAll($sql));
		return $this->db->getAll($sql);
	}
	//获取商品的多值属性
	public function getCheckAttr($goods_id){
		$sql = "SELECT attr_name,c.attr_value AS attr_value,attr_price
				FROM js_attribute a,js_goods_attr b,js_attr_value c
		        WHERE attr_type = '2' AND goods_id = $goods_id AND a.attr_id = b.attr_id AND a.attr_id = c.attr_id";
		return $this->db->getAll($sql);
	}
}