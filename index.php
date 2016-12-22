<?php
namespace simplehtmldom_1_5;
include_once "simple_html_dom.php";
/**
 * Created by PhpStorm.
 * User: nairoj
 * Date: 16-12-19
 * Time: 下午9:29
 */
session_start();
$dir = dirname(__FILE__);
$url = array(
    "checksum" => "http://jw.djtu.edu.cn/academic/getCaptcha.do",
    "login" => "http://jw.djtu.edu.cn/academic/j_acegi_security_check",
    "assess" => "http://jw.djtu.edu.cn/academic/accessModule.do?moduleId=508",
    "pre" => "http://jw.djtu.edu.cn/academic/eva/index/"
);
@$func = $_GET['func'];
if ($func === 'getcs') {
    $checkSum = curlget($url['checksum']);
    $checkSum = 'data:imge;base64,' . base64_encode($checkSum);
    echo $checkSum;
} elseif ($func === 'login') {
    if(!(isset($_POST['userid'])&&isset($_POST['passwd'])&&isset($_POST['checkcode']))){
        echo "请填写完整信息";die();
    }
    //登录
    $username = intval($_POST['userid']);
    $passwd = $_POST['passwd'];
    $checkcode = $_POST['checkcode'];
    $post_file = "j_username=$username&j_password=$passwd&j_captcha=$checkcode";
    recordLog('login:'.$username);
    $rs = curlget($url['login'], $post_file);
    //curl失败
    if($rs===-1){
        echo '失败..请稍后重试';
    }
    //登录失败  输出错误信息
    $rs = checkLogin($rs);
    if($rs['code']===0){
        echo $rs['msg'];
        die();
    }
    //登录成功
    $accesshtml = curlget($url['assess']);
    $html = str_get_html($accesshtml);
    $trdoms = $html->find('.infolist_common');
    $hrefs = array();
    foreach ($trdoms as $trdom) {
        @$href = $trdom->children(3)->children(0)->href;
        if ($href === null) continue;
        $hrefs[] = urlencode($url['pre'] . $href);
    }
    recordLog('count'.count($hrefs));
    if (count($hrefs) === 0) {
        echo '没有待评估的老师';
        die();
    }
    echo '正在自动评估 ' . count($hrefs) . ' 门课程，请稍后到教务查看';
    $hrefs = implode(',',$hrefs);
    exec("php $dir/doAssess.php $hrefs {$_SESSION['cookie_file']}");
} else {
    $_SESSION['cookie_file'] = tempnam($dir.'/tmp', 'cookie');
    chmod($_SESSION['cookie_file'],0777);
    $checkSum = curlget($url['checksum']);
    $checkSum = 'data:imge;base64,'.base64_encode($checkSum);
    require "login.php";
}

/**
 * @登录获取的页面文件 $html
 * @1为成功，0为失败 mixed
 */
function checkLogin($html)
{
    if (stripos($html, "check()")) {//有check函数表示还在登陆界面，即为登陆失败
        $rs['code'] = 0;
        $weizhi = strpos($html, '<div id="error">');
        $status = substr($html, $weizhi + 43, 6);
        $status = iconv("gbk", "utf-8", $status);
        if ($status == "您输入")
            $rs['msg'] = "验证码有误";
        elseif ($status == '用户名')
            $rs['msg'] = "用户名不存在";
        elseif ($status == '密码不')
            $rs['msg'] = "密码错误";
    } else {//登陆成功
        $rs['code'] = 1;
    }
    recordLog('checklogin:'.json_encode($rs));
    return $rs;
}

/**
 * @日志记录 $record
 */
function recordLog($record)
{
    $dir = dirname(__FILE__);
    $day = date('Y-m-d');
    $record = date(' H:i:s') . ' >>> ' . $record;
    file_put_contents("$dir/log/$day", $record . PHP_EOL, FILE_APPEND);
}

/**
 * @url $url
 * @post的数据 string $data
 * @return array|mixed
 */
function curlget($url, $data = '')
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $timeout = 30;
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    if ($data) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($ch, CURLOPT_COOKIEFILE, $_SESSION['cookie_file']);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $_SESSION['cookie_file']);
    $arr = curl_exec($ch);
    $curl_errno = curl_errno($ch);//判断出错
    curl_close($ch);
    if ($curl_errno > 0) {
        $arr = -1;
    }
    recordLog('curl_errno:'.$curl_errno);
    return $arr;
}
