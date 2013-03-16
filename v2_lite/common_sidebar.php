<?php
    include_once("conn.php");
?>
        <div class="sidebar">
          <div class="sidebar_top"></div>
          <div class="sidebar_item">
            <h1>Upcoming Next</h1>
            <p>
<?php
    $sql="select cid,title,start_time,type from contest where start_time>now() and isvirtual=0 order by start_time limit 0,5";
    $res=mysql_query($sql);
    $none=true;
    while (list($tcid,$tctitle,$tcsttime,$tctype)=mysql_fetch_array($res)) {
        if ($tctype==1) $tctitle.=" [CF]";
        $none=false;
?>
                <a href='contest_show.php?cid=<?php echo $tcid; ?>'><?php echo $tctitle; ?></a> at <?php echo $tcsttime; ?><br />
<?php
    }
    if ($none) {
?>
                No upcoming contest.
<?php
    }
?>
            </p>
          </div>
          <div class="sidebar_base"></div>
        </div>


