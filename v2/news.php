<?php
  include_once("conn.php");
  $pagetitle="News";
  include_once("header.php");
  include_once("menu.php");
?>
    <div id="site_content">
      <div id="sidebar_container">
        <!-- insert your sidebar items here -->
<?php
  include("common_sidebar.php");
?>
      </div>
      <div id="content_container">
        <div id="content_top"></div>
        <div id="content">
          <!-- insert the page content here -->
          <table class="display" style="margin-bottom:0" id="newslist">
            <thead>
              <tr>
                <th width="100px">News ID</th> 
                <th>Title</th>
                <th width="160px">Last Edit Time</th>
                <th width="120px">Author</th>
              </tr>
            </thead>
            <tfoot></tfoot>
            <tbody></tbody>
          </table>
        </div>
        <div id="content_base"></div>
      </div>
    </div>
<?php
    include("footer.php");
?>
<script type="text/javascript" src="pagejs/news.js"></script>
<?php
    include("end.php");
?>
