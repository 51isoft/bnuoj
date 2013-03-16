<?php
  include_once("conn.php");
  $cid=convert_str($_GET["cid"]);
  if (db_contest_exist($cid)&&(db_contest_ispublic($cid)||(db_contest_private($cid)&&db_user_in_contest($cid,$nowuser))||(db_contest_password($cid)&&db_user_in_contest($cid,$nowuser)))) {
    $query="select title,description,isprivate,start_time,end_time,isvirtual,owner,has_cha,challenge_end_time,challenge_start_time from contest where cid='$cid'";
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
          <h1 class="pagetitle"><?php echo db_get_contest_title($cid); ?></h1>
          <div class="center" style="margin-bottom:0">
            Contest Start Time: <?php echo $crow["start_time"]; ?> &nbsp;&nbsp;&nbsp;&nbsp;
            <?php if ( $crow["has_cha"]=="0") echo "Contest End Time: "; else echo "Coding End Time: "; ?><?php echo $crow["end_time"]; ?>
            <?php if ( $crow["has_cha"])  { ?>
            <br /> Challenge Start Time: <?php echo $crow[9]; ?> &nbsp;&nbsp;&nbsp;&nbsp; Challenge End Time: <?php echo $crow[8]; ?> 
            <?php } ?>
            <br />
            Current Server Time: <span id="servertime"><?php echo date("Y-m-d H:i:s"); ?></span> &nbsp;&nbsp;&nbsp;&nbsp;
<?php
            $canshow=true;
            $nowtime=time();
            if (db_contest_passed($cid)) echo "<span class='cpassed'>Passed</span>";
            else if (db_contest_intermission($cid)) {
                $diff = strtotime($crow[9])-$nowtime;
    			$diffhour  = (int)($diff/3600);
    			$diffminute = (int)(($diff-$diffhour*3600)/60);
    			$diffsecond = $diff-$diffhour*3600-$diffminute*60;
                echo "Countdown: <span id='counttime'>$diffhour:$diffminute:$diffsecond</span> &nbsp;&nbsp;&nbsp;&nbsp; <span class='crunning'>Intermission</span>";
            }
            else if (db_contest_challenging($cid)) {
                $diff = strtotime($crow[8])-$nowtime;
    			$diffhour  = (int)($diff/3600);
    			$diffminute = (int)(($diff-$diffhour*3600)/60);
    			$diffsecond = $diff-$diffhour*3600-$diffminute*60;
                echo "Countdown: <span id='counttime'>$diffhour:$diffminute:$diffsecond</span> &nbsp;&nbsp;&nbsp;&nbsp; <span class='crunning'>Challenging</span>";
            }
            else if (db_contest_running($cid)) {
                $diff = strtotime($crow[4])-$nowtime;
    			$diffhour  = (int)($diff/3600);
    			$diffminute = (int)(($diff-$diffhour*3600)/60);
    			$diffsecond = $diff-$diffhour*3600-$diffminute*60;
                echo "Countdown: <span id='counttime'>$diffhour:$diffminute:$diffsecond</span> &nbsp;&nbsp;&nbsp;&nbsp; <span class='crunning'>Running</span>";
            }
            else {
                $diff = strtotime($crow[3])-$nowtime;
    			$diffhour  = (int)($diff/3600);
    			$diffminute = (int)(($diff-$diffhour*3600)/60);
    			$diffsecond = $diff-$diffhour*3600-$diffminute*60;
                $canshow=false;
                echo "Countdown: <span id='counttime'>$diffhour:$diffminute:$diffsecond</span> &nbsp;&nbsp;&nbsp;&nbsp; <span class='cscheduled'>Not Started</span>";
            }
            if (db_user_match($nowuser,$nowpass)&&(db_user_isroot($nowuser)||strcasecmp($nowuser,db_contest_owner($cid))==0)) {
?>
            <br /><a target="_blank" href="contest_problem_merge.php?cid=<?php echo $cid; ?>">[Show All Problem Description] ( For print, shown to owner only. )</a>
<?php
            }
?>
          </div>
<?php
            if ($canshow) {
?>
          <table id="cplist" class="display">
            <thead>
              <tr>
                <th width="60px">Flag</th>
                <th width="55px">ID</th>
                <th>Title</th>
                <th width="100px">Ratio</th>
                <th width="100px">User</th>
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
                $query = "select count(*) from ".$prefix."status where contest_belong='$cid' and pid='$go[0]' and (result='Accepted' or result='Pretest Passed')";
                $result = mysql_query($query);
                list($acsum) = mysql_fetch_row($result);

                $query = "select count(distinct username) from ".$prefix."status where contest_belong='$cid' and pid='$go[0]'";
                $result = mysql_query($query);
                list($submitsumuser) = mysql_fetch_row($result);
                $query = "select count(distinct username) from ".$prefix."status where contest_belong='$cid' and pid='$go[0]' and (result='Accepted' or result='Pretest Passed')";
                $result = mysql_query($query);
                list($acsumuser) = mysql_fetch_row($result);

                if ($cctype!="99") {
                    $query = "select count(*) from status where contest_belong='$cid' and pid='$go[0]' and  (result='Accepted' or result='Pretest Passed') and username='$nowuser'";
                    $result = mysql_query($query);
                    list($userac) = mysql_fetch_row($result);
                    $query = "select count(*) from status where contest_belong='$cid' and pid='$go[0]' and username='$nowuser'";
                    $result = mysql_query($query);
                    list($usersubmit) = mysql_fetch_row($result);
                    $query = "select count(*) from status where contest_belong='$cid' and pid='$go[0]' and username='$nowuser' group by result";
                    if ($userac>0) $flag='Yes';
                    else if ($usersubmit>0) $flag='No';
                    else $flag='';
                } else $flag='';

                echo "<tr>\n";
                echo "<td> $flag </td>\n";
                echo "<td><a href='#problem/$go[2]'>",$go[1],"</a></td>\n";
                echo "<td><a href='#problem/$go[2]'>",$title," </a></td>\n";
                echo "<td> $acsum/$submitsum </td>\n";
                echo "<td> $acsumuser/$submitsumuser </td>\n";
                echo "</tr>\n";
            }

?>
            </tbody>
          </table>
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
