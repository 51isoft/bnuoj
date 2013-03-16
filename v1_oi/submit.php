<?php
	include_once("conn.php");
    $pagetitle="提交代码";
	$pid = $_GET['pid'];
	if ($pid=="") $pid="1000";
	if($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)){
		include("header.php");
		echo "<p class='warn'>Please login first.</p>";
		//echo "<script>window.location ='index.php';</script>";
	}
	else{
		include("header.php");
?>
<td>
<form action="action.php" method="post">
<div align=center><br><font size=4 color=#333399>提交代码</font><br>
用户名: <input name=user_id value=<?php echo "$nowuser";?> size=20 readonly="readonly" accesskey=u><br>
<?php echo "题号:<input name=problem_id value=$pid size=20 accesskey=p>" ?><br>
语言:<select size=1 name=language accesskey=l>
<option value=1>G++</option>
<option value=2>GCC</option>
<option value=3>Java</option>
<option value=4 selected>Pascal</option>
</select>
<br>源代码: <br>			
<textarea rows=30 name=source cols=70 accesskey=c onKeyUp="if(this.value.length > 32768) this.value=this.value.substr(0,32768)"></textarea><br>
<input type=submit value=提交 name=submit accesskey=s><input type=reset value=清空 name=reset>
</div>
</form>
</td>
<?php
}
include("footer.php");
?>
