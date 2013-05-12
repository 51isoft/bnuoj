<?php
include("header.php");
include("simple_html_dom.php");
?>
<center>
<?php
for ($i=0;$i<13;$i++) {
    $html=file_get_html("http://www.spoj.pl/problems/tutorial/sort=0,start=".($i*50));
    $table=$html->find("table.problems",0);
    $rows=$table->find("tr");
    for ($j=1;$j<sizeof($rows);$j++) {
        set_time_limit(60);
        $row=$rows[$j];
        //echo htmlspecialchars($row);
        $pid=trim($row->find("td",2)->plaintext);
        //echo $pid;
        file_get_contents("http://localhost/contest/crawler/spoj.php?code=$pid");
    }
    //die();
}
for ($i=0;$i<50;$i++) {
    $html=file_get_html("http://www.spoj.pl/problems/classical/sort=0,start=".($i*50));
    $table=$html->find("table.problems",0);
    $rows=$table->find("tr");
    for ($j=1;$j<sizeof($rows);$j++) {
        set_time_limit(60);
        $row=$rows[$j];
        //echo htmlspecialchars($row);
        $pid=trim($row->find("td",2)->plaintext);
        //echo $pid;
        file_get_contents("http://localhost/contest/crawler/spoj.php?code=$pid");
    }
    //die();
}

?>
</center>
<br>
<?php
include("footer.php");
?>


