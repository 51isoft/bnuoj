<?php include("header.php"); ?>
<center>
<?php

function mkdirs($path, $mode = 0755) //creates directory tree recursively 
{ 
    $dirs = explode('/',$path); 
    $pos = strrpos($path, "."); 
    if ($pos === false) { // note: three equal signs 
    // not found, means path ends in a dir not file 
        $subamount=0; 
    } 
    else { 
        $subamount=1; 
    } 
    for ($c=0;$c < count($dirs) - $subamount; $c++) { 
        $thispath=""; 
        for ($cc=0; $cc <= $c; $cc++) { 
            $thispath.=$dirs[$cc].'/'; 
        } 
        if (!file_exists($thispath)) { 
            //print "$thispath<br>"; 
            mkdir($thispath,$mode); 
        } 
    } 
}


    function pimage($ori) {
        $last=0;
        //echo $ori;
        while (stripos($ori,"<img",$last)!==false) {
            $now=stripos($ori,"<img",$last);
            $beg=$now;
            //echo $now;
            $now=$now+2;
            $now=stripos($ori,"src",$now);
            //echo $now;
            $now+=strlen("src");
            while ($ori[$now]!='=') $now++;
            $now++;
            while ($ori[$now]==' '||$ori[$now]=='"'||$ori[$now]=='\'') $now++;
            //echo $now." ".$ori[$now];
            $last=$now;
            while ($ori[$last]!=' '&&$ori[$last]!='>'&&$ori[$last]!='"'&&$ori[$last]!='\'') $last++;
//            $last--;
//            if(stripos($ori,"\"",$now)===false) {
//                $last=stripos($ori,">",$now);
//            }
//            else $last=stripos($ori,"\"",$now);
//            echo $ori[$last];
            $url=substr($ori,$now,$last-$now);
            

            $name='images/WHU/'.basename($url);
            if (strpos($url,"http://")===false) {
                if ($url[0]=='/') $url="http://acm.whu.edu.cn".$url;
                else $url="http://acm.whu.edu.cn/land/problem/".$url;
            }
            //echo $name;
            //die();
            mkdirs("/var/www/contest/".$name);
            $fp = fopen("/var/www/contest/".$name, "wb");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_FILE, $fp);
            $content = curl_exec($ch); 
            curl_close($ch); 
            fwrite($fp, $content);
            fclose($fp);
            $ori=substr($ori,0,$now).$name.substr($ori,$last);
            //echo $url;
            //die();
            //file_put_contents("/var/www/contest/".$url, $content);

            //$fp=fopen("/var/www/contest/".$url,"w+");
            //fclose($fp);
            $last=$now;
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

function br2nl($text) {    
    return preg_replace('/<br\\s*?\/??>/i', '', $text);   
}

    for ($pid=$from;$pid<=$to;$pid++) {
        $res=mysql_query("select pid from problem where vname like 'WHU' and vid like '$pid'");
        list($num)=mysql_fetch_array($res);
        if ($num) {
            echo "WHU $pid Already Exist, pid:$num.<br>\n";
            //continue;
        }
        $url="http://acm.whu.edu.cn/land/problem/detail?problem_id=$pid";
        $content=file_get_contents($url);
        if ($content=="") continue;
        //echo htmlspecialchars($content);die();
        if (strpos($content,"<div id=\"tt\">Ooooops!</div>")===false) {
            $chr="<div id=\"tt\">";
            $pos1=stripos($content,$chr)+strlen($chr);
            $chr="-";
            $pos1=stripos($content,$chr,$pos1)+strlen($chr);
            $pos2=stripos($content,"</div>",$pos1);
            $title=trim(br2nl(substr($content,$pos1,$pos2-$pos1)));
//            echo "Title: ".$title."<br>\n";die();

            $chr="Time Limit</strong>:";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content,"MS",$pos1);
            $time_limit=trim(substr($content,$pos1,$pos2-$pos1));
//            echo "Time Limit: ".$time_limit."<br>\n";die();

            /*$chr="><b>Case Time Limit:</b> ";
            if (strpos($content,$chr,$pos2)!==false) { 
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"MS</td>",$pos1);
                $case_limit=substr($content,$pos1,$pos2-$pos1);
            }
            else*/ $case_limit=$time_limit;
//            echo "Case Time Limit: ".$case_limit."<br>\n";die();

            $chr="Memory Limit</strong>:";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content,"KB",$pos1);
            $mem_limit=trim(substr($content,$pos1,$pos2-$pos1));
//            echo "Memory Limit: ".$mem_limit."<br>\n";die();

            $chr="<div class=\"ptt\">Description</div>";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content,"<div class=\"ptt\">Input</div>",$pos1);
            $desc=substr($content,$pos1,$pos2-$pos1);
