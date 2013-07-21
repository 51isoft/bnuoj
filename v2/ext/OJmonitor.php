<?php

include("../config.php");
include("../conn.php");
include("simple_html_dom.php");

$maxwaitnum=8;
set_time_limit(120);

function check_pku() {
    global $maxwaitnum;
    $html=file_get_html("http://poj.org/status");
    if ($html==null||$html->find("table",4)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("table",4)->find("tr");
        foreach ($res as $row) {
            $result=$row->find("td",3)->plaintext;
            // echo $result;
            if ($result=="Waiting") $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum waitings.";
        return "Normal";
    }
}

function check_hdu() {
    global $maxwaitnum;
    $html=file_get_html("http://acm.hdu.edu.cn/status.php");
    if ($html==null||$html->find("table.table_text",0)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("table.table_text tr");
        foreach ($res as $row) {
            $result=$row->find("td",2)->plaintext;
            // echo $result;
            if ($result=="Queuing") $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}

function check_uvalive() {
    global $maxwaitnum;
    $html=file_get_html("http://livearchive.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=19");
    if ($html==null||$html->find("td.maincontent table",0)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("td.maincontent table tr");
        foreach ($res as $row) {
            $result=$row->find("td",4)->plaintext;
            // echo $result;
            if ($result=="In judge queue") $num++;
            if ($result=="Submission error") $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}

function check_cf() {
    global $maxwaitnum;
    $html=file_get_html("http://www.codeforces.com/problemset/status");
    if ($html==null||$html->find("table",0)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("table tr");
        foreach ($res as $row) {
            $result=$row->find("td",5)->plaintext;
            // echo $result;
            if (stristr($result,"queu")||stristr($result,"waiting")) $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}

function check_sgu() {
    global $maxwaitnum;
    $html=file_get_html("http://acm.sgu.ru/status.php");
    if ($html==null||$html->find("table",12)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("table",12)->find("tr");
        foreach ($res as $row) {
            $result=$row->find("td",5)->plaintext;
            // echo $result;
            if (stristr($result,"queu")||stristr($result,"waiting")) $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}

function check_lightoj() {
    global $maxwaitnum;
    $ojuser="lightojuser";
    $ojpass="lightojpass";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://www.lightoj.com/login_check.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "lightoj.cookie");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "myuserid=".urlencode($ojuser)."&mypassword=".urlencode($ojpass)."&Submit=Login");
    $content = curl_exec($ch);
    curl_close($ch);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://www.lightoj.com/volume_submissions.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_COOKIEFILE, "lightoj.cookie");
    $content = curl_exec($ch);
    curl_close($ch); 

    $html=str_get_html($content);
    if ($html==null||$html->find("table",2)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("table",2)->find("tr");
        foreach ($res as $row) {
            $result=$row->find("td",6)->plaintext;
            // echo $result;
            if (stristr($result,"not judged yet")||stristr($result,"waiting")) $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}

function check_ural() {
    global $maxwaitnum;
    $html=file_get_html("http://acm.timus.ru/status.aspx");
    if ($html==null||$html->find("table.status",0)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("table.status",0)->find("tr");
        foreach ($res as $row) {
            $result=$row->find("td",5)->plaintext;
            // echo $result;
            if (stristr($result,"queu")||stristr($result,"waiting")) $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}

function check_zju() {
    global $maxwaitnum;
    $html=file_get_html("http://acm.zju.edu.cn/onlinejudge/showRuns.do?contestId=1");
    if ($html==null||$html->find("table.list",0)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("table.list tr");
        foreach ($res as $row) {
            $result=$row->find("td",2)->plaintext;
            // echo $result;
            if (stristr($result,"queu")||stristr($result,"waiting")) $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}

function check_uva() {
    global $maxwaitnum;
    $html=file_get_html("http://uva.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=19");
    if ($html==null||$html->find("div#col3_content_wrapper table",0)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("div#col3_content_wrapper table tr");
        foreach ($res as $row) {
            $result=$row->find("td",4)->plaintext;
            // echo $result;
            if ($result=="In judge queue") $num++;
            if ($result=="Submission error") $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}

function check_spoj() {
    global $maxwaitnum;
    $html=file_get_html("http://www.spoj.pl/status/");
    if ($html==null||$html->find("table.problems",0)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("table.problems tr");
        foreach ($res as $row) {
            $result=$row->find("td",4)->plaintext;
            // echo $result;
            if (stristr($result,"queu")||stristr($result,"waiting")) $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}

function check_uestc() {
    global $maxwaitnum;
    $html=file_get_html("http://acm.uestc.edu.cn/status.php");
    if ($html==null||$html->find("div.list",0)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("div.list ul");
        foreach ($res as $row) {
            $result=$row->find("li",3)->plaintext;
            // echo $result;
            if (stristr($result,"queu")||stristr($result,"waiting")) $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}

function check_fzu() {
    global $maxwaitnum;
    $html=file_get_html("http://acm.fzu.edu.cn/log.php");
    if ($html==null||$html->find("table",0)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("table tr");
        foreach ($res as $row) {
            $result=$row->find("td",2)->plaintext;
            // echo $result;
            if (stristr($result,"queu")||stristr($result,"waiting")) $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}

function check_nbut() {
    global $maxwaitnum;
    $html=file_get_html("http://ac.nbutoj.com/Problem/status.xhtml");
    if ($html==null||$html->find("table",0)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("table tr");
        foreach ($res as $row) {
            $result=$row->find("td",3)->plaintext;
            // echo $result;
            if (stristr($result,"queu")||stristr($result,"waiting")) $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}

function check_whu() {
    global $maxwaitnum;
    $html=file_get_html("http://acm.whu.edu.cn/land/status");
    if ($html==null||$html->find("table",0)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("table tr");
        foreach ($res as $row) {
            $result=$row->find("td",3)->plaintext;
            // echo $result;
            if (stristr($result,"queu")||stristr($result,"waiting")) $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}

function check_sysu() {
    global $maxwaitnum;
    $html=file_get_html("http://soj.me/status.php");
    if ($html==null||$html->find("table",0)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("table tr");
        foreach ($res as $row) {
            $result=$row->find("td",5)->plaintext;
            // echo $result;
            if (stristr($result,"queu")||stristr($result,"waiting")) $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}

function check_openjudge() {
    global $maxwaitnum;
    $html=file_get_html("http://poj.openjudge.cn/practice/status");
    if ($html==null||$html->find("table",0)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("table",0)->find("tr");
        foreach ($res as $row) {
            $result=$row->find("td",2)->plaintext;
            // echo $result;
            if ($result=="Waiting") $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum waitings.";
        return "Normal";
    }
}

function check_scu() {
    global $maxwaitnum;
    $html=file_get_html("http://cstest.scu.edu.cn/soj/solutions.action");
    if ($html==null||$html->find("table",1)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("table",1)->find("tr");
        foreach ($res as $row) {
            $result=$row->find("td",5)->plaintext;
            // echo $result;
            if (stristr($result,"queu")||stristr($result,"waiting")||stristr($result,"being")) $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}

function check_hust() {
    global $maxwaitnum;
    $html=file_get_html("http://acm.hust.edu.cn/status.php");
    if ($html==null||$html->find("table",1)==null) return "Down: cannot connect.";
    else {
        $num=0;
        $res=$html->find("table",1)->find("tr");
        foreach ($res as $row) {
            $result=$row->find("td font",0)->plaintext;
            // echo $result;
            if (stristr($result,"pending")||stristr($result,"waiting")) $num++;
        }
        if ($num>$maxwaitnum) return "Possibly down: more than $maxwaitnum queuings.";
        return "Normal";
    }
}


$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_pku())."' where name='PKU'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_hdu())."' where name='HDU'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_uvalive())."' where name='UVALive'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_cf())."' where name='CodeForces'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_sgu())."' where name='SGU'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_lightoj())."' where name='LightOJ'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_ural())."' where name='Ural'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_zju())."' where name='ZJU'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_uva())."' where name='UVA'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_spoj())."' where name='SPOJ'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_uestc())."' where name='UESTC'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_fzu())."' where name='FZU'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_nbut())."' where name='NBUT'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_whu())."' where name='WHU'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_sysu())."' where name='SYSU'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_openjudge())."' where name='OpenJudge'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_scu())."' where name='SCU'";
mysql_query($sql);
$sql="update ojinfo set lastcheck=now(), status='".convert_str(check_hust())."' where name='HUST'";
mysql_query($sql);

// echo check_pku();
// echo check_hdu();
// echo check_uvalive();
// echo check_cf();
// echo check_sgu();
// echo check_lightoj();
// echo check_ural();
// echo check_zju();
// echo check_uva();
// echo check_spoj();
// echo check_uestc();
// echo check_fzu();
// echo check_nbut();
// echo check_whu();
// echo check_sysu();
// echo check_openjudge();
// echo check_scu();
// echo check_hust();
?>
