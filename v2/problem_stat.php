<?php
  include_once('conn.php');
  $pid = convert_str($_GET['pid']);
  if ($pid=="") $pid="0";
  $querypage="select count(*) from problem where pid<'$pid' and hide=0";
  list($ppage)=mysql_fetch_array(mysql_query($querypage));
  $ppage=intval($ppage/$problemperpage)+1;
  $query="select title from problem where pid='$pid'";
  $result = mysql_query($query);
  list($ptitle)=@mysql_fetch_row($result);
  if (mysql_num_rows($result)>0 && !$hide) $pagetitle="Statistics of Problem ".$pid;
  else $pagetitle="Problem Unavailable";
  include_once("header.php");
  include_once("menu.php");
?>
    <div id="site_content">
      <div id="sidebar_container">
        <!-- insert your sidebar items here -->
<?php
  include("problem_sidebar.php");
?>
      </div>
      <div id="content_container">
        <div id="content_top"></div>
        <div id="content">
          <h1>Leaderboard of <a href="problem_show.php?pid=<?php echo $pid; ?>">Problem <?php echo $pid; ?></a></h1>
          <table class="display" id="pleader">
            <thead>
              <tr>
                <th width="45px">Rank</th>
                <th width="45px">ACs</th>
                <th width="65px">Runid</th>
                <th>Username</th>
                <th width="80px">Time</th>
                <th width="80px">Memory</th>
                <th width="120px">Language</th>
                <th width="80px">Length</th>
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
<script type="text/javascript">
var ppid='<?php echo $pid; ?>';
var pstatperpage=<?php echo $pstatuserperpage; ?>;
</script>
<script type="text/javascript" src="js/highcharts.js"></script>
<script type="text/javascript" src="pagejs/problem_stat.js"></script>
<?php
    include("end.php");
?>
