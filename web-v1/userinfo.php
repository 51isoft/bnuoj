<?php
	$name = $_GET['name'];
    $pagetitle=$name."'s Information";
    include("header.php");
    $name = $_GET['name'];
	$query="select username,nickname,school,email,register_time,last_login_time,ipaddr from user where username='$name'";
	$result=mysql_query($query);
	$arr = mysql_fetch_array($result);
?>
<center>
<br>
<form action='compare.php' method=get>
  <table width="40%" class='status'>
    <tr><td class='status'>
      Compare <input type='text' style='width:100px;height:24px;font:14px' name='name1' value='<?php echo $name; ?>'> And <input type='text' style='width:100px;height:24px;font:14px' name='name2' value='<?php echo $nowuser; ?>'> <input type='submit' size=10 value='Go'>
    </td></tr>
  </table>
</form>
<script>
window.alert=function(){};
document.write=function(){};
</script>
  <table width="40%">
      <td>Username: </td><td><?php echo"$arr[0]";?></td>
    </tr>
    <tr>
      <td>Nickname: </td><td><?php echo change_out_nick($arr[1]);?></td>
    </tr>
    <tr>
      <td>School: </td><td><?php echo"$arr[2]";?></td>
    </tr>
    <tr>
      <td>Email: </td><td><?php echo"$arr[3]";?></td>
    </tr>
    <tr>
      <td>Register Time: </td><td><?php echo"$arr[4]";?></td>
    </tr>
    <tr>
      <td>Last Login Time: </td><td><?php echo"$arr[5]";?></td>
    </tr>
    <tr>
      <td>Accepted: </td><td>
<?php
$query=mysql_query("select distinct pid from status where result='Accepted' and username='$arr[0]' order by pid");
while ($result=@mysql_fetch_row($query)) echo "<a href='problem_show.php?pid=$result[0]'>$result[0]</a>&nbsp; ";
?>

</td>
    </tr>
    <?php
    if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
    	echo "<tr><td>Last Login IP: </td><td>$arr[6]</td></tr>";
    }
    ?>
  </table>
<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript">
swfobject.embedSWF("open-flash-chart.swf", "my_chart", "40%", "260", "9.0.0", "expressInstall.swf", {"data-file":"user_data_echo.php?name=<?php echo $name; ?>"} );
</script>
<div id="my_chart"></div>
<h3>Neighbours</h3>
<table width="40%">
<tr>
<th>Rank</th><th>Username</th><th>Accepts</th><th>Submits</th>
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
