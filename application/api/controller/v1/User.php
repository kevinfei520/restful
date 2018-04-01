<?php
namespace app\api\controller\v1;

use think\Request;
use think\Response;
use think\Controller;
use app\api\controller\Api;
use app\api\model\SchoolCode as Schoolcode;
use app\api\controller\UnauthorizedException;

/**
 * 所有资源类接都必须继承基类控制器
 * 基类控制器提供了基础的验证，包含app_token,请求时间，请求是否合法的一系列的验证
 * 在所有子类中可以调用$this->clientInfo对象访问请求客户端信息，返回为一个数组
 * 在具体资源方法中，不需要再依赖注入，直接调用$this->request返回为请求具体信息的一个对象
 */
class User extends Api
{   
    /**
     * 允许访问的方式列表，资源数组如果没有对应的方式列表，请不要把该方法写上，如user这个资源，客户端没有delete操作
     */
    public $restMethodList = 'get|post|put';

    /**
     * restful没有任何参数
     *
     * @return \think\Response
     */
    public function index()
    {
        return 'index';
    }

    /**
     * post方式
     *
     * @param  \think\Request  $request
     * @return \think\Responses
     */
    public function save()
    {   

        $param = $this->clientInfo;
        foreach ($param as $key => $value) {
            if ( $key === 'access_token' ) {
               unset( $param[$key] );
            }else if ( $key === 'version' )  {
                unset( $param[$key] );
            }
        }
        $info = ['school_code','school_name','school_description'];   //定义需要那些参数
        $data = checkDigitalArr($info,$param);
        $schoolcode = new Schoolcode();  
        $reply = $schoolcode->saveinfo($data);
        if ($reply === 0 ) {
            return $this->returnmsg(400101,'The modification failed, please check whether the parameters are correct or revise');
        }else{
            return $this->sendSuccess('添加成功');
        }
    }

    /**
     * get方式
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read()
    {   
        $param = $this->clientInfo;
        $schoolcode = new Schoolcode();  
        $data = $schoolcode->info($param['id']);
        return $this->sendSuccess($data);
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
        $param = $this->clientInfo;
        $schoolcode = new Schoolcode();
        $info = array(    //允许修改的字段
               0 => 'school_code',
               1 => 'school_name',
               2 => 'school_description',
        );
        for ($i=0 ; $i < 3 ; $i++ ) { 
            if ( array_key_exists( $info[$i], $param ) ) {
               $data = $info[$i]; 
            }
        }
        $data = [ $data => $param[$data] ] ;
        $reply = $schoolcode->schoolupdate($param['id'],$data);
        if ($reply === 0 ) {
            return $this->returnmsg(400101,'The modification failed, please check whether the parameters are correct or revise');
        }else{
            return $this->sendSuccess('修改成功');
        }
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

    public function fans($id)
    {   
        return $id;
    }
}
