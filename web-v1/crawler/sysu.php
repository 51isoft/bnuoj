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
            

            $name='images/sysu/'.basename($url);
            if (strpos($url,"http://")===false) {
                if ($url[0]=='/') $url="http://soj.me".$url;
                else $url="http://soj.me/".$url;
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
        $res=mysql_query("select pid from problem where vname like 'SYSU' and vid like '$pid'");
        list($num)=mysql_fetch_array($res);
        if ($num) {
            echo "SYSU $pid Already Exist, pid:$num.<br>\n";
            //continue;
        }
        $url="http://soj.me/$pid";
        $content=file_get_contents($url);
        if ($content=="") continue;
        //echo htmlspecialchars($content);die();
        if (strpos($content,"<div id=\"error_msg\">")===false) {
            $chr="<center><h1>";
            $pos1=stripos($content,$chr)+strlen($chr);
            $chr=".";
            $pos1=stripos($content,$chr,$pos1)+strlen($chr);
            $pos2=stripos($content,"</h1>",$pos1);
            $title=trim(substr($content,$pos1,$pos2-$pos1));
//            echo "Title: ".$title."<br>\n";die();

            $chr="Time Limit:";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content,"secs",$pos1);
            $time_limit=intval(trim(substr($content,$pos1,$pos2-$pos1)))*1000;
//            echo "Time Limit: ".$time_limit."<br>\n";die();

            /*$chr="><b>Case Time Limit:</b> ";
            if (strpos($content,$chr,$pos2)!==false) { 
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"MS</td>",$pos1);
                $case_limit=substr($content,$pos1,$pos2-$pos1);
            }
            else*/ $case_limit=$time_limit;
//            echo "Case Time Limit: ".$case_limit."<br>\n";die();

            $chr="Memory Limit:";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content,"MB",$pos1);
            $mem_limit=intval(trim(substr($content,$pos1,$pos2-$pos1)))*1024;
//            echo "Memory Limit: ".$mem_limit."<br>\n";die();

            if (strpos($content,", Special Judge",$pos2)!==false) $spj=1;
            else $spj=0;

            $chr="<h1>Description</h1>";
            if (stripos($content,$chr,$pos2)) {
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"<h1>",$pos1);
                $desc=substr($content,$pos1,$pos2-$pos1);
//            echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();
                $desc=pimage($desc);
//            echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();

            }
            else $desc="";

            $chr="<h1>Input</h1>";
            if (stripos($content,$chr,$pos2)) {
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"<h1>Output</h1>",$pos1);
                $inp=substr($content,$pos1,$pos2-$pos1);
//            echo "Input: ".htmlspecialchars($inp)."<br>\n";
                $inp=pimage($inp);
//            echo "Input: ".htmlspecialchars($inp)."<br>\n";
            }else $inp="";


            $chr="<h1>Output</h1>";
            if (stripos($content,$chr,$pos2)) {
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"<h1>Sample Input</h1>",$pos1);
                $oup=substr($content,$pos1,$pos2-$pos1);
            //echo "Output: ".htmlspecialchars($oup)."<br>\n";
                $oup=pimage($oup);
//            echo "Output: ".htmlspecialchars($oup)."<br>\n";

            }else $oup="";

            $chr="<pre>";
            if (stripos($content,$chr,$pos2)) {
                $pos1=stripos($content,$chr,$pos1)+strlen($chr);
                $pos2=stripos($content,"</pre>",$pos1);
                $sin=substr($content,$pos1,$pos2-$pos1);
            //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//            echo "Sample In: ".htmlspecialchars($sin)."<br>\n";

            }else $sin="";


            $chr="<pre>";
            if (stripos($content,$chr,$pos2)) {

                $pos1=stripos($content,$chr,$pos1)+strlen($chr);
                $pos2=stripos($content,"</pre>",$pos1);
                $sout=substr($content,$pos1,$pos2-$pos1);
            //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//            echo "Sample Out: <pre>".$sout."</pre><br>\n";
            }else $sout="";

//            $chr="<div class=\"ptt\">Hint</div>";
//            $pos1=stripos($content,$chr,$pos1)+strlen($chr);
//            $pos2=stripos($content,"<div class=\"ptt\">Source</div>",$pos1);
//            $hint=trim(substr($content,$pos1,$pos2-$pos1));
//            $hint=pimage($hint);
//            echo "Hint: ".htmlspecialchars($hint)."<br>\n";

            $chr="<h1>Problem Source</h1>";
            if (strpos($content,$chr,$pos2)) {
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos1=strpos($content,"<p>",$pos1)+strlen("<p>");
                $pos2=strpos($content,"</p>",$pos1);
                $source=trim(strip_tags(substr($content,$pos1,$pos2-$pos1)));
            } else $source="";
            //echo "Output: ".htmlspecialchars($oup)."<br>\n";
            //echo "Source: <pre>".$source."</pre><br>\n";
            

if ($num=="") $sql_add_pro = "insert into problem (title,description,input,output,sample_in,sample_out,hint,source,hide,memory_limit,time_limit,special_judge_status,case_time_limit,basic_solver_value,number_of_testcase,isvirtual,vname,vid) values ('".addslashes($title)."','".addslashes($desc)."','".addslashes($inp)."','".addslashes($oup)."','".addslashes($sin)."','".addslashes($sout)."','".addslashes($hint)."','".addslashes($source)."','0','$mem_limit','$time_limit','$spj','$case_limit','0','0',1,'SYSU','$pid')";
else $sql_add_pro = "update problem set title='".addslashes($title)."',description='".addslashes($desc)."',input='".addslashes($inp)."',output='".addslashes($oup)."',sample_in='".addslashes($sin)."',sample_out='".addslashes($sout)."',hint='".addslashes($hint)."',source='".addslashes($source)."',hide='0',memory_limit='$mem_limit',time_limit='$time_limit',special_judge_status='$spj',case_time_limit='$case_limit',vname='SYSU',vid='$pid' where pid= $num";

            //$search  = array('\n', '\t', '\r');
            //$replace = array('\\n', '\\t', '\\r');
            //$sql_add_pro=str_replace($search, $replace, $sql_add_pro);
//            echo htmlspecialchars($sql_add_pro);
            $que_in = mysql_query($sql_add_pro);
            if($que_in){
                list($currpid)=mysql_fetch_array(mysql_query("select max(pid) from problem"));
                if ($num) echo "SYSU $pid has been recrawled as pid:$num<br>\n";
                else echo "SYSU $pid has been added as pid:$currpid<br>\n";
            }


        }
        else {
            echo "No Such Problem Called SYSU $pid.<br>\n";
        }
    }

?>
</center>
<br>
<?php include("footer.php"); ?>

