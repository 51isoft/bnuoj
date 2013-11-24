<?php
include("header.php");
include("simple_html_dom.php");
?>
<center>
<?php
for ($i=1;$i<29;$i++) {
    $html=file_get_html("http://acm.zju.edu.cn/onlinejudge/showProblems.do?contestId=1&pageNumber=$i");
    $table=$html->find("table.list",0);
    $rows=$table->find("tr");
    for ($j=1;$j<sizeof($rows);$j++) {
        $row=$rows[$j];
        //echo htmlspecialchars($row);
        $pid=$row->find("td",0)->plaintext;
        $acnum=$row->find("td",2)->find("a",0)->innertext;
        $totnum=$row->find("td",2)->find("a",1)->innertext;
        //echo "$pid $acnum $totnum<br>";
        mysql_query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='ZJU' and vid='$pid'");
    }
}
?>
</center>
<br>
<?php
include("footer.php");
?>

