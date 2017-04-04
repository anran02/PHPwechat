<?php
require '../WeChat.php';
use anran02\WeChat;
if(!is_weixin())
{
    echo '请在微信浏览器访问';exit;
}
//初始化sdk
$WeChat = new WeChat('wx5a7bc0d2c192485f', '9c3dd0bc9f4afd0afbfb1314a2e899fc', '1229675602', 'http://www.warmjar.com/demo/demo.php', null, null, null);



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