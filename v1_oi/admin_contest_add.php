<?php include("header.php");


	$sql_cn = "select max(cid) from contest";
	$que_cn = mysql_query($sql_cn);
	$num = mysql_fetch_array($que_cn);
	$n = $num[0]+1;

	$cid = $_GET['cid'];
	$sql_cid = "select * from contest where cid='$cid'";
	$que_cid = mysql_query($sql_cid);
	$r = mysql_fetch_array($que_cid);
	if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
<br>
<div align="center" class="fomr11">
  <form id="form1" name="form1" method="post" action="admin_contest_add_result.php">
    <table width="720px" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th colspan="2" class="fomr12" scope="col">比赛管理</th>
      </tr>
      <tr>
        <td class="fomr1"><div align="left">比赛名称：
            <input type="text" name="title" value="<?php echo $r[1];?>"/>
        </div></td>
        <td class="fomr1"><div align="left">比赛ID号（默认不填）：
            <input type="text" name="cid" value="<?php if($cid!=0)echo $r[0];else echo $n;?>" readonly="readonly"/>
        </div></td>

      </tr>
      <tr>
        <td colspan="2" class="fomr1"><div align="left">比赛描述</div></td>
      </tr>
      <tr>
        <td colspan="2" class="fomr1"><textarea name="description" cols="100" rows="15"><?php echo $r[2];?></textarea></td>
      </tr>
<tr>
<td colspan="3">时间格式: YYYY-MM-DD HH:MM:SS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请注意不要输错</td>
</tr>
      <tr>
        <td class="fomr1"><div align="left">开始时间：
            <input type="text" name="start_time" value="<?php echo $r[4];?>"/>
        </div></td>
        <td class="fomr1"><div align="left">结束时间：
            <input type="text" name="end_time" value="<?php echo $r[5];?>"/>
        </div></td>
      </tr>

        <tr>

		<td class="fomr1"><div align="left">内部赛：
           <input type="radio" name="isprivate" value="1" <? if($r[3]==1) echo"checked=\"checked\"";?>/>
            是
            <input type="radio" name="isprivate" value="0" <? if($r[3]==0) echo"checked=\"checked\"";?>/>
        否
        </div></td>

        <td class="fomr1"><div align="left">暂停显示排名时间（OI版无用）：
            <input type="text" name="lock_board_time" value="<?php echo $r[6];?>"/>
        </div></td>
      </tr>

      <tr><td class="fomr1"><div align="left">隐藏其他提交：
           <input type="radio" name="hide_others" value="1" <? if($r[7]==1) echo"checked=\"checked\"";?>/>
            是
            <input type="radio" name="hide_others" value="0" <? if($r[7]==0) echo"checked=\"checked\"";?>/>
        否
        </div></td></tr>
         <tr>
        <th colspan="2" class="fomr1"><input type="submit" name="Submit" value="提交" /></td>
      </tr>

  </table>
  </form>
</div>
<?php }
include("footer.php"); ?>
﻿
