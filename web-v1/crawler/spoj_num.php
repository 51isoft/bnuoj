<?php
include("header.php");
include("simple_html_dom.php");
?>
<center>
<?php
for ($i=0;$i<44;$i++) {
    $html=file_get_html("http://www.spoj.pl/problems/classical/sort=0,start=".($i*50));
    $table=$html->find("table.problems",0);
    $rows=$table->find("tr");
    for ($j=1;$j<sizeof($rows);$j++) {
        set_time_limit(60);
        $row=$rows[$j];
        //echo htmlspecialchars($row);
        $pid=trim($row->find("td",2)->plaintext);
        $phtml=file_get_html("http://www.spoj.pl/ranks/$pid/");
        $ptable=$phtml->find("table.problems",0);
        $acnum=$ptable->find("tr.lightrow td",2)->plaintext;
        $totnum=$ptable->find("tr.lightrow td",1)->plaintext;
        mysql_query("update problem set vacnum='$acnum',vtotalnum='$totnum' where vname='SPOJ' and vid='$pid'");
    }
    //die();
}

for ($i=0;$i<10;$i++) {
    $html=file_get_html("http://www.spoj.pl/problems/tutorial/sort=0,start=".($i*50));
    $table=$html->find("table.problems",0);
    $rows=$table->find("tr");
    for ($j=1;$j<sizeof($rows);$j++) {
        set_time_limit(60);
        $row=$rows[$j];
        //echo htmlspecialchars($row);
        $pid=trim($row->find("td",2)->plaintext);
        $phtml=file_get_html("http://www.spoj.pl/ranks/$pid/");
        $ptable=$phtml->find("table.problems",0);
        $acnum=$ptable->find("tr.lightrow td",2)->plaintext;
        $totnum=$ptable->find("tr.lightrow td",1)->plaintext;
        mysql_query("update problem set vacnum='$acnum',vtotalnum='$totnum' where vname='SPOJ' and vid='$pid'");
        //echo $pid.$acnum.$totnum;die();
    }
    //die();
}
?>
</center>
<br>
<?php
include("footer.php");
?>



