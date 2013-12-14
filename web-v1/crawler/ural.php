<?php include("header.php"); ?>
<center>
<?php
//error_reporting(E_ALL);
$ojuser="youruser";
$ojpass="yourpass";

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
            mkdirs("/var/www/contest/".$url);
            $fp = fopen("/var/www/contest/".$url, "wb");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://acm.timus.ru".$url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_GET, 1);  
            //curl_setopt($ch, CURLOPT_FILE, $fp);
            $content = curl_exec($ch); 
            curl_close($ch); 
            fwrite($fp, $content);
            fclose($fp);
            //echo $url;
            //die();
            //file_put_contents("/var/www/contest/".$url, $content);

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
        $res=mysql_query("select pid from problem where vname like 'Ural' and vid like '$pid'");
        list($num)=mysql_fetch_array($res);
        if ($num) {
            echo "Ural $pid Already Exist, pid:$num.<br>\n";
            //continue;
        }
        $url="http://acm.timus.ru/problem.aspx?num=$pid";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $content = curl_exec($ch); 
        curl_close($ch); 

        //$content=file_get_contents($url);
        //echo htmlspecialchars($content);

        //die();

        if (stripos($content,">Problem not found</DIV>")===false) {
            $chr="<H2 class=\"problem_title\">";
            $pos1=stripos($content,$chr)+strlen($chr);
            $chr=".";
            $pos1=stripos($content,$chr,$pos1)+strlen($chr);
            $pos2=stripos($content,"</H2>",$pos1);
            $title=trim(substr($content,$pos1,$pos2-$pos1));
            //echo "Title: ".$title."<br>\n";die();

            $chr="<DIV class=\"problem_limits\">Time Limit: ";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content," second",$pos1);
            $time_limit=substr($content,$pos1,$pos2-$pos1);
            $time_limit=intval(doubleval($time_limit)*1000);
            //echo "Time Limit: ".$time_limit."<br>\n";die();

            $case_limit=$time_limit;
//            echo "Case Time Limit: ".$case_limit."<br>\n";die();

            $chr="<BR>Memory Limit: ";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content," MB<BR>",$pos1);
            $mem_limit=substr($content,$pos1,$pos2-$pos1);
            $mem_limit=intval($mem_limit)*1024;
//            echo "Memory Limit: ".$mem_limit."<br>\n";die();

            $chr="<DIV ID=\"problem_text\">";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content,"<H3 CLASS=\"problem_subtitle\">Input",$pos1);
            $desc=substr($content,$pos1,$pos2-$pos1);
//            echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();
            $desc=pimage($desc);
//            echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();

            $chr=">Input";
            if (stripos($content,$chr,$pos2)!==false) {
                $chr="</h3>";
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"<H3 CLASS=\"problem_subtitle\">Output",$pos1);
                $inp=substr($content,$pos1,$pos2-$pos1);
                //echo "Input: ".htmlspecialchars($inp)."<br>\n";
                $inp=pimage($inp);
            }
            else $inp="";
//            echo "Input: ".htmlspecialchars($inp)."<br>\n";die();

            $chr=">Output";
            if (stripos($content,$chr,$pos2)!==false) {
                $chr="</h3>";
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"<H3 CLASS=\"problem_subtitle\">Sample",$pos1);
                $oup=substr($content,$pos1,$pos2-$pos1);
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
                $oup=pimage($oup);
            }
            else $oup="";
//            echo "Output: ".htmlspecialchars($oup)."<br>\n";die();

                $chr="<PRE CLASS=\"intable\">";
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"</pre>",$pos1);
                $sin=substr($content,$pos1,$pos2-$pos1);

                $chr="<PRE CLASS=\"intable\">";
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"</pre>",$pos1);
                $sout=substr($content,$pos1,$pos2-$pos1);

            $chr=">Hint";
            if (strpos($content,$chr,$pos2)!==false) {
                $chr="Hint</H3>";
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"<DIV CLASS=\"problem_source\">",$pos1);
                $hint=substr($content,$pos1,$pos2-$pos1);
            }
            else $hint="";
            $hint=pimage($hint);
//            echo "Hint: ".htmlspecialchars($hint)."<br>\n";

            $chr="Problem Source: </B>";
            if (strpos($content,$chr,$pos2)!==false) {
                $pos1=strpos($content,$chr,$pos2)+strlen($chr);
                $pos2=strpos($content,"<BR>",$pos1);
                //echo $pos1." ".$pos2;
                $source=trim(strip_tags(substr($content,$pos1,$pos2-$pos1)));
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
                //echo "Source: <pre>".$source."</pre><br>\n";die();
            }
            else $source="";
           
            $spj=0;
//            $sout="";

if ($num=="") $sql_add_pro = "insert into problem (title,description,input,output,sample_in,sample_out,hint,source,hide,memory_limit,time_limit,special_judge_status,case_time_limit,basic_solver_value,number_of_testcase,isvirtual,vname,vid) values ('".addslashes($title)."','".addslashes($desc)."','".addslashes($inp)."','".addslashes($oup)."','".addslashes($sin)."','".addslashes($sout)."','".addslashes($hint)."','".addslashes($source)."','0','$mem_limit','$time_limit','$spj','$case_limit','0','0',1,'Ural','$pid')";
else $sql_add_pro = "update problem set title='".addslashes($title)."',description='".addslashes($desc)."',input='".addslashes($inp)."',output='".addslashes($oup)."',sample_in='".addslashes($sin)."',sample_out='".addslashes($sout)."',hint='".addslashes($hint)."',source='".addslashes($source)."',hide='0',memory_limit='$mem_limit',time_limit='$time_limit',special_judge_status='$spj',case_time_limit='$case_limit',vname='Ural',vid='$pid' where pid= $num";

            //$search  = array('\n', '\t', '\r');
            //$replace = array('\\n', '\\t', '\\r');
            //$sql_add_pro=str_replace($search, $replace, $sql_add_pro);
//            echo htmlspecialchars($sql_add_pro);
            $que_in = mysql_query($sql_add_pro);
            if($que_in){
                list($currpid)=mysql_fetch_array(mysql_query("select max(pid) from problem"));
                if ($num) echo "Ural $pid has been recrawled as pid:$num<br>\n";
                else echo "Ural $pid has been added as pid:$currpid<br>\n";
            }


        }
        else {
            echo "No Such Problem Called Ural $pid.<br>\n";
        }
    }

?>
</center>
<br>
<?php include("footer.php"); ?>

