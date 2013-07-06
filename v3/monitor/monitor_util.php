<?php
include_once(dirname(__FILE__)."/../functions/global.php");
include_once(dirname(__FILE__)."/../functions/simple_html_dom.php");
include_once(dirname(__FILE__)."/../functions/problems.php");
include_once(dirname(__FILE__)."/../functions/pcrawlers.php");

$maxwaittime=30;

function monitor_insert_url($oj,$id,$url) {
   global $config;
    $db->query("select * from vurl where voj='$oj' and vid='$id'");
    if ($db->num_rows) $db->query("update vurl set url='".$db->escape($url)."' where voj='$oj' and vid='$id'");
    else $db->query("insert into vurl set url='".$db->escape($url)."', voj='$oj', vid='$id'");
}

function monitor_uva() {
    global $maxwaittime;
    for ($i=1;$i<3;$i++) {
        $url="http://uva.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=8&category=$i";
        $html=file_get_html($url);
        $main_a=$html->find("#col3_content_wrapper table a");
        foreach($main_a as $lone_a) {
            set_time_limit($maxwaittime);
            $l2url=$lone_a->href;
            $l2url="http://uva.onlinejudge.org/".htmlspecialchars_decode($l2url);
            $html2=file_get_html($l2url);
            $rows=$html2->find("#col3_content_wrapper table",0)->find("tr");
            for ($i=1;$i<sizeof($rows);$i++) {
                $row=$rows[$i];
                $pid=html_entity_decode(trim($row->find("td",1)->plaintext));
                $pid=iconv("utf-8","utf-8//ignore",trim(strstr($pid,'-',true)));
                $url="http://uva.onlinejudge.org/".htmlspecialchars_decode($row->find("td",1)->find("a",0)->href);
                monitor_insert_url("UVA",$pid,$url);
                if (problem_get_id_from_virtual("UVA",$pid)) continue;
            }
        }
    }
}

function monitor_spoj() {
    $used=array();
    foreach ( array("tutorial","classical") as $typec ) {
        $i=0;$pd=true;
        while ($pd) {
            $html=file_get_html("http://www.spoj.pl/problems/$typec/sort=0,start=".($i*50));
            $table=$html->find("table.problems",0);
            $rows=$table->find("tr");
            for ($j=1;$j<sizeof($rows);$j++) {
                $row=$rows[$j];
                $pid=trim($row->find("td",2)->plaintext);
                if ($used[$pid]) {
                    $pd=false;
                    break;
                }
                $used[$pid]=true;
                if (problem_get_id_from_virtual("SPOJ",$pid)) continue;
            }
            $i++;
        }
    }
}

function monitor_hdu() {
    global $db;
    $i=1;
    while (true) {
        $html=file_get_html("http://acm.hdu.edu.cn/listproblem.php?vol=$i");
        $table=$html->find("table",4);
        $txt=explode(";",$table->find("script",0)->innertext);
        if (sizeof($txt)<2) break;
        foreach ($txt as $one) {
            $det=explode(",",$one);
            $pid=$det[1];
            if (problem_get_id_from_virtual("HDU",$pid)) continue;
            pcrawler_hdu($pid);
        }
        $i++;
    }
    pcrawler_hdu_num();
}

function monitor_ural() {
    $html=file_get_html("http://acm.timus.ru/problemset.aspx?space=1&page=all");
    $table=$html->find("table.problemset",0);
    $rows=$table->find("tr");
    for ($j=2;$j<sizeof($rows)-1;$j++) {
        $row=$rows[$j];
        $pid=trim($row->find("td",1)->plaintext);
        if (problem_get_id_from_virtual("Ural",$pid)) continue;
    }
    $i++;

}


?>
