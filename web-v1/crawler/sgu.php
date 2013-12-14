<?php include("header.php"); ?>
<center>
<?php

    function pimage($ori) {
        $last=0;
//        if (stripos($ori,"<img ",$last)!==false) {
//            echo "has image.<br>";
            //die();
//        }
        //die();
        //echo $ori;
       while (stripos($ori,"<img",$last)!==false) {
            $now=stripos($ori,"<img",$last);
            $beg=$now;
            //echo $now;
            $now=$now+2;
            $now=stripos($ori,"src",$now);
            //echo $now;
            $now+=strlen("src");
            while ($ori[$now]!="=") $now++;
			$now++;
			while ($ori[$now]==" ") $now++;
			if ($ori[$now]=="\""||$ori[$now]=="'") $now++;
			if ($ori[$now]=="/") $now++;
            //echo $now;
            $last=$now;
            while ($ori[$last]!=' '&&$ori[$last]!='>'&&$ori[$last]!='"'&&$ori[$last]!='\'') $last++;
//            $last--;
//            if(stripos($ori,"\"",$now)===false) {
//                $last=stripos($ori,">",$now);
//            }
//            else $last=stripos($ori,"\"",$now);
//            echo $ori[$last];
            $url=substr($ori,$now,$last-$now);
			$url=str_replace("\\","/",$url);
//            echo $url;
            //die();
            if (stripos($url,"/temp")!==false) { 
                $url=substr($url,stripos($url,"temp"));
            }
            file_put_contents("/var/www/contest/".$url, file_get_contents("http://acm.sgu.ru/".$url));
            $ori=substr($ori,0,$now).$url.substr($ori,$last);
//            echo $url;

            //$fp=fopen("/var/www/contest/".$url,"w+");
            //fclose($fp);

        }
        return $ori;
    }
    $from=$_GET['from'];
    $to=$_GET['to'];
//    $from=1;
//    $to=1;
    if ($to-$from>100) {
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

//die();
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
            if (stripos($content,"<title> SSU Online Contester ::")===false&&stripos($content,"<title>Saratov State University :: Online Contester")===false) {
                $chr=$pid.". ";
                $pos1=stripos($content,$chr)+strlen($chr);
                $pos2=stripos($content,"</title>",$pos1);
                $title=substr($content,$pos1,$pos2-$pos1);
//                echo "Title: ".$title."<br>\n";die();
    
                $chr="time limit per test: ";
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content," sec.",$pos1);
                $time_limit=substr($content,$pos1,$pos2-$pos1);
                $time_limit=intval(doubleval($time_limit)*1000);
//                echo "Time Limit: ".$time_limit."<br>\n";die();
                $case_limit=$time_limit;
    
                $chr="memory limit per test: ";
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content," KB",$pos1);
                $mem_limit=substr($content,$pos1,$pos2-$pos1);
//                $mem_limit=intval($mem_limit);
//                echo "Memory Limit: ".$mem_limit."<br>\n";die();

                $chr="</P>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"<B><P ALIGN=\"JUSTIFY\">Input",$pos1);
                    $desc=substr($content,$pos1,$pos2-$pos1);
    //                echo "Desc: ".($desc)."<br>\n";die();
                    $desc=pimage($desc);
    //                echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();
                }
    
                $chr="Input</P></B>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"<B><P ALIGN=\"JUSTIFY\">Output",$pos1);
                    $inp=substr($content,$pos1,$pos2-$pos1);
                    //echo "Input: ".htmlspecialchars($inp)."<br>\n";
                    $inp=pimage($inp);
//                    echo "Input: ".htmlspecialchars($inp)."<br>\n";
                }
    
                $chr="Output</P></B>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"<P ALIGN=\"JUSTIFY\">Sample",$pos1);
                    $oup=substr($content,$pos1,$pos2-$pos1);
                    //echo "Output: ".htmlspecialchars($oup)."<br>\n";
                    $oup=pimage($oup);
//                    echo "Output: ".htmlspecialchars($oup)."<br>\n";
                }
    
                $chr="<P ALIGN=\"JUSTIFY\">Sample";
                $pos1=stripos($content,$chr,$pos2);
                while (stripos($content,$chr,$pos2)!==false) {
                    $pos2=stripos($content,"<FONT FACE=\"Courier New\">",$pos2);
                    $pos2=stripos($content,"</FONT>",$pos2);
//                    echo $pos2."<br>";
//                    $sin=substr($content,$pos1,$pos2-$pos1+strlen("</div></div></div>"));
                }
                $sin=substr($content,$pos1,$pos2-$pos1+strlen("</FONT>"));
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
                $sin=str_replace(">\r\n",">",$sin);
