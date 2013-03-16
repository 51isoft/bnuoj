<?php
  include("header.php");
  include("menu.php");
?>
    <div id="site_content">
        <!-- insert your sidebar items here -->
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <!-- insert the page content here -->
          <table class="display" id="rank">
            <thead>
              <tr>
                <th width='8%'> Rank </th>
                <th width='17%'> Username </th>
                <th width='55%'> Nickname </th>
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
        <div id="one_content_base"></div>
      </div>
    </div>

<?php
    include("footer.php");
?>
<style type="text/css" title="currentStyle">
@import "media/css/demo_table_jui.css";
@import "media/css/demo_page.css";
.datatablerowhighlight {
background-color: #ECFFB3 !important;
}
</style>
<script type="text/javascript">
var userperpage=<?php echo $userperpage; ?>;
</script>
<script type="text/javascript" src="pagejs/ranklist.js"></script>

<?php
    include("end.php");
?>
