<?php
  include_once("conn.php");
  $pagetitle="Problem List";
  include_once("header.php");
  include_once("menu.php");
  if ($_GET["page"]!="") $stp=$problemperpage*(intval(convert_str($_GET["page"]))-1);
  else $stp="0";
?>
    <div id="site_content">
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <!-- insert the page content here -->
          <div>
<?php
  if (db_user_match($nowuser,$nowpass)) {
?>
            <div class="ui-buttonset" style="display:inline">
              <input type="radio" id="showunsolve" name="radio1" /><label for="showunsolve">Unsolved</label>
              <input type="radio" id="showall" name="radio1" checked="checked" /><label for="showall">All</label>
            </div>
<?php
  }
?>
            <div class="ui-buttonset" style="display:inline">
              <input type="radio" id="showallp" name="radio2" /><label for="showallp">All</label>
              <input type="radio" id="showlocalp" name="radio2" checked="checked" /><label for="showlocalp">Local</label>
            </div>
            <div class="ui-buttonset" style="display:inline">
              <input type="radio" id="showlocal" name="radio3" checked="checked" /><label for="showlocal">Local Stat</label>
              <input type="radio" id="showremote" name="radio3" /><label for="showremote">Remote Stat</label>
              <input type="radio" id="showremu" name="radio3" /><label for="showremu">Remote User Stat</label>
            </div>
          </div>
          <table class="display" id="problist">
            <thead>
              <tr>
                <th width='55px'> Flag </th>
                <th width='70px'> PID </th>
                <th width='60%'> Title </th>
                <th width='40%'> Source </th>
                <th width='70px'> AC </th>
                <th width='70px'> All </th>
                <th width='80px'> AC </th>
                <th width='80px'> All </th>
                <th width='80px'> AC(U) </th>
                <th width='80px'> All(U) </th>
                <th width='120px' class="selectoj"> OJ </th>
                <th width='70px'> VID </th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <th> Flag </th>
                <th> PID </th>
                <th> Title </th>
                <th> Source </th>
                <th> AC </th>
                <th> All </th>
                <th> AC </th>
                <th> All </th>
                <th> AC(U) </th>
                <th> All(U) </th>
                <th class="selectoj"> OJ </th>
                <th> VID </th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div id="one_content_base"></div>
      </div>
    </div>

<?php
    include_once("footer.php");
?>
<script type="text/javascript">
var probperpage=<?php echo $problemperpage; ?>;
var pstart=<?php echo $stp; ?>;
var searchstr="<?php echo $_GET['search']; ?>";
var ojoptions='<?php echo $ojoptions; ?>';
</script>
<script type="text/javascript" src="pagejs/problem.js?<?php echo filemtime("pagejs/problem.js"); ?>"></script>
<?php
    include("end.php");
?>
