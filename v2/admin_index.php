<?php
  $pagetitle="Admin Page";
  include_once("header.php");
  include_once("menu.php");
  include_once 'ckeditor/ckeditor.php' ;
  require_once 'ckfinder/ckfinder.php' ;
  $ckeditor = new CKEditor( ) ;
  $ckeditor->basePath = 'ckeditor/' ;
  CKFinder::SetupCKEditor( $ckeditor,'ckfinder/' ) ;
?>
    <div id="site_content">
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <!-- insert the page content here -->
<?php
  if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
          <h1>Admin Page</h1>
          <p>
            Current Server Time: <span id="servertime"><?php echo date("Y-m-d H:i:s"); ?></span>
          </p>
          <div id="admintab">
            <ul>
              <li><a href="#notificationtab">Notification</a></li>
              <li><a href="#problemtab">Problem</a></li>
              <li><a href="#contesttab">Contest</a></li>
              <li><a href="#rejudgetab">Rejudge</a></li>
              <li><a href="#replaytab">Replay</a></li>
              <li><a href="#newstab">News</a></li>
              <li><a href="#othertab">Others</a></li>
            </ul>
            <div id="notificationtab">
              <form id="notiform" action="#" method="post">
                <textarea id="notifycontent" name="sub" style="width:900px;height:200px"><?php echo htmlspecialchars($substitle); ?></textarea><br />
                <button type="submit">Change</button>
              </form>
            </div>
            <div id="problemtab">
              <form id='pload' method="get" action="#">Problem ID: <input type="text" id="npid" /> <button type="submit"> Load </button> <button type="button" onclick="resetpdetail()"> Reset </button> </form>
              <form id="pdetail" method="post" action="#">
                <table style="width:100%">
                  <tr><th colspan="2">Problem Information</th></tr>
                  <tr><td style="min-width:160px;">Problem ID:</td><td><input type="text" name="p_id" readonly="readonly" style="background-color:#ccc" /></td></tr>
                  <tr><td>Title:</td><td><input type="text" name="p_name" style="width:400px" /></tr>
                  <tr><td>Hide:</td><td><input type="radio" name="p_hide" value="1" style="width:30px" /> Yes &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="p_hide" value="0" checked="checked" style="width:30px" /> No</td></tr>
                  <tr><td>Time Limit:</td><td><input type="text" name="time_limit" value="1000" /> MS</td></tr>
                  <tr><td>Case Time Limit:</td><td><input type="text" name="case_time_limit" value="1000" /> MS</td></tr>
                  <tr><td>Only Case Limit? :</td><td><input type="radio" name="p_ignore_noc" value="1" style="width:30px" /> Yes &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="p_ignore_noc" value="0" checked="checked" style="width:30px" /> No</td></tr>
                  <tr><td>Memory Limit:</td><td><input type="text" name="memory_limit" value="65536" /> KB</td></tr>
                  <tr><td>Number of Testcases:</td><td><input type="text" name="noc" value="1" /></td></tr>
                  <tr><td>Special Judge:</td><td><input type="radio" name="special_judge_status" value="2" style="width:30px" /> JAVA &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="special_judge_status" value="1" style="width:30px" /> C++ &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="special_judge_status" value="0" checked="checked" style="width:30px" /> No</td></tr>
                  <tr><td>Description:</td><td><textarea id="tdescription" name="description" style="width:560px;height:400px;margin: 10px;"></textarea></td></tr>
                  <tr><td>Input:</td><td><textarea id="tinput" name="input" style="width:560px;height:400px;margin: 10px;"></textarea></td></tr>
                  <tr><td>Output:</td><td><textarea id="toutput" name="output" style="width:560px;height:400px;margin: 10px;"></textarea></td></tr>
                  <tr><td>Sample Input:</td><td><textarea name="sample_in" style="width:560px;height:200px;margin: 10px;"></textarea></td></tr>
                  <tr><td>Sample Output:</td><td><textarea name="sample_out" style="width:560px;height:200px;margin: 10px;"></textarea></td></tr>
                  <tr><td>Hint:</td><td><textarea id="thint" name="hint" style="width:560px;height:400px;margin: 10px;"></textarea></td></tr>
                  <tr><td>Source:</td><td><textarea name="source" style="width:560px;height:100px;margin: 10px;"></textarea></td></tr>
                  <tr><td>Author:</td><td><textarea name="author" style="width:560px;height:100px;margin: 10px;"></textarea></td></tr>
                </table>
                <button type="submit">Submit</button>
              </form>
            </div>
            <div id="contesttab">
                <form id='cload' method="get" action="#">
                    Contest ID: <input type="text" id="ncid" /><br />
                    <button type="submit"> Load </button>
                    <button type="button" onclick="resetcdetail()"> Reset </button>
                    <button type="button" id="clockp"> Lock Problem </button>
                    <button type="button" id="culockp"> Unlock Problem </button>
                    <button type="button" id="cshare"> Share Code </button>
                    <button type="button" id="cunshare"> Unshare Code </button>
                    <button type="button" id="ctestall"> Test All </button>
                </form>
                <form method="post" action="" id="cdetail">
                    <table style="width:100%;">
                        <tr><th colspan="2">Contest Information</th></tr>
                        <tr><td style="width:160px">Contest ID:</td><td><input type="text" readonly="readonly" name="cid" style='background-color:#ccc' /></td></tr>
                        <tr><td>Title:</td><td><input type="text" name="title" style='width:400px' /></td></tr>
                        <tr><td>Type: </td><td><input type="radio" style='width:20px' name="ctype" value="0" checked="checked" /> ICPC format &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" style='width:20px' name="ctype" value="1" /> CF format </td></tr>
                        <tr><td>Has Challenge? : </td><td><input type="radio" style='width:20px' name="has_cha" value="0" checked="checked" /> No &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" style='width:20px' name="has_cha" value="1" /> Yes </td></tr>
                        <tr><td>Description:</td><td><textarea name="description" style="width:480px;height:150px"></textarea></td></tr>
                        <tr><td>Start Time: </td><td><input type="text" name="start_time" class="datepick" value='<?php echo date("Y-m-d")." 09:00:00"; ?>'/></td></tr>
                        <tr><td>End Time: </td><td><input type="text" name="end_time" class="datepick" value='<?php echo date("Y-m-d")." 14:00:00"; ?>'/></td></tr>
                        <tr><td>Lock Board Time: </td><td><input type="text" name="lock_board_time" class="datepick" value='<?php echo date("Y-m-d")." 15:00:00"; ?>'/></td></tr>
                        <tr class="chatimerow" style="display:none"><td>Challenge Start Time: </td><td><input type="text" name="challenge_start_time" class="datepick" value='<?php echo date("Y-m-d")." 14:10:00"; ?>'/></td></tr>
                        <tr class="chatimerow" style="display:none"><td>Challenge End Time: </td><td><input type="text" name="challenge_end_time" class="datepick" value='<?php echo date("Y-m-d")." 14:25:00"; ?>'/></td></tr>
                        <tr><td>Hide Others' Status: </td><td><input type="radio" style='width:20px' name="hide_others" value="1" /> Yes &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" style='width:20px' name="hide_others" value="0" checked="checked" /> No </td></tr>
                        <tr><td>Private: </td><td><input type="radio" style='width:20px' name="isprivate" value="1" /> Yes &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" style='width:20px' name="isprivate" value="0" checked="checked" /> No </td></tr>
                        <tr><td>Report:</td><td><textarea id="treport" name="report" style="width:480px;height:150px"></textarea></td></tr>
                    </table>
