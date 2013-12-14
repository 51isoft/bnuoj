<?php include("header.php"); ?>
<center>
<?php

    function pimage($ori) {
        $last=0;
        //echo $ori;
        while (stripos($ori,"<img ",$last)!==false) {
            $now=stripos($ori,"<img ",$last);
            $beg=$now;
            //echo $now;
            $now=$now+2;
            $now=stripos($ori,"src=",$now);
            //echo $now;
            $now+=strlen("src=");
            if ($ori[$now]=="\"") $now++;
            //echo $now;
            $last=$now;
            while ($ori[$last]!=' '&&$ori[$last]!='>'&&$ori[$last]!='"') $last++;
//            $last--;
//            if(stripos($ori,"\"",$now)===false) {
//                $last=stripos($ori,">",$now);
//            }
//            else $last=stripos($ori,"\"",$now);
//            echo $ori[$last];
            $url=substr($ori,$now,$last-$now);
            //echo $url;
            //die();
            if (substr($url,0,2)=='fo') {
                //echo $last;
                //die();
                $now=stripos($ori,"alt=\"",$last);
                $now+=strlen("alt=\"");
                $last=stripos($ori,"\"",$now);
                $url=substr($ori,$now,$last-$now);
                $end=stripos($ori,">",$last);
                //echo $url;
                $ori=substr_replace($ori,"[tex]".$url."[/tex]",$beg,$end-$beg+1);
                //echo htmlspecialchars($ori);die();
                $last=$beg;
            }
            else file_put_contents("/var/www/contest/".$url, file_get_contents("http://poj.org/".$url));

            //$fp=fopen("/var/www/contest/".$url,"w+");
            //fclose($fp);

        }
        return $ori;
    }
    $from=$_GET['from'];
    $to=$_GET['to'];
    $from=1000;
    $to=3700;
//    if ($to-$from>10) {
//        echo "Too many!\n".
//        "</center>".
//        "<br>";
//        include("footer.php");
//        die();
//    }
    if ($to==""||$from=="") {
        echo "Invalid!\n".
        "</center>".
        "<br>";
        include("footer.php");
        die();
    }

    for ($pid=$from;$pid<=$to;$pid++) {
        $res=mysql_query("select pid from problem where vname like 'PKU' and vid like '$pid'");
        list($num)=mysql_fetch_array($res);
        if ($num) {
            echo "PKU $pid Already Exist, pid:$num.<br>\n";
            //continue;
        }
        $url="http://poj.org/problem?id=$pid";
        $content=file_get_contents($url);
        //echo htmlspecialchars($content);
        if (strpos($content,"<li>Can not find problem")===false) {
            $chr="<div class=\"ptt\" lang=\"en-US\">";
            $pos1=strpos($content,$chr)+strlen($chr);
            $pos2=strpos($content,"</div>",$pos1);
            $title=substr($content,$pos1,$pos2-$pos1);
//            echo "Title: ".$title."<br>\n";die();

            $chr="<tr><td><b>Time Limit:</b> ";
            $pos1=strpos($content,$chr,$pos2)+strlen($chr);
            $pos2=strpos($content,"MS</td>",$pos1);
            $time_limit=substr($content,$pos1,$pos2-$pos1);
//            echo "Time Limit: ".$time_limit."<br>\n";die();

            $chr="><b>Case Time Limit:</b> ";
            if (strpos($content,$chr,$pos2)!==false) { 
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"MS</td>",$pos1);
                $case_limit=substr($content,$pos1,$pos2-$pos1);
            }
            else $case_limit=$time_limit;
//            echo "Case Time Limit: ".$case_limit."<br>\n";die();

            $chr="><b>Memory Limit:</b> ";
            $pos1=strpos($content,$chr,$pos2)+strlen($chr);
            $pos2=strpos($content,"K</td>",$pos1);
            $mem_limit=substr($content,$pos1,$pos2-$pos1);
//            echo "Memory Limit: ".$mem_limit."<br>\n";die();

            $chr="Description</p><div class=\"ptx\" lang=\"en-US\">";
            $pos1=strpos($content,$chr,$pos2)+strlen($chr);
            $pos2=strpos($content,"</div><p class=\"pst\">Input",$pos1);
            $desc=substr($content,$pos1,$pos2-$pos1);
//            echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();
            $desc=pimage($desc);
//            echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();

            $chr="Input</p><div class=\"ptx\" lang=\"en-US\">";
            $pos1=strpos($content,$chr,$pos2)+strlen($chr);
            $pos2=strpos($content,"</div><p class=\"pst\">Output",$pos1);
            $inp=substr($content,$pos1,$pos2-$pos1);
            //echo "Input: ".htmlspecialchars($inp)."<br>\n";
            $inp=pimage($inp);
//            echo "Input: ".htmlspecialchars($inp)."<br>\n";

            $chr="Output</p><div class=\"ptx\" lang=\"en-US\">";
            $pos1=strpos($content,$chr,$pos2)+strlen($chr);
            $pos2=strpos($content,"</div><p class=\"pst\">Sample Input",$pos1);
            $oup=substr($content,$pos1,$pos2-$pos1);
            //echo "Output: ".htmlspecialchars($oup)."<br>\n";
            $oup=pimage($oup);
//            echo "Output: ".htmlspecialchars($oup)."<br>\n";

            $chr="Sample Input</p><pre class=\"sio\">";
            $pos1=strpos($content,$chr,$pos2)+strlen($chr);
            $pos2=strpos($content,"</pre>",$pos1);
            $sin=substr($content,$pos1,$pos2-$pos1);
            //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//            echo "Sample In: <pre>".$sin."</pre><br>\n";

            $chr="Sample Output</p><pre class=\"sio\">";
            $pos1=strpos($content,$chr,$pos2)+strlen($chr);
            $pos2=strpos($content,"</pre>",$pos1);
            $sout=substr($content,$pos1,$pos2-$pos1);
            //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//            echo "Sample Out: <pre>".$sout."</pre><br>\n";

            $chr="Hint</p><div class=\"ptx\" lang=\"en-US\">";
            if (strpos($content,$chr,$pos2)!==false) {
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"</div><p class=\"pst\">Source",$pos1);
                $hint=substr($content,$pos1,$pos2-$pos1);
            }
            else $hint="";
            $hint=pimage($hint);
