<?php
include("header.php");
include("simple_html_dom.php");
?>
<center>
<?php
for ($i=1000;$i<1896;$i++) {
    $html=file_get_html("http://acm.timus.ru/detail.aspx?num=$i");
    $table=$html->find("table",4);
    if ($table==null) continue;
    $rows=$table->find("tr");
    $pid=$i;
    $acnum=$rows[3]->find("td",1)->innertext;
    $totnum=$rows[2]->find("td",1)->innertext;
    //echo "$pid $acnum $totnum<br>";
    mysql_query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='Ural' and vid='$pid'");
}
?>
</center>
<br>
<?php
include("footer.php");
?>

