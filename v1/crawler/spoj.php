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
            if (stripos($url,"http://")===false&&stripos($url,"https://")===false) $url="http://www.spoj.com/".$url;
            $name=basename($url);
            $name='images/spoj/'.strtr($name,":","_");
            //echo $name;
            //die();
            mkdirs("/var/www/contest/".$name);
            $fp = fopen("/var/www/contest/".$name, "wb");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
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


    $pid=$_GET['code'];

        $res=mysql_query("select pid from problem where vname like 'SPOJ' and vid like '$pid'");
        list($num)=mysql_fetch_array($res);
        if ($num) {
            echo "SPOJ $pid Already Exist, pid:$num.<br>\n";
            //continue;
        }
        $url="http://www.spoj.com/problems/$pid/";
        $content=file_get_contents($url);
        $content=iconv("iso-8859-2","UTF-8//IGNORE",$content);
        if (stripos($content,"was not found on this server.")===false) {
            $chr="<p align=\"justify\">";
            $pos1=stripos($content,$chr)+strlen($chr);
            $pos2=stripos($content,"<script type=\"text/javascript\"><!--",$pos1);
            if ($pos2===false) $pos2=stripos($content,"<hr>",$pos1);
            $desc=substr($content,$pos1,$pos2-$pos1);
            //echo "Description: <br>".nl2br(htmlspecialchars($desc))."\n";die();


            $pos2=0;
            $chr="<h1>";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos1=stripos($content,$chr,$pos1)+strlen($chr);
            $pos2=stripos($content,"</h1>",$pos1);
            $title=substr($content,$pos1,$pos2-$pos1);
            $title=trim(substr(strstr($title,"."),1));
            //echo "Title: <br>".nl2br(htmlspecialchars($title))."\n";die();


            $chr="<td>Time limit:</td><td>";
            $pos1=stripos($content,$chr,$pos2)+strlen($chr);
            $pos2=stripos($content,"S",$pos1);
            $time_limit=intval(doubleval(substr($content,$pos1,$pos2-$pos1))*1000+0.01);
//            echo "Time Limit: ".$time_limit."<br>\n";die();
            
            
            $case_limit=$time_limit;
//            echo "Case Time Limit: ".$case_limit."<br>\n";die();

            $mem_limit=256*1024;
//          echo "Memory Limit: ".$mem_limit."<br>\n";die();

//            echo $content;

            $desc=pimage($desc);
            //echo "Desc: ".htmlspecialchars($desc)."<br>\n";die();

            $chr="<td>Resource:</td><td>";
            if (stripos($content,$chr,$pos2)!==false) {
                $pos1=stripos($content,$chr,$pos2)+strlen($chr);
                $pos2=stripos($content,"</td></tr>",$pos1);
                //echo $pos1." ".$pos2;
                $source=html_entity_decode(trim(strip_tags(substr($content,$pos1,$pos2-$pos1))),ENT_QUOTES);
                //echo "Source: <pre>".$source."</pre><br>\n";die();
            }
            else $source="";

            $pos2=0;
            $author="";
            
            $spj=0;

if ($num=="") $sql_add_pro = "insert into problem (title,description,input,output,sample_in,sample_out,hint,source,hide,memory_limit,time_limit,special_judge_status,case_time_limit,basic_solver_value,number_of_testcase,isvirtual,vname,vid,author) values ('".addslashes($title)."','".addslashes($desc)."','".addslashes($inp)."','".addslashes($oup)."','".addslashes($sin)."','".addslashes($sout)."','".addslashes($hint)."','".addslashes($source)."','0','$mem_limit','$time_limit','$spj','$case_limit','0','0',1,'SPOJ','$pid','".addslashes($author)."')";
else $sql_add_pro = "update problem set title='".addslashes($title)."',description='".addslashes($desc)."',input='".addslashes($inp)."',output='".addslashes($oup)."',sample_in='".addslashes($sin)."',sample_out='".addslashes($sout)."',hint='".addslashes($hint)."',source='".addslashes($source)."',hide='0',memory_limit='$mem_limit',time_limit='$time_limit',special_judge_status='$spj',case_time_limit='$case_limit',vname='SPOJ',vid='$pid',author='".addslashes($author)."' where pid= $num";

            //$search  = array('\n', '\t', '\r');
            //$replace = array('\\n', '\\t', '\\r');
            //$sql_add_pro=str_replace($search, $replace, $sql_add_pro);
//            echo htmlspecialchars($sql_add_pro);
            $que_in = mysql_query($sql_add_pro);
            if($que_in){
                list($currpid)=mysql_fetch_array(mysql_query("select max(pid) from problem"));
                if ($num) echo "SPOJ $pid has been recrawled as pid:$num<br>\n";
                else echo "SPOJ $pid has been added as pid:$currpid<br>\n";
            }


        }
        else {
            echo "No Such Problem Called SPOJ $pid.<br>\n";
        }

?>
</center>
<br>
<?php include("footer.php"); ?>

