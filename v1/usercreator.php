<?php
include("header.php");
$check=$_GET['check'];
if ($check=="idontbelieveyoucanknowitcanyou1") {
echo "<table>";
$availchar="abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789";
$length=strlen($availchar)-1;
$pass="01234567";
srand(time());
echo "<tr><th>Username:</th><th>Password:</th><th>Asterisk?</tr>";
$cnt=0;
for ($i=1;$i<=100;$i++) {
    if ($i<10) $num="00".$i;
    else if ($i<100) $num="0".$i;
    else $num=$i;
    $user="Friend2011-".$num;
    for ($j=0;$j<8;$j++) $pass[$j]=$availchar[rand(0,$length)];
    echo "<tr><td><center>$user</center></td><td><center>$pass</center></td>";
/*	$tmp=rand(0,100);
    if ($tmp>75&&$cnt<30) echo "<td><center>*</center></td>";
	else */echo "<td></td>";
	echo "</tr>";
    $row[0] = $user;
    $row[1] = $pass;
/*	if ($tmp>75&&$cnt<30) {
        $row[2]="*".$user;
        $cnt++;
    }
    else*/ $row[2]= $user;
//    db_user_insert($row);
}
echo "<tr><td>Total</td><td>$cnt</td></tr>";
/*for ($i=1;$i<=30;$i++) {
    if ($i<10) $num="00".$i;
    else if ($i<100) $num="0".$i;
    else $num=$i;
    $user="bnuep10-".$num;
    for ($j=0;$j<8;$j++) $pass[$j]=$availchar[rand(0,$length)];
    echo "<tr><td><center>$user</center></td><td><center>$pass</center></td></tr>";
    $row[0] = $user;
    $row[1] = $pass;
    $row[2] = $user;    $row[3] = "BNUEP";
    db_user_insert($row);
}
for ($i=1;$i<=20;$i++) {
    if ($i<10) $num="00".$i;
    else if ($i<100) $num="0".$i;
    else $num=$i;
    $user="buaa10-".$num;
    for ($j=0;$j<8;$j++) $pass[$j]=$availchar[rand(0,$length)];
    echo "<tr><td><center>$user</center></td><td><center>$pass</center></td></tr>";
    $row[0] = $user;
    $row[1] = $pass;
    $row[2] = $user;    $row[3] = "BUAA";
    db_user_insert($row);
}
for ($i=1;$i<=20;$i++) {
    if ($i<10) $num="00".$i;
    else if ($i<100) $num="0".$i;
    else $num=$i;
    $user="bit10-".$num;
    for ($j=0;$j<8;$j++) $pass[$j]=$availchar[rand(0,$length)];
    echo "<tr><td><center>$user</center></td><td><center>$pass</center></td></tr>";
    $row[0] = $user;
    $row[1] = $pass;
    $row[2] = $user;    $row[3] = "BIT";
    db_user_insert($row);
}
for ($i=1;$i<=20;$i++) {
    if ($i<10) $num="00".$i;
    else if ($i<100) $num="0".$i;
    else $num=$i;
    $user="cugb10-".$num;
    for ($j=0;$j<8;$j++) $pass[$j]=$availchar[rand(0,$length)];
    echo "<tr><td><center>$user</center></td><td><center>$pass</center></td></tr>";
    $row[0] = $user;
    $row[1] = $pass;
    $row[2] = $user;    $row[3] = "CUGB";
    db_user_insert($row);
}
for ($i=1;$i<=20;$i++) {
    if ($i<10) $num="00".$i;
    else if ($i<100) $num="0".$i;
    else $num=$i;
    $user="bjtu10-".$num;
    for ($j=0;$j<8;$j++) $pass[$j]=$availchar[rand(0,$length)];
    echo "<tr><td><center>$user</center></td><td><center>$pass</center></td></tr>";
    $row[0] = $user;
    $row[1] = $pass;
    $row[2] = $user;    $row[3] = "BJTU";
    db_user_insert($row);
}*/
echo "</table>";
}
include("footer.php");
?>
