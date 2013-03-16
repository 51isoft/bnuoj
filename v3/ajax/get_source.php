<?php

include_once(dirname(__FILE__)."/../functions/users.php");
include_once(dirname(__FILE__)."/../functions/contests.php");
include_once(dirname(__FILE__)."/../functions/runs.php");

$runid = convert_str($_GET['runid']);
$cid = run_get_val($runid,"contest_belong");
$uname = run_get_val($runid,"username");

$ret["code"]=1;
if (!$current_user->is_valid()) {
    $ret["msg"]="Permission denined. Please login.";
}
else if (!(($isshared==TRUE&&($cid=="0"||contest_passed($cid)))||$current_user->match($uname)||$current_user->is_codeviewer())) {
    $ret["msg"]="Permission denined.";
}
else {
    $query="select result,memory_used,time_used,username,source,language,pid,isshared,contest_belong from status where runid='$runid'";
    $ret=$db->get_row($query,ARRAY_A);

    $ret["source"]=htmlspecialchars($ret["source"]);
    $ret["language"]=match_lang($ret["language"]);
    if ($current_user->match($uname)||$current_user->is_root()) $ret["control"]=1;
    else $ret["control"]=0;
    if ($_GET["cid"]!="") $ret["pid"]=contest_get_label_from_pid($cid,$ret["pid"]);
    $ret["code"]=0;
}
echo json_encode($ret);

?>
