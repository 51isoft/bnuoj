<?php
include_once(dirname(__FILE__)."/global.php");
include_once(dirname(__FILE__)."/simple_html_dom.php");

$crawled=array();
function process_and_get_image($ori,$path,$baseurl,$space_deli,$cookie) {
    $para["path"]=$path;$para["base"]=$baseurl;$para["trans"]=!$space_deli;$para["cookie"]=$cookie;
    if ($space_deli) $reg="/< *im[a]?g[^>]*src *= *[\"\\']?([^\"\\' >]*)[^>]*>/si";
    else $reg="/< *im[a]?g[^>]*src *= *[\"\\']?([^\"\\'>]*)[^>]*>/si";
    return preg_replace_callback($reg,
                                function($matches) use ($para) {
                                    global $config,$crawled;
                                    $url=trim($matches[1]);
                                    if (stripos($url,"http://")===false&&stripos($url,"https://")===false) {
                                        if ($para["trans"]) $url=str_replace(" ","%20",$url);
                                        $url=$para["base"].$url;
                                    }
                                    if ($crawled[$url]) return $result;
                                    $crawled[$url]=true;
                                    $name=basename($url);
                                    $name="images/".$para["path"]."/".strtr($name,":","_");

                                    $result=str_replace(trim($matches[1]),$name,$matches[0]);

                                    if (file_exists($config["base_local_path"].$name)) return $result;
                                    mkdirs($config["base_local_path"].$name);

                                    //echo $url;
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                    if ($para["cookie"]!="") curl_setopt($ch, CURLOPT_COOKIEFILE, $para["cookie"]);
                                    curl_setopt($ch, CURLOPT_HEADER, 0);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $content = curl_exec($ch); 
                                    curl_close($ch);
                                    //echo $content;

                                    $fp = fopen($config["base_local_path"].$name, "wb");
                                    fwrite($fp, $content);
                                    fclose($fp);
                                    return $result;
                                } ,
                                $ori);
}

function pcrawler_process_info($ret,$path,$baseurl,$space_deli=true,$cookie="") {
    $ret["description"]=process_and_get_image($ret["description"],$path,$baseurl,$space_deli,$cookie);
    $ret["input"]=process_and_get_image($ret["input"],$path,$baseurl,$space_deli,$cookie);
    $ret["output"]=process_and_get_image($ret["output"],$path,$baseurl,$space_deli,$cookie);
    $ret["hint"]=process_and_get_image($ret["hint"],$path,$baseurl,$space_deli,$cookie);
    return $ret;
}

function pcrawler_insert_problem($ret,$vname,$vid) {
    global $db;
    $vname=$db->escape($vname);
    $vid=$db->escape($vid);
    $db->query("select pid from problem where vname like '$vname' and vid like '$vid'");
    if ($db->num_rows==0) {
        $sql_add_pro = "insert into problem 
        (title,description,input,output,sample_in,sample_out,hint,source,hide,memory_limit,time_limit,special_judge_status,case_time_limit,basic_solver_value,number_of_testcase,isvirtual,vname,vid) values
        ('".$db->escape($ret["title"])."','".$db->escape($ret["description"])."','".$db->escape($ret["input"])."','".$db->escape($ret["output"])."','".$db->escape($ret["sample_in"])."','".$db->escape($ret["sample_out"])."','".$db->escape($ret["hint"])."','".$db->escape($ret["source"])."','0','".$ret["memory_limit"]."','".$ret["time_limit"]."','".$ret["special_judge_status"]."','".$ret["case_time_limit"]."','0','0',1,'$vname','$vid')";
        $db->query($sql_add_pro);
        $gnum=$db->insert_id;
    }
    else {
        list($gnum)=$db->get_row(null,ARRAY_N);
        $sql_add_pro = "update problem set 
                            title='".$db->escape($ret["title"])."',
                            description='".$db->escape($ret["description"])."',
                            input='".$db->escape($ret["input"])."',
                            output='".$db->escape($ret["output"])."',
                            sample_in='".$db->escape($ret["sample_in"])."',
                            sample_out='".$db->escape($ret["sample_out"])."',
                            hint='".$db->escape($ret["hint"])."',
                            source='".$db->escape($ret["source"])."',
                            hide='0',
                            memory_limit='".$ret["memory_limit"]."',
                            time_limit='".$ret["time_limit"]."',
                            special_judge_status='".$ret["special_judge_status"]."',
                            case_time_limit='".$ret["case_time_limit"]."',
                            vname='$vname',
                            vid='$vid'
                            where pid=$gnum";
        $db->query($sql_add_pro);
    }    
    return $gnum;
}

