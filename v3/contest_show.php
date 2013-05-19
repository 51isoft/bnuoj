<?php
include_once("functions/contests.php");
$cid = convert_str($_GET['cid']);
if (contest_exist($cid)) $pagetitle=strip_tags(contest_get_val($cid,"title"));
else $pagetitle="No Such Contest.";
include_once("header.php");
if (contest_exist($cid)&&($current_user->is_root()||contest_get_val($cid,"isprivate")==0||
                          (contest_get_val($cid,"isprivate")==1&&$current_user->is_in_contest($cid))||
                          (contest_get_val($cid,"isprivate")==2&&contest_get_val($cid,"password")==$_COOKIE[$config["cookie_prefix"]."contest_pass_$cid"]))) {
?>
      <ul class="nav nav-tabs" id="contest_nav">
        <!-- <div class="btn-group"> -->
          <li id="cinfo_a"><a href="#info">Information</a></li>
          <li id="cprob_a"><a href="#problem/0">Problems</a></li>
<?php
    if (contest_get_val($cid,"type")!=99) {//not replay
?>
          <li id="cstatus_a"><a href="#status">Status</a></li>
<?php
    }
?>
          <li id="cstand_a"><a href="#standing">Standing</a></li>

<?php
    if ($current_user->is_root()&&contest_get_val($cid,"type")!=99) {
?>
          <li id="cadminstand_a"><a href="#adminstanding">Standing(Admin)</a></li>
<?php
    }
?>
<?php
    if (contest_get_val($cid,"type")!=99) {
?>
          <li id="cclar_a"><a href="#clarify">Clarify</a></li>
<?php
    }
?>
          <li id="creport_a"><a href="#report">Report</a></li>
          <div class="pull-right btn-group">
<?php
    if (contest_get_val($cid,"has_cha")!=1) {//no challenge
?>
            <a class="btn btn-info" id="cset_a">Settings</a>
<?php
    }
?>
<?php
    if (contest_get_val($cid,"isvirtual")==0) {//standard contest
        if ($current_user->is_root()&&contest_get_val($cid,"type")!=99) {
?>
            <a href="admin_index.php?cid=<?=$cid?>#contesttab" class="btn btn-info">Edit</a>
<?php
        }
    }else {
        if (!contest_passed($cid)&&($current_user->is_root()||$current_user->match(contest_get_val($cid,"owner")))) {
?>
            <a href="contest_edit.php?cid=<?=$cid ?>" class="btn btn-info">Edit</a>
<?php
        }
    }
?>
<?php
    if ($current_user->is_valid()&&contest_passed($cid)) {//able to clone
?>
            <a href="contest.php?type=50&open=1&clone=1&cid=<?=$cid ?>" class="btn btn-info">Clone</a>
<?php
    }
?>
          </div>
        <!-- </div> -->
      </ul>
      <div id="contest_content">
        <div class="tcenter"><img src="img/ajax-loader.gif" />Loading...</div>
      </div>
      <div width="0px" id="temp_standing" style="display:none"></div>

      <div id="csetdlg" class="modal hide fade">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3>Standing Settings</h3>
        </div>
        <form id="csetform" action="#" method="post">
          <div class="modal-body">
            <table width="100%" id="csettable" class="table table-striped table-hover table-condensed basetable">
              <thead>
                <tr><th width="5%"><input type="checkbox" id="csetall"/></th><th width="10%">CID</th><th width="45%">Title</th><th width="20%">Start Time</th><th width="20%">End Time</th></tr>
              </thead>
              <tbody>
<?php

    foreach ((array)contest_get_comparable_list($cid) as $value) {
?>
                <tr><td><input type="checkbox" name="cid_<?=$value?>" <?= $value==$cid?"checked='checked'":"class='othc'" ?> /></td><td><?=$value?></td><td><a href='contest_show.php?cid=<?=$value?>' target="_blank"><?php if(contest_get_val($value,"type")==99) echo "<span style='color:blue'> [Replay] </span>"; if(contest_get_val($value,"isvirtual")==1) echo "<span style='color:blue'> [Virtual] </span>"; echo contest_get_val($value,"title"); ?></a></td><td><?=contest_get_val($value,"start_time")?></td><td><?=contest_get_val($value,"end_time")?></td></tr>
<?php
    }
?>
              </tbody>
            </table>
            <div class="well">
              <label class="radio"><input type='radio' name='shownum' value='0' <?=contest_get_val($cid,"has_cha")?"checked='checked'":"" ?> > Show All Teams ( Do NOT try this with auto refresh in IE&lt;9 ) </label>
              <label class="radio"><input type='radio' name='shownum' value='50'> Show top 50 teams </label>
              <label class="radio"><input type='radio' name='shownum' value='100' <?=!contest_get_val($cid,"has_cha")?"checked='checked'":"" ?>> Show top 100 teams </label>
              <label class="checkbox"><input type='checkbox' name='autoref' id='autoref'> Auto Refresh (10 Seconds) </label>
              <label class="checkbox"><input type='checkbox' name='anim' id='animate'> Animation? ( Only show top <?=$config["limits"]["max_rank_in_animation"]?> teams, Chrome RECOMMENDED! ) </label>
            </div>
          </div>
          <div class="modal-footer">
            <input name='login' class="btn btn-primary" type='submit' value='Confirm & Show' />
          </div>
          <input type='hidden' name='cid' value='<?=$cid ?>' />
        </form>
      </div>

<?php
} else if (contest_exist($cid)&&contest_get_val($cid,"password")!="") {
?>
        <div class="span12">
          <form id="cpasssub">
            <div class="input-append"><input type="password" name="cpass" id="contest_password" placeholder="Input password" /><button class="btn btn-primary" type="submit">Confirm</button></div> 
          </form>
        </div>

<?php
} else {
?>
        <div class="span12">
          <p class="alert alert-error">Contest Unavailable!</p>
        </div>

<?php
}
?>
<script type="text/javascript">
var statperpage = <?=$config["limits"]["status_per_page"]?>;
var gcid = '<?=$cid ?>';
var cpass= <?= contest_passed($cid)?"true":"false" ?>;
var cnt=<?php
if (contest_passed($cid)) echo "0";
else if (contest_intermission($cid)) echo strtotime(contest_get_val($cid,"challenge_start_time"));
else if (contest_challenging($cid)) echo strtotime(contest_get_val($cid,"challenge_end_time"));
else if (contest_running($cid)) echo strtotime(contest_get_val($cid,"end_time"));
else echo strtotime(contest_get_val($cid,"start_time"));
?>;
var stp=-1;
var refrate=<?=$config["status"]["refresh_rate"]?>;
var lim_times=<?=$config["status"]["max_refresh_times"]?>;
</script>
<link href="css/prettify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="js/prettify.js"></script>
<script type="text/javascript" src="js/adjlist.js?<?=filemtime("js/adjlist.js")?>" ></script>
<script type="text/javascript" src="js/animator.js"></script>
<script type="text/javascript" src="js/rankingTableUpdate.js"></script>
<script type="text/javascript" src="js/contest_show.js?<?php echo filemtime("js/contest_show.js"); ?>"></script>
<?php
include("footer.php");
?>
