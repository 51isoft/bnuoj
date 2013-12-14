<?php include("header.php"); ?>
<center>
<?php
//    $cate=0;
    function pimage($ori,$cate) {
        $last=0;
//        return $ori;
//        echo $ori; die();
        while (stripos($ori,"<img",$last)!==false) {
            $now=stripos($ori,"<img",$last);
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
//            echo $url;
            //die();
//            $url=substr($url,stripos($url,"data"));
//            echo $url;
//            die();
            file_put_contents("/var/www/contest/external/$cate/".$url, file_get_contents("http://livearchive.onlinejudge.org/external/$cate/".$url));
            $ori=substr($ori,0,$now)."external/$cate/".$url.substr($ori,$last);
//            echo $ori;die();
            //$fp=fopen("/var/www/contest/".$url,"w+");
            //fclose($fp);

        }
        return $ori;
    }
    $from=$_GET['from'];
    $to=$_GET['to'];
    if ($to-$from>10) {
        echo "Too many!\n".
        "</center>".
        "<br>";
        include("footer.php");
        die();
    }
    if ($to==""||$from=="") {
        echo "Invalid!\n".
        "</center>".
        "<br>";
        include("footer.php");
        die();
    }

    for ($pid=$from;$pid<=$to;$pid++) {
        $res=mysql_query("select pid from problem where vname like 'UVALive' and vid like '$pid'");
        list($num)=mysql_fetch_array($res);
        if ($num) {
            echo "UVALive $pid Already Exist, pid:$num.<br>\n";
            //continue;
        }
        $tpid=intval($pid)-1999;
        if ($pid>5722) $tpid+=10;
        $url="http://livearchive.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=8&page=show_problem&problem=".$tpid;
        $content=file_get_contents($url);
        //$content=iconv("gbk","UTF-8",$content);
        //echo htmlspecialchars($content);
//        die();
        if (strpos($content,"<h3>")!==false) {
            $chr="<h3>";
            $pos1=stripos($content,$chr)+strlen($chr);
            $pos2=stripos($content,"</h3>",$pos1);
            $title=substr($content,$pos1,$pos2-$pos1);
            $title=substr($title,6);
//            echo "Title: ".$title."<br>\n";die();

            $chr="Time limit: ";
            $pos1=strpos($content,$chr,$pos2)+strlen($chr);
            $pos2=strpos($content," seconds",$pos1);
            $time_limit=substr($content,$pos1,$pos2-$pos1);
            $time_limit=intval(doubleval($time_limit)*1000+0.01);
//            echo "Time Limit: ".$time_limit."<br>\n";die();
            
            
            $case_limit=$time_limit;
//            echo "Case Time Limit: ".$case_limit."<br>\n";die();

            $chr="<iframe src=\"";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content,"\" ",$pos1);
            $url=substr($content,$pos1,$pos2-$pos1);
//            $content=file_get_contents("http://livearchive.onlinejudge.org/".$url);
//            echo htmlspecialchars($content);die();

            $pos2=0;
            $chr="<a href=\"external";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
//            echo $pos1;
            $pos2=stripos($content,"\"",$pos1);
//            echo $pos2;
            $cate=substr($content,$pos1+1,stripos($content,"/",$pos1+1)-$pos1-1);
//            echo $cate;die();
            $pdflink="http://livearchive.onlinejudge.org/external".substr($content,$pos1,$pos2-$pos1);
            file_put_contents("/var/www/contest/external".substr($content,$pos1,$pos2-$pos1), file_get_contents($pdflink));
            $pdflink="external".substr($content,$pos1,$pos2-$pos1);
//            echo "PDF Link: ".$pdflink."<br>\n";die();
//            echo $content;

            $mem_limit="131072";    
            $pos2=0;
            $content=file_get_contents("http://livearchive.onlinejudge.org/".$url);
            $content=iconv("UTF-8","UTF-8//IGNORE",$content);
//            echo $content;die();
//            echo $mark;

            $chr="<o:p></o:p></span></p>";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content,"<h2>",$pos1)+strlen("<h2>");
            $pos2=stripos($content,"<h2>",$pos2)+strlen("<h2>");
            $pos2=stripos($content,"<h2>",$pos2)+strlen("<h2>");
            $desc=substr($content,$pos1,$pos2-$pos1);
