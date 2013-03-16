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
    if (db_contest_exist($cid)&&(db_contest_ispublic($cid)||(db_contest_private($cid)&&db_user_in_contest($cid,$nowuser))||(db_contest_password($cid)&&db_user_in_contest($cid,$nowuser)))) {
?>
      <div id="contest_nav" class="center">
        <a id="cinfo_a" class="button" href="#info">Information</a>
        <a id="cprob_a" class="button" href="#problem/0">Problems</a>
<?php
    if (db_contest_type($cid)!=99) {
?>
        <a id="cstatus_a" class="button" href="#status">Status</a>
<?php
    }
?>
        <a id="cstand_a" class="button" href="#standing">Standing</a>

<?php
    if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)&&db_contest_type($cid)!=99) {
?>
        <a id="cadminstand_a" class="button" href="#adminstanding">Standing(Admin)</a>
<?php
    }
?>
<?php
    if (db_contest_type($cid)!=99) {
?>
        <a id="cclar_a" class="button" href="#clarify">Clarify</a>
<?php
    }
?>
        <a id="creport_a" class="button" href="#report">Report</a>
<?php
    if (!db_contest_has_cha($cid)) {
?>
        <button id="cset_a">Settings</button>
<?php
    }
?>
<?php
    if (db_contest_isvirtual($cid)==0) {
        if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)&&db_contest_type($cid)!=99) {
?>
        <a href="admin_index.php?cid=<?php echo $cid;?>#contesttab" class="button">Edit</a>
<?php
        }
    }else {
        if (db_contest_started($cid)==false&&db_user_match($nowuser,$nowpass)&&(db_user_isroot($nowuser)||strcasecmp(db_contest_owner($cid),$nowuser)==0)) {
?>
        <a href="contest_edit.php?cid=<?php echo $cid;?>" class="button">Edit</a>
<?php
        }
    }
?>
<?php
    if (db_user_match($nowuser,$nowpass)&&db_contest_passed($cid)) {
?>
        <a href="contest.php?type=50&open=1&clone=1&cid=<?php echo $cid;?>" class="button">Clone</a>
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
        list($allp) = @mysql_fetch_array(mysql_query("SELECT allp FROM contest WHERE cid = '$cid'"));
        if (!db_contest_started($cid)) $csql="select cid,title,start_time,end_time,isvirtual,type from contest where cid = '$cid'";
        else $csql="select cid,title,start_time,end_time,isvirtual,type from contest where allp = '$allp' and start_time < NOW() order by cid desc";
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
              <tr><td><input type="checkbox" style="width:20px" name="cid_<?php echo $crow['cid']; ?>" <?php if ($crow['cid']==$cid) echo "checked='checked'" ?> <?php if ($crow['cid']!=$cid) echo "class='othc'" ?> /></td><td><?php echo $crow['cid']; ?></td><td><a href='contest_show.php?cid=<?php echo $crow['cid']; ?>' target="_blank"><?php if($crow['type']==99) echo "<span style='color:blue'> [Replay] </span>"; if($crow['isvirtual']==1) echo "<span style='color:blue'> [Virtual] </span>"; echo $crow['title']; ?></a></td><td><?php echo $crow['start_time']; ?></td><td><?php echo $crow['end_time']; ?></td></tr>
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
    if (db_contest_has_cha($cid)) {
?>
      <div id="cchainfo" class="topdialog" style="display:none" title="Challenge Info">
        <img src="" id="chasrcimage" /><br />
<?php
        if (db_contest_challenging($cid)||db_contest_intermission($cid)) {
?>
        <form id="cchaform" action="#" method="post">
          <b>Data Type: </b><input type='radio' name='chadata_type' value='0' checked="checked" style="width:20px"> Raw Data &nbsp;&nbsp;&nbsp;&nbsp; <input type='radio' name='chadata_type' value='1' style="width:20px"> Souce Code <br />
          <div id="cha_lang_select" style="display:none">
            <b>Language: </b><select name="chadata_lang">
              <option value="1" selected='selected' >GNU C++</option>
              <option value="2">GNU C</option>
              <option value="3">Oracle Java</option>
              <option value="4">Free Pascal</option>
              <option value="5">Python</option>
            </select><br />
          </div>
          <b>Data Detail: </b><br />
          <textarea rows="16" name="chadata_detail" style="width:650px" onKeyUp="if(this.value.length > 32768) this.value=this.value.substr(0,32768)"></textarea><br />
          <input type='submit' value='Challenge!' /><span id="chamsgbox" style="display:none; z-index:600;"></span>
          <input type="hidden" value="" name="username" id="chaformuser">
          <input type="hidden" value="" name="pid" id="chaformpid">
          <input type="hidden" value="" name="cid" id="chaformcid">
        </form>
<?php
        }
?>
        <div id="cchadetail" style="display:none;margin-bottom:10px">
          <h4>Challenge Detail</h4>
          <div class="content-wrapper ui-corner-all" style="padding:1em;" id="cchadetailcontent">
          </div>
        </div>
        <h4>Challenge History</h4>
        <div class="content-wrapper ui-corner-all" style="padding:1em;" id="cchahistory">
        </div>
      </div>
<?php
    }
?>
<?php
    } else if (db_contest_exist($cid)&&db_contest_password($cid)) {
?>
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
            Please Input Password: <input type="password" name="cpass" id="contest_password" style="padding:5px"  /> <button type="submit" id="cpasssub">Confirm</button>
        </div>
        <div id="one_content_base"></div>
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
<script type="text/javascript" src="js/sh_pascal.js"></script>
<script type="text/javascript" src="js/sh_sml.js"></script>
<script type="text/javascript" src="js/adjlist.js?<?php echo filemtime("js/adjlist.js"); ?>" ></script>
<script type="text/javascript" src="js/standing_func.js"></script>
<script type="text/javascript">
var currenttime = '<?php print date("l, F j, Y H:i:s",time()); ?>' //PHP method of getting server date
var statperpage = <?php echo $statusperpage; ?>;
var gcid = '<?php echo $cid; ?>';
var cpass= <?php if (db_contest_passed($cid)) echo "true"; else echo "false"; ?>;
var cnt=<?php 
$query="select start_time,end_time,challenge_end_time,challenge_start_time from contest where cid='$cid'";
$result=mysql_query($query);
$row=mysql_fetch_array($result);
$nowtime=time();
if (db_contest_passed($cid)) echo "0";
else if (db_contest_intermission($cid)) echo strtotime($row[3])-$nowtime;
else if (db_contest_challenging($cid)) echo strtotime($row[2])-$nowtime;
else if (db_contest_running($cid)) echo strtotime($row[1])-$nowtime;
else echo strtotime($row[0])-$nowtime;
?>;
var stp=-1;
</script>
<script type="text/javascript" src="pagejs/contest_show.js?<?php echo filemtime("pagejs/contest_show.js"); ?>"></script>
<?php
    include("end.php");
?>
