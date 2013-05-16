<?php
$pagetitle="Problem List";
include_once("header.php");
if ($_GET["page"]!="") $stp=intval(convert_str($_GET["page"]))-1;
else $stp="0";
?>
        <div class="span12">
          <!-- insert the page content here -->
          <div class="span12">
<?php
if ($current_user->is_valid()) {
?>
            <div class="btn-group">
              <button class="btn btn-info active" id="showall">All</button>
              <button class="btn btn-info" id="showunsolve">Unsolved</button>
            </div>
<?php
}
?>
            <div class="btn-group">
              <button class="btn btn-info active" id="showlocal">Local Stat</button>
              <button class="btn btn-info" id="showremote">Remote Stat</button>
              <button class="btn btn-info" id="showremu">Remote User Stat</button>
            </div>
          </div>
          <div id="flip-scroll">
            <table class="table table-striped table-hover cf basetable" id="problist" width="100%">
              <thead>
                <tr>
                  <th width="3%"> Flag </th>
                  <th width="7%"> PID </th>
                  <th width="30%"> Title </th>
                  <th width="28%"> Source </th>
                  <th width="8%"> AC </th>
                  <th width="8%"> All </th>
                  <th width="8%"> AC </th>
                  <th width="8%"> All </th>
                  <th width="8%"> AC(U) </th>
                  <th width="8%"> All(U) </th>
                  <th width="10%" class="selectoj"> OJ </th>
                  <th width="8%"> VID </th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>

<script type="text/javascript">
var probperpage=<?= $config["limits"]["problems_per_page"] ?>;
var pstart=<?= $stp ?>;
var searchstr="<?= $_GET['search'] ?>";
var ojoptions='<?= $ojoptions ?>';
</script>
<script type="text/javascript" src="js/problem.js?<?=filemtime("js/problem.js") ?>"></script>
<?php
include("footer.php");
?>
