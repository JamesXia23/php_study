<?php
//模型类基类
class Model{
	protected $db; //数据库连接对象
	protected $table; //表名
	protected $fields = array();  //字段列表

	public function __construct($table){
		$dbconfig['host'] = $GLOBALS['config']['host'];
		$dbconfig['user'] = $GLOBALS['config']['user'];
		$dbconfig['password'] = $GLOBALS['config']['password'];
		$dbconfig['dbname'] = $GLOBALS['config']['dbname'];
		$dbconfig['port'] = $GLOBALS['config']['port'];
		$dbconfig['charset'] = $GLOBALS['config']['charset'];
		
		$this->db = new Mysql($dbconfig);
		$this->table = $GLOBALS['config']['prefix'] . $table;

		//调用getFields字段
		$this->getFields();
	}

	/**
	 * 获取表字段列表
	 *
	 */
	private function getFields(){
		$sql = "DESC ". $this->table;
		$result = $this->db->getAll($sql);//得到表结构,二维数组每一行都是一个表的每一个字段的信息
		$pk = array();

		foreach ($result as $v) {
			$this->fields[] = $v['Field'];//Field字段对应的是表字段名称
			if ($v['Key'] == 'PRI') {	//Key字段对应的是表字段的键类型
				//如果存在主键的话，则将其保存到变量$pk中
				$pk[] = $v['Field'];//主键可能不止一列
			}
		}
		//如果存在主键，则将其加入到字段列表fields中
		if (count(pk)) {
			$this->fields['pk'] = $pk;
		}
	}

	/**
	 * 自动插入记录
	 * @access public
	 * @param $list array 关联数组
	 * @return mixed 成功返回插入的id，失败则返回false
	 */
	public function insert($list){
		$field_list = '';  //字段列表字符串
		$value_list = '';  //值列表字符串
		foreach ($list as $k => $v) {
			if (in_array($k, $this->fields)) {
				$field_list .= "`".$k."`" . ',';
				$value_list .= "'".$v."'" . ',';
			}
		}
		//去除右边的逗号
		$field_list = rtrim($field_list,',');//rtrim:从右侧删除第一个
		$value_list = rtrim($value_list,',');
		//构造sql语句
		$sql = "INSERT INTO `{$this->table}` ({$field_list}) VALUES ($value_list)";

//		echo $sql.'<br>';
		if ($this->db->query($sql)) {
			# 插入成功,返回最后插入的记录id
			return $this->db->getInsertId();
			//return true;
		} else {
			# 插入失败，返回false
			return false;
		}
		
	}

	/**
	 * 自动更新记录
	 * @access public
	 * @param $list array 需要更新的关联数组
	 * @return mixed 成功返回受影响的记录行数，失败返回false
	 */
	public function update($list){
		$uplist = ''; //更新列表字符串
		$where = '';   //更新条件,默认为0
		foreach ($list as $k => $v) {
			if (in_array($k, $this->fields)) {
				if (in_array($k, $this->fields['pk'])) {
					# 是主键列，构造条件
					$where .= "`$k`=$v and";
				} else {
					# 非主键列，构造更新列表
					$uplist .= "`$k`='$v'".",";
				}
			}
		}
		//去除where右边的 and
		$where = $where === '' ? 0 : rtrim($where,' and');
		//去除uplist右边的,
		$uplist = rtrim($uplist,',');

		//构造sql语句
		$sql = "UPDATE `{$this->table}` SET {$uplist} WHERE {$where}";

//		echo $sql;

		if ($this->db->query($sql)) {
			# 成功，并判断受影响的记录数
			if ($rows = mysql_affected_rows()) {
				# 有受影响的记录数
				return $rows;
			} else {
				# 没有受影响的记录数，没有更新操作
				return false;
			}	
		} else {
			# 失败，返回false

			return false;
		}
		
	}

	/**
	 * 自动删除
	 * @access public
	 * @param $pk array 主键数组
	 * @return mixed 成功返回删除的记录数，失败则返回false
	 */
	public function delete($pk){
		$where = ''; //条件字符串

		foreach($pk as $k => $v) {
			$where .= "`$k`=$v and";
		}
		$where = $where === '' ? 0 : rtrim($where,' and');

		//构造sql语句
		$sql = "DELETE FROM `{$this->table}` WHERE $where";

//		echo $sql;

		if ($this->db->query($sql)) {
			# 成功，并判断受影响的记录数
			if ($rows = mysql_affected_rows()) {
				# 有受影响的记录
				return $rows;
			} else {
				# 没有受影响的记录
				return true;
			}		
		} else {
			# 失败返回false
			return false;
		}
	}

	/**
	 * 通过主键获取信息
	 * @param $pk array 主键数组
	 * @return array 单条记录
	 */
	public function selectByPk($pk){
		$where = ''; //条件字符串

		foreach($pk as $k => $v) {
			$where .= "`$k`=$v and";
		}
		$where = $where === '' ? 0 : rtrim($where,' and');

		$sql = "select * from `{$this->table}` where {$where}";

		return $this->db->getRow($sql);
	}

	/**
	 * 获取总的记录数
	 * @param string $where 查询条件，如"id=1"
	 * @return number 返回查询的记录数
	 */
	public function total($where){
		if(empty($where)){
			$sql = "select count(*) from {$this->table}";
		}else{
			$sql = "select count(*) from {$this->table} where $where";
		}
		return $this->db->getOne($sql);
	}

	/**
	 * 分页获取信息
	 * @param $offset int 偏移量
	 * @param $limit int 每次取记录的条数
	 * @param $where string where条件,默认为空
	 */
	public function pageRows($offset, $limit,$where = ''){
		if (empty($where)){
			$sql = "select * from {$this->table} limit $offset, $limit";
		} else {
			$sql = "select * from {$this->table}  where $where limit $offset, $limit";
		}
		
		return $this->db->getAll($sql);
	}

	public function select($where = '') {
		if (empty($where)) {
			$sql = "select * from {$this->table}";
		} else {
			$sql = "select * from {$this->table} WHERE $where";
		}

		return $this->db->getAll($sql);
}
}