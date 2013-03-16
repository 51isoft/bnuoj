<?php
  include_once("conn.php");
  $cid=convert_str($_GET['cid']);
  $user=convert_str($_GET['user']);
  $label=convert_str($_GET['label']);
  if (db_contest_exist($cid)&&db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
      $sql="insert into balloons (cid,lable,username) values ('$cid','$label','$user')";
      $res=mysql_query($sql);
  }
  else {
  }
?>
