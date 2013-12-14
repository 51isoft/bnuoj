<?php
/*
 * Created on 2009-4-1
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 include("header.php");
echo "<center>";
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
$n = $problemcontestadd;

for($i=0;$i<$n;$i++){
 $cid[$i] = $_POST['cid'.$i];
 $pid[$i] = $_POST['pid'.$i];
 $lable[$i] = $_POST['lable'.$i];
if($pid[$i] == ""){
	continue;
}
 $query="select * from contest_problem where cid=$cid[$i] and lable='$lable[$i]'";
 $query = change_in($query);
 $result=mysql_query($query);
 $exist=mysql_num_rows($result);
 $query="select * from contest_problem where cid=$cid[$i] and pid=$pid[$i]";
 $result=mysql_query($query);
 $exist=$exist+mysql_num_rows($result);
 if($exist=="0"){
	 $sql = "insert into contest_problem (cid ,pid,lable) values('"."$cid[$i]"."','"."$pid[$i]"."','"."$lable[$i]"."')";
	 $sql = change_in($sql);
	 $que = mysql_query($sql);
	 if ($que) echo "Problem:".$lable[$i]."&nbsp;&nbsp;pid:".$pid[$i]."&nbsp;&nbsp;Successfully Added.<br/>";
	 else echo "Failed When Adding Problem:".$lable[$i]."&nbsp;&nbsp;pid:".$pid[$i]."<br/>";
 }
 else{
	echo "Failed When Adding Problem:".$lable[$i]."&nbsp;&nbsp;pid:".$pid[$i]."&nbsp;&nbsp;Problem or Lable Already Exist.<br/>";
 }

}
    $csql="select pid from contest_problem where cid=$cid[0]";
    $cres=mysql_query($csql);
    $all=array();
    while ($crow=mysql_fetch_array($cres)) array_push($all,$crow[0]);
    sort($all);
    $tot="";
    foreach ($all as $num) {
       $tot= $tot.$num.",";
    }
//    echo "$tot\n<br>";
    $csql="update contest set allp='$tot' where cid=$cid[0]";
    mysql_query($csql);

}
echo "</center>";
 include("footer.php");
?>
