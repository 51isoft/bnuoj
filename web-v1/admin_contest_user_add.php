<?php include("header.php");

$cid=$_GET['cid'];
	if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
		if (!db_contest_private($cid)) {
			echo "<center><span class='warn'>This contest is NOT private!</span></center>";
		}
		else {
?>
<br>
<div align="center" class="fomr11">
  <form id="form1" name="form1" method="post" action="admin_contest_user_add_result.php?cid=<?php echo $cid; ?>">
    <table width="720px" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th colspan="2" class="fomr12" scope="col">Add Users For Contest <?php echo $cid; ?></th>
      </tr>
      <tr>
        <td colspan="2" class="fomr1"><div align="left">Users ( Please use '|' character to separate the users )：</div></td>
      </tr>
      <tr>
          <td colspan="2" class="fomr1"><textarea name="names" cols="100" rows="15"></textarea></td>
      </tr>

      <tr>
        <th colspan="2" class="fomr1"><input type="submit" name="Submit" value="Submit" /></th>
      </tr>

  </table>
  </form>
</div>
<?php }
}
include("footer.php"); ?>
