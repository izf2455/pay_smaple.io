<?php
# 聚灵支付接口文档
define('SYSTEM_ROOT_E', dirname(__FILE__) . '/');
require './includes/common.php';
@header('Content-Type: text/html; charset=UTF-8');
$type = isset($_GET['type']) ? $_GET['type'] : exit('No type!');
$trade_no = daddslashes($_GET['trade_no']);
$row = $DB->query("SELECT * FROM pay_order WHERE trade_no='{$trade_no}' limit 1")->fetch();
if (!$row) {
	exit('该订单号不存在，请返回来源地重新发起请求！');
}
date_default_timezone_set('PRC');
$julingpay_config['cid'] = $conf['juling_api_cid'];
$julingpay_config['cookie'] = $conf['juling_api_cookie'];
function isHTTPS()
{
	if (defined('HTTPS') && HTTPS) {
		return true;
	}
	if (!isset($_SERVER)) {
		return FALSE;
	}
	if (!isset($_SERVER['HTTPS'])) {
		return FALSE;
	}
	if ($_SERVER['HTTPS'] === 1) {
		return TRUE;
	} elseif ($_SERVER['HTTPS'] === 'on') {
		return TRUE;
	} elseif ($_SERVER['SERVER_PORT'] == 443) {
		return TRUE;
	}
	return FALSE;
}
switch ((int) $type) {
	case 'alipay':
		$typeName = '支付宝';
		break;
	default:
		$typeName = '微信';
}

