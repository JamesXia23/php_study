<?php
//商品类型模型
class CartModel extends Model{

	public function getCart(){
		$user = $_SESSION['user'];
		$sql = "SELECT {$GLOBALS['config']['prefix']}goods.goods_id goods_id, goods_name, {$this->table}.goods_number goods_number
				FROM {$GLOBALS['config']['prefix']}goods, {$this->table}
				WHERE {$this->table}.user_id={$user['user_id']} AND {$this->table}.goods_id={$GLOBALS['config']['prefix']}goods.goods_id";
		return $this->db->getAll($sql);
	}
}