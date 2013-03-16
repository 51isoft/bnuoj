<?php
  include_once("conn.php");
  $pagetitle="Contest List";
  include_once("header.php");
  include_once("menu.php");
  if ($_GET["page"]!="") $stp=$problemperpage*(intval(convert_str($_GET["page"]))-1);
  else $stp="0";
?>
    <div id="site_content">
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <!-- insert the page content here -->
          <div>
            <button id="arrangevirtual" style="display:none;">Arrange</button>
            <div class="ui-buttonset" style="display:inline">
              <input type="radio" id="showall" name="radio1" checked="checked" /><label for="showall">All</label>
              <input type="radio" id="showstandard" name="radio1" /><label for="showstandard">Standard</label>
              <input type="radio" id="showvirtual" name="radio1" /><label for="showvirtual">Virtual</label>
            </div>
            <div class="ui-buttonset" style="display:inline">
              <input type="radio" id="showcall" name="radio2" checked="checked" /><label for="showcall">All</label>
              <input type="radio" id="showcicpc" name="radio2" /><label for="showcicpc">ICPC</label>
              <input type="radio" id="showccf" name="radio2" /><label for="showccf">CF</label>
              <input type="radio" id="showcreplay" name="radio2" /><label for="showcreplay">Replay</label>
              <input type="radio" id="showcnonreplay" name="radio2" /><label for="showcnonreplay">Non-Replay</label>
            </div>
            <div class="ui-buttonset" style="display:inline">
              <input type="radio" id="showtall" name="radio3" checked="checked" /><label for="showtall">All</label>
              <input type="radio" id="showtpublic" name="radio3" /><label for="showtpublic">Public</label>
              <input type="radio" id="showtprivate" name="radio3" /><label for="showtprivate">Private</label>
              <input type="radio" id="showtpassword" name="radio3" /><label for="showtpassword">Password</label>
            </div>
          </div>
          <table class="display" id="contestlist">
            <thead>
              <tr>
                <th width='65px'> CID </th>
                <th> Title </th>
                <th width='140px'> Start Time </th>
                <th width='140px'> End Time </th>
                <th width='100px'> Status </th>
                <th width='90px'> Access </th>
                <th width="95px"> Manager </th>
                <th> Private </th>
                <th> Type </th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>
        <div id="one_content_base"></div>
      </div>
    </div>
    <div id="arrangevdialog" class="topdialog" title="Arrange Virtual Contest" style="display:none">
        <div class='typenote ui-state-highlight ui-corner-all' style="color:blue;display:none;text-align:left;padding:5px">
            In CF, Parameter A represents the points lost per minute. Parameter B represents the points lost for each incorrect submit.<br />
            In CF Dynamic, parameters will decrease according to the AC ratio.<br />
            In TC, parameters defined as below. A + B must equal to 1. Parameter C is usually the length of this contest in TopCoder. Parameter E is the percentage of penalty for each incorrect submit.<br />
            <img src='tcpoint.png' />
        </div>
        <form method="post" action="" id="arrangeform">
            <div class="left" style='width:42%'>
                <table style="width:100%;">
                    <tr><th colspan="2">Contest Information</th></tr>
                    <tr><td>Title: <input type="text" name="title" style='width:250px' /> *</td></tr>
                    <tr><td>Type: <input type="radio" style='width:20px' name="ctype" value="0" checked="checked" /> ICPC format &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" style='width:20px' name="ctype" value="1" /> CF format </td></tr>
                    <tr><td colspan="2">Description: </td></tr>
                    <tr><td colspan="2"><textarea name="description" style="width:350px;height:150px"></textarea></td></tr>
                    <tr><td>Start Time: <input type="text" name="start_time" class="datepick" value='<?php echo date("Y-m-d")." 09:00:00"; ?>'/> *</td></tr>
                    <tr><td>( contest should be start after 10 minutes )</td></tr>
                    <tr><td>End Time: <input type="text" name="end_time" class="datepick" value='<?php echo date("Y-m-d")." 14:00:00"; ?>'/> *</td></tr>
                    <tr><td>( contest length should be between 30 minutes and 15 days )</td></tr>
                    <tr><td>Lock Board Time: <input type="text" name="lock_board_time" class="datepick" value='<?php echo date("Y-m-d")." 14:00:00"; ?>'/></td></tr>
                    <tr><td>( set it later than end time if you don't want to lock board )</td></tr>
                    <tr><td>Using Local Timezone?: <input type="radio" style='width:20px' name="localtime" value="1" />Yes <input type="radio" style='width:20px' name="localtime" value="0" checked="checked" />No</td></tr>
                    <tr><td><span id="localtz"></span><input name="localtz" type="hidden" id="tzinp" /></td></tr>
                    <tr><td>Hide Others' Status: <input type="radio" style='width:20px' name="hide_others" value="1" />Yes <input type="radio" style='width:20px' name="hide_others" value="0" checked="checked" />No</td></tr>
                    <tr><td>Password: <input type="password" name="password" /></td></tr>
                    <tr><td>( leave it blank if not needed )</td></tr>
                </table>
            </div>

<?php
if ($_GET['clone']==1) {
    $ccid=convert_str($_GET['cid']);
    if (db_contest_passed($ccid)&&(!db_contest_private($ccid)||(db_user_match($nowuser,$nowpass)&&(db_user_in_contest($ccid,$nowuser)||db_contest_isroot($nowuser))))) {
        $ccsql="select pid from contest_problem where cid='$ccid' order by lable asc";
        $ccres=mysql_query($ccsql);
        $ccrow=array();
        while ($onerow=mysql_fetch_array($ccres)) $ccrow[]=$onerow[0];
    }
}
$nn = $problemcontestadd;
?>
            <div class='right' style='width:54%'>
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
                                OJ: <select class="vpname" style="width:120px"><?php echo $ojoptions;?></select>
                                Problem ID: <input class="vpid" type="text" value="<?php echo $ccrow[$i];?>" />
                                <input class="vpid" type="hidden" name="pid<?php echo $i;?>" value="<?php echo $ccrow[$i];?>" /><?php if ($i==0) echo " *"; ?>
                                <br /><span style="overflow:hidden;color:red"></span>
                            </div>
                            <div style="clear:both;float:left;padding:6px;margin-left:10px;display:none" class="selptype">
                                Type: <input type="radio" style='width:12px' class='ptype' name="ptype<?php echo $i; ?>" value="1" checked="checked" /> CF &nbsp;&nbsp;
                                <input type="radio" style='width:12px' class='ptype' name="ptype<?php echo $i; ?>" value="2" /> TC
                                <input type="radio" style='width:12px' class='ptype' name="ptype<?php echo $i; ?>" value="3" /> CF Dynamic <br />
                            </div>
                            <div class='ui-state-highlight ui-corner-all selpara' style="padding:0.3em;display:none;clear:both">
                                <div class='cf tc'>Base Value (MP) : <input type="text" value='500' name="base<?php echo $i; ?>" /></div>
                                <div class='cf tc'>Min Value: <input type="text" value='150' name="minp<?php echo $i; ?>" /></div>
                                <div class='cf tc'>Parameter A: <input type="text" class='paraa' value="2" name="paraa<?php echo $i; ?>" /></div>
                                <div class='cf tc'>Parameter B: <input type="text" class='parab' value="50" name="parab<?php echo $i; ?>" /></div>
                                <div class='tc' style="display:none">Parameter C: <input class='parac' type="text" name="parac<?php echo $i; ?>" /></div>
                                <div class='tc' style="display:none">Parameter D: <input class='parad' type="text" name="parad<?php echo $i; ?>" /></div>
                                <div class='tc' style="display:none">Parameter E: <input class='parae' type="text" name="parae<?php echo $i; ?>" /></div>
                            </div>
                        </td>
                    </tr>
<?php
}
?>
                </table>
            </div>
            <div style='clear:both' class="center">
                <input type="submit" name="Submit" value="Submit" />
                <span>&nbsp;</span><span id="arrangemsgbox" style="display:none; z-index:300;width:200px"></span>
            </div> 
        </form>
    </div>
<?php
    include_once("footer.php");
?>
<script type="text/javascript" src="js/jstz.min.js"></script>
<script type="text/javascript">
var timezone = jstz.determine_timezone();
$("#localtz").html("( "+timezone.name()+" GMT"+timezone.offset()+" )");
$("#tzinp").val(timezone.name());
var searchstr='<?php echo $_GET['search']; ?>';
var conperpage=<?php echo $conperpage;?>;
var cshowtype='<?php echo $_GET['type']; ?>';
</script>
<script type="text/javascript" src="pagejs/contest.js?<?php echo filemtime("pagejs/contest.js"); ?>"></script>
<?php
    include("end.php");
?>
