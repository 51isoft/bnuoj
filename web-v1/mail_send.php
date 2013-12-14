<?php
	include_once("conn.php");
    $pagetitle="Send Mail";
	$reply=$_GET['reply'];
	if($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)){
		include("header.php");
		echo "<p class='warn'>Please login first.</p>";
		//echo "<script>window.location ='index.php';</script>";
	}
	else{
		include("header.php");
		list($rec,$tit,$cont)=mysql_fetch_array(mysql_query("select sender,title,content from mail where mailid='$reply'"));
		if ($tit!="") $tit="RE: ".$tit;
		if ($cont!="") {
			$cont=str_replace("\r","",$cont);
			$cont=str_replace("\n","\n&gt; ",$cont);
			$cont="&gt; ".$cont."\n\n";
		}
?>

<center>
<form name=mail action=mail_send_result.php method=post>
<table width=60%>
<caption class=mail>Send Mail</caption>
<tr><td>Reciever:<input name=reciever value='<?php echo $rec;?>'></input></td></tr>
<tr><td>Title:<input name=title size=100% value='<?php echo $tit;?>'></input></td></tr>
<tr><td>Content:</td></tr>
<tr><td><textarea name=content cols=100% rows=20 id="textareacontent"><?php echo $cont;?></textarea></td></tr>
<tr><td><input type=submit value=Send><input type=reset value=Reset></td></tr>
</table>
<script type="text/javascript" language="JavaScript">
document.getElementById('textareacontent').focus();
</script>

</form>

</center>

<?php
}
include("footer.php");
?>
