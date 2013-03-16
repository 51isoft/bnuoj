<?php
  include_once("conn.php");
  $pagetitle="Contest Editor";
  include_once("header.php");
  include_once("menu.php");
  $cid=convert_str($_GET['cid']);
?>
    <div id="site_content">
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
<?php
  if (db_contest_started($cid)==false&&db_user_match($nowuser,$nowpass)&&(db_user_isroot($nowuser)||strcasecmp(db_contest_owner($cid),$nowuser)==0)) {
      $trow=mysql_fetch_array(mysql_query("select * from contest where cid='$cid'"));
?>
          <div class='typenote ui-state-highlight ui-corner-all' style="color:blue;display:none;text-align:left;padding:5px">
            In CF, Parameter A represents the points lost per minute. Parameter B represents the points lost for each incorrect submit.<br />
            In CF Dynamic, parameters will decrease according to the AC ratio.<br />
            In TC, parameters defined as below. A + B must equal to 1. Parameter C is usually the length of this contest in TopCoder. Parameter E is the percentage of penalty for each incorrect submit.<br />
            <img src='tcpoint.png' />
          </div>
          <form method="post" action="" id="cmodifyform">
            <input name="cid" value='<?php echo $cid; ?>' type="hidden" />
            <div class="left" style='width:46%'>
                <table style="width:100%;">
                    <tr><th colspan="2">Contest Information</th></tr>
                    <tr><td>Title: <input type="text" name="title" value="<?php echo htmlspecialchars($trow['title']); ?>" style='width:250px' /> *</td></tr>
                    <tr><td>Type: <input type="radio" style='width:20px' name="ctype" value="0" <?php if ($trow['type']==0) echo 'checked="checked"'; ?> /> ICPC format &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" style='width:20px' name="ctype" value="1" <?php if ($trow['type']==1) echo 'checked="checked"'; ?> /> CF format </td></tr>
                    <tr><td colspan="2">Description: </td></tr>
                    <tr><td colspan="2"><textarea name="description" style="width:350px;height:150px"><?php echo htmlspecialchars($trow['description']); ?></textarea></td></tr>
                    <tr><td>Start Time: <input type="text" name="start_time" class="datepick" value='<?php echo $trow['start_time']; ?>'/> *</td></tr>
                    <tr><td>( contest should be start after 10 minutes )</td></tr>
                    <tr><td>End Time: <input type="text" name="end_time" class="datepick" value='<?php echo $trow['end_time']; ?>'/> *</td></tr>
                    <tr><td>( contest length should be between 30 minutes and 5 days )</td></tr>
                    <tr><td>Lock Board Time: <input type="text" name="lock_board_time" class="datepick" value='<?php echo $trow['lock_board_time']; ?>'/></td></tr>
                    <tr><td>( set it later than end time if you don't want to lock board )</td></tr>
                    <tr><td>Using Local Timezone?: <input type="radio" style='width:20px' name="localtime" value="1" />Yes <input type="radio" style='width:20px' name="localtime" value="0" checked="checked" />No</td></tr>
                    <tr><td><span id="localtz"></span><input name="localtz" type="hidden" id="tzinp" /></td></tr>
                    <tr><td>Hide Others' Status: <input type="radio" style='width:20px' name="hide_others" value="1" <?php if ($trow['hide_others']==1) echo 'checked="checked"'; ?> />Yes <input type="radio" style='width:20px' name="hide_others" value="0" <?php if ($trow['hide_others']==0) echo 'checked="checked"'; ?> />No</td></tr>
                    <tr><td>Password: <input type="password" name="password" /></td></tr>
                    <tr><td>( leave it blank if not needed )</td></tr>
                </table>
            </div>

<?php
    $ccid=$cid;
    $ccsql="select * from contest_problem where cid='$ccid' order by lable asc";
    $ccres=mysql_query($ccsql);
    $ccrow=array();
    while ($onerow=mysql_fetch_array($ccres)) $ccrow[]=$onerow;
    $nn = $problemcontestadd;
?>
            <div class='right' style='width:50%'>
                <table style="width:100%">
                    <tr><th colspan="2">Add Problems For Contest</th></tr>
                    <tr><th colspan="2">Leave Problem ID blank if you don't want to add it.</th></tr>
<?php
for($i=0;$i<$nn;$i++){
?>
                    <tr <?php if ($i>=$paratypemax) echo "class='pextra'"; ?>>
                        <td style="width:110px">Problem <?php echo chr($i+65);?> <input type="hidden" name="lable<?php echo $i; ?>" value="<?php echo chr($i+65); ?>" /></td>
                        <td class='selpid'>
                            <div style="float:left">
                                OJ: <select class="vpname" style="width:120px"><?php echo $ojoptions; ?></select>
                                Problem ID: <input style="width:100px" class="vpid" type="text" value="<?php echo $ccrow[$i]['pid'];?>" />
                                <input class="vpid" type="hidden" name="pid<?php echo $i;?>" value="<?php echo $ccrow[$i]['pid'];?>" /><?php if ($i==0) echo " *"; ?>
                                <br /><span style="overflow:hidden;color:red"></span>
                            </div>
                            <div style="clear:both;float:left;padding:6px;margin-left:10px;display:none" class="selptype">
                                Type: <input type="radio" style='width:12px' class='ptype' name="ptype<?php echo $i; ?>" value="1" <?php if ($ccrow[$i]['type']==1) echo 'checked="checked"'; ?> /> CF &nbsp;&nbsp;
                                <input type="radio" style='width:12px' class='ptype' name="ptype<?php echo $i; ?>" value="2" <?php if ($ccrow[$i]['type']==2) echo 'checked="checked"'; ?> /> TC &nbsp;&nbsp;
                                <input type="radio" style='width:12px' class='ptype' name="ptype<?php echo $i; ?>" value="3" <?php if ($ccrow[$i]['type']==3) echo 'checked="checked"'; ?> /> CF Dynamic <br />
                            </div>
                            <div class='ui-state-highlight ui-corner-all selpara' style="padding:0.3em;display:none;clear:both">
                                <div class='cf tc'>Base Value (MP) : <input type="text" value='<?php echo $ccrow[$i]['base'];?>' name="base<?php echo $i; ?>" /></div>
                                <div class='cf tc'>Min Value: <input type="text" value='<?php echo $ccrow[$i]['minp'];?>' name="minp<?php echo $i; ?>" /></div>
                                <div class='cf tc'>Parameter A: <input type="text" class='paraa' value="<?php echo $ccrow[$i]['para_a'];?>" name="paraa<?php echo $i; ?>" /></div>
                                <div class='cf tc'>Parameter B: <input type="text" class='parab' value="<?php echo $ccrow[$i]['para_b'];?>" name="parab<?php echo $i; ?>" /></div>
                                <div class='tc' style="display:none">Parameter C: <input class='parac' type="text" value="<?php echo $ccrow[$i]['para_c'];?>" name="parac<?php echo $i; ?>" /></div>
                                <div class='tc' style="display:none">Parameter D: <input class='parad' type="text" value="<?php echo $ccrow[$i]['para_d'];?>" name="parad<?php echo $i; ?>" /></div>
                                <div class='tc' style="display:none">Parameter E: <input class='parae' type="text" value="<?php echo $ccrow[$i]['para_e'];?>" name="parae<?php echo $i; ?>" /></div>
                            </div>
                        </td>
                    </tr>
<?php
}
?>
                </table>
            </div>
            <div style='clear:both;margin-bottom:0px' class="center">
                <input type="submit" name="Submit" value="Submit" />
                <span>&nbsp;</span><span id="arrangemsgbox" style="display:none; z-index:300;width:200px"></span>
            </div>
          </form>
<?php
  } else {
?>
          <div class="error">Invalid request!</div>
<?php
  }
?>
        </div>
        <div id="one_content_base"></div>
      </div>
    </div>
<?php
    include_once("footer.php");
?>
<script type="text/javascript" src="js/jstz.min.js"></script>
<script type="text/javascript">
var timezone = jstz.determine_timezone();
$("#localtz").html("( "+timezone.name()+" GMT"+timezone.offset()+" )");
$("#tzinp").val(timezone.name());
</script>
<script type="text/javascript" src="pagejs/contest_modify.js?<?php echo filemtime("pagejs/contest_modify.js"); ?>"></script>

<?php
    include("end.php");
?>
