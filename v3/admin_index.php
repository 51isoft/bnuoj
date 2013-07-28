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
          <ul class="nav nav-tabs" id="admintab">
            <li class="active"><a href="#notificationtab" data-toggle="tab">Notification</a></li>
            <li><a href="#problemtab" data-toggle="tab">Problem</a></li>
            <li><a href="#contesttab" data-toggle="tab">Contest</a></li>
            <li><a href="#rejudgetab" data-toggle="tab">Rejudge</a></li>
            <li><a href="#replaytab" data-toggle="tab">Replay</a></li>
            <li><a href="#newstab" data-toggle="tab">News</a></li>
            <li><a href="#pcrawlertab" data-toggle="tab">Problem Crawler</a></li>
            <li><a href="#othertab" data-toggle="tab">Others</a></li>
          </ul>
          <div class="tab-content">
            <div id="notificationtab" class="tab-pane active">
              <form id="notiform" action="ajax/admin_deal_notify.php" method="post" class="ajform">
                <textarea id="notifycontent" name="sub" rows="10" class="input-block-level"><?=get_substitle()?></textarea>
                <div class="pull-right">
                  <span id="msgbox" style="display:none"></span><button class="btn btn-primary" type="submit">Change</button>
                </div>
              </form>
              <br/>
            </div>
            <div id="problemtab" class="tab-pane">
              <form id='pload' method="get" action="#" class="form-inline">
                <input type="text" id="npid" class="input-medium" placeholder='Problem ID' />
                <button class="btn btn-primary" type="submit"> Load </button>
                <button class="btn btn-danger" type="button" onclick="resetpdetail()"> Reset </button>
              </form>
              <form id="pdetail" method="post" action="ajax/admin_deal_problem.php" class="form-horizontal ajform">
                <h4>Problem Details</h4>
                <table class="table table-bordered table-condensed" style="width:100%">
                  <tr><td>Problem ID</td><td><input type="text" name="p_id" class="input-small" /></td></tr>
                  <tr><td>Title</td><td><input type="text" name="p_name" class="input-xxlarge" /></tr>
                  <tr>
                    <td>Hide</td>
                    <td>
                      <label class="radio inline"><input type="radio" name="p_hide" value="1" /> Yes </label>
                      <label class="radio inline"><input type="radio" name="p_hide" value="0" checked="checked" /> No</label>
                    </td>
                  </tr>
                  <tr><td>Time Limit</td><td><input type="text" name="time_limit" value="1000" class="input-small" /> ms</td></tr>
                  <tr><td>Case Time Limit</td><td><input type="text" name="case_time_limit" value="1000" class="input-small" /> ms</td></tr>
                  <tr>
                    <td>Only Case Limit?</td>
                    <td>
                      <label class="radio inline"><input type="radio" name="p_ignore_noc" value="1" /> Yes</label>
                      <label class="radio inline"><input type="radio" name="p_ignore_noc" value="0" checked="checked" /> No</label>
                    </td>
                  </tr>
                  <tr><td>Memory Limit</td><td><input type="text" name="memory_limit" value="65536" class="input-small" /> KB</td></tr>
                  <tr class="hide"><td>Number of Testcases</td><td><input type="text" name="noc" value="1" class="input-small" /></td></tr>
                  <tr>
                    <td>Special Judge</td>
                    <td>
                      <label class="radio inline"><input type="radio" name="special_judge_status" value="2" /> JAVA</label>
                      <label class="radio inline"><input type="radio" name="special_judge_status" value="1" /> C++</label>
                      <label class="radio inline"><input type="radio" name="special_judge_status" value="0" checked="checked" /> No</label>
                    </td>
                  </tr>
                  <tr><td>Description</td><td><textarea id="tdescription" name="description"></textarea></td></tr>
                  <tr><td>Input</td><td><textarea id="tinput" name="input"></textarea></td></tr>
                  <tr><td>Output</td><td><textarea id="toutput" name="output"></textarea></td></tr>
                  <tr><td>Sample Input</td><td><textarea name="sample_in" class="input-block-level" rows="8" style="font-family: monospace;"></textarea></td></tr>
                  <tr><td>Sample Output</td><td><textarea name="sample_out" class="input-block-level" rows="8" style="font-family: monospace;"></textarea></td></tr>
                  <tr><td>Hint</td><td><textarea id="thint" name="hint"></textarea></td></tr>
                  <tr><td>Source</td><td><textarea name="source" class="input-block-level" rows="4"></textarea></td></tr>
                  <tr><td>Author</td><td><textarea name="author" class="input-block-level" rows="4"></textarea></td></tr>
                </table>
                <div class="pull-right">
                  <span id="msgbox" style="display:none"></span><button class="btn btn-primary" type="submit">Submit</button>
                </div>
              </form>
              <br/>
            </div>
            <div id="contesttab" class="tab-pane">
                <form id='cload' method="get" action="#" class="form-inline">
                    <input type="text" id="ncid" placeholder="Contest ID" class="input-small" />
                    <button class="btn btn-primary" type="submit"> Load </button>
                    <div class="btn-group">
                      <button class="btn" type="button" id="clockp"> Lock Problem </button>
                      <button class="btn" type="button" id="culockp"> Unlock Problem </button>
                    </div>
                    <div class="btn-group">
                      <button class="btn" type="button" id="cshare"> Share Code </button>
                      <button class="btn" type="button" id="cunshare"> Unshare Code </button>
                    </div>
                    <button class="btn" type="button" id="ctestall"> Test All </button>
                    <button class="btn btn-danger" type="button" onclick="resetcdetail()"> Reset </button>
                </form>
                <form method="post" action="ajax/admin_deal_contest.php" id="cdetail" class="ajform form-horizontal">
                    <h4>Contest Information</h4>
                    <table style="width:100%" class="table table-condensed table-bordered">
                        <tr><td>Contest ID</td><td><input type="text" name="cid" class="input-small" /></td></tr>
                        <tr><td>Title</td><td><input type="text" name="title" class="input-xxlarge" /></td></tr>
                        <tr>
                          <td>Type </td>
                          <td>
                            <label class="radio inline"><input type="radio" name="ctype" value="0" checked="checked" /> ICPC format</label>
                            <label class="radio inline"><input type="radio" name="ctype" value="1" /> CF format </label>
                          </td>
                        </tr>
                        <tr>
                          <td>Has Challenge? </td>
                          <td>
                            <label class="radio inline"><input type="radio" name="has_cha" value="0" checked="checked" /> No</label>
                            <label class="radio inline"><input type="radio" name="has_cha" value="1" /> Yes </label>
                          </td>
                        </tr>
                        <tr><td>Description</td><td><textarea name="description" class="input-block-level" rows="8"></textarea></td></tr>
                        <tr><td>Start Time</td><td><input type="text" name="start_time" class="datepick" value='<?= date("Y-m-d")." 09:00:00" ?>'/></td></tr>
                        <tr><td>End Time</td><td><input type="text" name="end_time" class="datepick" value='<?= date("Y-m-d")." 14:00:00" ?>'/></td></tr>
                        <tr><td>Lock Board Time</td><td><input type="text" name="lock_board_time" class="datepick" value='<?= date("Y-m-d")." 14:00:00" ?>'/></td></tr>
                        <tr class="chatimerow" style="display:none"><td>Challenge Start Time</td><td><input type="text" name="challenge_start_time" class="datepick" value='<?= date("Y-m-d")." 14:10:00" ?>'/></td></tr>
                        <tr class="chatimerow" style="display:none"><td>Challenge End Time</td><td><input type="text" name="challenge_end_time" class="datepick" value='<?= date("Y-m-d")." 14:25:00" ?>'/></td></tr>
                        <tr>
                          <td>Hide Others' Status</td>
                          <td>
                            <label class="radio inline"><input type="radio" name="hide_others" value="1" /> Yes</label>
                            <label class="radio inline"><input type="radio" name="hide_others" value="0" checked="checked" /> No </label>
                          </td>
                        </tr>
                        <tr>
                          <td>Private</td>
                          <td>
                            <label class="radio inline"><input type="radio" name="isprivate" value="1" /> Yes</label>
                            <label class="radio inline"><input type="radio" name="isprivate" value="0" checked="checked" /> No</label> 
                          </td>
                        </tr>
                        <tr><td>Report</td><td><textarea id="treport" name="report"></textarea></td></tr>
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
                                <div><input style="float:left;margin-right:20px" type="text" name="pid<?=$i?>" placeholder="Problem ID" class="input-medium" /></div>
                                <div style="float:left;display:none" class="selptype inline">
                                    <label class="radio inline"><input type="radio" class='ptype' name="ptype<?=$i?>" value="1" checked="checked" /> CF</label>
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
                    <h4>Add User For Contest (Seperate them by characters other than [A-Z0-9a-z_-] )</h4>
                    <textarea name="names" class="input-block-level" rows="8"></textarea>
                    <div class="pull-right" style="margin-top:10px">
                      <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                    <div id="msgbox" style="display:none;clear:both"></div>
                </form>
            </div>
            <div id="rejudgetab" class="tab-pane">
                <form id="crej" method='get' action="#" class="ajform form-horizontal">
                    <fieldset>
                        <legend>Rejudge Problem in Contest</legend>
                        <div class="control-group">
                          <label class="control-label" for="rejcid">Contest ID</label>
                          <div class="controls">
                            <input type="text" id="rejcid" placeholder="Contest ID" />
                          </div>
                        </div>
                        <div class="control-group">
                          <label class="control-label" for="rejpid">Problem ID</label>
                          <div class="controls">
                            <input type="text" id="rejpid" placeholder="Problem ID" />
                          </div>
                        </div>
                        <div class="control-group">
                          <label class="control-label">Rejudge AC?</label>
                          <div class="controls">
                            <label class="radio inline">
                              <input type="radio" name="rejac" value="1" /> Yes
                            </label>
                            <label class="radio inline">
                              <input type="radio" name="rejac" value="0" checked="checked" /> No
                            </label>
                          </div>
                        </div>
                    </fieldset>
                    <div class="control-group">
                      <div class="controls">
                        <button type="submit" class="btn btn-primary">Rejudge</button>
                        <span id="msgbox" style="display:none"></span>
                      </div>
                    </div>
                </form>
                <form id="cprej" method='get' action="#" class="ajform form-horizontal">
                    <fieldset>
                        <legend>Rejudge Problem in Contest (Using Label)</legend>
                        <div class="control-group">
                          <label class="control-label" for="rcid">Contest ID</label>
                          <div class="controls">
                            <input type="text" id="rcid" placeholder="Contest ID" />
                          </div>
                        </div>
                        <div class="control-group">
                          <label class="control-label" for="rpid">Problem Label</label>
                          <div class="controls">
                            <input type="text" id="rpid" placeholder="Problem Label" />
                          </div>
                        </div>
                        <div class="control-group">
                          <label class="control-label">Rejudge AC?</label>
                          <div class="controls">
                            <label class="radio inline">
                              <input type="radio" name="rac" value="1" /> Yes
                            </label>
                            <label class="radio inline">
                              <input type="radio" name="rac" value="0" checked="checked" /> No
                            </label>
                          </div>
                        </div>
                    </fieldset>
                    <div class="control-group">
                      <div class="controls">
                        <button type="submit" class="btn btn-primary">Rejudge</button>
                        <span id="msgbox" style="display:none"></span>
                      </div>
                    </div>
                </form>
                <form id="runrej" method='post' action="#" class="ajform form-horizontal">
                    <fieldset>
                        <legend>Rejudge Run</legend>
                        <div class="input-append">
                          <input type="text" id="runid" placeholder="Run ID" />
                          <button type="submit" class="btn btn-primary">Rejudge</button>
                        </div>
                        <span id="msgbox" style="display:none"></span>
                    </fieldset>
                </form>
                <form id="cha_crej" method='get' action="#" class="ajform form-horizontal">
                    <fieldset>
                        <legend>Rejudge All Challenges in Contest</legend>
                        <div class="input-append">
                          <input type="text" id="rcha_cid" placeholder="Contest ID" />
                          <button type="submit" class="btn btn-primary">Rejudge</button>
                        </div>
                        <span id="msgbox" style="display:none"></span>
                    </fieldset>
                </form>
                <br/>
            </div>
            <div id="replaytab" class="tab-pane">
                <form id="replaycrawl" method="get" action="ajax/admin_deal_crawl_replay.php" class="form-inline">
                  <h4>Auto Crawl</h4>
                  <label>OJ: <select name="oj" id="vcojname" class="input-medium">
                      <option value="HUSTV">HUST Vjudge</option>
                      <option value="ZJU">ZJU</option>
                      <option value="UESTC">UESTC</option>
                      <option value="UVA">UVA</option>
                      <option value="OpenJudge">OpenJudge</option>
                      <option value="SCU">SCU</option>
                      <option value="HUST">HUST</option>
                    </select>
                  </label>
                  <div class="input-append">
                    <input name="cid" id="vcid" type="text" class="input-small" placeholder="Contest ID" />
                    <button class="btn btn-primary">Crawl!</button>
                  </div>
                  <span id="msgbox" style="display:none"></span>
                </form>
                <form id="replaycrawlall" method="get" class="ajform form-inline" action="ajax/admin_crawl_hust_all.php">
                  <h4>Crawl All HUSTV Contests</h4>
                  <div class="input-append">
                    <input type="text" name="cid" placeholder="Contest ID" class="input-medium">
                    <button class="btn btn-danger">Crawl!</button>
                  </div>
                  <div id="msgbox" style="display:none;clear:both"></div>
                </form>
                <form id='replayform' method='post' class="ajform form-horizontal" action="ajax/admin_deal_replay.php" enctype="multipart/form-data">
                    <h4>Replay Contest Information</h4>
                    <table style="width:100%" class="table table-bordered table-condensed">
                        <tr><td class="span3">Contest Name</td><td><input type="text" name="name" class="input-xxlarge" /></td></tr>
                        <tr><td>Description</td><td><textarea name="description" class="input-block-level" rows="8"></textarea></td></tr>
                        <tr><td>Start Time</td><td><input type="text" name="start_time" value='<?=date("Y-m-d")." 09:00:00" ?>'/></td></tr>
                        <tr><td>End Time</td><td><input type="text" name="end_time" value='<?=date("Y-m-d")." 14:00:00" ?>'/></td></tr>
                        <tr><td>Submit Frequency</td><td><input type="text" name="sfreq" value="180" class="input-small" /> seconds (Minimum 10)</td></tr>
                        <tr><td>Standing File</td><td><input type="file" name="file" id="file" /></td></tr>
                        <tr><td>Or Standing URL</td><td><input name="repurl" id="repurl" type="text" class="input-block-level" /></td></tr>
                        <tr>
                            <td>File Type</td>
                            <td>
                                <label class="radio inline"><input type="radio" name="ctype" value="hdu" checked="checked" /> HDU Excel</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="myexcel" /> My Excel</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="licstar" /> licstar 2011 version (Zlinkin)</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="ctu" /> CTU Submits</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="ural" /> Ural</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="zju" /> ZJU Excel</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="jhinv" /> Jinhua</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="zjuhtml" /> ZJU HTML</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="neerc" /> NEERC</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="2011shstatus" /> 2011 Shanghai Status</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="pc2sum" /> PC<sup>2</sup> Summary</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="fdulocal2012" /> FDU Local 2012</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="uestc" /> UESTC</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="hustvjson" /> HUST VJudge JSON</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="fzuhtml" /> FZU HTML</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="usuhtml" /> USU HTML</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="sguhtml" /> SGU HTML</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="amt2011" /> Amritapuri 2011</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="nwerc" /> NWERC</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="ncpc" /> NCPC</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="uva" /> UVA</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="gcpc" /> GCPC</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="phuket" /> Phuket</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="spacific" /> South Pacific</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="icpcinfostatus" /> ACMICPC info Status</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="spoj" /> SPOJ</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="openjudge" /> OpenJudge</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="scu" /> SCU</label>
                                <label class="radio inline"><input type="radio" name="ctype" value="hust" /> HUST</label>
                            </td>
                        </tr>
                        <tr><td>Extra Information: </td><td><input type="text" name="extrainfo" class="input-xlarge" /></td></tr>
                        <tr>
                          <td>Virtual? : </td>
                          <td>
                            <label class="radio inline"><input type="radio" name="isvirtual" value="0" checked="checked" /> No</label>
                            <label class="radio inline"><input type="radio" name="isvirtual" value="1" /> Yes</label>
                          </td>
                        </tr>
                      </table>
                      <h4>Select Problems</h4>
                      <div class="input-append">
                        <input type='text' id="vclcid" id="appendedInput" class="input-small" placeholder="CID" />
                        <button class="btn btn-primary" type="button" id="vclonecid">Clone</button>
                      </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <div class="input-append">
                        <input type='text' id="vclsrc" id="appendedInput" class="input-large" placeholder="Source" />
                        <button class="btn btn-primary" type="button" id="vclonesrc">Clone</button>
                      </div>
                      <p><b>Fill in order. Leave Problem ID blank if not exists.</b></p>
                      <table style="width:100%" class="table table-bordered table-condensed">
