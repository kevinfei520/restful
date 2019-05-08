<?php
namespace app\api\model;
use think\Model;

/**
 * 课程的信息模型
 * @author   dingjingfei
 */
class SchoolRoom extends Model{

	/**
	 * 获取课程的详细信息
	 * @param    $id  school_id
	 */
	public function info( $id ){
		$where = array(
			'id' => $id
		);
		return $this->where($where)->find();
	}

	/**
	 * 获取课程的详细信息
	 * @param    $id  school_id
	 */
	public function infoAll( $id ){
		$where = array(
			'school_id' => $id
		);
		return $this->where($where)->select();
	}

	/**
	 * 添加课程信息
	 * @param  array  $data  添加信息
	 */
	public function saveinfo($data){
		$data['createtime'] = date('Y-m-d H:i:s',time()); //time();
		$data['status'] = 0;
		return $this->save($data);
	}

	/**
	 * 修改课程信息
	 */
	public function courseupdate($id,$data){
		$where = array(
			'id' => $id,
		);
		return $this->where($where)->update($data);
	}

	/**
	 * 删除课程信息(此处为逻辑删除)
	 */
	public function coursedelete($id){
		$where = array(
			'id' => $id,
		);
		return $this->where($where)->update(['status'=>1]);
	}

	
	
}