<?php
# 同步跳转通知文件
    define('SYSTEM_ROOT_E', dirname(__FILE__) . '/');
    require './includes/common.php';
    $julingpay_config['cid'] = $conf['juling_api_cid'];
    $julingpay_config['cookie'] = $conf['juling_api_cookie'];
    $cookie = $julingpay_config['cookie']; // 用户COOKIE字符串
	$cid = $julingpay_config['cid']; // 用户账户QQ账户
	$sing = md5('cid='.$cid.'&money='.$_GET['money'].'&out_trade_no='.$_GET['out_trade_no'].'&order='.$_GET['order'].'&name='.$_GET['name'].'&type='.$_GET['type'].'&status=1&cookie='.$cookie);
	if ($_GET['sing'] == $sing) {
	$trade_no = $_GET['out_trade_no'];
	$out_trade_no = $_GET['out_trade_no'];
	$money = $_GET['money'];
	$trade_status = $_GET['status'];
	$srow = $DB->query("SELECT * FROM pay_order WHERE trade_no='{$out_trade_no}' limit 1")->fetch();
	if ($srow) {
		$url = creat_callback($srow);
		if ($srow['status'] == 0) {
			echo '<script>window.location.href="' . $url['return'] . '";</script>';
		} else {
			echo '<script>window.location.href="' . $url['return'] . '";</script>';
		}
	} else {
		echo "订单记录获取验证失败！";
	}
		# 这里可以执行你自己的业务
		# 执行业务之前建议先判断是否已经处理过了，难免服务器进行多次的通知！
	}else{
		echo "验证失败！";
	}