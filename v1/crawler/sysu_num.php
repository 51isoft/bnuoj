<?php
include("header.php");
include("simple_html_dom.php");
?>
<center>
<?php
//for ($i=1;$i<12;$i++) {
    $html=file_get_html("http://soj.me/problem_tab.php?start=1000&end=99999");
    $table=$html->find("table",0);
    $rows=$table->find("tr");
    for ($j=1;$j<sizeof($rows);$j++) {
        $row=$rows[$j];
        //echo htmlspecialchars($row);
        $pid=$row->find("td",1)->plaintext;
        $acnum=$row->find("td",3)->plaintext;
        $totnum=$row->find("td",4)->plaintext;
        //echo "$pid $acnum $totnum<br>";die();
        mysql_query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='SYSU' and vid='$pid'");
    }
//}
?>
</center>
<br>
<?php
include("footer.php");
?>