function pcrawler_cf_one($cid,$num) {
    $pid=$cid.$num;
    $url="http://www.codeforces.com/problemset/problem/$cid/$num";
    $content=file_get_contents($url);
    $ret=array();
    if (stripos($content,"<title>Codeforces</title>")===false) {
        if (preg_match("/<div class=\"title\">$num\\. (.*)<\\/div>/sU", $content,$matches)) $ret["title"]=trim($matches[1]);
        if (preg_match("/time limit per test<\\/div>(.*) second/sU", $content,$matches)) $ret["time_limit"]=intval(trim($matches[1]))*1000;
        $ret["case_time_limit"]=$ret["time_limit"];
        if (preg_match("/memory limit per test<\\/div>(.*) megabyte/sU", $content,$matches)) $ret["memory_limit"]=intval(trim($matches[1]))*1024;
        if (preg_match("/output<\\/div>.*<div><p>(.*)<\\/div>/sU", $content,$matches)) $ret["description"]=trim($matches[1]);
        if (preg_match("/Input<\\/div>(.*)<\\/div>/sU", $content,$matches)) $ret["input"]=trim($matches[1]);
        if (preg_match("/Output<\\/div>(.*)<\\/div>/sU", $content,$matches)) $ret["output"]=trim($matches[1]);
        if (preg_match("/Sample test\\(s\\)<\\/div>(.*<\\/div><\\/div>)<\\/div>/sU", $content,$matches)) $ret["sample_in"]=trim($matches[1]);
        $ret["sample_out"]="";
        if (preg_match("/Note<\\/div>(.*)<\\/div><\\/div>/sU", $content,$matches)) $ret["hint"]=trim($matches[1]);
        if (preg_match("/<th class=\"left\" style=\"width:100%;\">(.*)<\\/th>/sU", $content,$matches)) $ret["source"]=trim(strip_tags($matches[1]));
        $ret["special_judge_status"]=0;
        return $ret;
    }
    else return false;
}

function pcrawler_codeforces($cid) {
    $msg="";
    $num='A';
    while ($row=pcrawler_cf_one($cid,$num)) {
        $row=pcrawler_process_info($row,"cf","http://codeforces.ru/");
        $id=pcrawler_insert_problem($row,"CodeForces",$cid.$num);
        $msg.="CodeForces $cid$num has been crawled as $id.<br>";
        $num++;
    }
    $msg.="No problem called CodeForces $cid$num.<br>";
    return $msg;
}


function pcrawler_codeforces_num() {
    global $db;
    $i=1;$one=0;
    while (true) {
        if ($one) break;
        $html=file_get_html("http://www.codeforces.com/problemset/page/$i");
        $table=$html->find("table.problems",0);
        $rows=$table->find("tr");
        for ($j=1;$j<sizeof($rows);$j++) {
            $row=$rows[$j];
            $pid=trim($row->find("td",0)->find("a",0)->innertext);
            $acnum=substr(trim($row->find("td",3)->find("a",0)->plaintext),7);
            $totnum=0;
            if ($pid=='1A') $one++;
            $db->query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='CodeForces' and vid='$pid'");
        }
        $i++;
    }
    return "Done";
}

function pcrawler_fzu($pid) {
    $url="http://acm.fzu.edu.cn/problem.php?pid=$pid";
    $content=file_get_contents($url);
    $ret=array();

    if (stripos($content,"<font size=\"+3\">No Such Problem!</font>")===false) {
        if (preg_match("/<b> Problem $pid(.*)<\\/b>/sU", $content,$matches)) $ret["title"]=trim($matches[1]);
        if (preg_match("/<br \\/>Time Limit:(.*) mSec/sU", $content,$matches)) $ret["time_limit"]=intval(trim($matches[1]));
        $ret["case_time_limit"]=$ret["time_limit"];
        if (preg_match("/Memory Limit : (.*) KB/sU", $content,$matches)) $ret["memory_limit"]=intval(trim($matches[1]));
        if (preg_match("/Problem Description<\\/h2><\\/b>(.*)<h2>/sU", $content,$matches)) $ret["description"]=trim($matches[1]);
        if (preg_match("/> Input<\\/h2>(.*)<h2>/sU", $content,$matches)) $ret["input"]=trim($matches[1]);
        if (preg_match("/> Output<\\/h2>(.*)<h2>/sU", $content,$matches)) $ret["output"]=trim($matches[1]);
        if (preg_match("/<div class=\"data\">(.*)<\\/div>/sU", $content,$matches)) $ret["sample_in"]=trim($matches[1]);
        if ($ret["sample_in"]=="") {
            if (preg_match("/<div class=\"data\">(.*)<\\/div>/sU", $content,$matches)) $ret["sample_out"]=trim($matches[1]);
        }
        else if (preg_match("/<div class=\"data\">.*<div class=\"data\">(.*)<\\/div>/sU", $content,$matches)) $ret["sample_out"]=trim($matches[1]);
        if (preg_match("/Hint<\\/h2>(.*)<h2>/sU", $content,$matches)) $ret["hint"]=trim($matches[1]);
        if (preg_match("/Source<\\/h2>(.*)<\\/div>/sU", $content,$matches)) $ret["source"]=trim($matches[1]);
        if (strpos($content,"<font color=\"blue\">Special Judge</font>")!==false) $ret["special_judge_status"]=1;
        else $ret["special_judge_status"]=0;

        $ret=pcrawler_process_info($ret,"fzu","http://acm.fzu.edu.cn/");
        $id=pcrawler_insert_problem($ret,"FZU",$pid);
        return "FZU $pid has been crawled as $id.<br>";
    }
    else return "No problem called FZU $pid.<br>";
}

