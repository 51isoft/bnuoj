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
            $url=substr($url,stripos($url,"renderer"));
            file_put_contents("/var/www/contest/".$url, file_get_contents("http://codeforces.ru/".$url));
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

//die();
    for ($cid=$from;$cid<=$to;$cid++) {
        $num='A';
        while (true) {
            $pid=$cid.$num;
            $res=mysql_query("select pid from problem where vname like 'CodeForces' and vid like '$pid'");
            list($gnum)=mysql_fetch_array($res);
//			echo $gnum.$pid;die();
            if ($gnum) {
                echo "CF $pid Already Exist, pid:$gnum.<br>\n";
                //continue;
            }
            $url="http://www.codeforces.com/problemset/problem/$cid/$num";
            $content=file_get_contents($url);
            //echo htmlspecialchars($content);die();
            //echo $url;
            //echo $content;die();
            if (strpos($content,"<title>Codeforces</title>")===false) {
                $chr="<div class=\"title\">".$num.". ";
                $pos1=strpos($content,$chr)+strlen($chr);
                $pos2=strpos($content,"</div>",$pos1);
                $title=substr($content,$pos1,$pos2-$pos1);
//                echo "Title: ".$title."<br>\n";die()
    
                $chr="time limit per test</div>";
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content," second",$pos1);
                $time_limit=substr($content,$pos1,$pos2-$pos1);
                $time_limit=intval($time_limit)*1000;
//                echo "Time Limit: ".$time_limit."<br>\n";die();

//                $chr="><b>Case Time Limit:</b> ";
//                if (strpos($content,$chr,$pos2)!==false) { 
//                    $pos1=strpos($content,$chr,$pos2)+strlen($chr);
//                    $pos2=strpos($content,"MS</td>",$pos1);
//                    $case_limit=substr($content,$pos1,$pos2-$pos1);
//                }
                $case_limit=$time_limit;
//                echo "Case Time Limit: ".$case_limit."<br>\n";die();
    
                $chr="memory limit per test</div>";
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content," megabytes",$pos1);
                $mem_limit=substr($content,$pos1,$pos2-$pos1);
                $mem_limit=intval($mem_limit)*1024;
//                echo "Memory Limit: ".$mem_limit."<br>\n";die();

                $chr="<div><p>";
                $pos1=strpos($content,$chr,$pos2)+strlen($chr)-3;
                $pos2=strpos($content,"</div>",$pos1);
                $desc=substr($content,$pos1,$pos2-$pos1);
//                echo "Desc: ".($desc)."<br>\n";die();
                $desc=pimage($desc);
//                echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();
    
                $chr="Input</div>";
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"</div>",$pos1);
                $inp=substr($content,$pos1,$pos2-$pos1);
                //echo "Input: ".htmlspecialchars($inp)."<br>\n";
                $inp=pimage($inp);
//                echo "Input: ".htmlspecialchars($inp)."<br>\n";die();
    
                $chr="Output</div>";
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"</div>",$pos1);
                $oup=substr($content,$pos1,$pos2-$pos1);
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
                $oup=pimage($oup);
//                echo "Output: ".htmlspecialchars($oup)."<br>\n";die();
    
/*                $chr="Sample Input</p><pre class=\"sio\">";
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
//              echo "Sample Out: <pre>".$sout."</pre><br>\n";
*/
                $chr="Sample test(s)</div>";
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"</div></div></div>",$pos1);
                $sin=substr($content,$pos1,$pos2-$pos1+strlen("</div></div>"));
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//                echo "Sample Test: <pre>".$sin."</pre><br>\n";die();
                
                $sout="";


                $chr="Note</div>";
                if (strpos($content,$chr,$pos2)!==false) {
                    $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                    if ($pos2===false) $pos2=strpos($content,"</div></div><p> </p>",$pos1);
                    else $pos2=strpos($content,"</div></div></div>            </div>",$pos1);
                    $hint=substr($content,$pos1,$pos2-$pos1);
                }
                else $hint="";
                $hint=pimage($hint);
//                echo "Hint: ".htmlspecialchars($hint)."<br>\n";

                if (strpos($content,"<div class=\"input-file\"><div class=\"property-title\">input</div>standard input</div><div class=\"output-file\"><div class=\"property-title\">output</div>standard output</div></div>")===false) {
                    
                }
                
                $pos2=0;
                $chr="                            <th class=\"left\" style=\"width:100%;\">";
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"</th>",$pos1);
                $source=strip_tags(substr($content,$pos1,$pos2-$pos1));
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//                echo "Source: <pre>".$source."</pre><br>\n";

                $spj=0; 

if ($gnum=="") $sql_add_pro = "insert into problem (title,description,input,output,sample_in,sample_out,hint,source,hide,memory_limit,time_limit,special_judge_status,case_time_limit,basic_solver_value,number_of_testcase,isvirtual,vname,vid) values ('".addslashes($title)."','".addslashes($desc)."','".addslashes($inp)."','".addslashes($oup)."','".addslashes($sin)."','".addslashes($sout)."','".addslashes($hint)."','".addslashes($source)."','0','$mem_limit','$time_limit','$spj','$case_limit','0','0',1,'CodeForces','$pid')";
else $sql_add_pro = "update problem set title='".addslashes($title)."',description='".addslashes($desc)."',input='".addslashes($inp)."',output='".addslashes($oup)."',sample_in='".addslashes($sin)."',sample_out='".addslashes($sout)."',hint='".addslashes($hint)."',source='".addslashes($source)."',hide='0',memory_limit='$mem_limit',time_limit='$time_limit',special_judge_status='$spj',case_time_limit='$case_limit',vname='CodeForces',vid='$pid' where pid= $gnum";

            //$search  = array('\n', '\t', '\r');
            //$replace = array('\\n', '\\t', '\\r');
            //$sql_add_pro=str_replace($search, $replace, $sql_add_pro);
//            echo htmlspecialchars($sql_add_pro);
            $que_in = mysql_query($sql_add_pro);
                if($que_in){
                    list($currpid)=mysql_fetch_array(mysql_query("select max(pid) from problem"));
                    if ($num) echo "CF $pid has been recrawled as pid:$num<br>\n";
                    else echo "CF $pid has been added as pid:$currpid<br>\n";
                }

            }
            else {
                echo "No Such Problem Called CF $pid.<br>\n";
                break;
            }
            $num++;
//            echo $num;
        }
    }

?>
</center>
<br>
<?php include("footer.php"); ?>

