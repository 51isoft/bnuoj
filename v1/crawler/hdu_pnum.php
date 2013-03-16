<?php
include("header.php");
include("simple_html_dom.php");
?>
<center>
<?php
for ($i=1000;$i<=4430;$i++) {
    set_time_limit(20);
    $pid=$i;
    $html=file_get_html("http://acm.hdu.edu.cn/statistic.php?pid=$i");
    $table=$html->find("table",4);
    if ($table==null) continue;
    $acnum=$table->find("td a",1)->plaintext;
    //echo "$pid $acnum<br>";
    mysql_query("update problem set vacpnum='$acnum' where vname='HDU' and vid='$pid'");
}
?>
</center>
<br>
<?php
include("footer.php");
?>

