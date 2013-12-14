<?php
include("header.php");
include("simple_html_dom.php");
?>
<center>
<?php
for ($i=1;$i<16;$i++) {
    $html=file_get_html("http://acm.uestc.edu.cn/problems.php?vol=$i");
    $table=$html->find("div.list",0);
    $rows=$table->find("ul");
    for ($j=0;$j<sizeof($rows);$j++) {
        $row=$rows[$j];
        //echo htmlspecialchars($row);
        $pid=trim($row->find("li",1)->plaintext);
        $acnum=trim($row->find("li",3)->plaintext);
        $totnum=trim($row->find("li",4)->plaintext);
        //echo "$pid $acnum $totnum<br>";die();
        mysql_query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='UESTC' and vid='$pid'");
    }
}
?>
</center>
<br>
<?php
include("footer.php");
?>

