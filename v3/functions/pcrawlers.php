<?php
include_once(dirname(__FILE__)."/global.php");
include_once(dirname(__FILE__)."/simple_html_dom.php");

$crawled=array();
function process_and_get_image($ori,$path,$baseurl) {
    $para["path"]=$path;$para["base"]=$baseurl;
    return preg_replace_callback("/< *img[^>]*src *= *[\"\\']?([^\"\\' >]*)[^>]*>/si",
                                function($matches) use ($para) {
                                    global $config,$crawled;
                                    $url=trim($matches[1]);
                                    if (stripos($url,"http://")===false&&stripos($url,"https://")===false) $url=$baseurl.$url;
                                    if ($crawled[$url]) return $result;
                                    $crawled[$url]=true;
                                    $name=basename($url);
                                    $name="images/".$para["path"]."/".strtr($name,":","_");

                                    $result=str_replace(trim($matches[1]),$name,$matches[0]);

                                    if (file_exists($config["base_local_path"].$name)) return $result;
                                    mkdirs($config["base_local_path"].$name);

                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                    curl_setopt($ch, CURLOPT_HEADER, 0);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $content = curl_exec($ch); 
                                    curl_close($ch);

                                    $fp = fopen($config["base_local_path"].$name, "wb");
                                    fwrite($fp, $content);
                                    fclose($fp);
                                    return $result;
                                } ,
                                $ori);
}

function pcrawler_process_info($ret,$path,$baseurl) {
    $ret["description"]=process_and_get_image($ret["description"],$path,$baseurl);
    $ret["input"]=process_and_get_image($ret["input"],$path,$baseurl);
    $ret["output"]=process_and_get_image($ret["output"],$path,$baseurl);
    $ret["hint"]=process_and_get_image($ret["hint"],$path,$baseurl);
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
        if (preg_match("/<th class=\"left\" style=\"width:100%;\">(.*)<\\/th>/sU", $content,$matches)) $ret["source"]=trim($matches[1]);
        $ret["special_judge_status"]=0;
        return $ret;
    }
    else return false;
}

function pcrawler_cf($cid) {
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


function pcrawler_cf_num() {
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

?>
