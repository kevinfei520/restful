<?php
namespace app\api\controller;

use app\api\controller\UnauthorizedException;
use app\api\controller\Send;
use app\api\model\Oauth;
use think\Exception;
use think\Request;
use think\Db;
use think\Cache;

class Oauth2
{
    use Send;
    
    /**
     * 过期时间秒数
     *
     * @var int
     */
    public static $expires = 72000;

    /**
     * 客户端信息
     *
     * @var
     */
    public $clientInfo;
    
    /**
     * 认证授权 通过用户信息和路由
     * @param Request $request
     * @return \Exception|UnauthorizedException|mixed|Exception
     * @throws UnauthorizedException
     */
    final function authenticate()
    {   
        $request = Request::instance(); //获取请求方式
        try {
            //验证授权
            $clientInfo = $this->getClient();
            $checkclient = $this->certification($clientInfo);
            if($checkclient){
                return $clientInfo;
            }
        } catch (Exception $e) {
            return $this->returnmsg(402,'Invalid1 authentication credentials.');
        }
    }

    /**
     * 获取用户信息
     * @param Request $request
     * @return $this
     * @throws UnauthorizedException
     */
    public function getClient()
    {   
        $request = Request::instance();
        //获取头部信息
        try {
            $clientInfo = $request->param();
        } catch (Exception $e) {
            return $this->returnmsg(402,$e.'Invalid authentication credentials.');
        }
        return $clientInfo;
    }

    /**
     * 获取用户信息后 验证权限
     * @return mixed
     */
    public function certification($data = []){
        //======下面注释部分是数据库验证access_token是否有效，示例为缓存中验证======
        $Oauth = new Oauth();
        $result = $Oauth->checkAppToken($data['access_token']);
        if($result == false){
            return $this->returnmsg(402,'Access_token expired or error！');
        }
        return true;
    }

    /**
     * 生成签名
     * _字符开头的变量不参与签名
     */
    public function makeSign ($data = [],$app_secret = '')
    {   
        unset($data['version']);
        unset($data['signature']);
        foreach ($data as $k => $v) {
            if(substr($data[$k],0,1) == '_'){
                unset($data[$k]);
            }
        }
        return $this->_getOrderMd5($data,$app_secret);
    }

    /**
     * 计算ORDER的MD5签名
     */
    private function _getOrderMd5($params = [] , $app_secret = '') {
        ksort($params);
        $params['key'] = $app_secret;
        return strtolower(md5(urldecode(http_build_query($params))));
    }

}