<?php
  include_once("conn.php");
  $pagetitle="Problem Category";
  include_once("header.php");
  include_once("menu.php");
//  include("problem_category_init.php");
  $scate=array();
  //var_dump($_POST);
  if (isset($_GET['category'])) {
      $catarr='[{"name": "catenum", "value":"1"}, {"name": "logic", "value":"or"}, {"name":"cate0", "value":"'.$_GET['category'].'"}]';
      list($ctname)=@mysql_fetch_array(mysql_query("select name from category where id='".convert_str($_GET['category'])."'"));
      $scate[]=htmlspecialchars($ctname);
  }
  else {
      if ($_POST['logic']=="or") $catarr='[ {"name":"logic", "value": "or"}';
      else $catarr='[ {"name":"logic", "value": "and"}';
      $num=0;
      foreach($_POST as $kkey=>$value) {
          if ($kkey=="logic") continue;
          list($ctname,$pt)=@mysql_fetch_array(mysql_query("select name,parent from category where id='".convert_str($value)."'"));
          if (isset($_POST["check".$pt])==$value) continue;
          $scate[]=htmlspecialchars($ctname);
          //$scate[]=htmlspecialchars($catename[$value]);
          $catarr.=',{"name":"cate'.$num.'", "value":"'.$value.'"}';
          $num++;
      }
      $catarr.=',{"name":"catenum", "value":"'.$num.'"} ]';
  }
?>
    <div id="site_content">
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <h1>Selected Categories</h1>
          <div class="content-wrapper ui-corner-all" style="margin-bottom:20px">
            <?php echo implode(" &nbsp; <b> ".htmlspecialchars(strtoupper($_POST['logic']))." &nbsp; </b> ", $scate); ?>
          </div>
          <div>
<?php
  if (db_user_match($nowuser,$nowpass)) {
?>
            <div class="ui-buttonset" style="display:inline">
              <input type="radio" id="showunsolve" name="radio1" /><label for="showunsolve">Unsolved</label>
              <input type="radio" id="showall" name="radio1" checked="checked" /><label for="showall">All</label>
            </div>
<?php
  }
?>
            <div class="ui-buttonset" style="display:inline">
              <input type="radio" id="showallp" name="radio2" checked="checked" /><label for="showallp">All</label>
              <input type="radio" id="showlocalp" name="radio2" /><label for="showlocalp">Local</label>
            </div>
            <div class="ui-buttonset" style="display:inline">
              <input type="radio" id="showlocal" name="radio3" checked="checked" /><label for="showlocal">Local Stat</label>
              <input type="radio" id="showremote" name="radio3" /><label for="showremote">Remote Stat</label>
              <input type="radio" id="showremu" name="radio3" /><label for="showremu">Remote User Stat</label>
            </div>
          </div>
          <table class="display" id="problist">
            <thead>
              <tr>
                <th width='45px'> Flag </th>
                <th width='70px'> PID </th>
                <th width='60%'> Title </th>
                <th width='40%'> Source </th>
                <th width='70px'> AC </th>
                <th width='70px'> All </th>
                <th width='80px'> AC </th>
                <th width='80px'> All </th>
                <th width='80px'> AC(U) </th>
                <th width='80px'> All(U) </th>
                <th width='120px' class="selectoj"> OJ </th>
                <th width='70px'> VID </th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <th> Flag </th>
                <th> PID </th>
                <th> Title </th>
                <th> Source </th>
                <th> AC </th>
                <th> All </th>
                <th> AC </th>
                <th> All </th>
                <th> AC(U) </th>
                <th> All(U) </th>
                <th class="selectoj"> OJ </th>
                <th> VID </th>
              </tr>
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
var probperpage=<?php echo $problemperpage; ?>;
var pstart=0;
var searchstr=<?php echo $catarr; ?>;
var ojoptions='<?php echo $ojoptions; ?>';
</script>
<script type="text/javascript" src="pagejs/problem_category.js?<?php echo filemtime("pagejs/problem_category.js"); ?>"></script>
<?php
    include("end.php");
?>
