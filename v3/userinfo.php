<?php
$pagetitle="Information of ".$_GET['name'];
include_once("header.php");
include_once("functions/users.php");
include_once("functions/sidebars.php");
$name=convert_str($_GET['name']);
$show_user=new User;
$show_user->load_info($name);
?>
<?php
if ($show_user->is_valid()) {
?>
        <div class="span8">
          <h2>Information of <?=$show_user->get_val('username')?></h2>
          <div id="userinfo">
            <table class="table">
              <tr><th class="span3">Username:</th><td class="span9"><?=$show_user->get_val('username')?> </td></tr>
              <tr><th>User ID:</th><td><?=$show_user->get_val('uid')?> </td></tr>
              <tr><th>Rank:</th><td><?=$show_user->get_val('rank')?> </td></tr>
              <tr><th>Nickname:</th><td><?=$show_user->get_val('nickname')?> </td></tr>
              <tr><th>School:</th><td><?=htmlspecialchars($show_user->get_val('school'))?> </td></tr>
              <tr><th>Email:</th><td><?=htmlspecialchars($show_user->get_val('email'))?> </td></tr>
              <tr><th>Register Time:</th><td><?=$show_user->get_val('register_time')?> </td></tr>
              <tr><th>Last Login:</th><td><?=$show_user->get_val('last_login_time')?> </td></tr>
<?php
  if ($current_user->is_root()) {
?>
              <tr><th>Last Login IP: </th><td><?=$show_user->get_val('ipaddr')?></td></tr>
<?php
  }
?>

              <tr>
                <th>Accepted:</th>
                <td>
                  <div style="width:100%">
                    <?=$show_user->get_val('total_ac')?> &nbsp;&nbsp; <button id="showac" class="btn btn-primary btn-mini">Show all</button><button id="hideac" class="btn btn-primary btn-mini hide">Hide</button>
                  </div>
                  <div style="width:100%" id="userac" class="collapse">
<?php
  $acpid=$show_user->get_accepted_pid();
  foreach ($acpid as $pid) echo "<a href='problem_show.php?pid=$pid' target='_blank'>$pid</a>&nbsp; ";
?>

                  </div>
                </td>
              </tr>
              <tr>
                <th>Compare:</th>
                <td>
                  <form id="compareform" class="form-search">
                    <div class="input-append"><input class="search-query" type='text' id='user2' value="<?=$current_user->get_username()?>" /><button type="submit" class="btn btn-primary" id="compare">Go!</button></div>
                    <button id="hidecompare" type="button" class="hide btn btn-primary">Hide</button>
                  </form>
                  <div class="collapse" id="compareinfo"></div>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="span4">
<?= sidebar_userinfo($show_user) ?>
        </div>
<?php
} else {
?>
        <div class="span12">
          <div class="alert alert-error">No such user!</div>
        </div>
<?php
}
?>

<script type="text/javascript" src="js/highcharts.js"></script>
<script type="text/javascript">
var nametoc="<?=$name?>";
</script>
<script type="text/javascript" src="js/userinfo.js?<?php echo filemtime("js/userinfo.js"); ?>"></script>

<?php
include("footer.php");
?>
