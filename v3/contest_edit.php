<?php
include_once("functions/contests.php");
include_once("functions/users.php");
$pagetitle="Contest Editor";
include_once("header.php");
$cid=convert_str($_GET['cid']);
?>
        <div class="span12">
<?php
if (contest_exist($cid)&&!contest_passed($cid)&&($current_user->is_root()||$current_user->match(contest_get_val($cid,"owner")))) {
?>
          <div class='well' style="color:blue;display:none;">
            In CF, Parameter A represents the points lost per minute. Parameter B represents the points lost for each incorrect submit.<br />
            In CF Dynamic, parameters will decrease according to the AC ratio.<br />
            In TC, parameters defined as below. A + B must equal to 1. Parameter C is usually the length of this contest in TopCoder. Parameter E is the percentage of penalty for each incorrect submit.<br />
            <img src='img/tcpoint.png' />
          </div>
          <form method="post" action="ajax/vcontest_modify.php" id="cmodifyform" class="ajform form-horizontal">
            <input name="cid" value='<?= $cid; ?>' type="hidden" />
            <div class="row-fluid">
                <div class="span6">
                    <table style="width:100%;">
                        <tr><th>Contest Information</th></tr>

                        <tr><td><input type="text" name="title" value="<?=contest_get_val($cid,"title") ?>" class="input-block-level" placeholder="Contest Title *" /></td></tr>
<?php
if (!contest_started($cid)) {
?>
                        <tr><td>Type: <label class="radio inline"><input type="radio" name="ctype" value="0" <?=contest_get_val($cid,"type")==0?'checked="checked"':'' ?> /> ICPC format</label><label class="radio inline"><input type="radio" name="ctype" value="1" <?=contest_get_val($cid,"type")==1?'checked="checked"':'' ?> /> CF format</label> </td></tr>
<?php
}
?>
                        <tr><td><textarea name="description" rows="8" class="input-block-level" placeholder="Contest Description"><?=htmlspecialchars(contest_get_val($cid,"description")) ?></textarea></td></tr>
<?php
if (!contest_started($cid)) {
?>
                        <tr><td><div class="input-append input-prepend date datepick"><span class="add-on">Start Time* : </span><input id="prependedInput" type="text" name="start_time" value='<?=contest_get_val($cid,"start_time") ?>'/><span class="add-on"><i class="icon-th"></i></span></div></td></tr>
                        <tr><td>( At least after 10 minutes )</td></tr>
                        <tr><td><div class="input-append input-prepend date datepick"><span class="add-on">End Time* : </span><input id="prependedInput" type="text" name="end_time" value='<?=contest_get_val($cid,"end_time") ?>'/><span class="add-on"><i class="icon-th"></i></span></div></td></tr>
                        <tr><td>( Length should be between 30 minutes and 15 days )</td></tr>
                        <tr><td><div class="input-append input-prepend date datepick"><span class="add-on">Lock Board Time: </span><input id="prependedInput" type="text" name="lock_board_time" value='<?=contest_get_val($cid,"lock_board_time") ?>'/><span class="add-on"><i class="icon-th"></i></span></div></td></tr>
                        <tr><td>( Set it later than end time if you don't want to lock board )</td></tr>
                        <tr><td><label class="radio inline"><input type="radio" name="localtime" value="1" />Use local timezone</label><label class="radio inline"><input type="radio" name="localtime" value="0" checked="checked" /> Use server timezone</label></td></tr>
                        <tr><td>Your timezone: <span id="localtz"></span><input name="localtz" type="hidden" id="tzinp" /></td></tr>
                        <tr><td><label class="radio inline"><input type="radio" name="hide_others" value="1" <?=contest_get_val($cid,"hide_others")==1?'checked="checked"':'' ?> /> Hide others' status</label><label class="radio inline"><input type="radio" name="hide_others" value="0" <?=contest_get_val($cid,"hide_others")==0?'checked="checked"':'' ?> />  Show others' status</label></td></tr>
<?php
}
?>
                        <tr><td><div class="input-prepend"><span class="add-on">Password: </span><input type="password" name="password" /></div></td></tr>
                        <tr><td>( Leave it blank if not needed )</td></tr>
                    </table>
                </div>

<?php
if (!contest_started($cid)) {
    $ccrow=(array)contest_get_problem_basic($cid);
    $nn = $config["limits"]["problems_on_contest_add"];
?>
                <div class='span6'>
                    <table style="width:100%">
                        <tr><th colspan="2">Add Problems For Contest</th></tr>
                        <tr><th colspan="2">Leave Problem ID blank if you don't want to add it.</th></tr>
<?php
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
                                    <label class="radio inline"><input type="radio" class='ptype' name="ptype<?=$i?>" value="1" <?=($trow['type']==1||$trow['type']==0)?'checked="checked"':'' ?> /> CF</label> &nbsp;&nbsp;
                                    <label class="radio inline"><input type="radio" class='ptype' name="ptype<?=$i?>" value="2" <?=$trow['type']==2?'checked="checked"':'' ?> /> TC</label> &nbsp;&nbsp;
                                    <label class="radio inline"><input type="radio" class='ptype' name="ptype<?=$i?>" value="3" <?=$trow['type']==3?'checked="checked"':'' ?> /> CF Dynamic</label> <br />
                                </div>
                                <div class='well selpara hide'>
                                    <div class='cf tc'><label class="input">Base Value (MP) : <input type="text" class="input-small" value='<?=$trow['base']?>' name="base<?=$i?>" /></label></div>
                                    <div class='cf tc'><label class="input">Min Value: <input type="text" class="input-small" value='<?=$trow['minp']?>' name="minp<?=$i?>" /></label></div>
                                    <div class='cf tc'><label class="input">Parameter A: <input type="text" class='paraa input-small' value="<?=$trow['para_a']?>" name="paraa<?=$i?>" /></label></div>
                                    <div class='cf tc'><label class="input">Parameter B: <input type="text" class='parab input-small' value="<?=$trow['para_b']?>" name="parab<?=$i?>" /></label></div>
                                    <div class='tc' style="display:none"><label class="input">Parameter C: <input class='parac input-small' value="<?=$trow['para_c']?>" type="text" name="parac<?=$i?>" /></label></div>
                                    <div class='tc' style="display:none"><label class="input">Parameter D: <input class='parad input-small' value="<?=$trow['para_d']?>" type="text" name="parad<?=$i?>" /></label></div>
                                    <div class='tc' style="display:none"><label class="input">Parameter E: <input class='parae input-small' value="<?=$trow['para_e']?>" type="text" name="parae<?=$i?>" /></label></div>
                                </div>
                            </td>
                        </tr>

<?php
    }
?>
                    </table>
                </div>
            </div>
<?php
}
?>
            <div style='clear:both;'>
                <input class="btn btn-primary" type="submit" name="Submit" value="Submit" />
                <span id="msgbox"></span>
            </div>
          </form>
<?php
} else {
?>
          <div class="alert alert-error">Invalid request!</div>
<?php
}
?>
        </div>
<script type="text/javascript" src="js/jstz.min.js"></script>
<script type="text/javascript">
var timezone = jstz.determine_timezone();
$("#localtz").html(timezone.name()+" GMT"+timezone.offset());
$("#tzinp").val(timezone.name());
</script>
<script type="text/javascript" src="js/contest_edit.js?<?=filemtime("js/contest_edit.js") ?>"></script>

<?php
include_once("footer.php");
?>