$julingpay_config['domain'] = (isHTTPS() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
$julingpay_config['path'] = $codepay_config['domain'] . dirname($_SERVER['REQUEST_URI']);
$julingpay_config['url'] = $julingpay_config['domain'] . $julingpay_config['path']; // 读取您最终的浏览地址！

$payurl = 'https://www.julingpay.com/gateway'; // 最新网关地址
$notify_url = $julingpay_config['url'] . 'jlnotify_url.php'; // 异步通知地址
$return_url = $julingpay_config['url'] . 'jlreturn_url.php'; // 同步跳转通知地址
$cookie = $julingpay_config['cookie']; // 用户COOKIE字符串
$cid = $julingpay_config['cid']; // 用户账户QQ账户
$order = $_GET['trade_no'];
$money = number_format($row['money'], 2, '.', '');
// 对用户资料进行签名SING值
$sing = md5('cid='.$cid.'&money='.$money.'&out_trade_no='.$order.'&notify_url='.$notify_url.'&return_url='.$return_url.'&name='.$_GET['name'].'&type='.$_GET['type'].'&cookie='.$cookie);
$url = $payurl.'?'.'cid='.$cid.'&money='.$money.'&out_trade_no='.$order.'&notify_url='.$notify_url.'&return_url='.$return_url.'&name='.$_GET['name'].'&type='.$_GET['type'].'&sing='.$sing;
session_start();
$_SESSION['order'] = $order;
$urlcode = file_get_contents($url);
if (!$urlcode) {
	exit('支付接口端返回异常');
}
$stat = json_decode($urlcode,true);
if ($stat['code'] != 1) {
	exit($stat['msg']);
}else{
	$qrcode = $stat['qrcode'];
}
?>







<!doctype html>
<html class="no-js">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<meta name="format-detection" content="email=no" />
<!-- 启用360浏览器的极速模式(webkit) -->
<meta name="renderer" content="webkit">
<!-- 避免IE使用兼容模式 -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- 针对手持设备优化，主要是针对一些老的不识别viewport的浏览器，比如黑莓 -->
<meta name="HandheldFriendly" content="true">
<!-- 微软的老式浏览器 -->
<meta name="MobileOptimized" content="320">
<!-- uc强制竖屏 -->
<meta name="screen-orientation" content="portrait">
<!-- QQ强制竖屏 -->
<meta name="x5-orientation" content="portrait">
<!-- UC强制全屏 -->
<meta name="full-screen" content="yes">
<!-- QQ强制全屏 -->
<meta name="x5-fullscreen" content="true">
<!-- UC应用模式 -->
<meta name="browsermode" content="application">
<!-- QQ应用模式 -->
<meta name="x5-page-mode" content="app">
<!--这meta的作用就是删除默认的苹果工具栏和菜单栏-->
<meta name="apple-mobile-web-app-capable" content="yes">
<!--网站开启对web app程序的支持-->
<meta name="apple-touch-fullscreen" content="yes">
<!--在web app应用下状态条（屏幕顶部条）的颜色-->
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<!-- windows phone 点击无高光 -->
<meta name="msapplication-tap-highlight" content="no">
<!--移动web页面是否自动探测电话号码-->
<meta http-equiv="x-rim-auto-match" content="none">
<!--移动端版本兼容 start -->
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" name="viewport" />
<!--移动端版本兼容 end -->
    <title><?php 
echo $row['name'];
?> - 支付宝扫码支付 | i优易支付</title>

    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="icon" type="image/png" href="/favicon.ico">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="/Public/zfb/js/assets/i/app-icon72x72@2x.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Amaze UI" />
    <link rel="apple-touch-icon-precomposed" href="/Public/zfb/js/assets/i/app-icon72x72@2x.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="/Public/zfb/js/assets/i/app-icon72x72@2x.png">
    <meta name="msapplication-TileColor" content="#0e90d2">

    <link rel="stylesheet" href="/includes/pay/amazeui.min.css">
    <link rel="stylesheet" href="/includes/pay/ali.css">
<meta name="__hash__" content="a7f55f8c2998c0e892ee07994c6c48a6_8fc67496a924f6a779d9f48faca948ef" /></head>
<body>
    <header class="wepay">
        <h1><a href="#" class="am-text-ir am-center">i优易支付</a></h1>
    </header>
    <div class="am-g am-g-fixed am-margin-vertical wx-box">
        <div class="am-u-md-8 am-u-sm-12 am-u-sm-centered am-margin-vertical am-text-center">
            </div>

            <div class="am-u-md-8 am-u-sm-12 am-u-sm-centered am-margin-vertical am-text-center"><p class="am-text-xxxl">￥<?php 
echo $row['money'];
?></p></div>
      
              
            <div class="am-u-md-8 am-u-sm-12 am-u-sm-centered am-margin-vertical am-text-center wx-code" id="qrcode">
			<img onclick="$('#use').hide()" id="use" src="/favicon.ico"
                         style="position: absolute;top: 50%;left: 50%;width:32px;height:32px;margin-left: -16px;margin-top: -16px">
			<img width="210" height="210" src="https://i.17fk.cn/index.php?h=10&m=2&e=Q&p=5.2&&l=1&url=<?php 
echo $qrcode;
?>">
			</div>

            <div class="am-u-md-8 am-u-sm-12 am-u-sm-centered am-margin-vertical am-text-center">
                <img src="/includes/pay/zfb.png" alt="请使用支付宝扫描
二维码完成支付"><br>

<div class="foot"> <p></p>
            
</div>
            </div>
            <div class="am-u-md-8 am-u-sm-12 am-u-sm-centered am-margin-vertical am-text-center">
              
				<div class="foot">
                   <div class="am-text-center">支付宝H5支付：<a href="<?php 
echo $qrcode;
?>">手机用户点我跳转支付！</a></div>

              
                <hr>
				<div class="foot">
                   <div class="am-text-center">商品名称：<?php 
echo $row['name'];
?></div>
                <hr>
				<div class="foot">
                   <div class="am-text-center">订单号：<?php 
echo $trade_no;
?></div>
                </div>

                <hr>             
                <div class="foot">
                    <div class="am-text-center">请在订单提交后5分钟内完成支付！</div> 
                </div>
                <hr>
            </div>
        </div>

  
<!--注意下面加载顺序 顺序错乱会影响业务-->
<script src="./includes/codepay/js/jquery-1.10.2.min.js"></script>
<!--[if lt IE 8]>
<script src="/js/json3.min.js"></script><![endif]-->
 <script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript">
//循环执行，每隔3秒钟执行一次showalert（） 
window.setInterval(showalert, 3000); 
function showalert() 
{ 
	$.ajax({
        type: "POST",
        url: "chaorder.php",
        data: {order: <?php echo $_SESSION['order']; ?>}
    }).done(function(re) {   
        if (re == 1) {
          <?php
          $srow = $DB->query("SELECT * FROM pay_order WHERE trade_no='{$_GET['trade_no']}' limit 1")->fetch();
          $url = creat_callback($srow);
          ?>
         window.location.href="<?php echo $url['return']; ?>";
        }
    })
} 
</script>
  
</body>
</html>