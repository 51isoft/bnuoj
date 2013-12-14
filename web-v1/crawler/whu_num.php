<?php
include("header.php");
?>
<center>
<?php
for ($i=1;$i<6;$i++) {
    $html=file_get_contents("http://acm.whu.edu.cn/land/problem/list?volume=$i");
    $chr="problem_data = ";
    $pos1=stripos($html,$chr)+strlen($chr);
    $pos2=stripos($html,"var is_admin",$pos1);
    $html=substr(trim(substr($html,$pos1,$pos2-$pos1)),0,-1);
    //echo $html;die();
    $html=json_decode($html);
    foreach ($html as $row) {
        $pid=$row->problem_id;
        $acnum=$row->accepted;
        $totnum=$row->submitted;
        //echo "$pid $acnum $totnum<br>";
        mysql_query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='WHU' and vid='$pid'");
    }
}
?>
</center>
<br>
<?php
include("footer.php");
?>

