<?php
  include_once("conn.php");
  if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
      $sql="select id from print where sent=0";
      $res=mysql_query($sql);
      while ($row=mysql_fetch_array($res)) {
?>
<div data-id="<?php echo $row[0]; ?>" class="item ui-corner-all" style="float:left;background-color:#000;padding:10px;margin:5px 5px 0 5px;width:130px;text-align:center">
<a style="color:#fff" class="send_a" href="admin_deal_print.php?id=<?php echo $row[0]; ?>" target="_blank"><?php echo "Print ID: ".$row[0]; ?></a>
</div>
<?php
      }
  }
  else {
  }
?>
