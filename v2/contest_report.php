<?php
  include_once("conn.php");
  $cid=convert_str($_GET["cid"]);
  if (db_contest_exist($cid)&&(db_contest_ispublic($cid)||(db_contest_private($cid)&&db_user_in_contest($cid,$nowuser))||(db_contest_password($cid)&&db_user_in_contest($cid,$nowuser)))) {
    $query="select report from contest where cid='$cid'";
    $result=mysql_query($query);
    $crow=mysql_fetch_array($result);
?>
      <div id="sidebar_container">
        <!-- insert your sidebar items here -->
<?php
  include("contest_sidebar.php");
?>
      </div>
      <div id="content_container">
        <div id="content_top"></div>
        <div id="content">
          <!-- insert the page content here -->
          <h1 class="pagetitle">Report to <?php echo db_get_contest_title($cid); ?></h1>
            Current Server Time: <span id="servertime"><?php echo date("Y-m-d H:i:s"); ?></span> &nbsp;&nbsp;&nbsp;&nbsp;
<?php
            $canshow=false;
            if (db_contest_passed($cid)) $canshow=true;
?>
<?php
            if ($canshow) {
?>
          <div id="contestrep" class="content-wrapper ui-corner-all" style="margin:20px 0;">
            <?php echo latex_content($crow["report"]); ?>
          </div>
          <h2>Problem Statistics</h2>
          <table id="cprobreport" class="display">
            <thead>
              <tr>
                <th width="60px">ID</th>
                <th>Title</th>
                <th width="110px">Ratio</th>
                <th width="110px">User</th>
              </tr>
            </thead>
            <tfoot></tfoot>
            <tbody>
<?php
            $cctype=db_contest_type($cid);
            if ($cctype=="99") $prefix="replay_";
            else $prefix="";
            $cha = "SELECT pid,lable,cpid FROM contest_problem WHERE cid = '$cid' order by lable asc";
            $que = mysql_query($cha);
            while (  $go = mysql_fetch_array($que) ) {
                $cha2 ="SELECT title FROM problem WHERE pid = '$go[0]'";
                $que2 = mysql_query($cha2);
                list($title) = mysql_fetch_row($que2);
                $query = "select count(*) from ".$prefix."status where contest_belong='$cid' and pid='$go[0]'";
                $result = mysql_query($query);
                list($submitsum) = mysql_fetch_row($result);
                $query = "select count(*) from ".$prefix."status where contest_belong='$cid' and pid='$go[0]' and result='Accepted'";
                $result = mysql_query($query);
                list($acsum) = mysql_fetch_row($result);

                $query = "select count(distinct username) from ".$prefix."status where contest_belong='$cid' and pid='$go[0]'";
                $result = mysql_query($query);
                list($submitsumuser) = mysql_fetch_row($result);
                $query = "select count(distinct username) from ".$prefix."status where contest_belong='$cid' and pid='$go[0]' and result='Accepted'";
                $result = mysql_query($query);
                list($acsumuser) = mysql_fetch_row($result);

                echo "<tr>\n";
                echo "<td>$go[1]</td>\n";
                echo "<td>$title</td>\n";
                echo "<td> $acsum/$submitsum </td>\n";
                echo "<td> $acsumuser/$submitsumuser </td>\n";
                echo "</tr>\n";
            }

?>
            </tbody>
          </table>
<?php
            } else {
?>
          <h2 style="margin-bottom:0">Not finished, come back later :) .</h2>
<?php
            }
?>
        </div>
        <div id="content_base"></div>
      </div>
<?php
    } else {
?>
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <p>
            <div class="error"><b>Contest Unavailable!</b></div>
          </p>
        </div>
        <div id="one_content_base"></div>
      </div>

<?php
    }
?>
