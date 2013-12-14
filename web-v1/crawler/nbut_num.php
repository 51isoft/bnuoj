<?php
include("header.php");
include("simple_html_dom.php");
?>
<center>
<?php
for ($i=1;$i<6;$i++) {
    $html=file_get_html("http://cdn.ac.nbutoj.com/Problem.xhtml?page=$i");
    //echo $html;
    $table=$html->find("table tbody",0);
    $rows=$table->find("tr");
    for ($j=0;$j<sizeof($rows);$j++) {
        $row=$rows[$j];
        //echo htmlspecialchars($row);
        $pid=$row->find("td",1)->plaintext;
        $tstr=$row->find("td",3)->plaintext;
        //echo $tstr;
        $acnum=trim(strstr($tstr,'/',true));
        $totnum=trim(substr(strstr(strstr($tstr,'(',true),'/'),1));
//        echo "$pid $acnum $totnum<br>";die();
        mysql_query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='NBUT' and vid='$pid'");
    }
}
?>
</center>
<br>
<?php
include("footer.php");
?>

