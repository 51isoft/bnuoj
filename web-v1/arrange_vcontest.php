<?php include("header.php");


	$sql_cn = "select max(cid) from contest";
	$que_cn = mysql_query($sql_cn);
	$num = mysql_fetch_array($que_cn);
	$n = $num[0]+1;

	$cid = $_GET['cid'];
	$sql_cid = "select * from contest where cid='$cid'";
	$que_cid = mysql_query($sql_cid);
	$r = mysql_fetch_array($que_cid);
	if (db_user_exist($nowuser)&&db_user_match($nowuser,$nowpass)&&(db_user_isroot($nowuser)||strcasecmp($nowuser,$r['owner']))) {
?>
<br>
<div align="center" class="fomr11">
  <form id="form1" name="form1" method="post" action="arrange_vcontest_result.php">
    <table width="720px" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th colspan="2" class="fomr12" scope="col">Virtual Contest Admin</th>
      </tr>
      <tr>
        <td class="fomr1"><div align="left">Title：
            <input type="text" name="title" size=50 value="<?php echo $r[1];?>"/> *
        </div></td></tr><tr>
        <td class="fomr1"><div align="left">Contest ID：
            <input type="text" name="cid" value="<?php if($cid!=0)echo $r[0];else echo $n;?>" readonly="readonly"/>
        </div></td>

      </tr>
      <tr>
        <td colspan="2" class="fomr1"><div align="left">Description：</div></td>
      </tr>
      <tr>
        <td colspan="2" class="fomr1"><textarea name="description" cols="100" rows="15"><?php echo $r[2];?></textarea></td>
      </tr>
<tr>
<td colspan="3">Time Format: YYYY-MM-DD HH:MM:SS</td>
</tr>
      <tr>
        <td class="fomr1"><div align="left">Start Time：
            <input type="text" name="start_time" value='<?php if ($r[4]=="") echo "2009-01-01 17:30:00"; else echo $r[4];?>'/> * ( contest should be start after 10 minutes )
        </div></td></tr><tr>
        <td class="fomr1"><div align="left">End Time：
            <input type="text" name="end_time" value='<?php if ($r[5]=="") echo "2009-01-01 22:30:00"; echo $r[5];?>'/> * ( contest length should be between 30 minutes and 5 days )
        </div></td>
      </tr>

        <tr>

        <td class="fomr1"><div align="left">Lock Board Time：
            <input type="text" name="lock_board_time" value='<?php if ($r[6]=="") echo "0000-00-00 00:00:00"; echo $r[6];?>'/> ( leave it blank if you don't want to lock the board )
        </div></td>
      </tr>

      <tr><td class="fomr1"><div align="left">Hide Others' Status：
           <input type="radio" name="hide_others" value="1" <? if($r[7]==1) echo"checked=\"checked\"";?>/>
            Yes
            <input type="radio" name="hide_others" value="0" <? if($r[7]==0) echo"checked=\"checked\"";?>/>
        No
        </div></td></tr></table>

<?php
$nn = $problemcontestadd;

?>
<table>
      <tr>
        <th colspan="4" class="fomr12" scope="col">Add Problems For Contest <?php echo $cid;?></th>
      </tr>
      <tr>
        <th colspan="4" class="fomr12" scope="col">Leave Problem ID blank if you don't want to add it.</th>
      </tr>
      <?php for($i=0;$i<$nn;$i++){?>

      <tr>
        <td class="fomr1">Problem <?php echo chr($i+65);?>
            <input type="hidden" name="lable<?php echo $i;?>" value="<?php echo chr($i+65);?>" /></td>
          <td class="fomr1">Problem ID：
          <input type="text" name="pid<?php echo $i;?>" /><?php if ($i==0) echo "*"; ?>

          <input type=hidden readonly="readonly" name="cid<?php echo $i;?>" value="<?php if($cid!=0)echo $cid;else echo $n;?>"/> </td>
      </tr>
      <?php
      }
      ?>

         <tr>
        <th colspan="2" class="fomr1"><input type="submit" name="Submit" value="Submit" /></td>
      </tr>

  </table>
  </form>
</div>
<?php }
else {
echo "<center><span class=warn>Please login first.</span></center>";
}
include("footer.php"); ?>
﻿
