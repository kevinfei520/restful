<?php
/**
 * 获取accesstoken
 */
namespace app\api\controller\v1;

use think\Db;
use think\Cache;
use think\Request;
use think\Response;
use think\Controller;
use app\api\controller\Api;
use app\api\controller\Send;
use app\api\controller\Factory;
use app\api\model\Oauth as Oauth;
use app\api\controller\Oauth2 as Oauth2;
use app\api\controller\UnauthorizedException;

class Token extends Controller
{	
	use Send;
	
	//手机客户端请求验证规则
	public static $rule_app = [
        'app_key'     =>  'require',
		'app_secret'     =>  'require'
    ];
    
    /**
     * 构造函数
     * 初始化检测请求时间，签名等
     */
    public function __construct()
    {
        $this->request = Request::instance();
    }
    
	/**
	 * 检测appkey的有效性
	 * @param 验证规则数组
	 */
	public function checkAppkey($rule)
	{	
		$result = $this->validate($this->request->param(),$rule);
		if(true !== $result){
			return $this->returnmsg(405,$result);
		}
		$Oauth = new Oauth();
		$result = $Oauth->checkAppkey($this->request->param("app_key"),$this->request->param("app_secret"));
		if($result == false){
			return $this->returnmsg(401,'App_key does not exist or has expired. Please contact management');
		}
	}
    
	/**
	 * 获取token值
	 * Enter description here ...
	 */
	public function token()
	{	
		//检测appkey
		$this->checkAppkey(self::$rule_app);
		try {
			$app_key = $this->request->param('app_key');
			$app_secret = $this->request->param('app_secret');
			$accessTokenInfo = $this->setAccessToken($app_key,$app_secret);
			return $this->sendSuccess($accessTokenInfo);
		} catch (\Exception $e) {
			$this->sendError(500, 'server error!!', 500);
		}
	}

	/**
	 * 检查签名
	 */
	public function checkSign()
	{	
		$baseAuth = Factory::getInstance(Oauth2);
		$app_secret = Oauth::get(['app_key' => $this->request->param('app_key')]);
    	$sign = $baseAuth->makesign($this->request->param(),$app_secret['app_secret']);//生成签名
    	if($sign !== $this->request->param('signature')){
    		return self::returnmsg(401,'Signature error',[],[]);
    	}
	}

	/**
	 * 设置AccessToken
	 * Enter description here ...
	 * @param $app_key
	 * @param $app_secret
	 */
    protected function setAccessToken($app_key, $app_secret){
        //生成令牌
        $accessToken = self::buildAccessToken($app_key,$app_secret);
        $accessTokenInfo = [
            'app_key' => $app_key,//用户信息
            'access_token' => $accessToken,//访问令牌
            'expires_in' => time() + Oauth2::$expires,//过期时间时间戳
        ];
        self::saveAccessToken($accessTokenInfo, $app_key);
        return $accessTokenInfo;
    }

    /**
     * 生成AccessToken
     * @return string
     */
    protected static function buildAccessToken($app_key,$app_secret){
        //生成AccessToken
        $str_pol = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789abcdefghijklmnopqrstuvwxyz";
		$nonce = str_shuffle($str_pol).time().$app_key.$app_secret;
		return md5($nonce,false);
    }

    /**
     * 存储
     * @param $accessToken
     * @param $accessTokenInfo
     */
    protected static function saveAccessToken($accessToken, $app_key){
    	$oauth = new Oauth();
    	$oauth->allowField(['access_token','expires_in'])->save($accessToken,['app_key'=>$app_key]);
    }
}