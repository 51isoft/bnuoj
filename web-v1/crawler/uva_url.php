<?php
require_once 'simple_html_dom.php';
include("header.php");
/*$url="http://uva.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=8&category=1";
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
        $url="http://uva.onlinejudge.org/".htmlspecialchars_decode($row->find("td",1)->find("a",0)->href);
        //echo $pid." ".$url."<br>";
        //echo "select * from vurl where voj='UVA' and vid='$pid'";die();
        if (mysql_num_rows(mysql_query("select * from vurl where voj='UVA' and vid='$pid'"))>0) mysql_query("update vurl set url='$url' where voj='UVA' and vid='$pid'");
        else mysql_query("insert into vurl set voj='UVA', vid='$pid', url='$url'");
    }
    //die();
}
*/
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
        $url="http://uva.onlinejudge.org/".htmlspecialchars_decode($row->find("td",1)->find("a",0)->href);
        //echo $pid." ".$url."<br>";
        if (mysql_num_rows(mysql_query("select * from vurl where voj='UVA' and vid='$pid'"))>0) mysql_query("update vurl set url='$url' where voj='UVA' and vid='$pid'");
        else mysql_query("insert into vurl set voj='UVA', vid='$pid', url='$url'");
    }
    //die();
}
?>
