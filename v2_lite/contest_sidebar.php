<?php
    include_once("conn.php");
?>
        <div class="sidebar">
          <div class="sidebar_top"></div>
          <div class="sidebar_item">
            <h1>Information</h1>
            <p>
<?php
    $sql="select description,type,unix_timestamp(start_time),unix_timestamp(end_time) from contest where cid='$cid'";
    $res=mysql_query($sql);
    list($tcinfo,$ctype,$cstt,$cedt)=mysql_fetch_array($res);
    if (db_contest_passed($cid)) $cstt=$cedt-$cstt;
    else $cstt=time()-$cstt;
    if ($cstt<0) $cstt=0;
?>
                <?php echo nl2br($tcinfo); ?>
            </p>
          </div>
          <div class="sidebar_base"></div>
        </div>
<?php
    if ($ctype==1) {
?>
        <div class="sidebar">
          <div class="sidebar_top"></div>
          <div class="sidebar_item">
            <h1>Current Value</h1>
            <table style="width:100%;margin:0">
                <thead>
                    <tr><th>Label</th><th>Value</th></tr>
                </thead>
                <tbody>
<?php
        $ssql="select * from contest_problem where cid='$cid' order by lable asc";
        $sres=mysql_query($ssql);
        while ($srow=mysql_fetch_array($sres)) {
?>
                    <tr><th><?php echo $srow['lable']; ?></th><td style="text-align:center"><?php echo cal_point($srow,$cstt); ?></td></tr>
<?php
        }
?>
                </tbody>
            </table>
          </div>
          <div class="sidebar_base"></div>
        </div>
<?php
    }
?>
<?php
  include("common_sidebar.php");
?>


