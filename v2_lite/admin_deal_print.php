<?php
  include_once("conn.php");
  $id=convert_str($_GET['id']);
  list($user,$content,$sent)=mysql_fetch_array(mysql_query("select username,content,sent from print where id='$id'"));
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <script type="text/javascript" src="js/jquery-1.7.min.js"></script>
  <link rel="shortcut icon" href="favicon.ico" />
  <link rel="stylesheet" type="text/css" href="style/style.css" />
</head>
<STYLE   TYPE="text/css">     
  @media print{  
    .notprint {display:none;}     
  }     
</STYLE>
<body>
<?php
  if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
      mysql_query("update print set sent=1 where id='$id'");
?>
Username: <?php echo $user; ?>
<blockquote style="margin:5px 0;padding:10px">
<pre>
<?php echo htmlspecialchars($content); ?>
</pre>
</blockquote>
<button onclick="window.print()" class="notprint">打印</button>
<?php
  }
  else {
?>
          <p>
            <div class="error"><b>无权查看。<b></div>
          </p>
<?php
  }
?>
</body>
</html>
