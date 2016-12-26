<?php
//商品相册模型
class GalaryModel extends Model{

	public function insertGalary($goods_id,$img_url_desc)
	{
		$data['goods_id'] = $goods_id;
		foreach($img_url_desc as $img){
			$data['img_url'] = $img['url'];
			$data['img_desc'] = $img['img_desc'];
			if(!$this->insert($data))
				return false;
		}
		return true;
	}
}