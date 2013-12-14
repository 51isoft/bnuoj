<?php
include("header.php");
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {

$pid = $_GET['pid'];
$cid = $_GET['cid'];
$rac=$_GET['rac'];

if($pid == ""){?>
	<script type="text/javascript">
			alert("Please Enter A Problem ID.");
		</script>
		<?php
}
else if($cid != ""){
	if ($rac==0) $sql_r = "update status set result='Rejudging' where pid='$pid' and contest_belong='$cid' and result!='Accepted' ";
    else $sql_r = "update status set result='Rejudging' where pid='$pid' and contest_belong='$cid' ";
}
else{
	$cid = 0;
    if ($rac==0) $sql_r = "update status set result='Rejudging' where pid='$pid' and contest_belong='$cid' and result!='Accepted' ";
    else $sql_r = "update status set result='Rejudging' where pid='$pid' and contest_belong='$cid' ";

}
$que_r = mysql_query($sql_r);

if($que_r){

		$host="localhost";
		//if (db_problem_isvirtual($pid)) $port=$vserver_port; else $port=$server_port;
        $port=$vserver_port;
		$fp = fsockopen($host,$port,$errno, $errstr);
		if (!$fp) {
			echo "<br>$errno ($srrstr) </br>\n";
		}
		else {
			$msg=$rejudgestring."\n".$pid."\n".$cid."\n";
			if (fwrite($fp,$msg)===FALSE) {
				echo "<br>can not send msg</br>";
				exit;
			}
			fclose($fp);
		}
}
?>
<div align="center" class="fomr11">
  <form id="form1" name="form1" method="get" action="">
    <table width="720px" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th colspan="4" class="fomr12" scope="col">Rejudge Admin</th>
      </tr>
      <tr>
        <td class="fomr1"><div align="left">Contest ID：
            <input type="text" name="cid" />        </td>
        <td class="fomr1"><span class="fomr1">
          <div align="left">
          Problem ID:
          <input type="text" name="pid" /></td>
          <td class="form1">Rejudge AC?
          <input type="radio" name="rac" value="1" />
          Yes
          <input type="radio" name="rac" value="0" checked="checked"/>
           No</td>
      </tr>
      <tr>
        <td align="center" colspan="4" class="fomr1"><input type="submit" name="Submit" value="Rejudge" /></td>
      </tr>
  </table>
  </form>
</div>
		<?php
}
include("footer.php");
?>
