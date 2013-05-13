<?php
$pagetitle="Online Status";
include_once("header.php");
if ( isset($_GET['start']) ) $start = convert_str($_GET['start']);
else $start = "0";
?>
        <div id="flip-scroll" class="span12">
          <div>
            <form id="filterform" class="form-inline" method="">
              <b>Filter: </b>
              <label>Username: <input type='text' name='showname' id="showname" placeholder="Username" class="input-small" value='<?= $current_user->get_username()?>' /></label>
              <label>Problem ID: <input type='text' name='showpid' id="showpid" placeholder="Problem ID" class="input-small" /></label>
              <label>Result:
                <select name="showres" id="showres" class="input-medium">
                  <option value=''>All</option>
                  <option value='Accepted'>Accepted</option>
                  <option value='Wrong Answer'>Wrong Answer</option>
                  <option value='Runtime Error'>Runtime Error</option>
                  <option value='Time Limit Exceed'>Time Limit Exceed</option>
                  <option value='Memory Limit Exceed'>Memory Limit Exceed</option>
                  <option value='Output Limit Exceed'>Output Limit Exceed</option>
                  <option value='Presentation Error'>Presentation Error</option>
                  <option value='Restricted Function'>Restricted Function</option>
                  <option value='Compile Error'>Compile Error</option>
                </select>
              </label>
              <label>Language:
                <select name="showlang" id="showlang" class="input-medium">
                  <option value="">All</option>
                  <option value="1">GNU C++</option>
                  <option value="2">GNU C</option>
                  <option value="3">Oracle Java</option>
                  <option value="4">Free Pascal</option>
                  <option value="5">Python</option>
                  <option value="6">C# (Mono)</option>
                  <option value="7">Fortran</option>
                  <option value="8">Perl</option>
                  <option value="9">Ruby</option>
                  <option value="10">Ada</option>
                  <option value="11">SML</option>
                  <option value="12">Visual C++</option>
                  <option value="13">Visual C</option>
                </select>
              </label>
              <button type='submit' class="btn btn-primary">Show</button>
            </form>
          </div>
          <div>
            <table class="table table-hover table-striped basetable cf" id="statustable" width="100%">
              <thead>
                <tr>
                  <th width='9%'>Username</th>
                  <th width='7%'>RunID</th>
                  <th width='6%'>PID</th>
                  <th width='12%'>Result</th>
                  <th width='9%'>Language</th>
                  <th width='8%'>Time</th>
                  <th width='8%'>Memory</th>
                  <th width='7%'>Length</th>
                  <th width='13%'>Submit Time</th>
                  <th width='0%'>Visible</th>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot></tfoot>
            </table>
          </div>
        </div>

        <div id="statusdialog" class="modal hide fade" style="display:none">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 id="dtitle">Title</h3>
          </div>
          <div class="modal-body">
            <div class="well" style="text-align:center" id="rcontrol">
              Result: <span id="rresult"></span> &nbsp;&nbsp;&nbsp; Memory Used: <span id="rmemory"></span> KB &nbsp;&nbsp;&nbsp; Time Used: <span id="rtime"></span> ms <br/>
              Language: <span id="rlang"></span> &nbsp;&nbsp;&nbsp; Username: <span id="ruser"></span> &nbsp;&nbsp;&nbsp; Problem ID: <span id="rpid"></span> <br/>
              Share Code? <div class="btn-group" id="rshare"><button id="sharey" type="button" class="btn btn-info">Yes</button><button id="sharen" type="button" class="btn btn-info">No</button></div> <br />
              <b id='sharenote'>This code is shared.</b>
            </div>
            <button class="pull-right btn btn-mini btn-inverse" data-clipboard-target="dcontent" id="copybtn">Copy</button>
            <pre id="dcontent"></pre>
          </div>
        </div>

<script type="text/javascript">
var statperpage=<?= $config["limits"]["status_per_page"] ?>;
var spstart=<?= $start ?>;
var refrate=<?=$config["status"]["refresh_rate"]?>;
var lim_times=<?=$config["status"]["max_refresh_times"]?>;
</script>
<script src="js/ZeroClipboard.min.js"></script>
<script src="js/jquery.history.js"></script>
<script type="text/javascript" src="js/status.js?<?=filemtime("js/status.js") ?>"></script>
<link href="css/prettify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="js/prettify.js"></script>
<?php
include_once("footer.php");
?>
