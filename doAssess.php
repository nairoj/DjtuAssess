<?php
namespace simplehtmldom_1_5;
include_once "simple_html_dom.php";
/**
 * Created by PhpStorm.
 * User: nairoj
 * Date: 16-12-20
 * Time: 下午10:29
 */
doAssess(explode(',',$argv[1]),$argv[2]);
recordLog2("$argv[1],$argv[2]");
unlink($argv[2]);
function recordLog2($record)
{
    $dir = dirname(__FILE__);
    $day = date('Y-m-d');
    $record = date('H:i:s') . ' >>> ' . $record;
    file_put_contents("$dir/log2/{$day}", $record . PHP_EOL, FILE_APPEND);
}
/**
 * @评估网址数组 $hrefs
 */
function doAssess($hrefs,$cookiefile)
{
    $score = array("A" => '100.0#5#34', 'B' => '80.0#5#35', 'C' => '60.0#5#36', 'D' => '40.0#5#37', 'E' => '20.0#5#38');
    $formhref = "http://jw.djtu.edu.cn/academic/eva/index/putresult.jsdo";
//    $href = urldecode($hrefs[0]);
    foreach ($hrefs as $href) {
$href = urldecode($href);
        $html = curlget($href,$cookiefile);
    $dom = str_get_html($html);
        $inputs = $dom->find("input[type='hidden']");
        $post_file = "";
        foreach ($inputs as $input) {
            $post_file .= $input->name . '=' . $input->value . '&';
        }
    $inputs = $dom->find("input[name='itemid']");
        foreach ($inputs as $input) {
            $key = 'itemid' . $input->value;
            $value = $score['A'];
            $post_file .= $key . '=' . $value . '&';
        }

    $post_file = substr($post_file, 0, strlen($post_file) - 1);
    recordLog2('do:'.$post_file);
    curlget($formhref,$cookiefile,$post_file);
    }
}
function curlget($url,$cookiefile, $data = '')
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
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
    $arr = curl_exec($ch);
    $curl_errno = curl_errno($ch);//判断出错
    curl_close($ch);
    if ($curl_errno > 0) {
        $arr = -1;
    }
    recordLog2('curl_errno:'.$curl_errno);
    return $arr;
}