function pcrawler_fzu_num() {
    global $db;

    $i=1;
    while (true) {
        $html=file_get_html("http://acm.fzu.edu.cn/list.php?vol=$i");
        $table=$html->find("table",0);
        $rows=$table->find("tr");
        if (sizeof($rows)<2) break;
        for ($j=1;$j<sizeof($rows);$j++) {
            $row=$rows[$j];
            //echo htmlspecialchars($row);
            $pid=$row->find("td",1)->plaintext;
            $tstr=$row->find("td",3)->plaintext;
            $acnum=substr(strstr(strstr($tstr,'('),'/',true),1);
            $totnum=substr(strstr(strstr($tstr,'/'),')',true),1);
            //echo "$pid $acnum $totnum<br>";die();
            $db->query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='FZU' and vid='$pid'");
        }
        $i++;
    }

    return "Done";
}

function pcrawler_hdu($pid) {
    $url="http://acm.hdu.edu.cn/showproblem.php?pid=$pid";
    $content=file_get_contents($url);
    $content=iconv("gbk","UTF-8//IGNORE",$content);
    $ret=array();

    if (stripos($content,"No such problem - <strong>Problem")===false) {
        if (preg_match("/<h1 style='color:#1A5CC8'>(.*)<\\/h1>/sU", $content,$matches)) $ret["title"]=trim($matches[1]);
        if (preg_match("/Time Limit:.*\\/(.*) MS/sU", $content,$matches)) $ret["time_limit"]=intval(trim($matches[1]));
        $ret["case_time_limit"]=$ret["time_limit"];
        if (preg_match("/Memory Limit:.*\\/(.*) K/sU", $content,$matches)) $ret["memory_limit"]=intval(trim($matches[1]));
        if (preg_match("/Problem Description.*<div class=panel_content>(.*)<\\/div><div class=panel_bottom>/sU", $content,$matches)) $ret["description"]=trim($matches[1]);
        if (preg_match("/<div class=panel_title align=left>Input.*<div class=panel_content>(.*)<\\/div><div class=panel_bottom>/sU", $content,$matches)) $ret["input"]=trim($matches[1]);
        if (preg_match("/<div class=panel_title align=left>Output.*<div class=panel_content>(.*)<\\/div><div class=panel_bottom>/sU", $content,$matches)) $ret["output"]=trim($matches[1]);
        if (preg_match("/<pre><div.*>(.*)<\\/div><\\/pre>/sU", $content,$matches)) $ret["sample_in"]=trim($matches[1]);
        if ($ret["sample_in"]=="") {
            if (preg_match("/<pre><div.*>(.*)<div|<pre><div.*>(.*)<\\/div><\\/pre>/sU", $content,$matches)) $ret["sample_out"]=trim($matches[1]);
        }
        else if (preg_match("/<\\/pre>.*<pre><div.*>(.*)<div|<\\/pre>.*<pre><div.*>(.*)<\\/div><\\/pre>/sU", $content,$matches)) $ret["sample_out"]=trim($matches[1]);
        if (preg_match("/<i>Hint<\\/i><\\/div>(.*)<\\/div><i style='font-size:1px'>/sU", $content,$matches)) $ret["hint"]=trim($matches[1]);
        if (preg_match("/<div class=panel_title align=left>Source<\\/div> (.*)<div class=panel_bottom>/sU", $content,$matches)) $ret["source"]=trim(strip_tags($matches[1]));
        if (strpos($content,"<font color=red>Special Judge</font>")!==false) $ret["special_judge_status"]=1;
        else $ret["special_judge_status"]=0;

        $ret=pcrawler_process_info($ret,"hdu","http://acm.hdu.edu.cn/");
        $id=pcrawler_insert_problem($ret,"HDU",$pid);
        return "HDU $pid has been crawled as $id.<br>";
    }
    else return "No problem called HDU $pid.<br>";
}

