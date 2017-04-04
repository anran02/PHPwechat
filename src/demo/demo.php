<?php
require '../LCWeChat.php';
use anran02\LCWeChat;
if(!is_weixin())
{
    echo '请在微信浏览器访问';exit;
}
//公众号信息配置
//应用id
$appi = '';
//应用秘钥
$appsecret = '';
//商户id  （支付用）
$mch_id = '';
//回调地址，注意要在微信后台配置的回调域名下
$redirect_url = '';
//初始化sdk
$WeChat = new LCWeChat($appi, $appsecret,$mch_id, $redirect_url, null, null, null);



//判断是否授权过
session_start();
if(isset($_SESSION['userinfo']))
{
    $userinfo = $_SESSION['userinfo'];
}
//没openid去授权
if(!$userinfo)
{
    //有code直接授权
    if($_GET['code'])
    {
        $re = $WeChat->get_auth_user($_GET['code']);
        if(!$re)
        {
            echo '发生错误';exit;
        }
        $_SESSION['userinfo'] = $WeChat->userInfo;
        echo '获取当前用户信息成功<br>';
        foreach($WeChat->userInfo as $key=>$value)
        {
            if(is_string($value))
            {
                echo $key.':'.$value.'<br>';
            }
            else
            {
                echo $key.':';print_r($value);echo '<br>';
            }
        }
        exit;
    }
    
    //选择要获取用户信息的作用域
    $url = $WeChat->get_auth_url('snsapi_userinfo');
//     $url = $WeChat->get_auth_url('snsapi_base');
    header("Location:".$url);exit;
}
else
{
    echo '用户已授权过，用户信息为:<br>';
    foreach($userinfo as $key =>$value)
    {
        if(is_string($value))
            {
                echo $key.':'.$value.'<br>';
            }
            else
            {
                echo $key.':';print_r($value);echo '<br>';
            }
    }
    exit;
}

/**
 * 是否微信浏览器
 */
function is_weixin()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    return !!strpos($user_agent, 'MicroMessenger');
}