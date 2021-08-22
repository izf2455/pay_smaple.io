<?php
define('SYSTEM_ROOT_E', dirname(__FILE__) . '/');
require './includes/common.php';
session_start();
$order = $_SESSION['order'];
	$rows = $DB->query("SELECT * FROM pay_order WHERE trade_no='{$order}' limit 1")->fetch();
	echo $rows['status'];
	?>