function pcrawler_hdu_num() {
    global $db;
    $i=1;
    while (true) {
        $html=file_get_html("http://acm.hdu.edu.cn/listproblem.php?vol=$i");
        $table=$html->find("table",4);
        $txt=explode(";",$table->find("script",0)->innertext);
        if (sizeof($txt)<2) break;
        foreach ($txt as $one) {
            $det=explode(",",$one);
            $pid=$det[1];
            $acnum=$det[sizeof($det)-2];
            $totnum=substr($det[sizeof($det)-1],0,-1);
            $db->query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='HDU' and vid='$pid'");
        }
        $i++;
    }

    return "Done";
}


function pcrawler_openjudge($pid) {
    $url="http://poj.openjudge.cn/practice/$pid";
    $content=file_get_contents($url);
    $ret=array();

    if (stripos($content,"<div id=\"pageTitle\"><h2>")!==false) {
        if (preg_match('/<div id="pageTitle"><h2>.*:(.*)<\/h2>/sU', $content,$matches)) $ret["title"]=trim($matches[1]);
        if (preg_match('/<dt>总时间限制: <\/dt>.*<dd>(.*)ms/sU', $content,$matches)) $ret["time_limit"]=intval(trim($matches[1]));
        $ret["case_time_limit"]=$ret["time_limit"];
        if (preg_match('/<dt>内存限制: <\/dt>.*<dd>(.*)kB/sU', $content,$matches)) $ret["memory_limit"]=intval(trim($matches[1]));
        if (preg_match('/<dt>描述<\/dt>.*<dd>(.*)<\/dd>/sU', $content,$matches)) $ret["description"]=trim($matches[1]);
        if (preg_match('/<dt>输入<\/dt>.*<dd>(.*)<\/dd>/sU', $content,$matches)) $ret["input"]=trim($matches[1]);
        if (preg_match('/<dt>输出<\/dt>.*<dd>(.*)<\/dd>/sU', $content,$matches)) $ret["output"]=trim($matches[1]);
        if (preg_match('/<dt>样例输入<\/dt>.*<pre>(.*)<\/pre>/sU', $content,$matches)) $ret["sample_in"]=trim($matches[1]);
        if (preg_match('/<dt>样例输出<\/dt>.*<pre>(.*)<\/pre>/sU', $content,$matches)) $ret["sample_out"]=trim($matches[1]);
        $ret["hint"]="";
        $ret["source"]="";
        $ret["special_judge_status"]=0;

        $ret=pcrawler_process_info($ret,"openjudge","http://poj.openjudge.cn/practice/$pid/");
        $id=pcrawler_insert_problem($ret,"OpenJudge",$pid);
        return "OpenJudge $pid has been crawled as $id.<br>";
    }
    else return "No problem called OpenJudge $pid.<br>";
}

function pcrawler_openjudge_num() {
    global $db;
    $got=array();
    $i=1;
    while (true) {
        $html=file_get_html("http://poj.openjudge.cn/practice/?page=$i");
        $table=$html->find("table",0);
        $rows=$table->find("tr");
        if ($got[$rows[1]->find("td",0)->plaintext]==true) break;
        for ($j=1;$j<sizeof($rows);$j++) {
            $row=$rows[$j];
            //echo htmlspecialchars($row);
            $pid=$row->find("td",0)->plaintext;
            $got[$pid]=true;
            $acnum=$row->find("td",3)->plaintext;
            $totnum=$row->find("td",4)->plaintext;
            //echo "$pid $acnum $totnum<br>";die();
            $db->query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='OpenJudge' and vid='$pid'");
        }
        $i++;
    }

    return "Done";
}

