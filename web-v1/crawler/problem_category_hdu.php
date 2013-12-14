<?php
include("header.php");
include("problem_category_init.php");
foreach ($key as $name => $id) {
    if (mysql_num_rows(mysql_query("select id from category where name='".convert_str($name)."'"))==0) {
        mysql_query("insert into category set name='".convert_str($name)."', id='$id', parent='".$fa[$name]."'");
    }
}

include("simple_html_dom.php");

$html=file_get_html("http://acm.hdu.edu.cn/typeclass.php");
$ta=$html->find("#group a");
foreach ($ta as $a) {
    set_time_limit(20);
    if (strstr($a->href,"problemclass.php?id=")==null) continue;
    echo iconv("gbk","utf-8//IGNORE",trim($a->plaintext))."<br />";
    $cat=$key[$map[iconv("gbk","utf-8//IGNORE",trim($a->plaintext))]];
    $html=file_get_html("http://acm.hdu.edu.cn".$a->href."&page=3");
    $rows=$html->find("#problem table tr");
    for ($i=1;$i<sizeof($rows);$i++) {
        $id=trim($rows[$i]->find("td",0)->plaintext);
        list($pid)=mysql_fetch_array(mysql_query("select pid from problem where vname='HDU' and vid='$id'"));
        $g=$cat;
        while ($g!=-1) {
            if (mysql_num_rows(mysql_query("select pcid from problem_category where pid='$pid' and catid='$g'"))==0) {
               mysql_query("insert into problem_category set pid='$pid', catid='$g', weight='1000'");
            }
            $g=$fa[$g];
        }
    }
}



include("footer.php");
?>
