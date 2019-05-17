<?php
namespace app\api\model;
use think\Model;

/**
 * 读取学校的信息
 */
class SchoolCode extends Model{

	/**
	 * 获取用户列表
	 */
	public function list(){
		return $this->select();
	}

	/**
	 * 获取学校详细信息
	 * @param    [int]     $id   [学校id]
	 */
	public function info( $id ){
		$where = array(
			'id' => $id
		);
		return $this->where($where)->find();
	}

	/**
	 * @param    [array]     $data  [需要保存的信息]
	 */
	public function saveinfo($data){
		return $this->save($data);
	}

	/**
	 * 修改学校信息
	 */
	public function schoolupdate($id,$data){
		$where = array(
			'id' => $id,
		);
		return $this->where($where)->update($data);
	}
	
	
}