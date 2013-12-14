<?php
include("header.php");
include("simple_html_dom.php");
?>
<center>
<?php
for ($i=1;$i<6;$i++) {
    $html=file_get_html("http://acm.sgu.ru/problemset.php?contest=0&volume=$i");
    $table=$html->find("table",11);
    $rows=$table->find("tr");
    for ($j=1;$j<sizeof($rows)-1;$j++) {
        $row=$rows[$j];
        //echo htmlspecialchars($row);
        $pid=$row->find("td",0)->plaintext;
        $acnum=$row->find("td",2)->find("a",0)->innertext;
        $totnum=0;
        //echo "$pid $acnum $totnum<br>";
        mysql_query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='SGU' and vid='$pid'");
    }
}
?>
</center>
<br>
<?php
include("footer.php");
?>

