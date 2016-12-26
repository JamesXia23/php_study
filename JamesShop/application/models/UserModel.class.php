<?php
//用户模型
class UserModel extends Model {
	//验证用户名和密码
	public function checkUser($username,$password) {
		$password = md5($password);
		$sql = "SELECT * FROM {$this->table}
		        WHERE user_name = '$username' AND password = '$password'
		        LIMIT 1";
		// echo $sql;
		// die;
		$user = $this->db->getRow($sql); //返回一维数组

		return empty($user) ? false : $user;
	}

//	public function isAdmin($username){
//		$sql = "SELECT is_admin FROM {$this->table}
//				WHERE user_name = '$username' LIMIT 1";
//		$is_admin = $this->db->getCol($sql)[0] == 1 ? true : false;
//		return $is_admin;
//	}
	/**
	 * 判断用户名是否存在
	 * @param $username
	 * @return bool 为true表示存在,为false表示不存在
	 */
	public function checkUsername($user_name){

		$where = "user_name = '{$user_name}'";
		return intval($this->total($where)) === 0 ? false : true;
	}

	/**
	 * 判断邮箱是否已经被使用
	 * @param $email
	 * @return bool 为true表示已被使用,为false表示未使用
	 */
	public function checkEmail($email){

		$where = "email = '{$email}'";
		return intval($this->total($where)) === 0 ? false : true;
	}
}