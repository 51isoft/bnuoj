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
            while ($ori[$last]!=' '&&$ori[$last]!='>'&&$ori[$last]!='"') $last++;
//            $last--;
//            if(stripos($ori,"\"",$now)===false) {
//                $last=stripos($ori,">",$now);
//            }
//            else $last=stripos($ori,"\"",$now);
//            echo $ori[$last];
            $url=substr($ori,$now,$last-$now);
            $name='images/zju/'.urldecode(substr(strstr($url,'='),1));
            //echo $url;
            //die();
            mkdirs("/var/www/contest/".$name);
            $fp = fopen("/var/www/contest/".$name, "wb");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://acm.zju.edu.cn/onlinejudge/".$url);
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

    for ($pid=$from;$pid<=$to;$pid++) {
        $res=mysql_query("select pid from problem where vname like 'ZJU' and vid like '$pid'");
        list($num)=mysql_fetch_array($res);
        if ($num) {
            echo "ZJU $pid Already Exist, pid:$num.<br>\n";
            //continue;
        }
        $url="http://acm.zju.edu.cn/onlinejudge/showProblem.do?problemCode=$pid";
        $content=file_get_contents($url);
//        $content=iconv("gbk","UTF-8//IGNORE",$content);
//        echo htmlspecialchars($content);
        if (stripos($content,"<div id=\"content_title\">Message</div>")===false) {
            $chr="<div id=\"content_body\">";
            $pos1=stripos($content,$chr)+strlen($chr);
            $pos2=stripos($content,"<hr>",$pos1)+strlen("<hr>");
            $pos2=stripos($content,"<hr>",$pos2)+strlen("<hr>");
            $pos2=stripos($content,"<hr>",$pos2)+strlen("<hr>");
            $pos2=stripos($content,"<center>",$pos2);
            $content=substr($content,$pos1,$pos2-$pos1);
            //echo "Content: <br>".nl2br(htmlspecialchars($content))."\n";die();
            //file_put_contents("zju/$pid.html",$content);


            $pos2=0;
            $chr="<span class=\"bigProblemTitle\">";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content,"</span>",$pos1);
            $title=substr($content,$pos1,$pos2-$pos1);


            $chr="Time Limit: </font> ";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content," Second",$pos1);
            $time_limit=intval(substr($content,$pos1,$pos2-$pos1))*1000;
//            echo "Time Limit: ".$time_limit."<br>\n";die();
            
            
            $case_limit=$time_limit;
//            echo "Case Time Limit: ".$case_limit."<br>\n";die();

            $chr="Memory Limit: </font> ";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content," KB",$pos1);
            $mem_limit=substr($content,$pos1,$pos2-$pos1);
//          echo "Memory Limit: ".$mem_limit."<br>\n";die();

//            echo $content;

            $chr="<hr>";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content,"<hr>",$pos1);
            $desc=substr($content,$pos1,$pos2-$pos1);
//            echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();
            $desc=pimage($desc);
