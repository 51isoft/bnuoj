<?php
    include("header.php");
    include("menu.php");
?>
    <div id="site_content">
      <div id="sidebar_container">
        <!-- insert your sidebar items here -->
        <div class="sidebar">
          <div class="sidebar_top"></div>
          <div class="sidebar_item">
            <h1>Latest News</h1>
	    <h4>New Website Launched</h4>
            <p>We've redesigned our website. Take a look around and let us know what you think.<br /><a href="#">Read more</a></p>
          </div>
          <div class="sidebar_base"></div>
        </div>
        <div class="sidebar">
          <div class="sidebar_top"></div>
          <div class="sidebar_item">
            <h1>Useful Links</h1>
            <ul>
              <li><a href="#">link 1</a></li>
              <li><a href="#">link 2</a></li>
              <li><a href="#">link 3</a></li>
              <li><a href="#">link 4</a></li>
            </ul>
          </div>
          <div class="sidebar_base"></div>
        </div>
        <div class="sidebar">
          <div class="sidebar_top"></div>
          <div class="sidebar_item">
            <h1>Useful Info</h1>
            <p>You can put anything you like in the sidebar. Latest news, useful links, images etc.</p>
          </div>
          <div class="sidebar_base"></div>
        </div>
      </div>
      <div id="content_container">
        <div id="content_top"></div>
        <div id="content">
          <!-- insert the page content here -->
          <h1>Welcome to a_bit_boxy</h1>
          <p>This standards compliant, simple, fixed width website template is released as an 'open source' design (under the <a href="http://creativecommons.org/licenses/by/3.0">Creative Commons Attribution 3.0 Licence</a>), which means that you are free to download and use it for anything you want (including modifying and amending it). All I ask is that you leave the 'design by dcarter' link in the footer of the template, but other than that...</p>
          <p>This template is written entirely in XHTML 1.1 and CSS, and can be validated using the links in the footer.</p>
          <p>You can view my other 'open source' template designs <a href="http://www.dcarter.co.uk/templates.html">here</a>.</p>
          <p>This template is a fully functional 5 page website, with a <a href="styles.html">styles</a> page that gives examples of all the styles available with this design.</p>
          <h1>Browser Compatibility</h1>
          <p>This template has been tested in the following browsers:</p>
          <ul>
            <li>Internet Explorer 8</li>
            <li>Internet Explorer 7</li>
            <li>FireFox 3</li>
            <li>Google Chrome 2</li>
            <li>Safari 4</li>
          </ul>
        </div>
        <div id="content_base"></div>
      </div>
    </div>
<script type="text/javascript">
    $("#menu li:nth-child(1)").addClass("tab_selected");
</script>

<?php
    include("footer.php");
?>