function pcrawler_sysu($pid) {
    $url="http://soj.me/$pid";
    $content=file_get_contents($url);
    $ret=array();

    if (stripos($content,"<div id=\"error_msg\">")===false) {
        if (preg_match('/<center><h1>.* (.*)<\/h1>/sU', $content,$matches)) $ret["title"]=trim($matches[1]);
        if (preg_match('/<p>Time Limit: (.*) secs/sU', $content,$matches)) $ret["time_limit"]=intval(trim($matches[1]))*1000;
        $ret["case_time_limit"]=$ret["time_limit"];
        if (preg_match('/, Memory Limit: (.*) MB/sU', $content,$matches)) $ret["memory_limit"]=intval(trim($matches[1]))*1024;
        if (preg_match('/<h1>Description<\/h1>(.*)<h1>/sU', $content,$matches)) $ret["description"]=trim($matches[1]);
        if (preg_match('/<h1>Input<\/h1>(.*)<h1>Input/sU', $content,$matches)) $ret["input"]=trim($matches[1]);
        if (preg_match('/<h1>Output<\/h1>(.*)<h1>Sample/sU', $content,$matches)) $ret["output"]=trim($matches[1]);
        if (preg_match('/<h1>Sample.*<pre>(.*)<\/pre>/sU', $content,$matches)) $ret["sample_in"]=trim($matches[1]);
        if (preg_match('/<h1>Sample.*<pre>.*<pre>(.*)<\/pre>/sU', $content,$matches)) $ret["sample_out"]=trim($matches[1]);
        $ret["hint"]="";
        if (preg_match('/<h1>Problem Source<\/h1>.*<p>(.*)<\/p>/sU', $content,$matches)) $ret["source"]=trim($matches[1]);
        if (strpos($content,"<font color=\"blue\">Special Judge</font>")!==false) $ret["special_judge_status"]=1;
        else $ret["special_judge_status"]=0;

        $ret=pcrawler_process_info($ret,"sysu","http://soj.me/");
        $id=pcrawler_insert_problem($ret,"SYSU",$pid);
        return "SYSU $pid has been crawled as $id.<br>";
    }
    else return "No problem called SYSU $pid.<br>";
}

function pcrawler_sysu_num() {
    global $db;

    $html=file_get_html("http://soj.me/problem_tab.php?start=1000&end=999999");
    $table=$html->find("table",0);
    $rows=$table->find("tr");
    for ($j=1;$j<sizeof($rows);$j++) {
        $row=$rows[$j];
        //echo htmlspecialchars($row);
        $pid=$row->find("td",1)->plaintext;
        $acnum=$row->find("td",3)->plaintext;
        $totnum=$row->find("td",4)->plaintext;
        //echo "$pid $acnum $totnum<br>";die();
        $db->query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='SYSU' and vid='$pid'");
    }

    return "Done";
}

function pcrawler_scu($pid) {
    $url="http://cstest.scu.edu.cn/soj/problem.action?id=$pid";
    $content=file_get_contents($url);
    $ret=array();
    $content=iconv("gbk","UTF-8//IGNORE",$content);
    //$content=mb_convert_encoding($content,"UTF-8","GBK, GB2312, windows-1252");
    if (stripos($content,"<title>ERROR</title>")===false) {
        if (preg_match('/<h1 align="center">.*: (.*)<\/h1>/sU', $content,$matches)) $ret["title"]=trim($matches[1]);
        $ret["case_time_limit"]=$ret["time_limit"]=$ret["memory_limit"]="0";

        $ret["description"]=file_get_contents("http://cstest.scu.edu.cn/soj/problem/$pid");
        $ret["description"]=mb_convert_encoding($ret["description"],"UTF-8","GBK, GB2312, windows-1252");

        $ret["input"]=$ret["output"]=$ret["sample_in"]=$ret["sample_out"]=$ret["hint"]=$ret["source"]="";
        $ret["special_judge_status"]=0;

        $ret=pcrawler_process_info($ret,"scu/$pid","http://cstest.scu.edu.cn/soj/problem/$pid/",false);
        $id=pcrawler_insert_problem($ret,"SCU",$pid);
        return "SCU $pid has been crawled as $id.<br>";
    }
    else return "No problem called SCU $pid.<br>";
}

function pcrawler_scu_num() {
    global $db;
    $i=0;
    while (true) {
        $html=file_get_html("http://cstest.scu.edu.cn/soj/problems.action?volume=$i");
        $table=$html->find("table",0);
        $rows=$table->find("tr");
        if (sizeof($rows)<4) break;
        for ($j=3;$j<sizeof($rows);$j++) {
            $row=$rows[$j];
            //echo htmlspecialchars($row);
            $pid=$row->find("td",1)->plaintext;
            $acnum=strip_tags($row->find("td",4)->innertext);
            $totnum=$row->find("td",3)->plaintext;
            //echo "$pid $acnum $totnum<br>";
            $db->query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='SCU' and vid='$pid'");
        }
        $i++;
    }

    return "Done";
}