<?php
  for($i=0;$i<$nn;$i++){
?>
                        <tr>
                            <td>Problem <?=chr($i+65) ?><input type="hidden" name="lable<?=$i?>" value="<?=chr($i+65) ?>" /></td>
                            <td>
                                OJ: <select class="vpname input-small"><?=$ojoptions; ?></select>
                                <input class="vpid input-small" name="vpid<?=$i?>" type="text" placeholder="Problem ID"/>
                                <input class="vpid" type="hidden" name="pid<?=$i?>" />
                                <br /><span style="overflow:hidden;color:red"></span>
                            </td>
                        </tr>
<?php
  }
?>
                    </table>
                    <div class="pull-right">
                      <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                    <div id="msgbox" style="display:none;clear:both"></div>
                </form>
            </div>
            <div id="newstab" class="tab-pane">
                <form id='nload' method="get" action="#" class="ajform form-inline">
                  <input type="text" id="nnid" class="input-medium" placeholder='News ID' />
                  <button class="btn btn-primary" type="submit"> Load </button>
                  <button class="btn btn-danger" type="button" onclick="resetndetail()"> Reset </button>
                </form>
                <h4>News Information</h4>
                <form id='ndetail' method="post" action="ajax/admin_deal_news.php" class="ajform form-inline">
                    <table style="width:100%;" class="table table-condensed table-bordered">
                        <tr><td class="span3">News ID</td><td><input type="text" readonly="readonly" name="newsid"/></td></tr>
                        <tr><td>Title</td><td><input type="text" name="title" class="input-xxlarge" /></td></tr>
                        <tr><td>Content</td><td><textarea id="tncontent" name="content" class="input-block-level" rows="8"></textarea></td></tr>
                    </table>
                    <div class="pull-right">
                      <span id="msgbox" style="display:none"></span><button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>
                <br />
            </div>
            <div id="othertab" class="tab-pane">
              <button id="spinfo" class="btn btn-danger syncbutton">Sync Problem Info</button>
              <button id="suinfo" class="btn btn-danger syncbutton">Sync User Info</button>
              <div class="alert alert-block" id="syncwait" style="display:none"></div>
              <h4>Delete virtual replays in range</h4>
              <form id='delcontest' method="get" action="ajax/admin_deal_delete_vreplay.php" class="ajform form-inline">
                From: <input type="text" name="fcid" placeholder="Contest ID" />
                To: <input type="text" name="tcid" placeholder="Contest ID" />
                <button type="submit" class="btn btn-danger">Delete</button>
                <div id="msgbox" style="display:none;clear:both"></div>
              </form>
            </div>
            <div id="pcrawlertab" class="tab-pane">
              <h4>Crawl a single problem/contest</h4>
              <form id='pcbasic' method="get" action="ajax/admin_deal_crawl_problem.php?type=0" class="ajform form-inline">
                <label>
                  OJ: 
                  <select class="input-medium" name="pcoj">