<?php
      $nn = $problemcontestadd;
?>
                    <table style="width:100%">
                        <tr><th colspan="2">Add Problems For Contest</th></tr>
                        <tr><td colspan="2">
                            Clone cid: <input type='text' id="clcid" /> <button type="button" id="cclonecid">Clone</button>
                            Clone source: <input type='text' id="clsrc" /> <button type="button" id="cclonesrc">Clone</button>
                        </td></tr>
                        <tr><th colspan="2">Leave Problem ID blank if you don't want to add it.</th></tr>
<?php
      for($i=0;$i<$nn;$i++){
?>
                        <tr>
                            <td style="width:300px;text-align:center;">Problem <input type="text" name="lable<?php echo $i; ?>" value="<?php echo chr($i+65); ?>" style="text-align:center;width:80px" /></td>
                            <td class='selpid'>
                                <div style="float:left">Problem ID: <input type="text" name="pid<?php echo $i;?>" /></div>
                                <div style="float:left;padding:6px;margin-left:10px;display:none" class="selptype">
                                    <input type="radio" style='width:20px' class='ptype' name="ptype<?php echo $i; ?>" value="1" checked="checked" /> CF &nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" style='width:20px' class='ptype' name="ptype<?php echo $i; ?>" value="2" /> TC <br />
                                </div>
                                <div class='ui-state-highlight ui-corner-all selpara' style="padding:0.3em;display:none;clear:both">
                                    <div class='cf tc'>Base Value (MP) : <input type="text" value='500' name="base<?php echo $i; ?>" /></div>
                                    <div class='cf tc'>Min Value: <input type="text" value='150' name="minp<?php echo $i; ?>" /></div>
                                    <div class='cf tc'>Parameter A: <input type="text" class='paraa' value="2" name="paraa<?php echo $i; ?>" /></div>
                                    <div class='cf tc'>Parameter B: <input type="text" class='parab' value="50" name="parab<?php echo $i; ?>" /></div>
                                    <div class='tc' style="display:none">Parameter C: <input class='parac' type="text" name="parac<?php echo $i; ?>" /></div>
                                    <div class='tc' style="display:none">Parameter D: <input class='parad' type="text" name="parad<?php echo $i; ?>" /></div>
                                    <div class='tc' style="display:none">Parameter E: <input class='parae' type="text" name="parae<?php echo $i; ?>" /></div>
                                    <div class='typenote' style="color:blue"></div>
                                </div>
                            </td>
                        </tr>
<?php
      }
