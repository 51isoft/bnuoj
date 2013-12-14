<?php
	include_once("conn.php");
	$pid = $_GET['pid'];
	$lab = $_GET['lable'];
	$cid = $_GET['cid'];
	if ($cid=="") $cid="0";
	if ($lab == "") { $lab = "A";}
	$query = "select isprivate from contest where cid='$cid'";
	$result = mysql_query($query);
	list($type) = @mysql_fetch_row($result);
	if($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)){
		include_once("header.php");
		echo "<p class='warn'>Please login first.</p>";
		//echo "<script>window.location ='index.php';</script>";
	}
	else if ($cid!="0"&&!db_contest_running($cid)) {
		include_once("header.php");
		echo "<p class='warn'>Contest Unavailable.</p>";
	}
	else if ($cid!="0"&&$type=="1"&&(!db_user_in_contest($cid,$nowuser)||!db_user_match($nowuser, $nowpass))) {
		include_once("header.php");
		echo "<p class='warn'>Private Contest, Please Login.</p>";
	}
	else{
		include_once("cheader.php");
?>
</table>
<?php include_once("cmenu.php"); ?>
<td>
<form action="contest_action.php" method="post">
<div align=center><br><font size=4 color=#333399>Submit Your Solution</font><br>
Username: <input name=user_id value=<?php echo "$nowuser";?> size=20 readonly="readonly" accesskey=u><br>
Contest ID:<input name=contest_id value=<?php echo "$cid";?> size=20 readonly="readonly" accesskey=u><br>
<?php echo "Problem ID:<input name=lable value=$lab size=20 accesskey=p readonly=readonly>" ?><br>
Language:<select size=1 name=language id=lang accesskey=l>
<option value=1 selected>GNU C++</option>
<option value=2>GNU C</option>
<option value=3>Oracle Java</option>
<option value=4>Free Pascal</option>
<option value=5>Python</option>
<option value=6>C# (Mono)</option>
<option value=7>Fortran</option>
<option value=8>Perl</option>
<option value=9>Ruby</option>
<option value=10>Ada</option>
<option value=11>SML</option>
<option value=12>Visual C++</option>
<option value=13>Visual C</option>
</select><br>
Source: <br>			
<textarea rows=30 name=source cols=70 accesskey=c onKeyUp="if(this.value.length > 32768) this.value=this.value.substr(0,32768)"></textarea><br>
<input type=submit value=Submit name=submit accesskey=s><input type=reset value=Reset name=reset>
</div>
</form>
</td>
<?php
    list($pid)=@mysql_fetch_array(mysql_query("select pid from contest_problem where cid=$cid and lable like '$lab'"));
    list($vid,$vname)=@mysql_fetch_array(mysql_query("select vid,vname from problem where pid=$pid"));
    if ($vid=="") $vid="0";
    echo "<script>adjustlist(\"$vid\",\"$vname\");</script>\n";
?>
<?php
}
include("footer.php");
?>
