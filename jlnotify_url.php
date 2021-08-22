<?php
# 异步通知文件
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
	if ($trade_status == 1) {
		$srow = $DB->query("SELECT * FROM pay_order WHERE trade_no='{$out_trade_no}' limit 1")->fetch();
        $users=$DB->query("SELECT * FROM pay_user WHERE id='{$srow['pid']}' limit 1")->fetch();
        $srow=$DB->query("SELECT * FROM pay_order WHERE trade_no='{$out_trade_no}' limit 1 for update")->fetch();
		if($srow['status']==0){
			if($DB->exec("update `pay_order` set `status` ='1' where `trade_no`='$out_trade_no'")){
				$DB->exec("update `pay_order` set `endtime` ='$date' where `trade_no`='$out_trade_no'");
		    $url = creat_callback($srow);
            curl_get($url['notify']);
            proxy_get($url['notify']);
			processOrder($srow);
			}
		}
		 exit('success');
	} else {
		exit('fail !status');
	}
		# 这里可以执行你自己的业务
		# 执行业务之前建议先判断是否已经处理过了，难免服务器进行多次的通知！
	}else{
		echo "验证失败！";
	}