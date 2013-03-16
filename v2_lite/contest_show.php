<?php
  include_once("conn.php");
  $cid = convert_str($_GET['cid']);
  if (db_contest_exist($cid)) $pagetitle=strip_tags(db_get_contest_title($cid));
  else $pagetitle="No Such Contest.";
  include_once("header.php");
  include_once("menu.php");
?>
    <script type="text/javascript" src="js/animator.js"></script>
    <script type="text/javascript" src="js/rankingTableUpdate.js"></script>
    <div id="site_content">
<?php
    if (db_contest_exist($cid)&&(db_contest_ispublic($cid)||db_user_in_contest($cid,$nowuser))) {
?>
      <div id="contest_nav" class="center">
        <button id="cinfo_a">Information</button>
        <button id="cprob_a">Problems</button>
<?php
    if (db_contest_type($cid)!=99) {
?>
        <button id="cstatus_a">Status</button>
<?php
    }
?>
        <button id="cstand_a">Standing</button>
<!--        <button id="cmerge_a">Merge Standing</button>-->
<?php
    if (db_contest_type($cid)!=99) {
?>
        <button id="cclar_a">Clarify</button>
<?php
    }
?>
        <button id="creport_a">Report</button>
<?php
    if (!db_contest_has_cha($cid)) {
?>
        <button id="cset_a">Settings</button>
<?php
    }
?>
<?php
    if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
        <button onclick="{location.href='admin_index.php?cid=<?php echo $cid;?>#contesttab'}">Edit</button>
        <button onclick="{location.href='balloon.php?cid=<?php echo $cid;?>'}">Balloon</button>
        <button id="cadminstand_a">Standing(Admin)</button>
<?php
    }
?>
      </div>
<?php
        if (db_contest_type($cid)==0) {
?>
      <div class='center' style='color:blue'>ICPC Format Contest</div>
<?php
        } else if (db_contest_type($cid)==1) {
?>
      <div class='center' style='color:blue'>CF Format Contest</div>
<?php
        } else if (db_contest_type($cid)==99) {
?>
      <div class='center' style='color:blue'>Replay Contest</div>
<?php
        }
?>
      <div id="contest_content">
        <div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>
      </div>
      <div id="temp_standing" style="display:none"></div>

<?php
        $csql="select cid,title,start_time,end_time,isvirtual,type from contest where cid = '$cid'";
//        echo $csql;
        $cres=mysql_query($csql);
?>
      <div id="csetdlg" class="topdialog" style="display:none" title="Standing Settings">
        <form id="csetform" action="#" method="post">
          <table style="width:780px" id="csettable" class="display">
            <thead>
              <tr><th style="width:50px"><input type="checkbox" style="width:20px" id="csetall"/></th><th style="width:60px">CID</th><th style="width:270px">Title</th><th style="width:160px">Start Time</th><th style="width:160px">End Time</th></tr>
            </thead>
            <tbody>
<?php
        while ($crow=mysql_fetch_array($cres)) {
?>
              <tr><td><input type="checkbox" style="width:20px" name="cid_<?php echo $crow['cid']; ?>" <?php if ($crow['cid']==$cid) echo "checked='checked'" ?> /></td><td><?php echo $crow['cid']; ?></td><td><a href='contest_show.php?cid=<?php echo $crow['cid']; ?>' target="_blank"><?php if($crow['type']==99) echo "<span style='color:blue'> [Replay] </span>"; if($crow['isvirtual']==1) echo "<span style='color:blue'> [Virtual] </span>"; echo $crow['title']; ?></a></td><td><?php echo $crow['start_time']; ?></td><td><?php echo $crow['end_time']; ?></td></tr>
<?php
        }
?>
            </tbody>
          </table>
          <div class="ui-state-highlight ui-corner-all" style="padding:1em;margin:15px 5px">
            <input type='radio' name='shownum' value='0' style="width:20px" <?php if (db_contest_has_cha($cid)) echo "checked='checked'"; ?> > Show All Teams ( Do NOT try this with auto refresh in IE&lt;9 ) <br />
            <input type='radio' name='shownum' value='50' style="width:20px"> Show top 50 teams <br />
            <input type='radio' name='shownum' value='100' <?php if (!db_contest_has_cha($cid)) echo "checked='checked'"; ?> style="width:20px"> Show top 100 teams <br />
            <input type='checkbox' name='autoref' id='autoref' style="width:20px"> Auto Refresh (10 Seconds) <br />
            <input type='checkbox' name='anim' id='animate' style="width:20px"> Animation? ( Only show top 20 teams, Chrome RECOMMENDED! ) <br />
          </div>
          <input type='submit' value='Confirm & Show' />
          <input type='hidden' name='cid' value='<?php echo $cid; ?>' />
        </form>
      </div>
<?php
    } else {
?>
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <p>
            <div class="error"><b>Contest Unavailable!</b></div>
          </p>
        </div>
        <div id="one_content_base"></div>
      </div>

<?php
    }
?>
    </div>

<?php
    include("footer.php");
?>
<script type="text/javascript" src="js/standing_func.js"></script>
<script type="text/javascript">
var currenttime = '<?php print date("l, F j, Y H:i:s",time()); ?>' //PHP method of getting server date
var statperpage = <?php echo $statusperpage; ?>;
var gcid = '<?php echo $cid; ?>';
var cpass= <?php if (db_contest_passed($cid)) echo "true"; else echo "false"; ?>;
</script>
<script type="text/javascript" src="pagejs/contest_show.js"></script>
<?php
    include("end.php");
?>
