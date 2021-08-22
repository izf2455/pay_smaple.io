<?php
/*
莫宇云支付系统：www.pxula.cn
*/
include("./includes/common.php");
if($conf['web_is']==1)sysmsg($conf['web_offtext']);
if($conf['web_is']==2)sysmsg($conf['web_offtext']);
$template = $conf['template'];
include("./includes/template/{$template}/index.html");
?>