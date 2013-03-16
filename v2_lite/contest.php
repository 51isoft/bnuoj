<?php
  include_once("conn.php");
  $pagetitle="Contest List";
  include_once("header.php");
  include_once("menu.php");
  if ($_GET["page"]!="") $stp=$problemperpage*(intval(convert_str($_GET["page"]))-1);
  else $stp="0";
?>
    <div id="site_content">
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <table class="display" id="contestlist">
            <thead>
              <tr>
                <th width='60px'> CID </th>
                <th> Title </th>
                <th width='140px'> Start Time </th>
                <th width='140px'> End Time </th>
                <th width='100px'> Status </th>
                <th width='80px'> Access </th>
                <th width="90px"> Manager </th>
                <th> Private </th>
                <th> Type </th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
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
var searchstr='<?php echo $_GET['search']; ?>';
var conperpage=<?php echo $conperpage;?>;
var cshowtype='<?php echo $_GET['type']; ?>';
</script>
<script type="text/javascript" src="pagejs/contest.js"></script>

<?php
if ($_GET['clone']==1) {
?>
<script type="text/javascript">
$("#arrangevdialog").dialog("open");
</script>
<?php
}
?>

<?php
    include("end.php");
?>
