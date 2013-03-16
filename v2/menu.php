<?php
    include_once("conn.php");
?>
      <div id="menubar">
        <ul class="nav" id="nav">
          <li id="home"><a href="index.php" accesskey="h">Home</a></li>
          <li id="ranklist"><a href="ranklist.php" accesskey="r">Ranklist</a></li>
          <li id="status"><a href="status.php" accesskey="s">Status</a></li>
          <li id="problem"><a href="problem.php?page=<?php echo $ppage; ?>" accesskey="p">Problem</a>
            <ul>
              <li id="localp"><a href="problem.php">Local Problem</a></li>
              <li id="allp"><a href="problem.php?page=1">All Problem</a></li>
              <li id="categoryp"><a href="problem_category.php">Problem Category</a></li>
            </ul>
          </li>
          <li id="contest"><a href="contest.php?type=50">Contest</a>
            <ul>
              <li id="stdcontest"><a href="contest.php?type=0">Contest (ICPC format)</a></li>
              <li id="cfcontest"><a href="contest.php?type=1">Contest (CF format)</a></li>
              <li id="repcontest"><a href="contest.php?type=99">Replay Contest</a></li>
              <li id="vcontest"><a href="contest.php?virtual=1">Virtual Contest</a></li>
            </ul>
          </li>
          <li id="discuss"><a href="discuss.php" accesskey="d">Discuss</a></li>
          <li id="ourteam"><a href="teaminfo.php">Our Team</a></li>
          <li><a href="http://www.oiegg.com/forumdisplay.php?fid=407" target="_blank">BBS</a></li>
          <li id="more"><a href="javascript:void(0);">More...</a>
            <ul>
              <li><a href="news.php" accesskey="n">News</a></li>
              <li><a href="otheroj.php">Recent Contests</a></li>
              <li><a href="training_stat.php">Training Stats</a></li>
              <li><a href="#">Coming Soon...</a></li>
            </ul>
          </li>
        </ul>
        <ul id="loginbar" class="nav">
          <li id="loginbutton"><a href="javascript:void(0);" id="login" accesskey="i">Login</a></li>
          <li id="register"><a href="javascript:void(0);" class="toregister" accesskey="g">Register</a></li>
        </ul>
        <ul id="logoutbar" class="nav">
          <li id="userspace">
            <a href="userinfo.php?name=<?php echo $nowuser ?>" id="displayname"><?php
    echo $nowuser;
    if (intval(db_get_unread_mail_number($nowuser))>0) echo "<b style='color:#F00'>(".db_get_unread_mail_number($nowuser).")</b>";
?></a>
            <ul>
              <li id="logoutbutton"><a href="javascript:void(0);" id="logout">Logout</a></li>
              <li><a href="modify.php?username=<?php echo $nowuser; ?>" id="modify">Modify My Information</a></li>
              <li><a href="mail.php" id="mail" accesskey="m">Mail<?php if (intval(db_get_unread_mail_number($nowuser))>0) echo "<b style='color:#F00'>(".db_get_unread_mail_number($nowuser).")</b>"; ?></a></li>
<?php
	if (db_user_isroot($nowuser)) {
?>
              <li><a href="admin_index.php" id="admin">Administration</a></li>
<?php
	}
?>
            </ul>
          </li>
        </ul>
        <script type="text/javascript">
            if ($.cookie("username")==null||$.cookie("password")==null||$.cookie("username")==""||$.cookie("password")==""||$.cookie("username")=="deleted"||$.cookie("password")=="deleted") {
                $("#logoutbar").hide();
                $("#loginbar").show();
            }
            else {
                $("#loginbar").hide();
                $("#logoutbar").show();
            }
        </script>
      </div>
    </div>
    <div style="clear:both;"></div>
    <marquee style="margin-bottom:-18px;" width="960" direction="left" behavior="alternate" scrollamount="1"><?php echo $substitle; ?></marquee>
    <script type="text/javascript">
        $('marquee').marquee().mouseover(function () {
            $(this).trigger('stop');
        }).mouseout(function () {
            $(this).trigger('start');
        });
    </script>

