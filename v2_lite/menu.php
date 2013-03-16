<?php
    include_once("conn.php");
?>
      <div id="menubar">
        <ul class="nav" id="nav">
          <li id="home"><a href="index.php" accesskey="h">Home</a></li>
          <li id="contest"><a href="contest.php">Contest</a></li>
          <li id="print"><a href="print.php">Print</a></li>
        </ul>
        <ul id="loginbar" class="nav">
          <li id="loginbutton"><a href="javascript:void(0);" id="login" accesskey="i">Login</a></li>
        </ul>
        <ul id="logoutbar" class="nav">
          <li id="userspace">
            <a href="userinfo.php?name=<?php echo $nowuser ?>" id="displayname"><?php echo $nowuser; ?></a>
            <ul>
              <li id="logoutbutton"><a href="javascript:void(0);" id="logout">Logout</a></li>
<?php
	if (db_user_isroot($nowuser)) {
?>
              <li><a href="admin_index.php" id="admin">Administration</a></li>
              <li><a href="admin_print.php" id="admin_print">Admin Print</a></li>
<?php
	}
?>
            </ul>
          </li>
        </ul>
        <script type="text/javascript">
            if ($.cookie("username")==null) {
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
    <marquee width="960" direction="left" behavior="alternate" scrollamount="1"><?php echo $substitle; ?></marquee>
    <script type="text/javascript">
        $('marquee').marquee().mouseover(function () {
            $(this).trigger('stop');
        }).mouseout(function () {
            $(this).trigger('start');
        });
    </script>

