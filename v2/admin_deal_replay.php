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
    if ($_POST['start_time']==""||$_POST['end_time']=="")  {
        echo "Invalid Time!";
        exit;
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
        deal_hdu($data);
    }
    if ($_POST["ctype"]=="myexcel") {
        $filename="replay_cid_".$mcid.".xls";
        movefile($filename);
        $data = new Spreadsheet_Excel_Reader("uploadstand/" . $filename);
        if ($pnum!=$data->colcount()-1) {
            echo "Expected ".($data->colcount()-1)." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_myexcel($data);
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
        if ($html->find("meta",0)==null) $standtable=$html->find("table",1);
        else $standtable=$html->find("table table",0);
        $nprob=strlen($_POST['extrainfo']);
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
//        echo htmlspecialchars($standtable);die();
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
    else if ($_POST["ctype"]=="zju") {
        $filename="replay_cid_".$mcid.".xls";
        movefile($filename);
        $data = new Spreadsheet_Excel_Reader("uploadstand/" . $filename);
        if ($pnum!=$data->colcount()-5) {
            echo "Expected ".($data->colcount()-5)." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_zju($data);
    }
    else if ($_POST["ctype"]=="jhinv") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("#standings",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-7;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_jhinv($standtable);
    }
    else if ($_POST["ctype"]=="zjuhtml") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find(".list",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_zjuhtml($standtable);
    }
    else if ($_POST["ctype"]=="neerc") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table",1);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_neerc($standtable);
    }
    else if ($_POST["ctype"]=="2011shstatus") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=strlen($_POST['extrainfo']);
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_2011shstatus($standtable);
    }
    else if ($_POST["ctype"]=="icpcinfostatus") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=strlen($_POST['extrainfo']);
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_icpcinfostatus($standtable);
    }
    else if ($_POST["ctype"]=="pc2sum") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-5;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_pc2sum($standtable);
    }
    else if ($_POST["ctype"]=="fdulocal2012") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-10;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_fdulocal2012($standtable);
    }
    else if ($_POST["ctype"]=="uestc") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find(".ranktable table",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_uestc($standtable);
    }
    else if ($_POST["ctype"]=="hustvjson") {
        $filename="replay_cid_".$mcid.".json";
        movefile($filename);
        $html=file_get_contents("uploadstand/" . $filename);
        /*$standtable=$html->find(".ranktable table",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }*/
        addcontest();
        deal_hustvjson($html);
    }
    else if ($_POST["ctype"]=="fzuhtml") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_fzuhtml($standtable);
    }
    else if ($_POST["ctype"]=="usuhtml") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table",4);
        $nprob=sizeof($standtable->find("tr",0)->children())-7;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_usuhtml($standtable);
    }
    else if ($_POST["ctype"]=="sguhtml") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table",5);
        $nprob=sizeof($standtable->find("tr",0)->children())-5;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_sguhtml($standtable);
    }
    else if ($_POST["ctype"]=="amt2011") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=sizeof($standtable->find("tr",1)->children())-5;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_amt2011($standtable);
    }
    else if ($_POST["ctype"]=="nwerc") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table.scoreboard",0);
        $nprob=sizeof($standtable->find("tr",1)->children())-5;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_nwerc($standtable);
    }
    else if ($_POST["ctype"]=="ncpc") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table#standings",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_ncpc($standtable);
    }
    else if ($_POST["ctype"]=="uva") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table",1);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_uva($standtable);
    }
    else if ($_POST["ctype"]=="gcpc") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table.scoreboard",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_gcpc($standtable);
    }
    else if ($_POST["ctype"]=="phuket") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("ul#scoreBoard",0);
        $nprob=sizeof($standtable->find("div.problems",0)->children())-3;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_phuket($standtable);
    }
    else if ($_POST["ctype"]=="spacific") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-6;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_spacific($standtable);
    }
    else if ($_POST["ctype"]=="spoj") {
        $filename="replay_cid_".$mcid.".html";
        movefile($filename);
        $html=file_get_html("uploadstand/" . $filename);
        $standtable=$html->find("table.problems",0);
        $nprob=sizeof($standtable->find("tr",0)->children())-4;
        if ($nprob!=$pnum) {
            echo "Expected ".$nprob." problems, got $pnum . Add failed.";
            exit;
        }
        addcontest();
        deal_spoj($standtable);
    }
    echo "Successfully Added.";
}

?>
