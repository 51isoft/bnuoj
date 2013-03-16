<?php
include_once(dirname(__FILE__)."/../functions/global.php");
include_once(dirname(__FILE__)."/../functions/discuss.php");
$proid = convert_str($_GET['pid']);
$page = convert_str($_GET['page']);
if($page == "") $page = 0;

$res=discuss_load_list($page,$proid);
//print_r($res);

echo json_encode($res);
?>

