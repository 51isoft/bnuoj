<?php
include("header.php");
include("simple_html_dom.php");
?>
<center>
<?php
for ($i=1;$i<32;$i++) {
    $html=file_get_html("http://poj.org/problemlist?volume=$i");
    $table=$html->find("table",4);
    $rows=$table->find("tr");
    for ($j=1;$j<sizeof($rows);$j++) {
        $row=$rows[$j];
        //echo htmlspecialchars($row);
        $pid=$row->find("td",0)->innertext;
        $acnum=$row->find("td",2)->find("a",0)->innertext;
        $totnum=$row->find("td",2)->find("a",1)->innertext;
        //echo "$pid $acnum $totnum<br>";
        mysql_query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='PKU' and vid='$pid'");
    }
}
?>
</center>
<br>
<?php
include("footer.php");
?>

