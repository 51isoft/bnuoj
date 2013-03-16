<?php
  include_once("header.php");
  include_once("menu.php");
  $cid=convert_str($_GET['cid']);
?>
    <div id="site_content">
          <!-- insert the page content here -->
<?php
  if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
<div id="printv">
</div>

<div id="temp_printv" style="display:none">
</div>

<?php
  }
  else {
?>
          <p>
            <div class="error"><b>Permission Denied!</b></div>
          </p>
<?php
  }
?>
    </div>

<?php
    include("footer.php");
?>
<script type="text/javascript" src="js/jquery.quicksand.js"></script>
<script type="text/javascript">
var ref;

function updateTable() {
    $.get("admin_print_data.php?randomid="+Math.random(),function(data) {
        $("#temp_printv").html(data);
        $('#printv').quicksand( $('#temp_printv div.item'),{ adjustHeight: 'dynamic' }, function() {
            ref=setTimeout(updateTable,10000);
        });
    });
}
updateTable();

</script>

<?php
    include("end.php");
?>