//            echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();
/*
            $chr="<div class=panel_title align=left>Input</div> <div class=panel_content>";
            if (stripos($content,$chr,$pos2)!==false) {
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"</div><div class=panel_bottom>",$pos1);
                $inp=substr($content,$pos1,$pos2-$pos1);
                //echo "Input: ".htmlspecialchars($inp)."<br>\n";
                $inp=pimage($inp);
            }
            else $inp="";
//            echo "Input: ".htmlspecialchars($inp)."<br>\n";

            $chr="<div class=panel_title align=left>Output</div> <div class=panel_content>";
            if (stripos($content,$chr,$pos2)!==false) {
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"</div><div class=panel_bottom>",$pos1);
                $oup=substr($content,$pos1,$pos2-$pos1);
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
                $oup=pimage($oup);
            }
            else $oup="";
//            echo "Output: ".htmlspecialchars($oup)."<br>\n";die();

            $chr="<pre><div style=\"font-family:Courier New,Courier,monospace;\">";
            if (stripos($content,$chr,$pos2)!==false) {
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"</div></pre>",$pos1);
                $sin=substr($content,$pos1,$pos2-$pos1);
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
    //            echo "Sample In: <pre>".$sin."</pre><br>\n";die();
            }
            else $sin="";

            $chr="<pre><div style=\"font-family:Courier New,Courier,monospace;\">";
            if (stripos($content,$chr,$pos2)!==false) {
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"</div></pre></div>",$pos1);
                $sout=substr($content,$pos1,$pos2-$pos1);
                //echo "Output: ".htmlspecialchars($oup)."<br>\n";
//                echo "Sample Out: <pre>".$sout."</pre><br>\n";die();
            }
            else $sout="";
            $sout=pimage($sout);

            $chr="<i>Hint</i></div>";
            if (stripos($content,$chr,$pos2)!==false) {
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"</div><i style='font-size:1px'>",$pos1);
                $hint=substr($content,$pos1,$pos2-$pos1);
            }
            else $hint="";
            $hint=pimage($hint);
//            echo "Hint: ".htmlspecialchars($hint)."<br>\n";die();
 */
            $chr="Source: <strong>";
            if (stripos($content,$chr,$pos2)!==false) {
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"</strong>",$pos1);
                //echo $pos1." ".$pos2;
                $source=html_entity_decode(trim(strip_tags(substr($content,$pos1,$pos2-$pos1))),ENT_QUOTES);
                //echo "Source: <pre>".$source."</pre><br>\n";die();
            }
            else {
                $chr="Contest: <strong>";
                if (stripos($content,$chr,$pos2)!==false) {
                    $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                    $pos2=stripos($content,"</strong>",$pos1);
                    //echo $pos1." ".$pos2;
                    $source=html_entity_decode(trim(strip_tags(substr($content,$pos1,$pos2-$pos1))),ENT_QUOTES);
                    //echo "Source: <pre>".$source."</pre><br>\n";die();
                }
                else $source="";
            }

            $pos2=0;
            $chr="Author: <strong>";
            if (stripos($content,$chr,$pos2)!==false) {
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"</strong>",$pos1);
                //echo $pos1." ".$pos2;
                $author=html_entity_decode(trim(strip_tags(substr($content,$pos1,$pos2-$pos1))),ENT_QUOTES);
                //echo "Source: <pre>".$source."</pre><br>\n";die();
            }
            else $author="";
            
            if (stripos($content,"<font color=\"blue\">Special Judge</font>",0)!==false) $spj=1;
            else $spj=0;

if ($num=="") $sql_add_pro = "insert into problem (title,description,input,output,sample_in,sample_out,hint,source,hide,memory_limit,time_limit,special_judge_status,case_time_limit,basic_solver_value,number_of_testcase,isvirtual,vname,vid,author) values ('".addslashes($title)."','".addslashes($desc)."','".addslashes($inp)."','".addslashes($oup)."','".addslashes($sin)."','".addslashes($sout)."','".addslashes($hint)."','".addslashes($source)."','0','$mem_limit','$time_limit','$spj','$case_limit','0','0',1,'ZJU','$pid','".addslashes($author)."')";
else $sql_add_pro = "update problem set title='".addslashes($title)."',description='".addslashes($desc)."',input='".addslashes($inp)."',output='".addslashes($oup)."',sample_in='".addslashes($sin)."',sample_out='".addslashes($sout)."',hint='".addslashes($hint)."',source='".addslashes($source)."',hide='0',memory_limit='$mem_limit',time_limit='$time_limit',special_judge_status='$spj',case_time_limit='$case_limit',vname='ZJU',vid='$pid',author='".addslashes($author)."' where pid= $num";

            //$search  = array('\n', '\t', '\r');
            //$replace = array('\\n', '\\t', '\\r');
            //$sql_add_pro=str_replace($search, $replace, $sql_add_pro);
//            echo htmlspecialchars($sql_add_pro);
            $que_in = mysql_query($sql_add_pro);
            if($que_in){
                list($currpid)=mysql_fetch_array(mysql_query("select max(pid) from problem"));
                if ($num) echo "ZJU $pid has been recrawled as pid:$num<br>\n";
                else echo "ZJU $pid has been added as pid:$currpid<br>\n";
            }


        }
        else {
            echo "No Such Problem Called ZJU $pid.<br>\n";
        }
    }

?>
</center>
<br>
<?php include("footer.php"); ?>

