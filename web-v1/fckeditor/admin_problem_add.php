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
        <th colspan="5" class="fomr12" scope="col">Problem Admin</th>
      </tr>
      <tr>
        <td class="fomr1"><div align="left">Title：<br/>
            <input type="text" name="p_name" value="<?php echo $r[1];?>"/>
            </div></td>
        <td class="fomr1"><span class="fomr1">
          <div align="left">
          <div align="left">PID：<br/>
            <input type="text" name="p_id" readonly="readonly" value="<?php echo $pid;?>"/>
          </div></td>
        <td class="fomr1"><div align="left">Hide：<br/>
            <input type="radio" name="hide" value="1" <? if($r[26]==1) echo"checked=\"checked\"";?>/>
            Yes
            <input type="radio" name="hide" value="0" <? if($r[26]==0) echo"checked=\"checked\"";?>/>
        No</div>        </td>
      </tr>

		<tr>
        <td class="fomr1"><div align="left">Memory Limit：<br/>
            <input type="text" name="memory_limit" value="<?php echo $r[23];?>"/>
            </div></td>
        <td class="fomr1"><span class="fomr1">
          <div align="left">
          <div align="left">Time Limit：<br/>
            <input type="text" name="time_limit" value="<?php echo $r[21];?>"/>
          </div></td>
        <td class="fomr1"><div align="left">Special Judge：<br/>

            <input type="radio" name="special_judge_status" value="1" <? if($r[18]==1) echo"checked=\"checked\"";?>/>
            Yes
            <input type="radio" name="special_judge_status" value="0" <? if($r[18]==0) echo"checked=\"checked\"";?>/>
        No</div>        </td>
      </tr>

		<tr>
        <td class="fomr1"><div align="left">Case Time Limit：<br/>
            <input type="text" name="case_time_limit" value="<?php echo $r[22];?>"/>
            </div></td>
        <td class="fomr1"><span class="fomr1">
          <div align="left">
          <div align="left">Basic Solver Value:<br/>
            <input type="text" name="basic_solver_value" value="<?php echo $r[19];?>"/>
          </div></td>
        <td class="fomr1"><div align="left">Number Of Testcases：<br/>
           <input type="text" name="ac_value" value="<?php echo $r[7];?>"/>
           </div>        </td>
      </tr>
<tr>
<td>Tag allowed: &lt;font> &lt;img> &lt;a> &lt;center> &lt;strong></td>
<tr>
      <tr>
        <td colspan="5" class="fomr1"><div align="left">Description:
<?php
$sBasePath="/contest/";

$oFCKeditor = new FCKeditor('description') ;
$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/office2003/' ;
$oFCKeditor->BasePath	= $sBasePath ;
$oFCKeditor->Height=400;
$oFCKeditor->Value	= $r[2] ;
$oFCKeditor->Create() ;
?>
        <!--    <textarea class="ckeditor" id="editor1" name="description" cols="100" rows="10"><?php echo $r[2];?></textarea>-->
        </div></td>
      </tr>
      <tr>
        <td colspan="5" class="fomr1"><div align="left">Input:
         <!-- <textarea class="ckeditor" id="editor2" name="input" cols="100" rows="10"><?php echo $r[3];?></textarea>-->
<?php
$sBasePath="/contest/";

$oFCKeditor = new FCKeditor('input') ;
$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/office2003/' ;
$oFCKeditor->BasePath	= $sBasePath ;
$oFCKeditor->Height=400;
$oFCKeditor->Value	= $r[3] ;
$oFCKeditor->Create() ;
?>
        </div></td>
      </tr>
      <tr>
        <td colspan="5" class="fomr1"><div align="left">Output:
         <!-- <textarea class="ckeditor" id="editor3" name="output" cols="100" rows="10"><?php echo $r[4];?></textarea>-->
<?php
$sBasePath="/contest/";

$oFCKeditor = new FCKeditor('output') ;
$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/office2003/' ;
$oFCKeditor->BasePath	= $sBasePath ;
$oFCKeditor->Height=400;
$oFCKeditor->Value	= $r[4] ;
$oFCKeditor->Create() ;
?>
        </div></td>
      </tr>
      <tr>
        <td colspan="5" class="fomr1"><div align="left">Sample Input:
          <textarea name="sample_in" cols="100" rows="10"><?php echo $r[5];?></textarea>
        </div></td>
      </tr>
      <tr>
        <td colspan="5" class="fomr1"><div align="left">Sample Output:
            <textarea name="sample_out" cols="100" rows="10"><?php echo $r[6];?></textarea>
        </div></td>
      </tr>
      <tr>
        <td colspan="5" class="fomr1"><div align="left">Hint:
        <!--  <textarea class="ckeditor" id="editor4" name="hint" cols="100" rows="10"><?php echo $r[24];?></textarea>-->
<?php
$sBasePath="/contest/";

$oFCKeditor = new FCKeditor('hint') ;
$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/office2003/' ;
$oFCKeditor->BasePath	= $sBasePath ;
$oFCKeditor->Height=400;
$oFCKeditor->Value	= $r[24] ;
$oFCKeditor->Create() ;
?>
        </div></td>
      </tr>
      <tr>
        <td colspan="5" class="fomr1"><div align="left">Source:
          <textarea name="source" cols="100" rows="10"><?php echo $r[25];?></textarea>
        </div></td>
      </tr>
      <tr>
        <td colspan="5" class="fomr1"><center><input type="submit" name="Submit" value="Submit" /></center></td>
      </tr>
  </table>
  </form>
</div>
<?php }include("footer.php"); ?>
