<?php
include("header.php");
include("problem_category_init.php");
foreach ($key as $name => $id) {
    if (mysql_num_rows(mysql_query("select id from category where name='".convert_str($name)."'"))==0) {
        mysql_query("insert into category set name='".convert_str($name)."', id='$id', parent='".$fa[$name]."'");
    }
}

include("simple_html_dom.php");

for ($i=1;$i<11;$i++) {
    set_time_limit(40);
    $html=file_get_html("http://codeforces.com/problemset/page/$i");
    $rows=$html->find("table.problems",0)->find("tr");
    //echo $rows[1];die();
    for ($i=1;$i<sizeof($rows);$i++) {
        $row=$rows[$i];
        //echo htmlspecialchars($row);
        $id=trim($row->find("td",0)->find("a",0)->innertext);
        list($pid)=mysql_fetch_array(mysql_query("select pid from problem where vname='CodeForces' and vid='$id'"));
        $cate=$row->find("td a.notice");
        foreach ($cate as $cat) {
            //echo $cat->plaintext;die();
            $catt=$key[$map[trim($cat->plaintext)]];
            if ($catt==0) {
                echo $cat;
                continue;
            }
            $g=$catt;
            $t=1000;
            while ($g!=-1) {
                if (mysql_num_rows(mysql_query("select pcid from problem_category where pid='$pid' and catid='$g'"))==0) {
                   mysql_query("insert into problem_category set pid='$pid', catid='$g', weight=$t");
                }
                $g=$fa[$g];
                $t=0;
            }
        }
    }
}


include("footer.php");
?>
