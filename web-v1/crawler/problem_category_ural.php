<?php
include("header.php");
include("problem_category_init.php");
foreach ($key as $name => $id) {
    if (mysql_num_rows(mysql_query("select id from category where name='".convert_str($name)."'"))==0) {
        mysql_query("insert into category set name='".convert_str($name)."', id='$id', parent='".$fa[$name]."'");
    }
}

include("simple_html_dom.php");

$html=file_get_html("http://acm.timus.ru/problemset.aspx");
$ta=$html->find("a");
foreach ($ta as $a) {
    set_time_limit(20);
    if (strstr($a->href,"problemset.aspx?space=1&amp;tag=")==null||strstr($a->plaintext,"Problems")==null) continue;
    echo $a->plaintext."<br />";
    if (strstr($a->plaintext,"for Beginners")) $cat=$key["Beginner"];
    else $cat=$key[$map[strstr($a->plaintext," Problems",true)]];
    $html=file_get_html("http://acm.timus.ru/".html_entity_decode($a->href));
    //echo "http://acm.timus.ru/".html_entity_decode($a->href);die();
    $rows=$html->find("table.problemset tr");
    for ($i=1;$i<sizeof($rows);$i++) {
        $id=$rows[$i]->find("td",1)->plaintext;
        list($pid)=mysql_fetch_array(mysql_query("select pid from problem where vname='URAL' and vid='$id'"));
        $g=$cat;
        while ($g!=-1) {
            if (mysql_num_rows(mysql_query("select pcid from problem_category where pid='$pid' and catid='$g'"))==0) {
               mysql_query("insert into problem_category set pid='$pid', catid='$g'");
            }
            $g=$fa[$g];
        }
    }
}



include("footer.php");
?>