//                echo "Sample Test: <pre>".$sin."</pre><br>\n";
                
                $sout="";


                $chr="<P ALIGN=\"JUSTIFY\">";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2);
                    $pos2=stripos($content,"</td></tr>",$pos1);
                    $hint=substr($content,$pos1,$pos2-$pos1);
                }
                else $hint="";
                $hint=pimage($hint);
//                echo "Hint: ".htmlspecialchars($hint)."<br>\n";
                
                $chr="Resource</td><td>: ";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"</td>",$pos1);
                    $source=strip_tags(substr($content,$pos1,$pos2-$pos1));
                }
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//                echo "Source: <pre>".$source."</pre><br>\n";

                $spj=0; 
            }
            else if (stripos($content,"<title> SSU Online Contester ::")!==false) {
				$chr="<h4>".$pid.". ";
                $pos1=stripos($content,$chr)+strlen($chr);
                $pos2=stripos($content,"</h4>",$pos1);
                $title=substr($content,$pos1,$pos2-$pos1);
//                echo "Title: ".$title."<br>\n";die();

                $chr="Time limit per test: ";
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content," second",$pos1);
                $time_limit=substr($content,$pos1,$pos2-$pos1);
                $time_limit=intval(doubleval($time_limit)*1000);
//                echo "Time Limit: ".$time_limit."<br>\n";die();
                $case_limit=$time_limit;

                $chr="Memory limit: ";
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content," kilobytes",$pos1);
                $mem_limit=substr($content,$pos1,$pos2-$pos1);
//                $mem_limit=intval($mem_limit);
//                echo "Memory Limit: ".$mem_limit."<br>\n";die();

                $chr="</div><br/>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"<div align = left style='margin-top:1em;'><b>Input",$pos1);
                    $desc=substr($content,$pos1,$pos2-$pos1);
    //                echo "Desc: ".($desc)."<br>\n";die();
                    $desc=pimage($desc);
    //                echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();
                }
                $chr="Input</b></div>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"<div align = left style='margin-top:1em;'><b>Output",$pos1);
                    $inp=substr($content,$pos1,$pos2-$pos1);
                    //echo "Input: ".htmlspecialchars($inp)."<br>\n";
                    $inp=pimage($inp);
//                    echo "Input: ".htmlspecialchars($inp)."<br>\n";
                }

                $chr="</b></div>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"<div align = left style='margin-top:1em;'>",$pos1);
                    $oup=substr($content,$pos1,$pos2-$pos1);
                    //echo "Output: ".htmlspecialchars($oup)."<br>\n";
                    $oup=pimage($oup);
//                    echo "Output: ".htmlspecialchars($oup)."<br>\n";
                }

                $chr="<div align = left style='margin-top:1em;'>";
                $pos1=stripos($content,$chr,$pos2);
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos2=stripos($content,"</table><br>",$pos2)+strlen("</table><br>");
//                    echo $pos2."<br>";
//                    $sin=substr($content,$pos1,$pos2-$pos1+strlen("</div></div></div>"));
                }
                $sin=substr($content,$pos1,$pos2-$pos1);
                $sin=pimage($sin);
                $sin=str_replace(">\r\n",">",$sin);
                $sin=str_replace("\r\n<","<",$sin);
//                $sin=str_replace("<pre>","",$sin);
//                $sin=str_replace("</pre>","",$sin);
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//                echo "Sample Test: <pre>".$sin."</pre><br>\n";

                $sout="";
                $chr="<b>Note</b></div>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"</div><hr>",$pos1);
                    $hint=substr($content,$pos1,$pos2-$pos1);
                }
                else $hint="";
                $hint=pimage($hint);
