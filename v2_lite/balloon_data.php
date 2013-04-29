<?php
  include_once("conn.php");
  $cid=convert_str($_GET['cid']);
  if (db_contest_exist($cid)&&db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
      $sql="select status.username,contest_problem.lable,color.color,color.tcolor,color.text from status,contest_problem,color where color.cid=status.contest_belong and color.lable=contest_problem.lable and status.pid=contest_problem.pid and contest_belong='$cid' and result='Accepted' and (status.username,contest_belong,contest_problem.lable) not in (select username,cid,lable from balloons) group by status.username,contest_problem.lable";
      $res=mysql_query($sql);
      while ($row=mysql_fetch_array($res)) {
?>
<div data-id="<?php echo $row[0]."-".$row[1]; ?>" class="item ui-corner-all" style="float:left;background-color:<?php echo $row[2]; ?>;padding:10px;margin:5px 5px 0 5px;width:130px;text-align:center">
<a style="color:<?php echo $row[3]; ?>" class="send_a" suser="<?php echo $row[0]; ?>" slabel="<?php echo $row[1]; ?>" scid="<?php echo $cid; ?>"><?php echo "User: ".$row[0]."<br />Problem: ".$row[1]."<br />Color: $row[4]"; ?></a>
</div>
<?php
      }
  }
  else {
  }
?>
