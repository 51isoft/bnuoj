<?php
require_once 'simple_html_dom.php';
include("header.php");
$url="http://uva.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=8";
$html=file_get_html($url);
$main_a=$html->find("#col3_content_wrapper table a");
$fir=0;
$trans=array(" :: "=>", ");
foreach($main_a as $lone_a) {
    $l2url=$lone_a->href;
    set_time_limit(20);
    $fir++;
    if ($fir<4||$fit>6) continue;
    $l2url="http://uva.onlinejudge.org/".htmlspecialchars_decode($l2url);
    $html2=file_get_html($l2url);
    $l2main_a=$html2->find("#col3_content_wrapper table a");
    foreach($l2main_a as $ltow_a) {
        set_time_limit(20);
        $l3url=$ltow_a->href;
        $l3url="http://uva.onlinejudge.org/".htmlspecialchars_decode($l3url)."&limit=2000&limitstart=0";
        $html3=file_get_html($l3url);
        $source=$html3->find(".contentheading",0)->plaintext;
        $source=substr($source,8);
        $source=trim(strtr($source,$trans));
        //echo $source;
        $probs=$html3->find("#col3_content_wrapper table a");
        foreach($probs as $prob) {
            //echo $prob->plaintext;die();
            $pid=html_entity_decode(trim($prob->plaintext));
            $pid=iconv("utf-8","utf-8//ignore",trim(strstr($pid,'-',true)));
            //echo $pid;die();
            $sql="update problem set source='$source' where vid='$pid' and vname='UVA'";
            //echo $sql;die();
            mysql_query($sql);
        }
    }
}
?>
