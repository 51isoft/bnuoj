<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="description" content="BNU Online Judge, A simple, full-featured Online Judge." />
  <meta name="keywords" content="Online Judge, BNU, OJ, BNUOJ, BOJ, Virtual Judge, Replay Contest, Problem Category" />
  <link rel="shortcut icon" href="favicon.ico" />
  <link rel="stylesheet" type="text/css" href="style/style.css?<?php echo filemtime("style/style.css"); ?>" />
  <link rel="stylesheet" type="text/css" href="css/overcast/jquery-ui-1.8.20.custom.css" />
  <link rel="stylesheet" type="text/css" href="css/jquery.snippet.min.css" />
  <link rel="stylesheet" type="text/css" href="media/css/data_table.css" />
  <title><?php echo $pagetitle==""?"BNU Online Judge":$pagetitle; ?></title>
  <script type="text/javascript" src="js/jquery-1.7.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui-1.8.20.custom.min.js"></script>
  <script type="text/javascript" src="js/bnuoj-ext.js?<?php echo filemtime("js/bnuoj-ext.js"); ?>"></script>
  <script type="text/javascript" src="media/js/jquery.dataTables.min.js?<?php echo filemtime("media/js/jquery.dataTables.min.js"); ?>"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-11927356-1']);
  _gaq.push(['_setDomainName', 'bnuoj.com']);
  _gaq.push(['_setAllowLinker', true]);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body>
  <div id="main">
    <div id="browsercapa">
      You are using Internet Explorer 5/6/7!<br />
      Please update or change your browser to get better experience. <br />
      Or click <a href="../contest">here</a> to switch to the old version of BNUOJ.
    </div>
    <script type="text/javascript">
      if ($.browser.msie  && parseInt($.browser.version) < 8) $("#browsercapa").show();
    </script>
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the logo text -->
          <!-- <h1>BNU O<span class="logo_colour">nline </span>J<span class="logo_colour">udge</span></h1> -->
          <h1>BNU <span class="logo_colour">Online Judge</span></h1>
          <h2>A simple, full-featured Online Judge.</h2>
        </div>
      </div>

