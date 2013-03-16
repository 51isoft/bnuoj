<?php
$name1=$_GET['name1'];
$name2=$_GET['name2'];
$pagetitle="Compare ".$name1." and ".$name2;
include("header.php");
$name1=$_GET['name1'];
$name2=$_GET['name2'];
$ac1=mysql_query("select distinct pid from status where username='$name1' and result='Accepted' order by pid");
$total1=mysql_query("select distinct pid from status where username='$name1' order by pid");
$ac2=mysql_query("select distinct pid from status where username='$name2' and result='Accepted' order by pid");
$total2=mysql_query("select distinct pid from status where username='$name2' order by pid");
$numt1=$numt2=0;
while (list($temp)=@mysql_fetch_array($ac1)) $mapa1[$temp]=true;
while (list($temp)=@mysql_fetch_array($ac2)) $mapa2[$temp]=true;
while (list($temp)=@mysql_fetch_array($total1)) {
	$pidt1[$numt1++]=$temp;
	$mapt1[$temp]=true;
}
while (list($temp)=@mysql_fetch_array($total2)) {
	$pidt2[$numt2++]=$temp;
	$mapt2[$temp]=true;
}
$nboth=$nonly1=$nonly2=$ntbf1=$ntbf2=$natbf=0;
$i=$j=0;
while ($i<$numt1||$j<$numt2)
{
	if ($i>=$numt1) {
		while ($j<$numt2) {
			if ($mapa2[$pidt2[$j]]==true) $only2[$nonly2++]=$pidt2[$j]; else $tbf2[$ntbf2++]=$pidt2[$j];
			$j++;
		}
		break;
	}
	if ($j>=$numt2) {
		while ($i<$numt1) {
			if ($mapa1[$pidt1[$i]]==true) $only1[$nonly1++]=$pidt1[$i]; else $tbf1[$ntbf1++]=$pidt1[$i];
			$i++;
		}
		break;
	}
	if ($pidt1[$i]==$pidt2[$j]) {
		if ($mapa1[$pidt1[$i]]==true&&$mapa2[$pidt2[$j]]==true) $both[$nboth++]=$pidt1[$i];
		else if ($mapa1[$pidt1[$i]]==true&&$mapa2[$pidt2[$j]]==false) {
			$only1[$nonly1++]=$pidt1[$i];
			$tbf2[$ntbf2++]=$pidt2[$j];
		}
		else if ($mapa1[$pidt1[$i]]==false&&$mapa2[$pidt2[$j]]==true) {
			$only2[$nonly2++]=$pidt2[$j];
			$tbf1[$ntbf1++]=$pidt1[$i];
		}
		else $atbf[$natbf++]=$pidt1[$i];
		$i++;$j++;
	}
	else if ($pidt1[$i]<$pidt2[$j]) {
		if ($mapa1[$pidt1[$i]]==true) $only1[$nonly1++]=$pidt1[$i]; else $tbf1[$ntbf1++]=$pidt1[$i];
		$i++;
	}
	else {
		if ($mapa2[$pidt2[$j]]==true) $only2[$nonly2++]=$pidt2[$j]; else $tbf2[$ntbf2++]=$pidt2[$j];
		$j++;
	}
}
?>
<center>
<br>
<form action='compare.php' method=get>
  <table width="40%" class='status'>
    <tr><td class='status'>
      Compare <input type='text' style='width:100px;height:24px;font:14px' name='name1' value='<?php echo $name1; ?>'> And <input type='text' style='width:100px;height:24px;font:14px' name='name2' value='<?php echo $name2; ?>'> <input type='submit' size=10 value='Go'>
    </td></tr>
  </table>
</form>
<table width=45%>
<?php
echo "<caption style='font-size:22px;'><a href='userinfo.php?name=$name1' style='font-size:22px;'>$name1</a> VS <a href='userinfo.php?name=$name2' style='font-size:22px;'>$name2</a></caption>";
echo "<tr>";
echo "<th>Problems Only <a href='userinfo.php?name=$name1'>$name1</a> Accepted:</th>";
echo "</tr>";
echo "<tr>";
echo "<td>";
for ($i=0;$i<$nonly1;$i++)
	if (!$mapt2[$only1[$i]]) echo "<a href='problem_show.php?pid=$only1[$i]'>$only1[$i]</a>&nbsp; ";
	else echo "<a href='problem_show.php?pid=$only1[$i]' style='color:red;'>$only1[$i]</a>&nbsp; ";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<th>Problems Only <a href='userinfo.php?name=$name2'>$name2</a> Accepted:</th>";
echo "</tr>";
echo "<tr>";
echo "<td>";
for ($i=0;$i<$nonly2;$i++) 
	if (!$mapt1[$only2[$i]]) echo "<a href='problem_show.php?pid=$only2[$i]'>$only2[$i]</a>&nbsp; ";
	else echo "<a href='problem_show.php?pid=$only2[$i]' style='color:red;'>$only2[$i]</a>&nbsp; ";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<th>Problems Both <a href='userinfo.php?name=$name1'>$name1</a> And <a href='userinfo.php?name=$name2'>$name2</a> Accepted:</th>";
echo "</tr>";
echo "<tr>";
echo "<td>";
for ($i=0;$i<$nboth;$i++) echo "<a href='problem_show.php?pid=$both[$i]'>$both[$i]</a>&nbsp; ";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<th>Problems <a href='userinfo.php?name=$name1'>$name1</a> Tried But Failed:</th>";
echo "</tr>";
echo "<tr>";
echo "<td>";
for ($i=0;$i<$ntbf1;$i++) echo "<a href='problem_show.php?pid=$tbf1[$i]'>$tbf1[$i]</a>&nbsp; ";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<th>Problems <a href='userinfo.php?name=$name2'>$name2</a> Tried But Failed:</th>";
echo "</tr>";
echo "<tr>";
echo "<td>";
for ($i=0;$i<$ntbf2;$i++) echo "<a href='problem_show.php?pid=$tbf2[$i]'>$tbf2[$i]</a>&nbsp; ";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<th>Problems Both <a href='userinfo.php?name=$name1'>$name1</a> And <a href='userinfo.php?name=$name2'>$name2</a> Tried But Failed:</th>";
echo "</tr>";
echo "<tr>";
echo "<td>";
for ($i=0;$i<$natbf;$i++) echo "<a href='problem_show.php?pid=$atbf[$i]'>$atbf[$i]</a>&nbsp; ";
echo "</td>";
echo "</tr>";
?>
</table>
</center>
<?php
include("footer.php");
?>
