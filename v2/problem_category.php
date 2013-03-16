<?php
  include_once("conn.php");
  $pagetitle="Problem Category";
  include_once("header.php");
  include_once("menu.php");
?>
    <div id="site_content">
      <div id="sidebar_container">
<?php
  include("common_sidebar.php");
?>
      </div> 
      <div id="content_container">
        <div id="content_top"></div>
        <div id="content">
          <form method="post" action="problem_category_result.php">
            <h1>Categories</h1>
<ul>
<?php
  function show_category($row) {
      list($num)=mysql_fetch_array(mysql_query("select count(*) from problem_category where catid='".$row['id']."'"));
      echo "<li><input class='ccheck' type='checkbox' value='".$row['id']."' name='check".$row['id']."' /> ".$row['name']." ($num) ";
      $res=mysql_query("select * from category where parent='".$row['id']."'");
      $fir=true;
      while ($row=mysql_fetch_array($res)) {
          if ($fir) {
              echo " <a href='#' class='cexpand'>[Expand]</a><a href='#' class='chide' style='display:none'>[Hide]</a></li>\n";
              echo "<ul style='display:none'>\n";
              $fir=false;
          }
          show_category($row);
      }
      if ($fir==false) echo "</ul>\n";
      else echo "</li>\n";
  }

  $res=mysql_query("select * from category where parent='-1'");
  while ($row=mysql_fetch_array($res)) {
      show_category($row);
  }
?>
</ul>
            Logical Operation: <input name="logic" value="or" type="radio" checked="checked"> OR &nbsp;&nbsp;&nbsp;&nbsp; <input name="logic" value="and" type="radio"> AND <br /> ( <b>Note:</b> If a node is checked, all its children will be ignored. )
            <br /><br /><button type="submit">Submit</button>
          </form>
        </div>
        <div id="content_base"></div>
      </div>
    </div>

<?php
    include_once("footer.php");
?>
<script type="text/javascript">
$(".ccheck").change(function() {
    if ($(this).attr('checked')!=null) {
        $(".ccheck",$(this).parent().next("ul")).attr('checked', true);
        $val=$(this).parent();
        $uc=$("input:not(:checked)",$val.next());
        while ($val.length && $uc.length==0) {
            $(".ccheck:first",$val).attr('checked', true);
            $val=$val.parent().prev();
            $uc=$("input:not(:checked)",$val.next());
        }
    }
    else {
        $(".ccheck",$(this).parent().next("ul")).attr('checked', false);
        $val=$(this).parent();
        while ($val.length) {
            $(".ccheck:first",$val).attr('checked', false);
            $val=$val.parent().prev();
        }
    }
});
$(".cexpand").click(function() {
    $(this).parent().next().show("blind");
    $(this).hide();
    $(this).next().show();
    return false;
});
$(".chide").click(function() {
    $(this).parent().next().hide("blind");
    $(this).hide();
    $(this).prev().show();
    return false;
});
$("button").button();
$("#problem").addClass("tab_selected");
</script>
<?php
    include("end.php");
?>
