<?php
namespace app\api\controller\v1;

use think\Request;
use think\Response;
use think\Controller;
use app\api\controller\Api;
use app\api\controller\UnauthorizedException;

/**
 * 所有资源类接都必须继承基类控制器
 * 基类控制器提供了基础的验证，包含app_token,请求时间，请求是否合法的一系列的验证
 * 在所有子类中可以调用$this->clientInfo对象访问请求客户端信息，返回为一个数组
 * 在具体资源方法中，不需要再依赖注入，直接调用$this->request返回为请求具体信息的一个对象
 */
/**
 * 班级信息接口
 */
class Classroom extends Api
{   
    /**
     * 允许访问的方式列表，资源数组如果没有对应的方式列表，请不要把该方法写上，如user这个资源，客户端没有delete操作
     */
    public $restMethodList = 'get|post|put|delete';

    /**
     * restful没有任何参数 
     * @return \think\Response
     */
    public function index()
    {
        $param = $this->clientInfo;
        return 'index';
    }

    /**
     * get方式
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read()
    {   
        echo "get";
    }

    /**
     * 单条记录find
     */
    public function fans($id)
    {   
        return $id;
    }

    /**
     * post方式
     *
     * @param  \think\Request  $request
     * @return \think\Responses
     */
    public function save()
    {   
        echo "post";
    }

    /**
     * PUT方式
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update()
    {
       echo "put";
    }

    /**
     * delete方式
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete()
    {
        return 'delete';
    }

    
}
