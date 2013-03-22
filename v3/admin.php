<?php
$pagetitle="Admin Page";
include_once("header.php");
include_once("functions/global.php");
include_once 'ckeditor/ckeditor.php' ;
require_once 'ckfinder/ckfinder.php' ;
$ckeditor = new CKEditor( ) ;
$ckeditor->basePath = 'ckeditor/' ;
CKFinder::SetupCKEditor( $ckeditor,'ckfinder/' ) ;
?>
        <div class="span12">
          <!-- insert the page content here -->
<?php
if ($current_user->is_root()) {
?>
          <h1>Admin Page</h1>
          <p>
            Current Server Time: <span id="servertime"><?php echo date("Y-m-d H:i:s"); ?></span>
          </p>
          <ul class="nav nav-tabs" id="admintab">
            <li class="active"><a href="#notificationtab" data-toggle="tab">Notification</a></li>
            <li><a href="#problemtab" data-toggle="tab">Problem</a></li>
            <li><a href="#contesttab" data-toggle="tab">Contest</a></li>
            <li><a href="#rejudgetab" data-toggle="tab">Rejudge</a></li>
            <li><a href="#replaytab" data-toggle="tab">Replay</a></li>
            <li><a href="#newstab" data-toggle="tab">News</a></li>
            <li><a href="#othertab" data-toggle="tab">Others</a></li>
          </ul>
          <div class="tab-content">
            <div id="notificationtab" class="tab-pane active">
              <form id="notiform" action="#" method="post">
                <textarea id="notifycontent" name="sub" rows="10" class="input-block-level"><?=get_substitle()?></textarea>
                <div class="pull-right">
                  <span id="msgbox" style="display:none"></span><button class="btn btn-primary" type="submit">Change</button>
                </div>
              </form>
            </div>
            <div id="problemtab" class="tab-pane">
              <form id='pload' method="get" action="#" class="form-inline">
                <input type="text" id="npid" class="input-medium" placeholder='Problem ID' />
                <button class="btn btn-primary" type="submit"> Load </button>
                <button class="btn btn-danger" type="button" onclick="resetpdetail()"> Reset </button>
              </form>
              <form id="pdetail" method="post" action="#" class="form-horizontal ajform">
                <h4>Problem Details</h4>
                <table class="table table-bordered table-condensed" style="width:100%">
                  <tr><td>Problem ID</td><td><input type="text" name="p_id" readonly="readonly" class="input-small" /></td></tr>
                  <tr><td>Title</td><td><input type="text" name="p_name" class="input-xxlarge" /></tr>
                  <tr>
                    <td>Hide</td>
                    <td>
                      <label class="radio inline"><input type="radio" name="p_hide" value="1" /> Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;
                      <label class="radio inline"><input type="radio" name="p_hide" value="0" checked="checked" /> No</label>
                    </td>
                  </tr>
                  <tr><td>Time Limit</td><td><input type="text" name="time_limit" value="1000" class="input-small" /> ms</td></tr>
                  <tr><td>Case Time Limit</td><td><input type="text" name="case_time_limit" value="1000" class="input-small" /> ms</td></tr>
                  <tr>
                    <td>Only Case Limit?</td>
                    <td>
                      <label class="radio inline"><input type="radio" name="p_ignore_noc" value="1" /> Yes</label> &nbsp;&nbsp;&nbsp;&nbsp;
                      <label class="radio inline"><input type="radio" name="p_ignore_noc" value="0" checked="checked" /> No</label>
                    </td>
                  </tr>
                  <tr><td>Memory Limit</td><td><input type="text" name="memory_limit" value="65536" class="input-small" /> KB</td></tr>
                  <tr class="hide"><td>Number of Testcases</td><td><input type="text" name="noc" value="1" class="input-small" /></td></tr>
                  <tr>
                    <td>Special Judge</td>
                    <td>
                      <label class="radio inline"><input type="radio" name="special_judge_status" value="2" /> JAVA</label> &nbsp;&nbsp;&nbsp;&nbsp;
                      <label class="radio inline"><input type="radio" name="special_judge_status" value="1" /> C++</label> &nbsp;&nbsp;&nbsp;&nbsp;
                      <label class="radio inline"><input type="radio" name="special_judge_status" value="0" checked="checked" /> No</label>
                    </td>
                  </tr>
                  <tr><td>Description</td><td><textarea id="tdescription" name="description"></textarea></td></tr>
                  <tr><td>Input</td><td><textarea id="tinput" name="input"></textarea></td></tr>
                  <tr><td>Output</td><td><textarea id="toutput" name="output"></textarea></td></tr>
                  <tr><td>Sample Input</td><td><textarea name="sample_in" class="input-block-level" rows="8"></textarea></td></tr>
                  <tr><td>Sample Output</td><td><textarea name="sample_out" class="input-block-level" rows="8"></textarea></td></tr>
                  <tr><td>Hint</td><td><textarea id="thint" name="hint"></textarea></td></tr>
                  <tr><td>Source</td><td><textarea name="source" class="input-block-level" rows="4"></textarea></td></tr>
                  <tr><td>Author</td><td><textarea name="author" class="input-block-level" rows="4"></textarea></td></tr>
                </table>
                <div class="pull-right">
                  <span id="msgbox" style="display:none"></span><button class="btn btn-primary" type="submit">Submit</button>
                </div>
              </form>
            </div>
            <div id="contesttab" class="tab-pane">
                <form id='cload' method="get" action="#">
                    Contest ID: <input type="text" id="ncid" /><br />
                    <div class="btn-group">
                      <button class="btn btn-primary" type="submit"> Load </button>
                      <button class="btn" type="button" id="clockp"> Lock Problem </button>
                      <button class="btn" type="button" id="culockp"> Unlock Problem </button>
                      <button class="btn" type="button" id="cshare"> Share Code </button>
                      <button class="btn" type="button" id="cunshare"> Unshare Code </button>
                      <button class="btn" type="button" id="ctestall"> Test All </button>
                    </div>
                    <button class="btn btn-danger" type="button" onclick="resetcdetail()"> Reset </button>
                </form>
                <form method="post" action="" id="cdetail" class="ajform form-horizontal">
                    <h4>Contest Information</h4>
                    <table style="width:100%" class="table table-condensed table-bordered">
                        <tr><td>Contest ID:</td><td><input type="text" readonly="readonly" name="cid" class="input-small" /></td></tr>
                        <tr><td>Title:</td><td><input type="text" name="title" class="input-xxlarge" /></td></tr>
                        <tr>
                          <td>Type: </td>
                          <td>
                            <label class="radio inline"><input type="radio" name="ctype" value="0" checked="checked" /> ICPC format</label> &nbsp;&nbsp;&nbsp;&nbsp; 
                            <label class="radio inline"><input type="radio" name="ctype" value="1" /> CF format </label>
                          </td>
                        </tr>
                        <tr>
                          <td>Has Challenge? : </td>
                          <td>
                            <label class="radio inline"><input type="radio" name="has_cha" value="0" checked="checked" /> No</label> &nbsp;&nbsp;&nbsp;&nbsp; 
                            <label class="radio inline"><input type="radio" name="has_cha" value="1" /> Yes </label>
                          </td>
                        </tr>
                        <tr><td>Description:</td><td><textarea name="description" class="input-block-level" rows="8"></textarea></td></tr>
                        <tr><td>Start Time: </td><td><input type="text" name="start_time" class="datepick" value='<?= date("Y-m-d")." 09:00:00" ?>'/></td></tr>
                        <tr><td>End Time: </td><td><input type="text" name="end_time" class="datepick" value='<?= date("Y-m-d")." 14:00:00" ?>'/></td></tr>
                        <tr><td>Lock Board Time: </td><td><input type="text" name="lock_board_time" class="datepick" value='<?= date("Y-m-d")." 15:00:00" ?>'/></td></tr>
                        <tr class="chatimerow" style="display:none"><td>Challenge Start Time: </td><td><input type="text" name="challenge_start_time" class="datepick" value='<?= date("Y-m-d")." 14:10:00" ?>'/></td></tr>
                        <tr class="chatimerow" style="display:none"><td>Challenge End Time: </td><td><input type="text" name="challenge_end_time" class="datepick" value='<?= date("Y-m-d")." 14:25:00" ?>'/></td></tr>
                        <tr>
                          <td>Hide Others' Status: </td>
                          <td>
                            <label class="radio inline"><input type="radio" name="hide_others" value="1" /> Yes</label> &nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="radio inline"><input type="radio" name="hide_others" value="0" checked="checked" /> No </label>
                          </td>
                        </tr>
                        <tr>
                          <td>Private: </td>
                          <td>
                            <label class="radio inline"><input type="radio" name="isprivate" value="1" /> Yes</label> &nbsp;&nbsp;&nbsp;&nbsp; 
                            <label class="radio inline"><input type="radio" name="isprivate" value="0" checked="checked" /> No</label> 
                          </td>
                        </tr>
                        <tr><td>Report:</td><td><textarea id="treport" name="report"></textarea></td></tr>
                    </table>
<?php
  $nn = $config["limits"]["problems_on_contest_add"];
?>
                    <h4>Add Problems For Contest</h4>
                    <div class="input-append">
                      <input type='text' id="clcid" id="appendedInput" class="input-small" placeholder="CID" />
                      <button class="btn btn-primary" type="button" id="cclonecid">Clone</button>
                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <div class="input-append">
                      <input type='text' id="clsrc" id="appendedInput" class="input-large" placeholder="Source" />
                      <button class="btn btn-primary" type="button" id="cclonesrc">Clone</button>
                    </div>
                    <p><b>Leave Problem ID blank if you don't want to add it.</b></p>
                    <table style="width:100%" class="table table-condensed">
<?php
  for($i=0;$i<$nn;$i++){
?>
                        <tr>
                            <td class="span3">
                              <div class="input-prepend">
                                <span class="add-on">Problem </span>
                                <input id="prependedInput" class="input-mini" type="text" name="lable<?=$i ?>" value="<?=chr($i+65) ?>" style="text-align:center;" />
                              </div>
                            </td>
                            <td class='selpid span9'>
                                <div><input style="float:left;margin-right:20px" type="text" name="pid<?=$i?>" placeholder="Problem ID" class="input-small" /></div>
                                <div style="float:left;display:none" class="selptype inline">
                                    <label class="radio inline"><input type="radio" class='ptype' name="ptype<?=$i?>" value="1" checked="checked" /> CF</label> &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label class="radio inline"><input type="radio" class='ptype' name="ptype<?=$i?>" value="2" /> TC</label> <br />
                                </div>
                                <div class='well selpara' style="display:none;clear:both">
                                    <div class='cf tc'><label>Base Value (MP) : <input class="input-small" type="text" value='500' name="base<?=$i?>" /></label></div>
                                    <div class='cf tc'><label>Min Value: <input class="input-small" type="text" value='150' name="minp<?=$i?>" /></label></div>
                                    <div class='cf tc'><label>Parameter A: <input type="text" class='input-small paraa' value="2" name="paraa<?=$i?>" /></label></div>
                                    <div class='cf tc'><label>Parameter B: <input type="text" class='input-small parab' value="50" name="parab<?=$i?>" /></label></div>
                                    <div class='tc' style="display:none"><label>Parameter C: <input class='input-small parac' type="text" name="parac<?=$i?>" /></label></div>
                                    <div class='tc' style="display:none"><label>Parameter D: <input class='input-small parad' type="text" name="parad<?=$i?>" /></label></div>
                                    <div class='tc' style="display:none"><label>Parameter E: <input class='input-small parae' type="text" name="parae<?=$i?>" /></label></div>
                                    <div class='typenote' style="color:blue"></div>
                                </div>
                            </td>
                        </tr>
<?php
  }
?>
                    </table>
                    <h4>Add User For Contest (Seperate them by '|')</h4>
                    <textarea name="names" class="input-block-level" rows="8"></textarea>
                    <div class="pull-right" style="margin-top:10px">
                      <span id="msgbox" style="display:none"></span><button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>
            </div>
            <div id="rejudgetab" class="tab-pane">
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
            <div id="replaytab" class="tab-pane">
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
                            <td style="text-align:center">Problem <?php echo chr($i+65); ?><input type="hidden" name="lable<?=$i?>" value="<?php echo chr($i+65); ?>" /></td>
                            <td>
                            OJ: <select class="vpname" style="width:120px;padding:5px"><?echo $ojoptions; ?></select>
                                Problem ID: <input class="vpid" name="vpid<?=$i?>" type="text" />
                                <input class="vpid" type="hidden" name="pid<?=$i?>" />
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
            <div id="newstab" class="tab-pane">
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
            <div id="othertab" class="tab-pane">
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
<script type="text/javascript" src="js/admin.js?<?php echo filemtime("js/admin.js"); ?>" ></script>
<?php
include("footer.php");
?>
