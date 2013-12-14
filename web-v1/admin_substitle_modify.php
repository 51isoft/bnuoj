<?php include("header.php");


	if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
<br>
<div align="center" class="fomr11">
  <form id="form1" name="form1" method="post" action="admin_substitle_modify_result.php">
    <table width="720px" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th colspan="2" class="fomr12" scope="col">Notification Modify</th>
      </tr>
      <tr>
        <td colspan="2" class="fomr1"><div align="left">Substitle：</div></td>
      </tr>
      <tr>
          <td colspan="2" class="fomr1"><textarea name="sub" cols="100" rows="15"><?php echo htmlspecialchars($substitle);?></textarea></td>
      </tr>

      <tr>
        <th colspan="2" class="fomr1"><input type="submit" name="Submit" value="Submit" /></th>
      </tr>

  </table>
  </form>
</div>
<?php }
include("footer.php"); ?>
