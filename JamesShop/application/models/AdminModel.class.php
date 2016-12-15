<?php
//admin模型
class AdminModel extends Model {
	//验证用户名和密码
	public function checkUser($username,$password) {
		$password = md5($password);
		$sql = "SELECT * FROM {$this->table}
		        WHERE user_name = '$username' AND password = '$password' AND is_admin = '1'
		        LIMIT 1";
		// echo $sql;
		// die;
		$user = $this->db->getRow($sql); //返回一维数组

		return empty($user) ? false : $user;
	}

	public function getAdmins() {
		$sql = "SELECT * FROM {$this->table}";
		return $this->db->getAll($sql);
	}
}