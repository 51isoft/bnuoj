<?php
require_once 'simple_html_dom.php';
include("header.php");
$url="http://livearchive.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=8";
$html=file_get_html($url);
$main_a=$html->find(".maincontent table a");
$fir=15;
$trans=array(" :: "=>", ");
foreach($main_a as $lone_a) {
    $l2url=$lone_a->href;
    if ($fir>0) {
        $fir--;
        continue;
    }
    $l2url="http://livearchive.onlinejudge.org/".htmlspecialchars_decode($l2url);
    $html2=file_get_html($l2url);
    $l2main_a=$html2->find(".maincontent table a");
    foreach($l2main_a as $ltow_a) {
        $l3url=$ltow_a->href;
        $l3url="http://livearchive.onlinejudge.org/".htmlspecialchars_decode($l3url);
        $html3=file_get_html($l3url);
        $source=$html3->find(".contentheading",0)->plaintext;
        $source=substr($source,8);
        $source=trim(strtr($source,$trans));
//        echo $source;
        $probs=$html3->find(".maincontent table a");
        foreach($probs as $prob) {
            //echo $prob->plaintext;die();
            $pid=substr($prob->plaintext,0,4);
            //echo $pid;die();
            $sql="update problem set source='$source' where vid='$pid' and vname='uvalive'";
//            echo $sql;die();
            mysql_query($sql);
        }
    }
}
?>
