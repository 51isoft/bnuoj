<?php
include("header.php");
$pid = $_GET['pid'];

if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
	$sql_pid = "select * from problem where pid='$pid'";
	$que_pid = mysql_query($sql_pid);
	$r = mysql_fetch_array($que_pid);
    if ($pid=='') {
        $r[23]=65536;
        $r[21]=1000;
        $r[22]=1000;
        $r[7]=1;
    }

?>
<div align="center" class="fomr11">
  <form id="form1" name="form1" method="post" action="admin_problem_add_result.php">
    <table width="720px" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th colspan="5" class="fomr12" scope="col">题目管理<br /></th>
      </tr>
      <tr>
        <td class="fomr1"><div align="left">题目标题：<br/>
            <input type="text" name="p_name" value="<?php echo $r[1];?>"/>
            </div></td>
        <td class="fomr1"><span class="fomr1">
          <div align="left">
          <div align="left">题目ID（不需要填）：<br/>
            <input type="text" name="p_id" readonly="readonly" value="<?php echo $pid;?>"/>
          </div></td>
        <td class="fomr1"><div align="left">题目是否隐藏不可见：<br/>
            <input type="radio" name="hide" value="1" <? if($r[26]==1) echo"checked=\"checked\"";?>/>
            是
            <input type="radio" name="hide" value="0" <? if($r[26]==0) echo"checked=\"checked\"";?>/>
        否</div>        </td>
      </tr>

		<tr>
        <td class="fomr1"><div align="left">内存限制：<br/>
            <input type="text" name="memory_limit" value="<?php echo $r[23];?>"/>
            </div></td>
        <td class="fomr1"><span class="fomr1">
          <div align="left">
          <div align="left"><br/>
          </div></td>
        <td class="fomr1"><div align="left">使用特殊判题（答案不唯一）：<br/>

            <input type="radio" name="special_judge_status" value="1" <? if($r[18]=='1') echo"checked=\"checked\"";?>/>
            是
            <input type="radio" name="special_judge_status" value="0" <? if($r[18]!='1') echo"checked=\"checked\"";?>/>
        否</div>        </td>
      </tr>

		<tr>
        <td class="fomr1"><div align="left">每个Case点的时间限制：<br/>
            <input type="text" name="case_time_limit" value="<?php echo $r[22];?>"/>
            </div></td>
        <td class="fomr1"><span class="fomr1">
          <div align="left">
          <div align="left"><br/>
          </div></td>
        <td class="fomr1"><div align="left">测试数据个数：<br/>
           <input type="text" name="ac_value" value="<?php echo $r[7];?>"/>
           </div>        </td>
      </tr>
      <tr>
        <td colspan="5" class="fomr1"><div align="left">题目描述:
<?php
$sBasePath="/contestoi/fckeditor/";

$oFCKeditor = new FCKeditor('description') ;
$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/office2003/' ;
$oFCKeditor->BasePath   = $sBasePath ;
$oFCKeditor->Height=400;
$oFCKeditor->Value      = $r[2] ;
$oFCKeditor->Create() ;
?>
        </div></td>
      </tr>
      <tr>
        <td colspan="5" class="fomr1"><div align="left">输入:
<?php
$sBasePath="/contestoi/fckeditor/";

$oFCKeditor = new FCKeditor('input') ;
$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/office2003/' ;
$oFCKeditor->BasePath   = $sBasePath ;
$oFCKeditor->Height=400;
$oFCKeditor->Value      = $r[3] ;
$oFCKeditor->Create() ;
?>
        </div></td>
      </tr>
      <tr>
        <td colspan="5" class="fomr1"><div align="left">输出:
<?php
$sBasePath="/contestoi/fckeditor/";

$oFCKeditor = new FCKeditor('output') ;
$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/office2003/' ;
$oFCKeditor->BasePath   = $sBasePath ;
$oFCKeditor->Height=400;
$oFCKeditor->Value      = $r[4] ;
$oFCKeditor->Create() ;
?>
        </div></td>
      </tr>
      <tr>
        <td colspan="5" class="fomr1"><div align="left">输入样例:
          <textarea name="sample_in" cols="100" rows="10"><?php echo $r[5];?></textarea>
        </div></td>
      </tr>
      <tr>
        <td colspan="5" class="fomr1"><div align="left">输出样例:
            <textarea name="sample_out" cols="100" rows="10"><?php echo $r[6];?></textarea>
        </div></td>
      </tr>
      <tr>
        <td colspan="5" class="fomr1"><div align="left">提示信息:
<?php
$sBasePath="/contestoi/fckeditor/";

$oFCKeditor = new FCKeditor('hint') ;
$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/office2003/' ;
$oFCKeditor->BasePath   = $sBasePath ;
$oFCKeditor->Height=400;
$oFCKeditor->Value      = $r[24] ;
$oFCKeditor->Create() ;
?>
        </div></td>
      </tr>
      <tr>
        <td colspan="5" class="fomr1"><div align="left">来源:
          <textarea name="source" cols="100" rows="10"><?php echo $r[25];?></textarea>
        </div></td>
      </tr>
      <tr>
        <td colspan="5" class="fomr1"><center><input type="submit" name="Submit" value="提交" /></center></td>
      </tr>
  </table>
  </form>
</div>
<?php }include("footer.php"); ?>
