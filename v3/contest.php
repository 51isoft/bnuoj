<?php
$pagetitle="Contest List";
include_once("header.php");
include_once("functions/contests.php");
?>
        <div class="span12">
          <button id="arrangevirtual" class="hide btn btn-primary">Arrange VContest</button>
          <div class="btn-group">
            <button id="showall" class="btn btn-info active">All</button>
            <button id="showstandard" class="btn btn-info">Standard</button>
            <button id="showvirtual" class="btn btn-info">Virtual</button>
          </div>
          <div class="btn-group">
            <button id="showcall" class="btn btn-info active">All</button>
            <button id="showcicpc" class="btn btn-info">ICPC</button>
            <button id="showccf" class="btn btn-info">CF</button>
            <button id="showcreplay" class="btn btn-info">Replay</button>
            <button id="showcnonreplay" class="btn btn-info">Non-Replay</button>
          </div>
          <div class="btn-group">
            <button id="showtall" class="btn btn-info active">All</button>
            <button id="showtpublic" class="btn btn-info">Public</button>
            <button id="showtprivate" class="btn btn-info">Private</button>
            <button id="showtpassword" class="btn btn-info">Password</button>
          </div>
          
          <div id="flip-scroll">
              <table width="100%" class="table table-hover table-striped cf basetable" id="contestlist">
                <thead>
                  <tr>
                    <th width='10%'> CID </th>
                    <th width="30%"> Title </th>
                    <th width='15%'> Start Time </th>
                    <th width='15%'> End Time </th>
                    <th width='10%'> Status </th>
                    <th width='10%'> Access </th>
                    <th width="10%"> Manager </th>
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
        </div>
    <div id="arrangevdialog" class="modal hide fade">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Arrange a virtual contest</h3>
        </div>
        <form method="post" action="ajax/vcontest_arrange.php" class="ajform form-inline" id="arrangeform">
            <div class="modal-body">
                <div class="well hide typenote">
                    In CF, Parameter A represents the points lost per minute. Parameter B represents the points lost for each incorrect submit.<br />
                    In CF Dynamic, parameters will decrease according to the AC ratio.<br />
                    In TC, parameters defined as below. A + B must equal to 1. Parameter C is usually the length of this contest in TopCoder. Parameter E is the percentage of penalty for each incorrect submit.<br />
                    <img src='img/tcpoint.png' />
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <table style="width:100%;">
                            <tr><th>Contest Information</th></tr>
                            <tr><td><input type="text" name="title" class="input-block-level" placeholder="Contest Title *" /></td></tr>
                            <tr><td>Type: <label class="radio inline"><input type="radio" name="ctype" value="0" checked="checked" /> ICPC format</label><label class="radio inline"><input type="radio" name="ctype" value="1" /> CF format</label> </td></tr>
                            <tr><td><textarea name="description" rows="8" class="input-block-level" placeholder="Contest Description"></textarea></td></tr>
                            <tr><td><div class="input-append input-prepend date datepick"><span class="add-on">Start Time* : </span><input id="prependedInput" type="text" name="start_time" value='<?=date("Y-m-d")." 09:00:00"?>'/><span class="add-on"><i class="icon-th"></i></span></div></td></tr>
                            <tr><td>( At least after 10 minutes )</td></tr>
                            <tr><td><div class="input-append input-prepend date datepick"><span class="add-on">End Time* : </span><input id="prependedInput" type="text" name="end_time" value='<?=date("Y-m-d")." 14:00:00"?>'/><span class="add-on"><i class="icon-th"></i></span></div></td></tr>
                            <tr><td>( Length should be between 30 minutes and 15 days )</td></tr>
                            <tr><td><div class="input-append input-prepend date datepick"><span class="add-on">Lock Board Time: </span><input id="prependedInput" type="text" name="lock_board_time" value='<?=date("Y-m-d")." 14:00:00"?>'/><span class="add-on"><i class="icon-th"></i></span></div></td></tr>
                            <tr><td>( Set it later than end time if you don't want to lock board )</td></tr>
                            <tr><td><label class="radio inline"><input type="radio" name="localtime" value="1" />Use local timezone</label><label class="radio inline"><input type="radio" name="localtime" value="0" checked="checked" /> Use server timezone</label></td></tr>
                            <tr><td>Your timezone: <span id="localtz"></span><input name="localtz" type="hidden" id="tzinp" /></td></tr>
                            <tr><td><label class="radio inline"><input type="radio" name="hide_others" value="1" /> Hide others' status</label><label class="radio inline"><input type="radio" name="hide_others" value="0" checked="checked" />  Show others' status</label></td></tr>
                            <tr><td><div class="input-prepend"><span class="add-on">Password: </span><input type="password" name="password" /></div></td></tr>
                            <tr><td>( Leave it blank if not needed )</td></tr>
                        </table>
                    </div>

<?php
if ($_GET['clone']==1) {
    $ccid=convert_str($_GET['cid']);
    if (contest_passed($ccid)&&(!contest_is_private($ccid)||($current_user->is_valid()&&($current_user->is_in_contest($ccid)||$current_user->is_root())))) {
        $ccrow=contest_get_problem_basic($ccid);
    }
}
$nn = $config["limits"]["problems_on_contest_add"];
?>
                    <div class='span6'>
                        <table style="width:100%">
                            <tr><th colspan="2">Add Problems For Contest</th></tr>
                            <tr><th colspan="2">Leave Problem ID blank if you don't want to add it.</th></tr>
<?php
$ccrow=(array) $ccrow;
for($i=0;$i<$nn;$i++){
    if ($i>=sizeof($ccrow)) $trow=array();
    else $trow=$ccrow[$i];
?>
                            <tr <?= ($i>=$config["limits"]["problems_on_contest_add_cf"])?"class='pextra'":"" ?>>
                                <th class="span3">Problem <?=chr($i+65)?> <input type="hidden" name="lable<?=$i?>" value="<?=chr($i+65)?>" /></th>
                                <td class="span9">
                                    <div>
                                        OJ: <select class="vpname input-small"><?=$ojoptions;?></select>
                                        <input class="vpid input-medium" type="text" value="<?=$trow["pid"]?>" placeholder="Problem ID" />
                                        <input class="vpid" type="hidden" name="pid<?=$i?>" value="<?=$trow["pid"]?>" /><?= $i==0?" *":""?>
                                        <br /><span></span>
                                    </div>
                                    <div class="selptype hide">
                                        <label class="radio inline"><input type="radio" class='ptype' name="ptype<?=$i?>" value="1" checked="checked" /> CF</label>
                                        <label class="radio inline"><input type="radio" class='ptype' name="ptype<?=$i?>" value="2" /> TC</label>
                                        <label class="radio inline"><input type="radio" class='ptype' name="ptype<?=$i?>" value="3" /> CF Dynamic</label>
                                    </div>
                                    <div class='well selpara hide'>
                                        <div class='cf tc'><label class="input">Base Value (MP) : <input type="text" class="input-small" value='500' name="base<?=$i?>" /></label></div>
                                        <div class='cf tc'><label class="input">Min Value: <input type="text" class="input-small" value='150' name="minp<?=$i?>" /></label></div>
                                        <div class='cf tc'><label class="input">Parameter A: <input type="text" class='paraa input-small' value="2" name="paraa<?=$i?>" /></label></div>
                                        <div class='cf tc'><label class="input">Parameter B: <input type="text" class='parab input-small' value="50" name="parab<?=$i?>" /></label></div>
                                        <div class='tc' style="display:none"><label class="input">Parameter C: <input class='parac input-small' type="text" name="parac<?=$i?>" /></label></div>
                                        <div class='tc' style="display:none"><label class="input">Parameter D: <input class='parad input-small' type="text" name="parad<?=$i?>" /></label></div>
                                        <div class='tc' style="display:none"><label class="input">Parameter E: <input class='parae input-small' type="text" name="parae<?=$i?>" /></label></div>
                                    </div>
                                </td>
                            </tr>
<?php
}
?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="msgbox" style="display:none"></span>
                <input name='login' class="btn btn-primary" type='submit' value='Submit' />
            </div>
        </form>
    </div>

<script type="text/javascript" src="js/jstz.min.js"></script>
<script type="text/javascript">
var timezone = jstz.determine_timezone();
$("#localtz").html(timezone.name()+" GMT"+timezone.offset());
$("#tzinp").val(timezone.name());
var searchstr='<?=$_GET['search']?>';
var conperpage=<?=$config["limits"]["contests_per_page"]?>;
var cshowtype='<?=$_GET['type']?>';
</script>
<script type="text/javascript" src="js/contest.js?<?=filemtime("js/contest.js")?>"></script>

<?php
include("footer.php");
?>
