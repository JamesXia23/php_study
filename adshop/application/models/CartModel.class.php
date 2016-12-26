<?php
//商品类型模型
class CartModel extends Model{

	public function getCart(){
		$user = $_SESSION['user'];
		$sql = "SELECT ad_goods.goods_id goods_id, goods_name, ad_cart.goods_number goods_number
				FROM ad_goods, ad_cart
				WHERE ad_cart.user_id={$user['user_id']} AND ad_cart.goods_id=ad_goods.goods_id";
		return $this->db->getAll($sql);
	}
}