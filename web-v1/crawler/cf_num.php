<?php
include("header.php");
include("simple_html_dom.php");
?>
<center>
<?php
for ($i=1;$i<10;$i++) {
    $html=file_get_html("http://www.codeforces.com/problemset/page/$i");
    $table=$html->find("table.problems",0);
    $rows=$table->find("tr");
    for ($j=1;$j<sizeof($rows);$j++) {
        $row=$rows[$j];
        //echo htmlspecialchars($row);
        $pid=trim($row->find("td",0)->find("a",0)->innertext);
        $acnum=substr(trim($row->find("td",3)->find("a",0)->plaintext),7);
        $totnum=0;
        //$totnum=$row->find("td",2)->find("a",1)->innertext;
        //echo "$pid $acnum $totnum<br>";
        mysql_query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='CodeForces' and vid='$pid'");
    }
}
?>
</center>
<br>
<?php
include("footer.php");
?>

