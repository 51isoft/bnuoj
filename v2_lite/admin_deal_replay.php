<?php
include("conn.php");
require_once 'ext/excel_reader2.php';
require_once 'ext/simple_html_dom.php';
include("ext/replays.php");

foreach ($_POST as $key => $value) {
    $_POST[$key]=convert_str($_POST[$key]);
}

if (is_numeric($_POST['sfreq'])) $sfreq=intval($_POST['sfreq']);
if ($sfreq==""||$sfreq<10) $sfreq=10;

$pnum=$mcid=0;

if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
    if ($_POST['name']=="") {
        echo "No Contest Name!";
        exit;
    }

    $pnum=0;
    while ($_POST['pid'.$pnum]!="") {
        if (!db_problem_exist($_POST['pid'.$pnum])) {
            echo "Invalid Problem ID ".$_POST['pid'.$pnum].".";
            exit;
        }
        $pnum++;
    }
    $sttime=strtotime($_POST['start_time']);
    $edtime=strtotime($_POST['end_time']);
    $result = mysql_query("SHOW TABLE STATUS LIKE 'contest'");
    $row = mysql_fetch_array($result);

    $mcid = $row['Auto_increment'];

    if ($_POST["ctype"]=="hdu") {
        $filename="replay_cid_".$mcid.".xls";
        movefile($filename);
        $data = new Spreadsheet_Excel_Reader("uploadstand/" . $filename);
        if ($pnum!=$data->colcount()-4) {
            echo "Expected ".($data->colcount()-4)." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_hdu($data,$pnum);
    }
    else if ($_POST["ctype"]=="licstar") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("#standings",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-6;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_licstar($standtable);
    }
    else if ($_POST["ctype"]=="ctu") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        if ($html->find("meta",0)==null) $standtable=$html->find("table",0);
        else $standtable=$html->find("table table",0);
        $nprob=strlen($_POST['extrainfo']);
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_ctu($standtable);
    }
    else if ($_POST["ctype"]=="ural") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table.monitor",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-5;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_ural($standtable);
    }
    if ($_POST["ctype"]=="zju") {
        $filename="replay_cid_".$mcid.".xls";
        movefile($filename);
        $data = new Spreadsheet_Excel_Reader("uploadstand/" . $filename);
        if ($pnum!=$data->colcount()-5) {
            echo "Expected ".($data->colcount()-5)." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_zju($data,$pnum);
    }
    echo "Successfully Added.";
}

?>