?>
                    </table>
                    <table style="width:100%">
                        <tr><th>Add User For Contest (Seperate them by '|')</th></tr>
                        <tr><td><textarea name="names" style="width:800px;height:200px;"></textarea></td></tr>
                    </table>
                    <button type="submit">Submit</button>
                </form>
            </div>
            <div id="rejudgetab">
                <form id="crej" method='get' action="#">
                    <fieldset>
                        <legend>Rejudge Problem in Contest</legend>
                        <table style="width:100%">
                            <tr><td style="width:160px;border:0;">Contest ID: </td><td style="border:0;"><input type="text" id="rejcid" /></td></tr>
                            <tr><td style="border:0;">Problem ID: </td><td style="border:0;"><input type="text" id="rejpid" /></td></tr>
                            <tr><td style="border:0;">Rejudge AC?</td><td style="border:0;"><input type="radio" name="rejac" value="1" style="width:30px" /> Yes &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="rejac" value="0" checked="checked" style="width:30px" /> No</td></tr>
                        </table>
                        <button type="submit">Rejudge</button>
                    </fieldset>
                </form>
                <form id="cprej" method='get' action="#">
                    <fieldset>
                        <legend>Rejudge Problem in Contest (Using Label)</legend>
                        <table style="width:100%">
                            <tr><td style="width:160px;border:0;">Contest ID: </td><td style="border:0;"><input type="text" id="rcid" /></td></tr>
                            <tr><td style="border:0;">Problem Label: </td><td style="border:0;"><input type="text" id="rpid" /></td></tr>
                            <tr><td style="border:0;">Rejudge AC?</td><td style="border:0;"><input type="radio" name="rac" value="1" style="width:30px" /> Yes &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="rac" value="0" checked="checked" style="width:30px" /> No</td></tr>
                        </table>
                        <button type="submit">Rejudge</button>
                    </fieldset>
                </form>
                <form id="runrej" method='post' action="#">
                    <fieldset>
                        <legend>Rejudge Runid</legend>
                        <table style="width:100%">
                            <tr><td style="width:160px;border:0;">Run ID: </td><td style="border:0;"><input type="text" id="runid" /></td></tr>
                        </table>
                        <button type="submit">Rejudge</button>
                    </fieldset>
                </form>
                <form id="cha_crej" method='get' action="#">
                    <fieldset>
                        <legend>Rejudge All Challenges in Contest</legend>
                        <table style="width:100%">
                            <tr><td style="width:160px;border:0;">Contest ID: </td><td style="border:0;"><input type="text" id="rcha_cid" /></td></tr>
                        </table>
                        <button type="submit">Rejudge</button>
                    </fieldset>
                </form>
            </div>
            <div id="replaytab">
                <table style="width:100%">
                    <tr><th colspan="2">Auto Crawl Form</th></tr>
                    <tr>
                        <td style="width:260px">Auto Crawl:</td>
                        <td>
                            OJ: <select id="vcojname" style="width:120px;padding:5px">
                                <option value="HUSTV">HUST Vjudge</option>
                                <option value="ZJU">ZJU</option>
                                <option value="UESTC">UESTC</option>
                                <option value="UVA">UVA</option>
                            </select>
                            Contest ID: <input id="vcid" type="text" />
                            <button type="button" id="replaycrawl">Crawl!</button>
                        </td>
                    </tr>
                </table>
                <form id='replayform' method='post' action="admin_deal_replay.php" enctype="multipart/form-data">
                    <table style="width:100%">
                        <tr><th colspan="2">Replay Contest Information</th></tr>
                        <tr><td style="width:260px">Contest Name:</td><td><input type="text" name="name" style="width:400px" /></td></tr>
                        <tr><td>Description:</td><td><textarea name="description" style="width:480px;height:150px"></textarea></td></tr>
                        <tr><td>Start Time: </td><td><input type="text" name="start_time" value='<?php echo date("Y-m-d")." 09:00:00"; ?>'/></td></tr>
                        <tr><td>End Time: </td><td><input type="text" name="end_time" value='<?php echo date("Y-m-d")." 14:00:00"; ?>'/></td></tr>
                        <tr><td>Submit Frequency: </td><td><input type="text" name="sfreq" value="180" /> Second(s) (Minimum 10)</td></tr>
                        <tr><td>Standing File: </td><td><input type="file" name="file" id="file" /></td></tr>
                        <tr><td>Or Standing URL: </td><td><input name="repurl" id="repurl" type="text" style="width:600px" /></td></tr>
                        <tr>
                            <td>File Type: </td>
                            <td>
                                <input type="radio" style='width:20px' name="ctype" value="hdu" checked="checked" /> HDU Excel &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="myexcel" /> My Excel &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="licstar" /> licstar 2011 version (Zlinkin) &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="ctu" /> CTU Submits &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="ural" /> Ural &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="zju" /> ZJU Excel &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="jhinv" /> Jinhua &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="zjuhtml" /> ZJU HTML &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="neerc" /> NEERC &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="2011shstatus" /> 2011 Shanghai Status &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="pc2sum" /> PC<sup>2</sup> Summary &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="fdulocal2012" /> FDU Local 2012 &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="uestc" /> UESTC &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="hustvjson" /> HUST VJudge JSON &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="fzuhtml" /> FZU HTML &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="usuhtml" /> USU HTML &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="sguhtml" /> SGU HTML &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="amt2011" /> Amritapuri 2011 &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="nwerc" /> NWERC &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="ncpc" /> NCPC &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="uva" /> UVA &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="gcpc" /> GCPC &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="phuket" /> Phuket &nbsp;&nbsp;&nbsp;&nbsp; 
                                <input type="radio" style='width:20px' name="ctype" value="spacific" /> South Pacific &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" style='width:20px' name="ctype" value="icpcinfostatus" /> ACMICPC info Status &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" style='width:20px' name="ctype" value="spoj" /> SPOJ
                            </td>
                        </tr>
                        <tr><td>Extra Information: </td><td><input type="text" name="extrainfo" style="width:400px;" /></td></tr>
                        <tr><td>Virtual? : </td><td><input type="radio" style='width:20px' name="isvirtual" value="0" checked="checked" /> No &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" style='width:20px' name="isvirtual" value="1" /> Yes</td></tr>
                        <tr><th colspan="2">Add Problems For Contest</th></tr>
                        <tr><td colspan="2">
                            Clone cid: <input type='text' id="vclcid" /> <button type="button" id="vclonecid">Clone</button>
                            Clone source: <input type='text' id="vclsrc" /> <button type="button" id="vclonesrc">Clone</button>
                        </td></tr>
                        <tr><th colspan="2">Fill in order. Leave Problem ID blank if not exists.</th></tr>
