<?php
  include_once("header.php");
  include_once("menu.php");
?>
    <div id="site_content">
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <!-- insert the page content here -->
          <h1>Training Stat</h1>
          <table id="stat_info">
            <thead>
              <tr>
                <th>Username/Contest</th>
                <th class="mylast">Total</th>
                <th>*</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <p>
            *: with Accepts outside the contests.<br />
            Click header to sort.
          </p>
          <div id="control">
            <form id="addc">Contest ID: <input style="padding:5px" id="cnum" type="text" /> <button type="submit">Add</button></form>
            <form id="addu">Username: <input style="padding:5px" id="uname" type="text" /> <button type="submit">Add</button></form>
          </div>
        </div>
        <div id="one_content_base"></div>
      </div>
    </div>

<?php
    include("footer.php");
?>
<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="pagejs/training_stat.js?<?php echo filemtime("pagejs/training_stat.js"); ?>"></script>
<?php
    include("end.php");
?>