function pcrawler_hust($pid) {
    $url="http://acm.hust.edu.cn/problem.php?id=$pid";
    $content=file_get_contents($url);
    $ret=array();

    if (stripos($content,"<h2>No Such Problem!</h2>")===false) {
        if (preg_match('/<h2>(.*)<\/h2></sU', $content,$matches)) $ret["title"]=trim($matches[1]);
        if (preg_match('/Time Limit: <\/b>(.*) Sec/sU', $content,$matches)) $ret["time_limit"]=intval(trim($matches[1]))*1000;
        $ret["case_time_limit"]=$ret["time_limit"];
        if (preg_match('/Memory Limit: <\/b>(.*) MB/sU', $content,$matches)) $ret["memory_limit"]=intval(trim($matches[1]))*1024;
        if (preg_match('/Description<\/h2>(.*)<h2>Input/sU', $content,$matches)) $ret["description"]=trim($matches[1]);
        if (preg_match('/Input<\/h2>(.*)<h2>Output/sU', $content,$matches)) $ret["input"]=trim($matches[1]);
        if (preg_match('/Output<\/h2>(.*)<h2>Sample/sU', $content,$matches)) $ret["output"]=trim($matches[1]);
        if (preg_match('/<h2>Sample.*<pre>(.*)<\/pre>/sU', $content,$matches)) $ret["sample_in"]=trim($matches[1]);
        if (preg_match('/<h2>Sample.*<pre>.*<pre>(.*)<\/pre>/sU', $content,$matches)) $ret["sample_out"]=trim($matches[1]);
        if (preg_match('/HINT<\/h2>(.*)<h2>Source/sU', $content,$matches)) $ret["hint"]=trim($matches[1]);
        if (preg_match('/Source<\/h2>.*<p>(.*)<\/p>/sU', $content,$matches)) $ret["source"]=trim($matches[1]);
        if (strpos($content,"<b style=\"color:red\">Special Judge</b>")!==false) $ret["special_judge_status"]=1;
        else $ret["special_judge_status"]=0;

        $ret=pcrawler_process_info($ret,"hust","http://acm.hust.edu.cn/");
        $id=pcrawler_insert_problem($ret,"HUST",$pid);
        return "HUST $pid has been crawled as $id.<br>";
    }
    else return "No problem called HUST $pid.<br>";
}

function pcrawler_hust_num() {
    global $db;

    $i=1;
    while (true) {
        $html=file_get_html("http://acm.hust.edu.cn/problemset.php?page=$i");
        $table=$html->find("table",1);
        $rows=$table->find("tr");
        if (sizeof($rows)<3) break;
        for ($j=2;$j<sizeof($rows);$j++) {
            $row=$rows[$j];
            //echo htmlspecialchars($row);
            $pid=$row->find("td",1)->plaintext;
            $acnum=$row->find("td a",1)->plaintext;
            $totnum=$row->find("td a",2)->plaintext;
            //echo "$pid $acnum $totnum<br>";die();
            $db->query("update problem set vacnum='$acnum', vtotalnum='$totnum' where vname='HUST' and vid='$pid'");
        }
        $i++;
    }

    return "Done";
}

function pcrawler_pku($pid){
    $url = "http://poj.org/problem?id=$pid";
    $content = file_get_contents($url);
    $ret = array();
    
    if (trim($content) == "") return "No problem called PKU $pid.<br>";
    if (stripos($content, "Can not find problem") === false){
        if (preg_match('/<div class="ptt" lang="en-US">(.*)<\/div>/sU', $content, $matches)) $ret["title"] = trim($matches[1]);
        if (preg_match('/<td><b>Time Limit:<\/b> (.*)MS<\/td>/sU', $content, $matches)) $ret["time_limit"] = intval(trim($matches[1]));
        $ret["case_time_limit"] = $ret["time_limit"];
        if (preg_match('/<td><b>Memory Limit:<\/b> (.*)K<\/td>/sU', $content, $matches)) $ret["memory_limit"] = intval(trim($matches[1]));
        if (preg_match('/<p class="pst">Description<\/p><div class="ptx" lang="en-US">(.*)<\/div><p class="pst">Input<\/p>/sU', $content, $matches)) $ret["description"] = trim($matches[1]);
        if (preg_match('/<p class="pst">Input<\/p><div class="ptx" lang="en-US">(.*)<\/div><p class="pst">Output<\/p>/sU', $content, $matches)) $ret["input"] = trim($matches[1]);
        if (preg_match('/<p class="pst">Output<\/p><div class="ptx" lang="en-US">(.*)<\/div><p class="pst">Sample Input<\/p>/sU', $content, $matches)) $ret["output"] = trim($matches[1]);
        if (preg_match('/<p class="pst">Sample Input<\/p><pre class="sio">(.*)<\/pre><p class="pst">Sample Output<\/p>/sU', $content, $matches)) $ret["sample_in"] = trim($matches[1]);
        if (preg_match('/<p class="pst">Sample Output<\/p><pre class="sio">(.*)<\/pre><p class="pst">Source<\/p>/sU', $content, $matches)) $ret["sample_out"] = trim($matches[1]);
        if (preg_match('/<p class="pst">Source<\/p><div class="ptx" lang="en-US">(.*)<\/div>/sU', $content, $matches)) $ret["source"] = trim(strip_tags($matches[1]));
        if (strpos($content, '<td style="font-weight:bold; color:red;">Special Judge</td>') !== false) $ret["special_judge_status"] = 1;
        else $ret["special_judge_status"] = 0;

        $ret = pcrawler_process_info($ret, "pku", "http://poj.org/");
        $id = pcrawler_insert_problem($ret, "PKU", $pid);
        return "PKU $pid has been crawled as $id.<br>";
    }
    else{
        return "No problem called PKU $pid.<br>";
    }
}