//                echo "Hint: ".htmlspecialchars($hint)."<br>\n";

                $chr="Resource:</td><td>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"</td>",$pos1);
                    $source=strip_tags(substr($content,$pos1,$pos2-$pos1));
                }
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//                echo "Source: <pre>".$source."</pre><br>\n";

                $spj=0;
            }
            else {
                $chr="<h4>".$pid.". ";
                $pos1=stripos($content,$chr)+strlen($chr);
                $pos2=stripos($content,"</h4>",$pos1);
                $title=substr($content,$pos1,$pos2-$pos1);
//                echo "Title: ".$title."<br>\n";die();

                $chr="time limit per test: ";
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content," sec.",$pos1);
                $time_limit=substr($content,$pos1,$pos2-$pos1);
                $time_limit=intval(doubleval($time_limit)*1000);
//                echo "Time Limit: ".$time_limit."<br>\n";die();
                $case_limit=$time_limit;

                $chr="memory limit per test: ";
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content," KB",$pos1);
                $mem_limit=substr($content,$pos1,$pos2-$pos1);
//                $mem_limit=intval($mem_limit);
//                echo "Memory Limit: ".$mem_limit."<br>\n";die();

                $chr="<div align = left>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"</div><div align = left><br><b>Input",$pos1);
                    $desc=substr($content,$pos1,$pos2-$pos1);
    //                echo "Desc: ".($desc)."<br>\n";die();
                    $desc=pimage($desc);
    //                echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();
                }
                $chr="Input</b></div><div align = left>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"</div><div align = left><br><b>Output",$pos1);
                    $inp=substr($content,$pos1,$pos2-$pos1);
                    //echo "Input: ".htmlspecialchars($inp)."<br>\n";
                    $inp=pimage($inp);
//                    echo "Input: ".htmlspecialchars($inp)."<br>\n";
                }

                $chr="Output</b></div><div align = left>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"</div><div align = left><br><b>Sample",$pos1);
                    $oup=substr($content,$pos1,$pos2-$pos1);
                    //echo "Output: ".htmlspecialchars($oup)."<br>\n";
                    $oup=pimage($oup);
//                    echo "Output: ".htmlspecialchars($oup)."<br>\n";
                }

                $chr="<div align = left><br><b>Sample";
                $pos1=stripos($content,$chr,$pos2);
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos2=stripos($content,"<div align = left><div align = right>",$pos2);
//                    echo $pos2."<br>";
//                    $sin=substr($content,$pos1,$pos2-$pos1+strlen("</div></div></div>"));
                }
                $sin=substr($content,$pos1,$pos2-$pos1);
				$sin=pimage($sin);
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
                $sin=str_replace(">\r\n",">",$sin);
				$sin=str_replace("\r\n<","<",$sin);
                $sin=str_replace("<pre>","",$sin);
                $sin=str_replace("</pre>","",$sin);
//                echo "Sample Test: <pre>".$sin."</pre><br>\n";
                $sout="";
                $chr="<b>Note</b></div>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"</div><hr>",$pos1);
                    $hint=substr($content,$pos1,$pos2-$pos1);
                }
                else $hint="";
                $hint=pimage($hint);
//                echo "Hint: ".htmlspecialchars($hint)."<br>\n";

                $chr="Resource:</td><td>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"</td>",$pos1);
                    $source=strip_tags(substr($content,$pos1,$pos2-$pos1));
                }
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//                echo "Source: <pre>".$source."</pre><br>\n";

                $spj=0;
            }

if ($gnum=="") $sql_add_pro = "insert into problem (title,description,input,output,sample_in,sample_out,hint,source,hide,memory_limit,time_limit,special_judge_status,case_time_limit,basic_solver_value,number_of_testcase,isvirtual,vname,vid) values ('".addslashes($title)."','".addslashes($desc)."','".addslashes($inp)."','".addslashes($oup)."','".addslashes($sin)."','".addslashes($sout)."','".addslashes($hint)."','".addslashes($source)."','0','$mem_limit','$time_limit','$spj','$case_limit','0','0',1,'SGU','$pid')";
else $sql_add_pro = "update problem set title='".addslashes($title)."',description='".addslashes($desc)."',input='".addslashes($inp)."',output='".addslashes($oup)."',sample_in='".addslashes($sin)."',sample_out='".addslashes($sout)."',hint='".addslashes($hint)."',source='".addslashes($source)."',hide='0',memory_limit='$mem_limit',time_limit='$time_limit',special_judge_status='$spj',case_time_limit='$case_limit',vname='SGU',vid='$pid' where pid= $gnum";

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