//            echo "Hint: ".htmlspecialchars($hint)."<br>\n";

            $chr="Source</p><div class=\"ptx\" lang=\"en-US\">";
            $pos1=strpos($content,$chr,$pos2)+strlen($chr);
            $pos2=strpos($content,"</div>",$pos1);
            $source=strip_tags(substr($content,$pos1,$pos2-$pos1));
            //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//            echo "Source: <pre>".$source."</pre><br>\n";
            
            if (strpos($content,"<td style=\"font-weight:bold; color:red;\">Special Judge</td>",$pos2)!==false) $spj=1;
            else $spj=0;

if ($num=="") $sql_add_pro = "insert into problem (title,description,input,output,sample_in,sample_out,hint,source,hide,memory_limit,time_limit,special_judge_status,case_time_limit,basic_solver_value,number_of_testcase,isvirtual,vname,vid) values ('".addslashes($title)."','".addslashes($desc)."','".addslashes($inp)."','".addslashes($oup)."','".addslashes($sin)."','".addslashes($sout)."','".addslashes($hint)."','".addslashes($source)."','0','$mem_limit','$time_limit','$spj','$case_limit','0','0',1,'PKU','$pid')";
else $sql_add_pro = "update problem set title='".addslashes($title)."',description='".addslashes($desc)."',input='".addslashes($inp)."',output='".addslashes($oup)."',sample_in='".addslashes($sin)."',sample_out='".addslashes($sout)."',hint='".addslashes($hint)."',source='".addslashes($source)."',hide='0',memory_limit='$mem_limit',time_limit='$time_limit',special_judge_status='$spj',case_time_limit='$case_limit',vname='PKU',vid='$pid' where pid= $num";

            //$search  = array('\n', '\t', '\r');
            //$replace = array('\\n', '\\t', '\\r');
            //$sql_add_pro=str_replace($search, $replace, $sql_add_pro);
//            echo htmlspecialchars($sql_add_pro);
//            $que_in = mysql_query($sql_add_pro);
            if($que_in){
                list($currpid)=mysql_fetch_array(mysql_query("select max(pid) from problem"));
                if ($num) echo "PKU $pid has been recrawled as pid:$num<br>\n";
                else echo "PKU $pid has been added as pid:$currpid<br>\n";
            }


        }
        else {
            echo "No Such Problem Called PKU $pid.<br>\n";
        }
    }

?>
</center>
<br>
<?php include("footer.php"); ?>