function pcrawler_lightoj($pid){

    global $config;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://www.lightoj.com/login_check.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/lightoj.cookie");
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, "myuserid=".urlencode($config["accounts"]["lightoj"]["username"])."&mypassword=".urlencode($config["accounts"]["lightoj"]["password"])."&Submit=Login");
    $content = curl_exec($ch);
    curl_close($ch); 

    $url = "http://www.lightoj.com/volume_showproblem.php?problem=$pid";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/lightoj.cookie");
    $content = curl_exec($ch); 
    curl_close($ch); 

    $ret = array();

    if (trim($content) == "<script>location.href='volume_problemset.php'</script>") return "No problem called LIGHTOJ $pid.<br>";
    if (stripos($content, "Can not find problem") === false){
        if (preg_match('/<div id="problem_name">(.*) - (.*)<\/div>/sU', $content, $matches)) $ret["title"] = trim($matches[2]);
        if (preg_match('/Time Limit: <span style="color: #B45F04;">(.*) second/sU', $content, $matches)) $ret["time_limit"] = intval(trim($matches[1])) * 1000;
        $ret["case_time_limit"] = $ret["time_limit"];
        if (preg_match('/Memory Limit: <span style="color: #B45F04;">(.*) MB/sU', $content, $matches)) $ret["memory_limit"] = intval(trim($matches[1])) * 1024;
        if (preg_match('/<div class=Section1>(.*)<h1>Input<\/h1>/sU', $content, $matches)) $ret["description"] = trim($matches[1]);
        if (preg_match('/<h1>Input<\/h1>.*<p class=MsoNormal>(.*)<\/p>/sU', $content, $matches)) $ret["input"] = trim($matches[1]);
        if (preg_match('/<h1>Output<\/h1>.*<p class=MsoNormal>(.*)<\/p>/sU', $content, $matches)) $ret["output"] = trim($matches[1]);
        if (preg_match('/<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0.*>.*<h1>Sample Input<\/h1>.*<\/table>/sU', $content, $matches)) $ret["sample_in"] = trim($matches[0]);
//        if (preg_match('', $content, $matches)) $ret["sample_out"] = trim($matches[1]);
        $ret["sample_out"] = "";
        if (preg_match('/<div id="problem_setter">.*Problem Setter:(.*)<\/div>/sU', $content, $matches)) $ret["source"] = trim(strip_tags($matches[1]));
        $ret["special_judge_status"] = 0;

        $ret = pcrawler_process_info($ret, "lightoj/$pid", "http://www.lightoj.com/",true,"/tmp/lightoj.cookie");
        $id = pcrawler_insert_problem($ret, "LightOJ", $pid);
        return "LightOJ $pid has been crawled as $id.<br>";
    }
    else{
        return "No problem called LightOJ $pid.<br>";
    }
    unlink("/tmp/lightoj.cookie");
}

