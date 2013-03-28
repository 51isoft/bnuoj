<?php
include_once(dirname(__FILE__)."/../functions/problems.php");
include_once(dirname(__FILE__)."/../functions/users.php");
$vname=convert_str($_GET['vname']);
$vid=convert_str($_GET['vid']);
$ret["code"]=1;
if ($current_user->is_root()) {
    if ($vname=="BNU") {
        $ret["pid"]=$vid;
        $ret["title"]=problem_get_title($ret["pid"]);
        if ($ret["title"]) $ret["code"]=0;
    }
    else {
        $ret["pid"]=problem_get_id_from_virtual($vname,$vid);
        $ret["title"]=problem_get_title($ret["pid"]);
        if ($ret["pid"]&&$ret["title"]) $ret["code"]=0;
    }
    if ($ret["code"]==0&&problem_hidden($ret["pid"])) {
        unset($ret);
        $ret["code"]=1;
    }
}
else $ret["msg"]="Please login as root!";
echo json_encode($ret);
?>