//            echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();
            $desc=pimage($desc);
//            echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();

            $chr="<div class=\"ptt\">Input</div>";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content,"<div class=\"ptt\">Output</div>",$pos1);
            $inp=substr($content,$pos1,$pos2-$pos1);
//            echo "Input: ".htmlspecialchars($inp)."<br>\n";
            $inp=pimage($inp);
//            echo "Input: ".htmlspecialchars($inp)."<br>\n";

            $chr="<div class=\"ptt\">Output</div>";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content,"<div class=\"ptt\">Sample Input</div>",$pos1);
            $oup=substr($content,$pos1,$pos2-$pos1);
            //echo "Output: ".htmlspecialchars($oup)."<br>\n";
            $oup=pimage($oup);
//            echo "Output: ".htmlspecialchars($oup)."<br>\n";


            $chr="<div class=\"ptt\">Sample Input</div>";
            $pos1=stripos($content,$chr,$pos1)+strlen($chr);
            $pos2=stripos($content,"<div class=\"ptt\">Sample Output</div>",$pos1);
            $sin=substr($content,$pos1,$pos2-$pos1);
            //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//            echo "Sample In: ".htmlspecialchars($sin)."<br>\n";

            $chr="<div class=\"ptt\">Sample Output</div>";
            $pos1=stripos($content,$chr,$pos1)+strlen($chr);
            $pos2=stripos($content,"<div class=\"ptt\">Hint</div>",$pos1);
            $sout=substr($content,$pos1,$pos2-$pos1);
            //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//            echo "Sample Out: <pre>".$sout."</pre><br>\n";

            $chr="<div class=\"ptt\">Hint</div>";
            $pos1=stripos($content,$chr,$pos1)+strlen($chr);
            $pos2=stripos($content,"<div class=\"ptt\">Source</div>",$pos1);
            $hint=trim(substr($content,$pos1,$pos2-$pos1));
            $hint=pimage($hint);
//            echo "Hint: ".htmlspecialchars($hint)."<br>\n";

            $chr="<div class=\"ptt\">Source</div>";
            $pos1=strpos($content,$chr,$pos2)+strlen($chr);
            $pos2=strpos($content,"<br />",$pos1);
            $source=trim(strip_tags(substr($content,$pos1,$pos2-$pos1)));
            //echo "Output: ".htmlspecialchars($oup)."<br>\n";
            //echo "Source: <pre>".$source."</pre><br>\n";
            
            if (strpos($content,"Special Judge</strong>: Yes",$pos2)!==false) $spj=1;
            else $spj=0;

if ($num=="") $sql_add_pro = "insert into problem (title,description,input,output,sample_in,sample_out,hint,source,hide,memory_limit,time_limit,special_judge_status,case_time_limit,basic_solver_value,number_of_testcase,isvirtual,vname,vid) values ('".addslashes($title)."','".addslashes($desc)."','".addslashes($inp)."','".addslashes($oup)."','".addslashes($sin)."','".addslashes($sout)."','".addslashes($hint)."','".addslashes($source)."','0','$mem_limit','$time_limit','$spj','$case_limit','0','0',1,'WHU','$pid')";
else $sql_add_pro = "update problem set title='".addslashes($title)."',description='".addslashes($desc)."',input='".addslashes($inp)."',output='".addslashes($oup)."',sample_in='".addslashes($sin)."',sample_out='".addslashes($sout)."',hint='".addslashes($hint)."',source='".addslashes($source)."',hide='0',memory_limit='$mem_limit',time_limit='$time_limit',special_judge_status='$spj',case_time_limit='$case_limit',vname='WHU',vid='$pid' where pid= $num";

            //$search  = array('\n', '\t', '\r');
            //$replace = array('\\n', '\\t', '\\r');
            //$sql_add_pro=str_replace($search, $replace, $sql_add_pro);
//            echo htmlspecialchars($sql_add_pro);
            $que_in = mysql_query($sql_add_pro);
            if($que_in){
                list($currpid)=mysql_fetch_array(mysql_query("select max(pid) from problem"));
                if ($num) echo "WHU $pid has been recrawled as pid:$num<br>\n";
                else echo "WHU $pid has been added as pid:$currpid<br>\n";
            }


        }
        else {
            echo "No Such Problem Called WHU $pid.<br>\n";
        }
    }

?>
</center>
<br>
<?php include("footer.php"); ?>

