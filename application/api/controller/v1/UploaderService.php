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
 * 上传资源服务接口
 */
class UploaderService extends Api
{   
    /**
     * 允许访问的方式列表，资源数组如果没有对应的方式列表，请不要把该方法写上，如user这个资源，客户端没有delete操作
     */
    public $restMethodList = 'get|post|put|delete';

    /**
     * restful没有任何参数
     *
     * @return \think\Response
     */
    public function index()
    {   
        $param = $this->clientInfo;
        $course = new SchoolRoom();
        $replay = $course->infoAll( $param['shool_id'] );
        if ($replay == null) {
            return $this->returnmsg(400201,'There is no class under the school');
        }else{
            return $this->sendSuccess($replay);
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
        $course = new SchoolRoom();  
        $data = $course->info($param['id']);
        return $this->sendSuccess($data);
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
        $info = ['room_no','room_name','school_id'];   //定义需要那些参数
        $data = checkDigitalArr($info,$param);
        $course = new SchoolRoom();  
        $reply = $course->saveinfo($param);
        if ($reply === 1) {
           return $this->sendSuccess('添加成功');
        }
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
        $course = new SchoolRoom(); 
        $info = array(    //允许修改的字段
               0 => 'room_no',
               1 => 'room_name',
               2 => 'school_id',
        );
        for ($i=0 ; $i < 3 ; $i++ ) { 
            if ( array_key_exists( $info[$i], $param ) ) {
               $data = $info[$i]; 
            }
        }
        $data = [ $data => $param[$data] ] ;
        $reply = $course->courseupdate($param['id'],$data);
        if ($reply === 0 ) {
            return $this->returnmsg(400202,'The modification failed, please check whether the parameters are correct or revise');
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

    /**
     * 图片上传
     * @param $imginfo - 图片的资源，数组类型。['图片类型','图片大小','图片进行base64加密后的字符串']
     * @param $companyid - 公司id
     * @return mixed
     */
    public function uploadImage( $imginfo , $companyid ) {
        $image_type = strip_tags($imginfo[0]);  //图片类型
        $image_size = intval($imginfo[1]);  //图片大小
        $image_base64_content = strip_tags($imginfo[2]); //图片进行base64编码后的字符串

        $upload = new UploaderService();
        $upconfig = $upload->upconfig;

        if(($image_size > $upconfig['maxSize']) || ($image_size == 0)) {
            $array['status'] = 13;
            $array['comment'] = "图片大小不符合要求！";
            return $array;
        }

        if(!in_array($image_type,$upconfig['exts'])) {
            $array['status'] = 14;
            $array['comment'] = "图片格式不符合要求！";
            return $array;
        }

        // 设置附件上传子目录
        $savePath = 'bus/group/' . $companyid . '/';
        $upload->upconfig['savePath'] = $savePath;

        //图片保存的名称
        $new_imgname = uniqid().mt_rand(100,999).'.'.$image_type;

        //base64解码后的图片字符串
        $string_image_content = base64_decode($image_base64_content);

        // 保存上传的文件
        $array = $upload->upload($string_image_content,$new_imgname);

        return $array;
    }
}
