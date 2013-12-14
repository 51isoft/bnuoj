<?php
require_once 'simple_html_dom.php';
include("header.php");
$url="http://uva.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=8&category=1";
$html=file_get_html($url);
$main_a=$html->find("#col3_content_wrapper table a");
foreach($main_a as $lone_a) {
    set_time_limit(20);
    $l2url=$lone_a->href;
    $l2url="http://uva.onlinejudge.org/".htmlspecialchars_decode($l2url);
    $html2=file_get_html($l2url);
    $rows=$html2->find("#col3_content_wrapper table",0)->find("tr");
    for ($i=1;$i<sizeof($rows);$i++) {
        $row=$rows[$i];
        $pid=html_entity_decode(trim($row->find("td",1)->plaintext));
        $pid=iconv("utf-8","utf-8//ignore",trim(strstr($pid,'-',true)));
        $totnum=$row->find("td",4)->innertext;
        $acnum=$row->find("td",5)->find("div",0)->find("div",1)->innertext;
        $acnum=substr($acnum,0,-1);
        //echo $acnum;
        if ($acnum[0]=='N') $acnum=0;
        else {
            $acnum=intval(doubleval($acnum)/100*intval($totnum)+0.1);
        }
        echo "$pid $acnum $totnum<br>";
        mysql_query("update problem set vacpnum='$acnum', vtotalpnum='$totnum' where vname='UVA' and vid='$pid'");
    }
    //die();
}

$url="http://uva.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=8&category=2";
$html=file_get_html($url);
$main_a=$html->find("#col3_content_wrapper table a");
foreach($main_a as $lone_a) {
    set_time_limit(20);
    $l2url=$lone_a->href;
    $l2url="http://uva.onlinejudge.org/".htmlspecialchars_decode($l2url);
    $html2=file_get_html($l2url);
    $rows=$html2->find("#col3_content_wrapper table",0)->find("tr");
    for ($i=1;$i<sizeof($rows);$i++) {
        $row=$rows[$i];
        $pid=html_entity_decode(trim($row->find("td",1)->plaintext));
        $pid=iconv("utf-8","utf-8//ignore",trim(strstr($pid,'-',true)));
        $totnum=$row->find("td",4)->innertext;
        $acnum=$row->find("td",5)->find("div",0)->find("div",1)->innertext;
        $acnum=substr($acnum,0,-1);
        //echo $acnum;
        if ($acnum[0]=='N') $acnum=0;
        else {
            $acnum=intval(doubleval($acnum)/100*intval($totnum)+0.1);
        }
        echo "$pid $acnum $totnum<br>";
        mysql_query("update problem set vacpnum='$acnum', vtotalpnum='$totnum' where vname='UVA' and vid='$pid'");
    }
    //die();
}
?>

