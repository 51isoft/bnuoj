<?php
  include_once("conn.php");
  $pagetitle="Online Status";
  include_once("header.php");
  include_once("menu.php");
  if ( isset($_GET['start']) ) $start = convert_str($_GET['start']);
  else $start = "0";
?>
    <div id="site_content">
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <!-- insert the page content here -->
          <form id="filterform" method="">
            <table class="filter">
              <tr>
                <th>Filter:</th>
                <td><label>User:</label></td>
                <td><input type='text' name='showname' style="width:90px" value='<?php if (isset($_GET["showname"])) echo $_GET["showname"]; else echo $nowuser;?>' /></td>
                <td><label>PID:</label></td>
                <td><input type='text' name='showpid' style="width:50px" value='<?php if (isset($_GET["showpid"])) echo $_GET["showpid"];?>' /></td>
                <td><label>Result:</label></td>
                <td>
                  <select size="1" name="showres" id="showres">
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
                </td>
                <td><label>Language:</label></td>
                <td>
                  <select size="1" name="showlang" id="showlang">
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
                </td>
                <th><input type='submit' value='Show' /></th>
              </tr>
            </table>
          </form>
          <table class="display" id="statustable">
            <thead>
              <tr>
                <th width='8%'>User</th>
                <th width='7%'>RunID</th>
                <th width='6%'>PID</th>
                <th width='12%'>Result</th>
                <th width='9%'>Language</th>
                <th width='8%'>Time</th>
                <th width='8%'>Memory</th>
                <th width='7%'>Length</th>
                <th width='13%'>Submit Time</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>
        <div class="topdialog" id="sourcewindow">
        </div>
        <div id="one_content_base"></div>
      </div>
    </div>

<?php
    include_once("footer.php");
?>
<script type="text/javascript" src="js/sh_pascal.js"></script>
<script type="text/javascript" src="js/sh_sml.js"></script>
<script type="text/javascript">
var statperpage=<?php echo $statusperpage; ?>;
var spstart=<?php echo $start; ?>;
</script>
<script type="text/javascript" src="pagejs/status.js?<?php echo filemtime("pagejs/status.js"); ?>"></script>
<?php
    include_once("end.php");
?>
