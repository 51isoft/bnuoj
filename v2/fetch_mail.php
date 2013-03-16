<?php
	include_once("conn.php");
	$mailid = convert_str($_GET['mailid']);
	if($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)||!db_mail_user_match($nowuser,$mailid)){
		echo "Invalid Mail Request.";
	}
	else{
        $query="select sender,title,content,mail_time,reciever from mail where mailid='$mailid'";
        $res=mysql_query($query);
        list($sender,$title,$content,$mailtime,$reciever)=@mysql_fetch_array($res);
        echo "<div style='clear:both;margin:auto;font-weight:bold;text-align:center'><label id='mailtitle' style='display:none'>".htmlspecialchars($title)."</label>Sender: <label id='mailsender'>$sender</label>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;Time: $mailtime</div>\n";
        echo "<div class='center'><a href='javascript:void(0)' class='button replybutton' name='$mailid'>Reply</a></div>\n<div style='clear:both;text-align:left;border:1px solid #000'><pre id='mailcontent' style='margin:10px;font-size:14px'>".htmlspecialchars($content)."</pre></div>\n";
        echo "<div class='center'><a href='javascript:void(0)' class='button replybutton' name='$mailid'>Reply</a></div>";
        $query="update mail set status=true where mailid='$mailid'";
        if (strcasecmp($nowuser,trim($reciever))==0) $res=mysql_query($query);
    }
?>

