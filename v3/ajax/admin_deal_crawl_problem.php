<?php
include_once(dirname(__FILE__)."/../functions/pcrawlers.php");
include_once(dirname(__FILE__)."/../functions/users.php");
$ret=array();
$ret["code"]=1;
if (!$current_user->is_root()) {
    $ret["msg"]="Please login as root!";
    die(json_encode($ret));
}

if ($_GET["pcoj"]=="CodeForces") $func="pcrawler_cf";
else if ($_GET["pcoj"]=="FZU") $func="pcrawler_fzu";
else if ($_GET["pcoj"]=="HDU") $func="pcrawler_hdu";
else if ($_GET["pcoj"]=="OpenJudge") $func="pcrawler_openjudge";
else if ($_GET["pcoj"]=="SYSU") $func="pcrawler_sysu";
else if ($_GET["pcoj"]=="SCU") $func="pcrawler_scu";
else if ($_GET["pcoj"]=="HUST") $func="pcrawler_hust";
else {
    $ret["msg"]="Invalid OJ!";
    die(json_encode($ret));
}


if ($_GET["type"]==0) {//single
    $ret["msg"]=$func($_GET["pcid"]);
    $ret["code"]=0;
    echo json_encode($ret);
} else if ($_GET["type"]==1) {//range
    for ($i=intval($_GET["pcidfrom"]);$i<=intval($_GET["pcidto"]);$i++) $ret["msg"].=$func($i);
    $ret["code"]=0;
    echo json_encode($ret);

} else if ($_GET["type"]==2) {//num
    $func.="_num";
    $ret["msg"]=$func();
    $ret["code"]=0;
    echo json_encode($ret);
} else {
    $ret["msg"]="Invalid request!";
    die(json_encode($ret));
}


?>
