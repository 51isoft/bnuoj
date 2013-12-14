<?php include("header.php");
$cid = $_GET['cid'];
	if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
<br>
<div align="center" class="fomr11">
  <form id="form1" name="form1" method="post" action="admin_contest_report_modify_result.php">
    <table width="720px" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th colspan="2" class="fomr12" scope="col">Contest Report</th>
      </tr>
      <tr>
        <td colspan="2" class="fomr1"><div align="left">Content ( will show after the contest ):</div></td>
      </tr>
      <tr>
          <td colspan="2" class="fomr1">
<input type="hidden" name="cid" value="<?php echo $cid;?>" />
<?php
list($query)=mysql_fetch_row(mysql_query("select report from contest where cid='$cid'"));
$sBasePath="/contest/fckeditor/";

$oFCKeditor = new FCKeditor('report') ;
$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/office2003/' ;
$oFCKeditor->BasePath	= $sBasePath ;
$oFCKeditor->Height=400;
$oFCKeditor->Value	= $query;
$oFCKeditor->Create() ;
?>
 
	 </td>
      </tr>

      <tr>
        <th colspan="2" class="fomr1"><input type="submit" name="Submit" value="Submit" /></th>
      </tr>

  </table>
  </form>
</div>
<?php }
include("footer.php"); ?>
