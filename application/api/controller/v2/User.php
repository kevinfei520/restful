<?php
namespace app\api\controller\v2;

use app\api\model\User as UserModel;

class User 
{	
	//获取列表信息
	public function index()
	{
		return 'index';
	}

    // 获取用户信息
    public function read($id = 0)
    {
        return $id;
    }


}