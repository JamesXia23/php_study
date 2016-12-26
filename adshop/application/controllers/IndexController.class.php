<?php
//前台首页控制器
class IndexController extends Controller {
	//显示首页
	public function indexAction(){
		//获取所有的分类
		$categoryModel = new CategoryModel('category');
		$cats = $categoryModel->getCats();
		//获取所有品牌
		$brandModel = new BrandModel('brand');
		$brands = $brandModel->select();

		$ranks = $categoryModel->getRanks();
		$lastLevel = count($ranks);
		$page = $this->generalCat(1, $cats, $lastLevel, "");

		$cframe = isset($_GET['cframe']) ? $_GET['cframe'] : "";
		$aframe = isset($_GET['aframe']) ? $_GET['aframe'] : "";
		$brand_id = isset($_GET['brand_id']) ? "&brand_id=" . $_GET['brand_id'] : "";
		$cat_id = isset($_GET['cat_id']) ? "&cat_id=" . $_GET['cat_id'] : "";

		$src = "";
		if($cframe != '' && $aframe != ''){
			$src = 'index.php?c=' . $cframe . '&a=' . $aframe . $brand_id .$cat_id;
		}
		$username = $this->login_username;
		$path = $this->showAccordingToUser();
//		echo $path;
		include  CUR_VIEW_PATH . "index.html";

	}
	public function generalCat($level, $cats, $lastLevel, $parent_id){
		$str = "";
		if($level > $lastLevel){
			return "";
		}

		foreach ($cats as $cat):
			if($cat['cat_rank'] == $level && $cat['parent_id'] == $parent_id){
				$str .= "<li class='dropdown-submenu'>";
					$str .= "<a tabindex='-1' href='javascript:;' onclick=\"showPage('index.php?c=goods&a=index&cat_id=".$cat['cat_id']."')\">".$cat['cat_name']."</a>";
					if($cat['hasChild'] == 1)
						$str .= "<ul class='dropdown-menu'>";
					$str .= $this->generalCat($level+1, $cats, $lastLevel, $cat['cat_id']);
					if($cat['hasChild'] == 1)
						$str .= "</ul>";
				$str .= "</li>";
			}
		endforeach;
		return $str;
	}
	public function showAccordingToUser(){
		if($this->login_status == 0){
			return "adminnav.html";
		} elseif($this->login_status == 1) {
			return "usernav.html";
		} elseif($this->login_status == 2) {
			return "anonymousnav.html";
		}
	}
	//生成验证码
	public function codeAction(){
		//引入验证码类
		$this->library('Captcha');
		//实例化对象
		$captcha = new Captcha();
		//调用方法生成验证码
		$captcha->generateCode();
	}
}