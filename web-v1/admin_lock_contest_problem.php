<?php
include("header.php");
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {

$cid = $_GET['cid'];

if($cid == ""){?>
	<script type="text/javascript">
			alert("Please Enter A Contest ID.");
		</script>
		<?php
}
else{
	$sql_r = "update problem set hide=true where pid=any(select pid from contest_problem where cid=$cid)";
	$que_r = mysql_query($sql_r);
}

?>
<div align="center" class="fomr11">
  <form id="form1" name="form1" method="get" action="">
    <table width="720px" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th colspan="4" class="fomr12" scope="col">Admin Lock Contest Problem</th>
      </tr>
      <tr>
        <th class="fomr1">Contest ID：
            <input type="text" name="cid" />        </th>
      </tr>
      <tr>
        <td align="center" colspan="4" class="fomr1"><input type="submit" name="Submit" value="Lock" /></td>
      </tr>
  </table>
  </form>
</div>
		<?php
}
include("footer.php");
?>
