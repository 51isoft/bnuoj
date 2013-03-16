<?php
  include_once("conn.php");
  $name=convert_str($_GET['name']);
  $pagetitle="Information of ".$name;
  include_once("header.php");
  include_once("menu.php");
  $query="select uid,username,nickname,school,email,register_time,last_login_time,ipaddr from user where username='$name'";
  $result=mysql_query($query);
  $arr = mysql_fetch_array($result);
  $query="SELECT rownum,total_ac FROM ( SELECT @rownum := @rownum +1 rownum, ranklist . * FROM (SELECT @rownum :=0) r, ranklist) AS t where username='$name'";
  $result=mysql_query($query);
  list($rank,$acnum) = mysql_fetch_array($result);
?>
    <div id="site_content">
      <div id="sidebar_container">
        <!-- insert your sidebar items here -->
<?php
  include("userinfo_sidebar.php");
?>
      </div>
      <div id="content_container">
        <div id="content_top"></div>
        <div id="content">
          <!-- insert the page content here -->
<?php
  if (db_user_exist($name)) {
?>
          <h2>Information of <?php echo $arr['username'];?></h2>
          <div id="userinfo" class="">
            <table width="100%" style="margin-bottom:0">
              <tr><th width="120px">Username:</th><td>&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $arr['username'];?> </td></tr>
              <tr><th>User ID:</th><td>&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $arr['uid'];?> </td></tr>
              <tr><th>Rank:</th><td>&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $rank;?> </td></tr>
              <tr><th>Nickname:</th><td>&nbsp;&nbsp;&nbsp;&nbsp; <?php echo change_out_nick($arr['nickname']);?> </td></tr>
              <tr><th>School:</th><td>&nbsp;&nbsp;&nbsp;&nbsp; <?php echo htmlspecialchars($arr['school']);?> </td></tr>
              <tr><th>Email:</th><td>&nbsp;&nbsp;&nbsp;&nbsp; <?php echo htmlspecialchars($arr['email']);?> </td></tr>
              <tr><th>Register Time:</th><td>&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $arr['register_time'];?> </td></tr>
              <tr><th>Last Login:</th><td>&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $arr['last_login_time'];?> </td></tr>
<?php
    if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
              <tr><th>Last Login IP: </th><td>&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $arr['ipaddr']; ?></td></tr>
<?php
    }
?>

              <tr>
                <th>Accepted:</th>
                <td>
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <?php echo $acnum; ?>
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <button id="showac">Show all</button><button id="hideac" style="display:none">Hide</button>
                  <div class="extendinfo" id="userac" style="padding-left:15px">
<?php
    $query=mysql_query("select distinct pid from status where result='Accepted' and username='$name' order by pid");
    while ($result=@mysql_fetch_row($query)) echo "<a href='problem_show.php?pid=$result[0]' target='_blank'>$result[0]</a>&nbsp; ";
?>

                  </div>
                </td>
              </tr>
              <tr>
                <th>Compare:</th>
                <td>
                  <form id="compareform">
                    &nbsp;&nbsp;&nbsp;&nbsp; <input type='text' id='user2' style="width:120px;padding: 0.48em 0 0.47em 0.45em;" value="<?php echo $nowuser; ?>" />&nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" id="compare" value="Go!" />
                    <button id="hidecompare" type="button" style="display:none">Hide</button>
                  </form>
                  <div class="extendinfo" id="compareinfo"></div>
                </td>
              </tr>
            </table>
          </div>
<?php
  } else {
?>
          <p>
            <div class="error"><b>No Such User!</b></div>
          </p>
<?php
  }
?>
        </div>
        <div id="content_base"></div>
      </div>
    </div>

<?php
    include("footer.php");
?>
<script type="text/javascript" src="js/highcharts.js"></script>
<script type="text/javascript">
var nametoc="<?php echo $name;?>";
</script>
<script type="text/javascript" src="pagejs/userinfo.js?<?php echo filemtime("pagejs/userinfo.js"); ?>"></script>

<?php
    include("end.php");
?>
