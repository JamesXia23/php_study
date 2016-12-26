<?php
//商品模型
class GoodsModel extends Model {
	//查找出带相册的商品
	public function selectGoods($where = ''){
		$allGoods = $this->select($where);
		$allGoods = $this->addGalary($allGoods);
		return $allGoods;
	}
	public function searchGoods($keyword){
		$sql = "SELECT * FROM {$this->table} WHERE goods_name LIKE '%{$keyword}%'";
		$allGoods = $this->db->getAll($sql);
		$allGoods = $this->addGalary($allGoods);
		return $allGoods;
	}
	public function addGalary($allGoods){
		for($i = 0; $i < count($allGoods); $i++){
			$sql = "SELECT img_url FROM ad_galary WHERE goods_id = {$allGoods[$i]['goods_id']}";
			$allGoods[$i]['goods_galary'] = $this->db->getCol($sql);
		}
		return $allGoods;
	}
}