<?=$ojoptions?>
                  </select>
                </label>
                <input type="text" name="pcid" placeholder="Problem/Contest    ID/Code" />
                <button type="submit" id="spinfo" class="btn btn-primary">Crawl!</button>
                <div id="msgbox" style="display:none;clear:both"></div>
              </form>
              <h4>Crawl problems/contests in range</h4>
              <form id='pcrange' method="get" action="ajax/admin_deal_crawl_problem.php?type=1" class="ajform form-inline">
                <label>
                  OJ: 
                  <select class="input-medium" name="pcoj">
<?=$ojoptions?>
                  </select>
                </label>
                <label>From: <input type="text" name="pcidfrom" placeholder="Problem/Contest    ID/Code" /></label>
                <label>To: <input type="text" name="pcidto" placeholder="Problem/Contest    ID/Code" /></label>
                <button type="submit" id="spinfo" class="btn btn-primary">Crawl!</button>
                <div id="msgbox" style="display:none;clear:both"></div>
              </form>
              <h4>Crawl problem stats</h4>
              <form id='pcnum' method="get" action="ajax/admin_deal_crawl_problem.php?type=2" class="ajform form-inline">
                <label>
                  OJ: 
                  <select class="input-medium" name="pcoj">
<?=$ojoptions?>
                  </select>
                </label>
                <button type="submit" id="spinfo" class="btn btn-primary">Crawl!</button>
                <div id="msgbox" style="display:none;clear:both"></div>
              </form>
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
<script type="text/javascript" src="js/admin.js?<?=filemtime("js/admin.js")?>" ></script>
<?php
include("footer.php");
?>
