<?php
include_once(dirname(__FILE__)."/../functions/users.php");
include_once(dirname(__FILE__)."/../functions/problems.php");
$uname=convert_str($_POST['user_id']);
$pid=convert_str($_POST['problem_id']);
$lang=convert_str($_POST['language']);
$src=convert_str($_POST['source']);
$isshare=convert_str($_POST['isshare']);

if ($isshare!="0") $isshare="1";
setcookie($config["cookie_prefix"]."defaultshare",$isshare,time()+7*24*60*60,$config["base_path"]);
$ip=get_ip();
$ret=array();
$ret["code"]=1;
if (strlen($src)>$config["limits"]["max_source_code_len"]) {
    $ret["msg"]="Source too long!";
    echo json_encode($ret);
    die();
}
if (strlen($src)==0) {
    $ret["msg"]="No source code!";
    echo json_encode($ret);
    die();
}
if (!$current_user->is_valid()||!$current_user->match($uname)) {
    clear_cookies();
    $ret["msg"]="Invalid User.";
    echo json_encode($ret);
    die();
}
if (!problem_exist($pid)) {
    $ret["msg"]="No Such Problem.";
    echo json_encode($ret);
    die();
}
if (problem_hidden($pid)&&!$current_user->is_root()) {
    $ret["msg"]="No Such Problem.";
    echo json_encode($ret);
    die();
}
if ($lang==0) {
    $ret["msg"]="Please Select Language.";
    echo json_encode($ret);
    die();
}

if (time()-strtotime($current_user->get_val("last_submit_time"))<5) {
    $ret["msg"]="Too Fast!";
    echo json_encode($ret);
    die();
}

if ($lang<4&&$lang>0) setcookie($config["cookie_prefix"]."lastlang",$lang,time()+60*60*24*30,$config["base_path"]);



$query="insert into status set pid='$pid' ,source='$src' ,contest_belong='0', result='Waiting', language='$lang', username='$uname', ipaddr='$ip', isshared='$isshare', time_submit='".date("Y-m-d G:i:s",time())."' ";
$result=$db->query($query);
$nowid=$db->insert_id;
$query="update problem set total_submit=total_submit+1 where pid='$pid' ";
$result=$db->query($query);
$query="update user set total_submit=total_submit+1 where username='$uname' ";
$result = $db->query($query);

$host=$config["contact"]["server"];
$port=$config["contact"]["port"];
$fp = @fsockopen($host,$port,$errno, $errstr);
if (!$fp) {
    $ret["msg"]="Submitted.";
    $ret["code"]=0;
    echo json_encode($ret);
    die();
}
else {
    $msg=$config["contact"]["submit"]."\n".$nowid;
    list($vname)=@$db->get_row("select vname from problem where pid='$pid'",ARRAY_N);
    $msg=$msg."\n".$vname;
    if (@fwrite($fp,$msg)===FALSE) {
        $ret["msg"]="Submitted.";
        $ret["code"]=0;
        echo json_encode($ret);
        die();
    }
    fclose($fp);
}
$ret["msg"]="Submitted.";
$ret["code"]=0;
echo json_encode($ret);

?>
