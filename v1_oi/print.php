<?php
	include_once("conn.php");
	if($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)){
		include("header.php");
		echo "<p class='warn'>Please login first.</p>";
		//echo "<script>window.location ='index.php';</script>";
	}
	else{
		include("header.php");
?>
<td>
<form action="print_action.php" method="post">
<div align=center><br><font size=4 color=#333399>打印页面:</font><br>
队伍名: <input name=user_id value=<?php echo "$nowuser";?> size=20 readonly="readonly" accesskey=u><br>
比赛: <br>			
<textarea rows=30 name=content cols=70 accesskey=c onKeyUp="if(this.value.length > 65536) this.value=this.value.substr(0,65536)"></textarea><br>
<input type=submit value=Submit name=submit accesskey=s><input type=reset value=Reset name=reset>
</div>
</form>
</td>
<?php
}
include("footer.php");
?>
