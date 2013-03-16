<?php
  include_once("header.php");
  include_once("menu.php");
  $cid=convert_str($_GET['cid']);
?>
    <div id="site_content">
          <!-- insert the page content here -->
<?php
  if (db_contest_exist($cid)&&db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
<script type="text/javascript">
var checked=true;
var cid=<?php echo $cid; ?>;
</script>
<div id="balloon">
</div>

<div id="temp_balloon" style="display:none">
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
    $.get("balloon_data.php?cid="+cid+"&randomid="+Math.random(),function(data) {
        $("#temp_balloon").html(data);
        $('#balloon').quicksand( $('#temp_balloon div.item'),{ adjustHeight: 'dynamic' }, function() {
            $("#balloon .send_a").click(function() {
                clearTimeout(ref);
                alert("Balloon for "+$(this).attr("suser")+", problem "+$(this).attr("slabel")+".");
                $.get("balloon_send.php?cid="+$(this).attr("scid")+"&user="+$(this).attr("suser")+"&label="+$(this).attr("slabel")+"&randomid="+Math.random(),function(data) {
                    updateTable();
                });
                return false;
            });
            ref=setTimeout(updateTable,5000);
        });
    });
}

if (checked==true) {
    updateTable();
}

</script>

<?php
    include("end.php");
?>
