<?php
  include_once("conn.php");
  $pagetitle="Ranklist";
  include_once("header.php");
  include_once("menu.php");
?>
    <div id="site_content">
      <div id="sidebar_container">
        <!-- insert your sidebar items here -->
<?php
  include_once("common_sidebar.php");
?>

      </div> 
      <div id="content_container">
        <div id="content_top"></div>
        <div id="content">
          <!-- insert the page content here -->
          <table class="display" id="rank">
            <thead>
              <tr>
                <th width='11%'> Rank </th>
                <th width='18%'> Username </th>
                <th width='51%'> Nickname </th>
                <th width='10%'> AC </th>
                <th width='10%'> All </th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            </tfoot>
          </table>
        </div>
        <div id="content_base"></div>
      </div>
    </div>

<?php
    include_once("footer.php");
?>
<script type="text/javascript">
var userperpage=<?php echo $userperpage; ?>;
</script>
<script type="text/javascript" src="pagejs/ranklist.js?<?php echo filemtime("pagejs/ranklist.js"); ?>"></script>
<?php
    include("end.php");
?>