<?php
      for($i=0;$i<$nn;$i++){
?>
                        <tr>
                            <td style="text-align:center">Problem <?php echo chr($i+65); ?><input type="hidden" name="lable<?php echo $i; ?>" value="<?php echo chr($i+65); ?>" /></td>
                            <td>
                            OJ: <select class="vpname" style="width:120px;padding:5px"><?echo $ojoptions; ?></select>
                                Problem ID: <input class="vpid" name="vpid<?php echo $i;?>" type="text" />
                                <input class="vpid" type="hidden" name="pid<?php echo $i;?>" />
                                <br /><span style="overflow:hidden;color:red"></span>
                            </td>
                        </tr>
<?php
      }
?>
                    </table>
                    <button type="submit" id="replaysubmit">Submit</button>
                </form>
                <div id="dealreplay" style="display:none"><img src="style/ajax-loader.gif" /> Processing...</div>
            </div>
            <div id="newstab">
                <form id='nload' method="get" action="#">
                    News ID: <input type="text" id="nnid" /><br />
                    <button type="submit"> Load </button>
                    <button type="button" onclick="resetndetail()"> Reset </button>
                </form>
                <form id='ndetail' method="post" action="#">
                    <table style="width:100%;">
                        <tr><th colspan="2">News Information</th></tr>
                        <tr><td style="width:100px">News ID:</td><td><input type="text" readonly="readonly" name="newsid" style='background-color:#ccc' /></td></tr>
                        <tr><td>Title:</td><td><input type="text" name="ntitle" style='width:400px' /></td></tr>
                        <tr><td>Content:</td><td><textarea id="tncontent" name="ncontent" style="width:480px;height:150px"></textarea></td></tr>
                    </table>
                    <button type="submit">Add</button>
                </form>
            </div>
            <div id="othertab">
                <button style="button" id="spinfo" class="syncbutton">Sync Problem Info</button>
                <button style="button" id="suinfo" class="syncbutton">Sync User Info</button>
                <div id="syncwait" style="display:none"><img src="style/ajax-loader.gif" /> Loading...</div>
            </div>
          </div>
<?php
    $ckeditor->replace('tdescription');
    $ckeditor->replace('tinput');
    $ckeditor->replace('toutput');
    $ckeditor->replace('thint');
    $ckeditor->replace('treport');
    $ckeditor->replace('tncontent');
  }
  else {
?>
          <div class='error'>Invalid Request!</div>
<?php
  }
?>
        </div>
        <div id="one_content_base"></div>
      </div>
    </div>

<?php
    include("footer.php");
?>
<?php
  if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
<script type="text/javascript">
var currenttime = '<?php print date("l, F j, Y H:i:s",time()); ?>' //PHP method of getting server date
</script>
<script type="text/javascript" src="pagejs/admin.js?<?php echo filemtime("pagejs/admin.js"); ?>" ></script>
<?php
  }
    include("end.php");
?>
