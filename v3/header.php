<?php
include_once("functions/users.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="description" content="BNU Online Judge, A simple, full-featured Online Judge." />
    <meta name="keywords" content="Online Judge, BNU, OJ, BNUOJ, BOJ, Virtual Judge, Replay Contest, Problem Category" />
    <meta name="author" content="51isoft">
    <link rel="shortcut icon" href="ico/bnuoj.ico" />
    <title><?= $pagetitle==""?"BNU Online Judge":$pagetitle ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Le styles -->
    <link href="<?= file_exists("css/style/bootstrap.".$_COOKIE[$config["cookie_prefix"]."style"].".min.css")?"css/style/bootstrap.".$_COOKIE[$config["cookie_prefix"]."style"].".min.css":"css/style/bootstrap.".$config["default_style"].".min.css" ?>" rel="stylesheet">
    <link href="css/bnuoj.css?<?=filemtime("css/bnuoj.css") ?>" rel="stylesheet">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="css/DT_bootstrap.css" rel="stylesheet">
    <link href="css/select2.css" rel="stylesheet">
    <link href="css/fullcalendar.css" rel="stylesheet">
    <link href="css/datetimepicker.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.js"></script>
    <script src="js/DT_bootstrap.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/bootstrap-datetimepicker.js"></script>
    <script src="js/bnuoj-ext.js?<?=filemtime("js/bnuoj-ext.js") ?>"></script>

  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="<?= $_COOKIE[$config["cookie_prefix"]."fluid_width"]==true?"container-fluid":"container" ?>">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="index.php">BNUOJ</a>
          <div class="nav-collapse collapse">
            <ul class="nav" id="nav">
              <li class="dropdown" id="problem"><a class="dropdown-toggle" data-toggle="dropdown" href="problem.php">Problem <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li id="localp"><a href="problem.php#oj=BNU">Local Problems</a></li>
                  <li id="allp"><a href="problem.php">All Problems</a></li>
                  <li id="categoryp"><a href="problem_category.php">Problem Category</a></li>
                </ul>
              </li>
              <li id="status"><a href="status.php">Status</a></li>
              <li class="dropdown" id="contest"><a class="dropdown-toggle" data-toggle="dropdown" href="contest.php?type=50">Contest <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li id="stdcontest"><a href="contest.php?type=50">Standard Contests</a></li>
                  <li id="stdcontest"><a href="contest.php?type=0">Contests (ICPC format)</a></li>
                  <li id="cfcontest"><a href="contest.php?type=1">Contests (CF format)</a></li>
                  <li id="repcontest"><a href="contest.php?type=99">Replay Contests</a></li>
                  <li id="vcontest"><a href="contest.php?virtual=1">Virtual Contests</a></li>
                </ul>
              </li>
              <li id="ranklist"><a href="ranklist.php">Ranklist</a></li>
              <li id="discuss"><a href="discuss.php">Discuss</a></li>
              <li class="dropdown" id="more"><a class="dropdown-toggle" data-toggle="dropdown" href="teaminfo.php">More... <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="news.php">News</a></li>
                  <li><a href="teaminfo.php">Our Team</a></li>
                  <li><a href="http://www.oiegg.com/forumdisplay.php?fid=407" target="_blank">BBS</a></li>
                  <li class="divider"></li>
                  <li><a href="recent_contest.php">Recent Contests</a></li>
                  <!--<li><a href="training_stat.php">Training Stats</a></li>-->
                  <li class="divider"></li>
                  <li class="disabled"><a>Coming Soon...</a></li>
                </ul>
              </li>
            </ul>
<?php
if (!$current_user->is_valid()) {
?>
            <ul id="loginbar" class="nav pull-right">
              <li id="loginbutton"><a href="#" id="login">Login</a></li>
              <li id="register"><a href="#" class="toregister">Register</a></li>
            </ul>
<?php
} else {
?>
            <ul id="logoutbar" class="nav pull-right">
              <li class="dropdown" id="userspace">
                <a class="dropdown-toggle" data-toggle="dropdown" href="userinfo.php?name=<?=$nowuser?>" id="displayname"><?=$nowuser.($current_user->get_unread_mail_count()>0?"<b style='color:#F00'>(".$current_user->get_unread_mail_count().")</b>":"")?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="userinfo.php?name=<?=$nowuser?>">Show My Information</a></li>
                  <li><a href="#" id="modify">Modify My Information</a></li>
                  <li><a href="mail.php" id="mail">Mail<?=($current_user->get_unread_mail_count()>0?"<b style='color:#F00'>(".$current_user->get_unread_mail_count().")</b>":"")?></a></li>
<?php
  if ($current_user->is_root()) {
?>
                  <li><a href="admin_index.php" id="admin">Administration</a></li>
<?php
  }
?>
                  <li id="logoutbutton"><a href="#" id="logout">Logout</a></li>
                </ul>
              </li>
            </ul>
<?php
}
?>
            <p class="pull-right navbar-text"><span id="servertime"><?=date("Y-m-d H:i:s")?></span>&nbsp;</p>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    <marquee class="hidden-phone" direction="left" behavior="alternate" scrollamount="2" style="position:absolute;width:100%;"><?=get_substitle()?></marquee>
    <div class="hidden-phone" id="marqueepos"></div>
<script>
//var currenttime = '<?=date("l, F j, Y H:i:s",time())?>';
var currenttime = '<?=time()?>';
var cookie_prefix = '<?=$config["cookie_prefix"] ?>';
var default_style= '<?=$config["default_style"]?>';
</script>
    <div class="<?= $_COOKIE[$config["cookie_prefix"]."fluid_width"]==true?"container-fluid":"container" ?>" id="page-content">
      <div class="row-fluid">
