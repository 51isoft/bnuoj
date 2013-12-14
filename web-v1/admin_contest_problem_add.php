<?php
include("header.php");
$cid = $_GET['cid'];
$n = $problemcontestadd;
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
<br>
<div align="center" class="fomr11">
  <form id="form1" name="form1" method="post" action="admin_contest_problem_result.php">
    <table width="720px" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th colspan="4" class="fomr12" scope="col">Add Problems For Contest <?php echo $cid;?></th>
      </tr>
      <tr>
        <th colspan="4" class="fomr12" scope="col">Leave Problem ID blank if you don't want to add it.</th>
      </tr>
      <?php for($i=0;$i<$n;$i++){?>

      <tr>
        <td class="fomr1"><div align="left">Lable：
            <input type="text" name="lable<?php echo $i;?>" value="<?php echo chr($i+65);?>"" />        </td>
        <td class="fomr1"><span class="fomr1">
          <div align="left">
          Problem ID：
          <input type="text" name="pid<?php echo $i;?>" /></td>

          <td class="fomr1"><span class="fomr1">
          <div align="left">
          Contest ID：
          <input type="text" readonly="readonly" name="cid<?php echo $i;?>" value="<?php echo $cid;?>"/></td>
      </tr>
      <?php
      }
      ?>
      <tr>
        <td colspan="4" class="fomr1" align="center"><input type="submit" name="Submit" value="Submit" /></td>
      </tr>
  </table>
  </form>
</div>
<?php }include("footer.php"); ?>
