<?php
include_once(dirname(__FILE__)."/../functions/users.php");
include_once(dirname(__FILE__)."/../functions/problems.php");
require_once(dirname(__FILE__)."/../functions/excel_reader2.php");
require_once(dirname(__FILE__)."/../functions/simple_html_dom.php");
require_once(dirname(__FILE__)."/../functions/replays.php");

foreach ($_POST as $key => $value) {
    $_POST[$key]=convert_str($_POST[$key]);
}

if (is_numeric($_POST['sfreq'])) $sfreq=intval($_POST['sfreq']);
else $sfreq=10;
if ($sfreq==""||$sfreq<10) $sfreq=10;

$pnum=$mcid=0;

$ret=array();
$ret["code"]=1;

if ($current_user->is_root()) {
    if ($_POST['name']=="") {
        $ret["msg"]="No name!";
        die(json_encode($ret));
    }

    $pnum=0;
    while ($_POST['pid'.$pnum]!="") {
        if (!problem_exist($_POST['pid'.$pnum])) {
            $ret["msg"]="Invalid problem ID ".$_POST['pid'.$pnum].".";
            die(json_encode($ret));
        }
        $pnum++;
    }
    if ($_POST['start_time']==""||$_POST['end_time']=="")  {
        $ret["msg"]="Invalid Time!";
        die(json_encode($ret));
    }
    $sttime=strtotime($_POST['start_time']);
    $edtime=strtotime($_POST['end_time']);

    if ($sttime==""||$edtime=="") {
        $ret["msg"]="Invalid Time!";
        die(json_encode($ret));
    }

    $row = $db->get_row("SHOW TABLE STATUS LIKE 'contest'",ARRAY_A);
    $mcid = $row['Auto_increment'];

    if ($_POST["ctype"]=="hdu") {
        $filename="replay_cid_".$mcid.".xls";
        replay_move_uploaded_file($filename);
        $data = new Spreadsheet_Excel_Reader("../uploadstand/" . $filename);
        if ($pnum!=$data->colcount()-4) {
            $ret["msg"]="Expected ".($data->colcount()-4)." problems, got $pnum . Add failed.";
            die(json_encode($ret));
        }
        replay_add_contest();
        replay_deal_hdu($data);
    }
    if ($_POST["ctype"]=="myexcel") {
        $filename="replay_cid_".$mcid.".xls";
        replay_move_uploaded_file($filename);
        $data = new Spreadsheet_Excel_Reader("../uploadstand/" . $filename);
        if ($pnum!=$data->colcount()-1) {
            $ret["msg"]="Expected ".($data->colcount()-1)." problems, got $pnum . Add failed.";
            die(json_encode($ret));
        }
        replay_add_contest();
        replay_deal_myexcel($data);
    }
    else if ($_POST["ctype"]=="licstar") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("#standings",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-6;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_licstar($standtable);
    }
    else if ($_POST["ctype"]=="ctu") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        if ($html->find("meta",0)==null) $standtable=$html->find("table",1);
        else $standtable=$html->find("table table",0);
        $nprob=strlen($_POST['extrainfo']);
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
//        echo htmlspecialchars($standtable);die();
        replay_add_contest();
        replay_deal_ctu($standtable);
    }
    else if ($_POST["ctype"]=="ural") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table.monitor",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-5;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_ural($standtable);
    }
    else if ($_POST["ctype"]=="zju") {
        $filename="replay_cid_".$mcid.".xls";
        replay_move_uploaded_file($filename);
        $data = new Spreadsheet_Excel_Reader("../uploadstand/" . $filename);
        if ($pnum!=$data->colcount()-5) {
            $ret["msg"]="Expected ".($data->colcount()-5)." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_zju($data);
    }
    else if ($_POST["ctype"]=="jhinv") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("#standings",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-7;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_jhinv($standtable);
    }
    else if ($_POST["ctype"]=="zjuhtml") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find(".list",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_zjuhtml($standtable);
    }
    else if ($_POST["ctype"]=="neerc") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table",1);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_neerc($standtable);
    }
    else if ($_POST["ctype"]=="2011shstatus") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=strlen($_POST['extrainfo']);
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_2011shstatus($standtable);
    }
    else if ($_POST["ctype"]=="icpcinfostatus") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=strlen($_POST['extrainfo']);
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_icpcinfostatus($standtable);
    }
    else if ($_POST["ctype"]=="pc2sum") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-5;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_pc2sum($standtable);
    }
    else if ($_POST["ctype"]=="fdulocal2012") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-10;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_fdulocal2012($standtable);
    }
    else if ($_POST["ctype"]=="uestc") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find(".ranktable table",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_uestc($standtable);
    }
    else if ($_POST["ctype"]=="hustvjson") {
        $filename="replay_cid_".$mcid.".json";
        replay_move_uploaded_file($filename);
        $html=file_get_contents("../uploadstand/" . $filename);
        replay_add_contest();
        replay_deal_hustvjson($html);
    }
    else if ($_POST["ctype"]=="fzuhtml") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_fzuhtml($standtable);
    }
    else if ($_POST["ctype"]=="usuhtml") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table",4);
        $nprob=sizeof($standtable->find("tr",0)->children())-7;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_usuhtml($standtable);
    }
    else if ($_POST["ctype"]=="sguhtml") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table",5);
        $nprob=sizeof($standtable->find("tr",0)->children())-5;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_sguhtml($standtable);
    }
    else if ($_POST["ctype"]=="amt2011") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=sizeof($standtable->find("tr",1)->children())-5;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_amt2011($standtable);
    }
    else if ($_POST["ctype"]=="nwerc") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table.scoreboard",0);
        $nprob=sizeof($standtable->find("tr",1)->children())-5;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_nwerc($standtable);
    }
    else if ($_POST["ctype"]=="ncpc") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table#standings",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_ncpc($standtable);
    }
    else if ($_POST["ctype"]=="uva") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table",1);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_uva($standtable);
    }
    else if ($_POST["ctype"]=="gcpc") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table.scoreboard",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_gcpc($standtable);
    }
    else if ($_POST["ctype"]=="phuket") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("ul#scoreBoard",0);
        $nprob=sizeof($standtable->find("div.problems",0)->children())-3;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_phuket($standtable);
    }
    else if ($_POST["ctype"]=="spacific") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-6;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_spacific($standtable);
    }
    else if ($_POST["ctype"]=="spoj") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table.problems",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_spoj($standtable);
    }
    else if ($_POST["ctype"]=="openjudge") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_openjudge($standtable);
    }
    else if ($_POST["ctype"]=="scu") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=sizeof($standtable->find("tr",1)->children())-5;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_scu($standtable);
    }
    else if ($_POST["ctype"]=="hust") {
        $filename="replay_cid_".$mcid.".html";
        replay_move_uploaded_file($filename);
        $html=file_get_html("../uploadstand/" . $filename);
        $standtable=$html->find("table",1);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            $ret["msg"]="Expected ".$nprob." problems, got $pnum . Add failed.";
            die(json_encode($ret));   
        }
        replay_add_contest();
        replay_deal_hust($standtable);
    }
    $ret["code"]=0;
    $ret["msg"]="Successfully Added.";
}
else $ret["msg"]="Please login as root!";
echo json_encode($ret);
?>
