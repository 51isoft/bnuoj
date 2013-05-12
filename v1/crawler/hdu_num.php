<?php
include("header.php");
include("simple_html_dom.php");
?>
<center>
<?php
for ($i=1;$i<37;$i++) {
    $html=file_get_html("http://acm.hdu.edu.cn/listproblem.php?vol=$i");
    $table=$html->find("table",4);
    $txt=explode(";",$table->find("script",0)->innertext);
    //nl2br(var_dump($txt));
    foreach ($txt as $one) {
        $det=explode(",",$one);
        $pid=$det[1];
        $acnum=$det[sizeof($det)-2];
        $totnum=substr($det[sizeof($det)-1],0,-1);
        //echo "$pid $acnum $totnum<br>";
        mysql_query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='HDU' and vid='$pid'");
    }
}
?>
</center>
<br>
<?php
include("footer.php");
?>

