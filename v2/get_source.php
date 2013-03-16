<?php
	include_once("conn.php");
    //if (!db_user_match($nowuser,$nowpass)||!db_user_isroot($nowuser)) {echo "<div style='clear:both'><h2>Temporarily forbidden.</h2></div>\n";die();}
	$runid = convert_str($_GET['runid']);
	$query="select result,memory_used,time_used,username,source,language,pid,isshared,contest_belong from status where runid='$runid'";
	$result = mysql_query($query);
	list($res,$mu,$tu,$uname,$sour,$lang,$pid,$isshared,$cid)=mysql_fetch_row($result);
	$sour=htmlspecialchars($sour);
	if ($nowuser!= ""&&$nowpass!=""&&db_user_match($nowuser,$nowpass)&&(($isshared==TRUE&&($cid=="0"||db_contest_passed($cid)))||strcasecmp($nowuser,$uname)==0||db_user_iscodeviewer($nowuser))) {
//    if (db_user_iscodeviewer($nowuser)) {
        echo "<div style='clear:both;margin:auto;font-weight:bold;text-align:center'>Result: $res &nbsp;&nbsp;&nbsp; Memory Used: $mu KB &nbsp;&nbsp;&nbsp; Time Used: $tu ms </div>\n";
//        echo "<input type='hidden' id='dealrunid' value='$runid' />";
        $shjs=match_shjs($lang);
        $lang=match_lang($lang);
        echo "<div style='clear:both;margin:auto;font-weight:bold;text-align:center'>Language: $lang &nbsp;&nbsp;&nbsp; User Name: $uname &nbsp;&nbsp;&nbsp; Problem ID: $pid <div>\n";
        if ($nowuser!= ""&&$nowpass!=""&&db_user_match($nowuser,$nowpass)&&(strcasecmp($nowuser,$uname)==0||db_user_isroot($nowuser))) {
            if ($isshared) echo '<div style="clear:both;margin:auto;text-align:center">Share Code? &nbsp;&nbsp;&nbsp;&nbsp; <input name="tisshare" type="radio" style="width:16px" value="1" checked="checked" />Yes &nbsp;&nbsp;&nbsp;&nbsp; <input name="tisshare" value="0" type="radio" style="width:16px" />No</div>';
            else echo '<div style="clear:both;margin:auto;text-align:center">Share Code? &nbsp;&nbsp;&nbsp;&nbsp; <input name="tisshare" type="radio" style="width:16px" value="1" />Yes &nbsp;&nbsp;&nbsp;&nbsp; <input name="tisshare" value="0" type="radio" style="width:16px" checked="checked" />No</div>';
        }
        if ($isshared) echo "<div style='clear:both;margin:auto;color:blue;font-weight:bold;text-align:center' id='sharenote'><b>This code is shared.</b></div>\n";
        else echo "<div style='clear:both;margin:auto;color:blue;font-weight:bold;text-align:center;display:none' id='sharenote'><b>This code is shared.</b></div>\n";
		echo "<div style='clear:both;text-align:left'><pre id='source_code' class='$shjs'>";
		echo $sour;
		echo "</pre></div>\n";
	}
	else {
		echo "<div style='clear:both'><h2>Permission denined.</h2></div>\n";
	}
?>
