<?php
include("conn.php");
include("ext/simple_html_dom.php");
$oj=convert_str($_GET["oj"]);
$cid=convert_str($_GET["cid"]);
$res=array();

function getidbytitle($name,$oj) {
    $qname=convert_str($name);
    $pid=null;
    //echo strtr($name,"'","%");
    $name2=strtr($name,array("'"=>"%","\""=>"%"));
    list($pid)=mysql_fetch_array(mysql_query("select pid from problem where title='$qname' and vname='$oj'"));
    if ($pid==null) list($pid)=mysql_fetch_array(mysql_query("select pid from problem where title like '".$name2."' and vname='$oj'"));
    return $pid;
}

function getidbyid($id,$oj) {
    $pid=null;
    list($pid)=mysql_fetch_array(mysql_query("select pid from problem where vid='$id' and vname='$oj'"));
    return $pid;
}


function crawl_zju($cid) {
    global $res;
    $html=file_get_html("http://acm.zju.edu.cn/onlinejudge/showContestProblems.do?contestId=$cid");
    if ($html->find("div.message")!=null) {
        $res["result"]=1;
        return;
    }
    $titles=$html->find("table.list td.problemTitle font");
    for ($i=0;$i<sizeof($titles);$i++) {
        $tname=getidbytitle(trim($titles[$i]->innertext),"ZJU");
        if ($tname==null) {
            $res["result"]=1;
            return;
        }
        $res["vpid$i"]=$tname;
    }
    $html=file_get_html("http://acm.zju.edu.cn/onlinejudge/contestInfo.do?contestId=$cid");
    $sttime=trim(strstr($html->find(".dateLink",0)->plaintext,"(",true));
    $length=$html->find(".contestInfoTable tr", 3)->find("td",1)->plaintext;
    $edtime=date("Y-m-d H:i:s",strtotime($sttime)+strtotime($length)-time());
    $title=$html->find(".contestInfoTable tr", 1)->find("td",1)->plaintext;
    $res["start_time"]=$sttime;
    $res["end_time"]=$edtime;
    $res["name"]=$title;
    $res["description"]=$res["repurl"]="http://acm.zju.edu.cn/onlinejudge/showContestRankList.do?contestId=$cid";
    $res["ctype"]="zjuhtml";
    $res["result"]=0;
    $res["isvirtual"]=0;
}

function hustv_convert($oj) {
    if ($oj=="POJ") return "PKU";
    if ($oj=="ZOJ") return "ZJU";
    return $oj;
}

function crawl_hustv($cid) {
    global $res;
    $tuCurl=curl_init();
    curl_setopt($tuCurl,CURLOPT_URL,"http://acm.hust.edu.cn/vjudge/contest/view.action?cid=$cid");
    curl_setopt($tuCurl,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($tuCurl,CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($tuCurl,CURLOPT_USERAGENT,"BNUOJ, Orz Isun");
    $html=curl_exec($tuCurl);
    curl_close($tuCurl);
    $html=str_get_html($html);
    if ($html->find("#viewContest")==null) {
        $res["result"]=1;
        return;
    }
    $titles=$html->find("#viewContest td.center a");
    for ($i=0;$i<sizeof($titles);$i++) {
        //echo $titles[$i]->plaintext;
        $oj=strstr($titles[$i]->plaintext," ",true);
        $oj=hustv_convert($oj);
        $id=trim(strstr($titles[$i]->plaintext," "));
        $tname=getidbyid($id,$oj);
        if ($tname==null) {
            $res["result"]=1;
            return;
        }
        $res["vpid$i"]=$tname;
    }
    $sttime=date("Y-m-d H:i:s",$html->find("#overview tr",1)->find("td",1)->plaintext/1000);
    $edtime=date("Y-m-d H:i:s",$html->find("#overview tr",2)->find("td",1)->plaintext/1000);
    $title=trim($html->find("#contest_title",0)->plaintext);
    $res["start_time"]=$sttime;
    $res["end_time"]=$edtime;
    $res["name"]=$title;
    $res["description"]=$res["repurl"]="http://acm.hust.edu.cn:8080/judge/data/standing/$cid.json";
    $res["ctype"]="hustvjson";
    $res["result"]=0;
    $res["isvirtual"]=1;
}

function crawl_uestc($cid) {
    global $res;
    $html=file_get_html("http://acm.uestc.edu.cn/contest.php?cid=$cid");
    if ($html->find("div#login_all")!=null) {
        $res["result"]=1;
        return;
    }
    $titles=$html->find("div.list ul");
    for ($i=0;$i<sizeof($titles);$i++) {
        $title=$titles[$i]->find("li a",1);
//        echo $title;
        if ($title==null) continue;
        $tname=getidbytitle(trim($title->innertext),"UESTC");
        if ($tname==null) {
            $res["result"]=1;
            return;
        }
        $res["vpid$i"]=$tname;
    }
    $sttime=trim($html->find("#big_title span.h4",0)->plaintext);
    $edtime=trim($html->find("#big_title span.h4",1)->plaintext);
    $title=trim($html->find("#big_title h2",0)->plaintext);
    $res["start_time"]=$sttime;
    $res["end_time"]=$edtime;
    $res["name"]=$title;
    $res["description"]=$res["repurl"]="http://acm.uestc.edu.cn/contest_ranklist.php?cid=$cid";
    $res["ctype"]="uestc";
    $res["result"]=0;
    $res["isvirtual"]=0;
}

function crawl_uva($cid) {
    global $res;
    $html=file_get_html("http://uva.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=13&page=show_contest&contest=$cid");
    if ($html->find("h1")!=null) {
        $res["result"]=1;
        return;
    }
    $titles=$html->find("div.tabbertab table tr");
    for ($i=1;$i<sizeof($titles);$i++) {
        $title=$titles[$i]->find("td",1);
//        echo $title;
        if ($title==null) continue;
        $tname=getidbytitle(trim($title->innertext),"UVA");
        if ($tname==null) {
            $res["result"]=1;
            return;
        }
        $res["vpid".($i-1)]=$tname;
    }
    $sttime="";
    $edtime="";
    $title=trim($html->find("div.componentheading",0)->plaintext);
    $res["start_time"]=$sttime;
    $res["end_time"]=$edtime;
    $res["name"]=$title;
    $res["description"]=$res["repurl"]="http://uva.onlinejudge.org/index2.php?option=com_onlinejudge&Itemid=13&page=show_contest_standings&contest=$cid";
    $res["ctype"]="uva";
    $res["result"]=0;
    $res["isvirtual"]=0;
}


if ($oj=="ZJU") crawl_zju($cid);
if ($oj=="HUSTV") crawl_hustv($cid);
if ($oj=="UESTC") crawl_uestc($cid);
if ($oj=="UVA") crawl_uva($cid);

echo json_encode($res);

?>
