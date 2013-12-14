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
            if ($ori[$now]=="\""||$ori[$now]=="'") $now++;
            //echo $now;
            $last=$now;
            while ($ori[$last]!=' '&&$ori[$last]!='>'&&$ori[$last]!='"'&&$ori[$now]!="'") $last++;
//            $last--;
//            if(stripos($ori,"\"",$now)===false) {
//                $last=stripos($ori,">",$now);
//            }
//            else $last=stripos($ori,"\"",$now);
//            echo $ori[$last];
            $url=substr($ori,$now,$last-$now);
            //echo $url;
            //die();
            $url=substr($url,stripos($url,"data"));
            //echo $url;
            //die();
            file_put_contents("/var/www/contest/".$url, file_get_contents("http://acm.hdu.edu.cn/".$url));
            $ori=substr($ori,0,$now).$url.substr($ori,$last);
            //echo $ori;die();
            //$fp=fopen("/var/www/contest/".$url,"w+");
            //fclose($fp);

        }
        return $ori;
    }
    $from=$_GET['from'];
    $to=$_GET['to'];
    $from=1000;
    $to=4070;
/*    if ($to-$from>10) {
        echo "Too many!\n".
        "</center>".
        "<br>";
        include("footer.php");
        die();
    }*/
    if ($to==""||$from=="") {
        echo "Invalid!\n".
        "</center>".
        "<br>";
        include("footer.php");
        die();
    }

    for ($pid=$from;$pid<=$to;$pid++) {
        $res=mysql_query("select pid from problem where vname like 'HDU' and vid like '$pid'");
        list($num)=mysql_fetch_array($res);
        if ($num) {
            echo "HDU $pid Already Exist, pid:$num.<br>\n";
            //continue;
        }
        $url="http://acm.hdu.edu.cn/showproblem.php?pid=$pid";
        $content=file_get_contents($url);
        $content=iconv("gbk","UTF-8",$content);
        //echo htmlspecialchars($content);
        if (strpos($content,"No such problem - <strong>Problem")===false) {
            $chr="<h1 style='color:#1A5CC8'>";
            $pos1=strpos($content,$chr)+strlen($chr);
            $pos2=strpos($content,"</h1>",$pos1);
            $title=substr($content,$pos1,$pos2-$pos1);
//            echo "Title: ".$title."<br>\n";die();

            $chr="000/";
            $pos1=strpos($content,$chr,$pos2)+strlen($chr);
            $pos2=strpos($content," MS ",$pos1);
            $time_limit=substr($content,$pos1,$pos2-$pos1);
//            echo "Time Limit: ".$time_limit."<br>\n";die();
            
            
            $case_limit=$time_limit;
//            echo "Case Time Limit: ".$case_limit."<br>\n";die();

            $chr="/";
            $pos1=strpos($content,$chr,$pos2+20)+strlen($chr);
            $pos2=strpos($content," K ",$pos1);
            $mem_limit=substr($content,$pos1,$pos2-$pos1);
//            echo "Memory Limit: ".$mem_limit."<br>\n";die();

            $chr="Problem Description</div> <div class=panel_content>";
            $pos1=strpos($content,$chr,$pos2)+strlen($chr);
            $pos2=strpos($content,"</div><div class=panel_bottom>",$pos1);
            $desc=substr($content,$pos1,$pos2-$pos1);
//            echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();
            $desc=pimage($desc);
//            echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();

            $chr="<div class=panel_title align=left>Input</div> <div class=panel_content>";
            if (strpos($content,$chr,$pos2)!==false) {
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"</div><div class=panel_bottom>",$pos1);
                $inp=substr($content,$pos1,$pos2-$pos1);
                //echo "Input: ".htmlspecialchars($inp)."<br>\n";
                $inp=pimage($inp);
            }
            else $inp="";
//            echo "Input: ".htmlspecialchars($inp)."<br>\n";

            $chr="<div class=panel_title align=left>Output</div> <div class=panel_content>";
            if (strpos($content,$chr,$pos2)!==false) {
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"</div><div class=panel_bottom>",$pos1);
                $oup=substr($content,$pos1,$pos2-$pos1);
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
                $oup=pimage($oup);
            }
            else $oup="";
//            echo "Output: ".htmlspecialchars($oup)."<br>\n";die();

            $chr="<pre><div style=\"font-family:Courier New,Courier,monospace;\">";
            if (strpos($content,$chr,$pos2)!==false) {
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"</div></pre>",$pos1);
                $sin=substr($content,$pos1,$pos2-$pos1);
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
    //            echo "Sample In: <pre>".$sin."</pre><br>\n";die();
            }
            else $sin="";

            $chr="<pre><div style=\"font-family:Courier New,Courier,monospace;\">";
            if (strpos($content,$chr,$pos2)!==false) {
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"</pre></div>",$pos1);
                $sout=substr($content,$pos1,$pos2-$pos1);
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//                echo "Sample Out: <pre>".$sout."</pre><br>\n";die();
            }
            else $sout="";

            $chr="<i>Hint</i></div>";
            if (strpos($content,$chr,$pos2)!==false) {
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"</div><i style='font-size:1px'>",$pos1);
                $hint=substr($content,$pos1,$pos2-$pos1);
            }
            else $hint="";
            $hint=pimage($hint);
//            echo "Hint: ".htmlspecialchars($hint)."<br>\n";die();

            $chr="<div class=panel_title align=left>Source</div> ";
            if (strpos($content,$chr,$pos2)!==false) {
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"<div class=panel_bottom>&nbsp;</div>",$pos1);
                $source=trim(strip_tags(substr($content,$pos1,$pos2-$pos1)));
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
               // echo "Source: <pre>".$source."</pre><br>\n";die();
            }
            else $source="";
            
            if (strpos($content,"<font color=red>Special Judge</font>",0)!==false) $spj=1;
            else $spj=0;

if ($num=="") $sql_add_pro = "insert into problem (title,description,input,output,sample_in,sample_out,hint,source,hide,memory_limit,time_limit,special_judge_status,case_time_limit,basic_solver_value,number_of_testcase,isvirtual,vname,vid) values ('".addslashes($title)."','".addslashes($desc)."','".addslashes($inp)."','".addslashes($oup)."','".addslashes($sin)."','".addslashes($sout)."','".addslashes($hint)."','".addslashes($source)."','0','$mem_limit','$time_limit','$spj','$case_limit','0','0',1,'HDU','$pid')";
else $sql_add_pro = "update problem set title='".addslashes($title)."',description='".addslashes($desc)."',input='".addslashes($inp)."',output='".addslashes($oup)."',sample_in='".addslashes($sin)."',sample_out='".addslashes($sout)."',hint='".addslashes($hint)."',source='".addslashes($source)."',hide='0',memory_limit='$mem_limit',time_limit='$time_limit',special_judge_status='$spj',case_time_limit='$case_limit',vname='HDU',vid='$pid' where pid= $num";

            //$search  = array('\n', '\t', '\r');
            //$replace = array('\\n', '\\t', '\\r');
            //$sql_add_pro=str_replace($search, $replace, $sql_add_pro);
//            echo htmlspecialchars($sql_add_pro);
            $que_in = mysql_query($sql_add_pro);
            if($que_in){
                list($currpid)=mysql_fetch_array(mysql_query("select max(pid) from problem"));
                if ($num) echo "HDU $pid has been recrawled as pid:$num<br>\n";
                else echo "HDU $pid has been added as pid:$currpid<br>\n";
            }


        }
        else {
            echo "No Such Problem Called HDU $pid.<br>\n";
        }
    }

?>
</center>
<br>
<?php include("footer.php"); ?>

