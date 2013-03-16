<?php
//put some global functions here
include_once(dirname(__FILE__)."/db_basic.php");
include_once(dirname(__FILE__)."/cookie.php");

//include_once(dirname(__FILE__).'/../latexrender/latex.php');
function latex_content($a) { return $a; }

function pwd($a) {
    return sha1("fdsoijfdows".md5($a."8943udo1=_*()3e2"));
}
function match_shjs($lang) {
    switch ($lang) {
        case "1":
            $lang="cpp";
            break;
        case "2":
            $lang="c";
            break;
        case "3":
            $lang="java";
            break;
        case "4":    
            $lang="pascal";
            break;
        case "5":
            $lang="python";
            break;
        case "6":
            $lang="csharp";
            break;
        case "7":
            $lang="cpp";
            break;
        case "8":
            $lang="perl";
            break;
        case "9":
            $lang="ruby";
            break;
        case "10":
            $lang="cpp";
            break;
        case "11":
            $lang="sml";
            break;
        case "12":
            $lang="cpp";
            break;
        case "13":
            $lang="c";
            break;
        case "14":
            $lang="c";
            break;
        case "15":
            $lang="cpp";
            break;
    }
    return $lang;
}
function match_lang($lang) {
    switch ($lang) {
        case "1":
            $lang="GNU C++";
            break;
        case "2":
            $lang="GNU C";
            break;
        case "3":
            $lang="Oracle Java";
            break;
        case "4":   
            $lang="Free Pascal";
            break;
        case "5":
            $lang="Python";
            break;
        case "6":
            $lang="C# (Mono)";
            break;
        case "7":
            $lang="Fortran";
            break;
        case "8":
            $lang="Perl";
            break;
        case "9":
            $lang="Ruby";
            break;
        case "10":
            $lang="Ada";
            break;
        case "11":
            $lang="Standard ML";
            break;
        case "12":
            $lang="Visual C++";
            break;
        case "13":
            $lang="Visual C";
            break;
        case "14":
            $lang="CLang";
            break;
        case "15":
            $lang="CLang++";
            break;
    }
    return $lang;
}

function get_ip() {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    if($ip=="") $ip = $_SERVER['REMOTE_ADDR'];
    return $ip;
}

function get_substitle() {
    global $db;
    $substitle=$db->get_row("select substitle from config",ARRAY_N);
    return $substitle[0];
}

function convert_str($str) {
    global $db;
    if ($str===null) return "";
    if (get_magic_quotes_gpc()) { 
        return $str; 
    }
    return $db->escape($str); 
}
function hash_password($pwd) {
    return sha1(md5($pwd));
}

function change_out_nick($str){
    $change = array(
        '&lt;'=>'<',
    );
    $s=strtr($str,$change);
    $s=strip_tags(nl2br($s));
    return htmlspecialchars($s);
}

function clear_cookies() {
    global $config;
    setcookie($config["cookie_prefix"]."username","",0,$config["base_path"]);
    setcookie($config["cookie_prefix"]."password","",0,$config["base_path"]);
}

function set_cookies($username,$password,$time=0) {
    global $config;
    setcookie($config["cookie_prefix"]."username",$username,$time,$config["base_path"]);
    setcookie($config["cookie_prefix"]."password",$password,$time,$config["base_path"]);
}

?>