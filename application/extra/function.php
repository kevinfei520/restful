<?php

/**
 * 系统公共库文件
 * 主要定义系统公共函数库
 */


/**
 * 检查param中的key是否和info中相等，和正确
 * Enter description here ...
 * @param $info $param
 */
function checkDigitalArr($info,$param){
	$data = []; $reply = [];
	foreach ($info as $key => $value) {
		foreach ($param as $k => $v) {
			if ( $value === $k ) {
				$data[$key] = $k;
				$reply[$k] = $v; 
			}
		}
	}
	$result = array_diff_assoc($info,$data);
	if ( empty($result) ) {
		return  $reply;
	}else{
		$res = implode(",",$result);
		return returnmsg(400203,'The parameter is incomplete. Please check it.'.' '."'".$res."'");
	}
}

/**
 * 如果需要允许跨域请求，请在记录处理跨域options请求问题，并且返回200，以便后续请求，这里需要返回几个头部。。
 * @param code 状态码
 * @param message 返回信息
 * @param data 返回信息
 * @param header 返回头部信息
 */
function returnmsg($code = '400', $message = '',$data = [],$header = [])
{	
	http_response_code($code);    //设置返回头部
	$return['code'] = $code;
	$return['message'] = $message;
	if (!empty($data)) $return['data'] = $data;
	// 发送头部信息
    foreach ($header as $name => $val) {
        if (is_null($val)) {
            header($name);
        } else {
            header($name . ':' . $val);
        }
    }
	exit(json_encode($return,JSON_UNESCAPED_UNICODE));
}





?>