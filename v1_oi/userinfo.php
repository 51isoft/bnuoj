<?php
	$name = $_GET['name'];
    $pagetitle=$name." 的信息";
    include("header.php");
	$query="select username,nickname,school,email,register_time,last_login_time,ipaddr from user where username='$name'";
	$result=mysql_query($query);
	$arr = mysql_fetch_array($result);
?>
<center>
<br>
<form action='compare.php' method=get>
  <table width="40%" class='status'>
    <tr><td class='status'>
      比较 <input type='text' style='width:100px;height:24px;font:14px' name='name1' value='<?php echo $name; ?>'> 和 <input type='text' style='width:100px;height:24px;font:14px' name='name2' value='<?php echo $nowuser; ?>'>的做题数据 <input type='submit' size=10 value='开始比较'>
    </td></tr>
  </table>
</form>
  <table width="40%">
      <td>用户名: </td><td><?php echo"$arr[0]";?></td>
    </tr>
    <tr>
      <td>昵称: </td><td><?php echo"$arr[1]";?></td>
    </tr>
    <tr>
      <td>学校: </td><td><?php echo"$arr[2]";?></td>
    </tr>
    <tr>
      <td>邮箱: </td><td><?php echo"$arr[3]";?></td>
    </tr>
    <tr>
      <td>注册时间: </td><td><?php echo"$arr[4]";?></td>
    </tr>
    <tr>
      <td>上次登陆时间: </td><td><?php echo"$arr[5]";?></td>
    </tr>
    <tr>
      <td>做对的题目: </td><td>
<?php
$query=mysql_query("select distinct pid from status where result='Accepted' and username='$arr[0]' order by pid");
while ($result=@mysql_fetch_row($query)) echo "<a href='problem_show.php?pid=$result[0]'>$result[0]</a>&nbsp; ";
?>

</td>
    </tr>
    <?php
    if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
    	echo "<tr><td>上次登陆IP: </td><td>$arr[6]</td></tr>";
    }
    ?>
  </table>
<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript">
swfobject.embedSWF("open-flash-chart.swf", "my_chart", "40%", "260", "9.0.0", "expressInstall.swf", {"data-file":"user_data_echo.php?name=<?php echo $name; ?>"} );
</script>
<div id="my_chart"></div>
<h3>排名相似者列表</h3>
<table width="40%">
<tr>
<th>排名</th><th>用户名</th><th>通过题数</th><th>提交数</th>
</tr>
<?php
	list($total_user)=mysql_fetch_array(mysql_query("select count(*) from ranklist"));
	list($tac,$ts,$tuid)=mysql_fetch_array(mysql_query("select total_ac,total_submit,uid from ranklist where username='$name'"));
	list($rankq)=mysql_fetch_array(mysql_query("select count(*) from ranklist where total_ac>$tac or (total_ac=$tac and total_submit<$ts) or (total_ac=$tac and total_submit=$ts and uid<=$tuid)"));
	if ($rankq-4<0) $st=0; else $st=$rankq-4;
	if ($rankq+2>$total_user-1) $fi=$total_user-1;
	else $fi=$rankq+2;
	$crank=$st+1;
	$num=$fi-$st+1;
	$rankres=mysql_query("select * from ranklist limit $st,$num");
	while ($rankrow=mysql_fetch_array($rankres)) {
		if ($crank!=$rankq) {
			echo "<tr><td><center>$crank</center></td>";
			echo "<td><center><a href='userinfo.php?name=$rankrow[1]'>$rankrow[1]</a></center></td>";
			echo "<td><center><a href=status.php?showname=$rankrow[1]&showres=Accepted>$rankrow[3]</a></center></td>";
			echo "<td><center><a href=status.php?showname=$rankrow[1]>$rankrow[4]</a></center></td></tr>";
		}
		else {
			echo "<tr><td><center><strong>$crank</strong></center></td>";
			echo "<td><center><a href='userinfo.php?name=$rankrow[1]'><strong>$rankrow[1]</strong></a></center></td>";
			echo "<td><center><a href=status.php?showname=$rankrow[1]&showres=Accepted><strong>$rankrow[3]</strong></a></center></td>";
			echo "<td><center><a href=status.php?showname=$rankrow[1]><strong>$rankrow[4]</strong></a></center></td></tr>";
		}
		$crank++;
	}
?>
</table>
</center>
<?php
include("footer.php");
?>
