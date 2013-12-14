<?php
	include_once("conn.php");
	$start = $_GET['start'];
	if ($start=="") $start=0;
	if($nowuser== ""||$nowpass==""||!db_user_match($nowuser,$nowpass)){
		include("header.php");
		echo "<p class='warn'>Please login first.</p>";
		//echo "<script>window.location ='index.php';</script>";
	}
	else{
        $pagetitle="Mail List of ".$nowuser;
		include("header.php");
$query="select count(*) from mail where reciever='$nowuser'";
$res=mysql_query($query);
list($tot)=mysql_fetch_array($res);
$end=$start+$mailperpage;
?>
<center>

<table width=60% class=mail>

<caption class=mail>Mail List Of <?php echo $nowuser;?></caption>
<tr>
<th width=20% class=mail>Sender</th><th width=60% class=mail>Topic</th><th width=20% class=mail>Time</th>
</tr>

<?php
$query="select mailid,sender,title,mail_time,status from mail where reciever='$nowuser' order by mailid desc limit $start,$mailperpage";
$res=mysql_query($query);
while ($row=mysql_fetch_array($res)) {
$row[2]=str_replace(" ","&nbsp;",$row[2]);
echo "<tr><td width=20% class=mail><center><a href='userinfo.php?name=$row[1]'>".$row[1]."</a></center></td>";
if ($row[4]==true) echo "<td width=60% class=mail><a href='mail_show.php?mailid=$row[0]'>".change_in($row[2])."</a></td>";
else echo "<td width=60% class=mail><a href='mail_show.php?mailid=$row[0]'><strong>".change_in($row[2])."</strong></a></td>";
echo "<td width=20% class=mail><center>".$row[3]."</center></td></tr>";
}


?>
</table>
<a href='mail_send.php' class='bottom_link'>[Send Mail]</a>
<?php

if ($start!=0) {
	if ($start<$mailperpage) echo "<a href='mail_index.php?start=0' class='bottom_link'>[Previous]</a>";
	else {
		$prev=$start-$mailperpage;
		echo "<a href='mail_index.php?start=$prev' class='bottom_link'>[Previous]</a>";
	}
}
if ($end < $tot) {
	echo "<a href='mail_index.php?start=$end' class='bottom_link'>[Next]</a>";
}

?>

</center>
<?php
}
include("footer.php");
?>