function pcrawler_uestc($pid){
    $url = "http://acm.uestc.edu.cn/problem.php?pid=$pid";
    $content = file_get_contents($url);
    $ret = array();

    if (strpos($content, '<h2><center>ERROR!</center></h2>
') !== false) return "No problem called UESTC $pid.<br>";
    if (stripos($content, "Can not find problem") === false){
        if (preg_match('/<div id="big_title">.*<h2>(.*)<\/h2>/sU', $content, $matches)) $ret["title"] = trim($matches[1]);
        if (preg_match('/<h3>Time Limit: <span class="h4">(.*)ms<\/span>/sU', $content, $matches)) $ret["time_limit"] = intval(trim($matches[1]));
        $ret["case_time_limit"] = $ret["time_limit"];
        if (preg_match('/Memory Limit: <span class="h4">(.*)kB<\/span>/sU', $content, $matches)) $ret["memory_limit"] = intval(trim($matches[1]));
        if (preg_match('/<h2>Description<\/h2>.*<div class="bg">.*<\/div>(.*)<div class="bg">.*<\/div>/sU', $content, $matches)) $ret["description"] = trim($matches[1]);
        if (preg_match('/<h2>Input<\/h2>.*<div class="bg">.*<\/div>(.*)<div class="bg">.*<\/div>/sU', $content, $matches)) $ret["input"] = trim($matches[1]);
        if (preg_match('/<h2>Output<\/h2>.*<div class="bg">.*<\/div>(.*)<div class="bg">.*<\/div>/sU', $content, $matches)) $ret["output"] = trim($matches[1]);
        if (preg_match('/<h2>Sample Input<\/h2>.*<div class="bg">.*<\/div>(.*)<div class="bg">.*<\/div>/sU', $content, $matches)) $ret["sample_in"] = trim($matches[1]);
        if (preg_match('/<h2>Sample Output<\/h2>.*<div class="bg">.*<\/div>(.*)<div class="bg">.*<\/div>/sU', $content, $matches)) $ret["sample_out"] = trim($matches[1]);
        if (preg_match('/<h2>Source<\/h2>.*<div class="bg">.*<\/div>(.*)<div class="bg">.*<\/div>/sU', $content, $matches)) $ret["source"] = trim(strip_tags($matches[1]));
        if (preg_match('/<h2>Hint<\/h2>.*<div class="bg">.*<\/div>(.*)<div class="bg">.*<\/div>/sU', $content, $matches)) $ret["hint"] = trim(strip_tags($matches[1]));
        if (strpos($content, '') !== false) $ret["special_judge_status"] = 1;
        else $ret["special_judge_status"] = 0;

        $ret = pcrawler_process_info($ret, "uestc", "http://acm.uestc.edu.cn/");
        $id = pcrawler_insert_problem($ret, "UESTC", $pid);
        return "UESTC $pid has been crawled as $id.<br>";
    }
    else{
        return "No problem called UESTC $pid.<br>";
    }
}


function pcrawler_ural($pid){
    $url = "http://acm.timus.ru/problem.aspx?space=1&num=$pid";
    $content = file_get_contents($url);
    $ret = array();

    if (strpos($content, '<DIV STYLE="color:Red; text-align:center;">Problem not found</DIV>') !== false) return "No problem called ural $pid.<br>";
    if (stripos($content, "Can not find problem") === false){
        if (preg_match('/<H2 class="problem_title">.*\. (.*)<\/H2>/sU', $content, $matches)) $ret["title"] = trim($matches[1]);
        if (preg_match('/<DIV class="problem_limits">Time limit: (.*) second<BR>/sU', $content, $matches)) $ret["time_limit"] = intval(doubleval(trim($matches[1])) * 1000);
        $ret["case_time_limit"] = $ret["time_limit"];
        if (preg_match('/<DIV class="problem_limits">.*<BR>Memory limit: (.*) MB<BR>/sU', $content, $matches)) $ret["memory_limit"] = intval(trim($matches[1])) * 1024;
        if (preg_match('/<DIV ID="problem_text">(.*)<H3 CLASS="problem_subtitle">Input<\/H3>/sU', $content, $matches)) $ret["description"] = trim($matches[1]);
        if (preg_match('/<H3 CLASS="problem_subtitle">Input<\/H3>(.*)<H3 CLASS="problem_subtitle">Output<\/H3>/sU', $content, $matches)) $ret["input"] = trim($matches[1]);
        if (preg_match('/<H3 CLASS="problem_subtitle">Output<\/H3>(.*)<H3 CLASS="problem_subtitle">Sample/sU', $content, $matches)) $ret["output"] = trim($matches[1]);
        if (preg_match('/<TABLE CLASS="sample">.*<\/TABLE>/sU', $content, $matches)) $ret["sample_in"] = trim($matches[0]);
        $ret["sample_out"] = "";
        if (preg_match('/<B>Problem Source: <\/B>(.*)<BR>/sU', $content, $matches)) $ret["source"] = trim(strip_tags($matches[1]));
        $ret["special_judge_status"] = 0;

        $ret = pcrawler_process_info($ret, "ural", "http://acm.ural.edu.cn/");
        $id = pcrawler_insert_problem($ret, "Ural", $pid);
        return "ural $pid has been crawled as $id.<br>";
    }
    else{
        return "No problem called ural $pid.<br>";
    }
}


?>
