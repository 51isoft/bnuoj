<?php
include("header.php");
include("simple_html_dom.php");
?>
<center>
<?php
for ($i=1;$i<12;$i++) {
    $html=file_get_html("http://acm.fzu.edu.cn/list.php?vol=$i");
    $table=$html->find("table",0);
    $rows=$table->find("tr");
    for ($j=1;$j<sizeof($rows);$j++) {
        $row=$rows[$j];
        //echo htmlspecialchars($row);
        $pid=$row->find("td",1)->plaintext;
        $tstr=$row->find("td",3)->plaintext;
        $acnum=substr(strstr(strstr($tstr,'('),'/',true),1);
        $totnum=substr(strstr(strstr($tstr,'/'),')',true),1);
        //echo "$pid $acnum $totnum<br>";die();
        mysql_query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='FZU' and vid='$pid'");
    }
}
?>
</center>
<br>
<?php
include("footer.php");
?>

