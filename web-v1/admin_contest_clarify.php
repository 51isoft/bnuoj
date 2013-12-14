<?php
 $cid = $_GET['cid'];
 include("cheader.php");
 include("cmenu.php");

 $sql_cha = "select * from contest_clarify where cid='$cid' order by ccid desc";
 $que_cha = mysql_query($sql_cha);
	if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>

<div align="center" class="fomr11">
  <form  name="form1" method="post" action="admin_contest_clarify_result.php?cid=<?php echo $cid; ?>">
    <table width="720px" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <th colspan="2"  scope="col">Admin Clarify</th>
      </tr>
      <tr>
           <th colspan="2" width="10%" class="fomr1" scope="col"><input type="submit" name="Submit" value="Reply" /></th>
       </tr>
      <?php
      $n = 0;
       while($arr_cha = mysql_fetch_array($que_cha)){
       	$n++;
?>
      <tr>
        <td colspan="2" class="fomr1"><div align="left" class="STYLE1">Question：</div></td>
      </tr>
	 <tr>
		<td colspan=2><pre class="discuss">
	 <?php echo $arr_cha[2];?>
		</pre></td>
	 </tr>
      <tr>
        <td  class="fomr1" scope="col"><div align="left">Contest ID：<?php echo $arr_cha[1];?></div></th>
        <td  class="fomr1" scope="col"><div align="left">Questioner：<?php echo $arr_cha[4];?></div></th>
      </tr>

      <tr>
        	<td class="fomr1"><div align="left">Is Public：
           <input type="radio" name="ispublic<?php echo $n;?>" value="1" <?php if($arr_cha[5]==1) echo"checked=\"checked\"";?>/>
            Yes
            <input type="radio" name="ispublic<?php echo $n;?>" value="0" <?php if($arr_cha[5]==0) echo"checked=\"checked\"";?>/>
        	No
        </div></td>
      </tr>

      <tr>
        <th colspan="2" width="10%" class="fomr1" scope="col"><input type="text" name="ccid<?php echo $n; ?>" style="display:none" value="<?php echo $arr_cha[0];?>" /></th>
      </tr>

      <tr>
        <th colspan="2" class="fomr1" scope="col"><textarea name="reply<?php echo $n;?>" cols="60" rows="5"><?php echo $arr_cha[3];?></textarea></th>
      </tr>
      <?php
 }?>
 	  <tr>
        <th colspan="2" width="10%" class="fomr1" scope="col"><input type="text" name="num" value="<?php echo $n;?>" /></th>
      </tr>


      <tr>
        <th colspan="2" width="10%" class="fomr1" scope="col"><input type="submit" name="Submit" value="Reply" /></th>
      </tr>
    </table>
  </form>
</div>
<?php
}
include("footer.php");
?>
