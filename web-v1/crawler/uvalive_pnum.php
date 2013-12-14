<?php
require_once 'simple_html_dom.php';
include("header.php");
$url="http://livearchive.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=8&category=1";
$html=file_get_html($url);
$main_a=$html->find(".maincontent table a");
foreach($main_a as $lone_a) {
    $l2url=$lone_a->href;
    $l2url="http://livearchive.onlinejudge.org/".htmlspecialchars_decode($l2url);
    $html2=file_get_html($l2url);
    $rows=$html2->find(".maincontent table",0)->find("tr");
    for ($i=1;$i<sizeof($rows);$i++) {
        $row=$rows[$i];
        $pid=substr(trim($row->find("td",1)->plaintext),0,4);
        $totnum=$row->find("td",4)->innertext;
        $acnum=$row->find("td",5)->find("div",0)->find("div",1)->innertext;
        $acnum=substr($acnum,0,-1);
        //echo $acnum;
        if ($acnum[0]=='N') $acnum=0;
        else {
            $acnum=intval(doubleval($acnum)/100*intval($totnum)+0.1);
        }
        //echo "$pid $acnum $totnum<br>";
        mysql_query("update problem set vacpnum='$acnum', vtotalpnum='$totnum' where vname='UVALive' and vid='$pid'");
    }
    //die();
}
?>
