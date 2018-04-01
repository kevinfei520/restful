<?php
namespace app\api\model;

use think\Model;

/**
 * 存储用户appid，app_secret等值，为每个用户分配对应的值，生成access_token
 */
class Oauth extends Model{

	protected $resultSetType = '';
	protected $readonly = ['app_key'];
	
	/**
	 * 验证合法的appkey
	 * @param appkey 
	 * @return true|false
	 */
	public function checkAppkey($app_key,$app_secret)
	{
		$where = array(
			'app_key' => $app_key,
			'app_secret' => $app_secret
		);
		$info = $this->where($where)->find();
		$data = $info->data;
		if($data == null) return false;
		if($data['expires_in'] > time()){
			return true;
		}
		return false;
	}
	
	/**
	 * 检查app的token
	 * Enter description here ...
	 * @param $app_key
	 * @param $access_token
	 */
	public function checkAppToken($access_token){
		$where = array(
			'access_token' => $access_token,
			'expires_in' => array('gt',time())
		);
		$info = $this->where($where)->find();
		$data = $info->data;
		if($data == null) return false;
		return true;
	}
}