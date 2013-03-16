<?php include("header.php"); ?>
<center>
<?php

    $from=$_GET['from'];
    $to=$_GET['to'];
//    $from=1;
//    $to=1;
    for ($pid=$from;$pid<=$to;$pid++) {
        $res=mysql_query("select pid from problem where vname like 'SGU' and vid like '$pid'");
        list($gnum)=mysql_fetch_array($res);
        if ($gnum) {
            echo "SGU $pid Already Exist, pid:$gnum.<br>\n";
            //continue;
        }
        $url="http://acm.sgu.ru/problem.php?contest=0&problem=$pid";
        $content=file_get_contents($url);
        $content=iconv("windows-1251","UTF-8//IGNORE",$content);
//        echo htmlspecialchars($content);die();
        if (stripos($content,"<h4>no such problem</h4>")===false) {
                $source="";
                $chr="Resource</td><td>: ";
				$pos2=0;
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"</td>",$pos1);
                    $source=strip_tags(substr($content,$pos1,$pos2-$pos1));
                }

                $chr="Resource:</td><td>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"</td>",$pos1);
                    $source=strip_tags(substr($content,$pos1,$pos2-$pos1));
                }

                $chr="Resource:</td><td>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"</td>",$pos1);
                    $source=strip_tags(substr($content,$pos1,$pos2-$pos1));
                }

$sql_add_pro = "update problem set source='".addslashes($source)."' where pid= $gnum";

            //$search  = array('\n', '\t', '\r');
            //$replace = array('\\n', '\\t', '\\r');
            //$sql_add_pro=str_replace($search, $replace, $sql_add_pro);
//            echo htmlspecialchars($sql_add_pro);
            $que_in = mysql_query($sql_add_pro);
                if($que_in){
                    list($currpid)=mysql_fetch_array(mysql_query("select max(pid) from problem"));
                    if ($num) echo "SGU $pid has been recrawled as pid:$num<br>\n";
                    else echo "SGU $pid has been added as pid:$currpid<br>\n";
                }

            }
            else {
                echo "No Such Problem Called SGU $pid.<br>\n";
            }
    }

?>
</center>
<br>
<?php include("footer.php"); ?>