//            echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();
            $desc=pimage($desc,$cate);
//            echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();
            $desc="<p><a href='$pdflink' class='bottom_link'>[PDF Link]</a></p>".$desc;

            $chr=">Input";
            if (stripos($content,$chr,$pos2)!==false) {
                $chr="</h2>";
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"<h2>",$pos1);
                $inp=substr($content,$pos1,$pos2-$pos1);
                //echo "Input: ".htmlspecialchars($inp)."<br>\n";
                $inp=pimage($inp,$cate);
            }
            else $inp="";
//            echo "Input: ".htmlspecialchars($inp)."<br>\n";die();

            $chr=">Output";
            if (stripos($content,$chr,$pos2)!==false) {
                $chr="</h2>";
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"<h2>",$pos1);
                $oup=substr($content,$pos1,$pos2-$pos1);
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
                $oup=pimage($oup,$cate);
            }
            else $oup="";
//            echo "Output: ".htmlspecialchars($oup)."<br>\n";die();

                $chr="</h2>";
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
//                $pos1=stripos($content,$chr,$pos1)+strlen($chr);
                $pos2=stripos($content,"<h2>",$pos1);
                $sin=substr($content,$pos1,$pos2-$pos1);
                $trans = array("\r"=>"","\n\n" => "\n");
//                $sin=strip_tags($sin);
                $sin=strtr($sin,$trans);
                ///echo "Output: ".htmlspecialchars($oup)."<br>\n";
//                echo "Sample In: <pre>".$sin."</pre><br>\n";die();

                $chr="</h2>";
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"</div>",$pos1);
                $sout=substr($content,$pos1,$pos2-$pos1);
                $sout=strtr($sout,$trans);
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//                echo "Sample Out: <pre>".$sout."</pre><br>\n";die();
/*
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
                //echo $pos1." ".$pos2;
                $source=trim(strip_tags(substr($content,$pos1,$pos2-$pos1)));
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
                //echo "Source: <pre>".$source."</pre><br>\n";die();
            }
            else $source="";
            
            if (strpos($content,"<font color=red>Special Judge</font>",0)!==false) $spj=1;
            else $spj=0;
*/
            $hint="";
            $source="";
            $spj=0;

if ($num=="") $sql_add_pro = "insert into problem (title,description,input,output,sample_in,sample_out,hint,source,hide,memory_limit,time_limit,special_judge_status,case_time_limit,basic_solver_value,number_of_testcase,isvirtual,vname,vid) values ('".addslashes($title)."','".addslashes($desc)."','".addslashes($inp)."','".addslashes($oup)."','".addslashes($sin)."','".addslashes($sout)."','".addslashes($hint)."','".addslashes($source)."','0','$mem_limit','$time_limit','$spj','$case_limit','0','0',1,'UVALive','$pid')";
else $sql_add_pro = "update problem set title='".addslashes($title)."',description='".addslashes($desc)."',input='".addslashes($inp)."',output='".addslashes($oup)."',sample_in='".addslashes($sin)."',sample_out='".addslashes($sout)."',hint='".addslashes($hint)."',source='".addslashes($source)."',hide='0',memory_limit='$mem_limit',time_limit='$time_limit',special_judge_status='$spj',case_time_limit='$case_limit',vname='UVALive',vid='$pid' where pid= $num";

            //$search  = array('\n', '\t', '\r');
            //$replace = array('\\n', '\\t', '\\r');
            //$sql_add_pro=str_replace($search, $replace, $sql_add_pro);
//            echo htmlspecialchars($sql_add_pro);
            $que_in = mysql_query($sql_add_pro);
            if($que_in){
                list($currpid)=mysql_fetch_array(mysql_query("select max(pid) from problem"));
                if ($num) echo "UVALive $pid has been recrawled as pid:$num<br>\n";
                else echo "UVALive $pid has been added as pid:$currpid<br>\n";
            }


        }
        else {
            echo "No Such Problem Called UVALive $pid.<br>\n";
        }
    }

?>
</center>
<br>
<?php include("footer.php"); ?>

