<?php
	include_once("conn.php");
	$proid = convert_str($_GET['pid']);
	$pagetitle="Discuss";
    if ($proid!="") $pagetitle=$pagetitle." For Problem ".$proid;
    include("header.php");
    include("menu.php");
?>
    <div id="site_content">
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <!-- insert the page content here -->
          <div id='dcontent'>
            <div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>
          </div>
        </div>
        <div id="one_content_base"></div>
      </div>
    </div>


<?php
    include("footer.php");
?>
<script type="text/javascript">
var ppid='<?php echo $proid; ?>';
</script>
<script type="text/javascript" src="pagejs/discuss.js?<?php echo filemtime("pagejs/discuss.js"); ?>"></script>
<?php
    include ("end.php");
?>
