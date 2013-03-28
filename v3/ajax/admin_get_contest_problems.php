<?php
include_once(dirname(__FILE__)."/../functions/users.php");
include_once(dirname(__FILE__)."/../functions/contests.php");
$i=0;
$ret=array();
$ret['code']=1;
if (!$current_user->is_root()) {
    die(json_encode($ret));
}
$type=convert_str($_GET['type']);
$value=convert_str($_GET['value']);
$out=convert_str($_GET['out']);

if ($type=="cid") {
    $res=contest_get_problem_basic($value);
    foreach((array) $res as $prob) {
        $ret[$out."pid".$i]=$prob["pid"];
        $i++;
    }
    $ret['result']=0;
}
else {
    $res=$db->get_results("select pid from problem where source='$value' order by pid",ARRAY_A);
    foreach((array) $res as $prob) {
        $ret[$out."pid".$i]=$prob["pid"];
        $i++;
    }
    $ret['result']=0;
}

echo json_encode($ret);
?>
