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

        <div class="sidebar">
          <div class="sidebar_top"></div>
          <div class="sidebar_item">
            <h1>Latest News <span style="font-size:12px"><a href="news.php">[more...]</a></span></h1>
<?php
    $sql="select * from news order by time_added desc limit 0,$newsperpage";
    $res=mysql_query($sql);
    $none=true;
    while ($row=mysql_fetch_array($res)) {
        $none=false;
        $row['title']=strip_tags($row['title']);
        if (strlen($row['title'])>30) $row['title']=mb_strcut($row['title'],0,30,'UTF-8')."<a name='".$row['newsid']."' class='newslink' href='#'>[...]</a>";
        $row['content']=strip_tags($row['content']);
        if (strlen($row['content'])>100) $row['content']=mb_strcut($row['content'],0,100,'UTF-8');
        $row['content'].="<a name='".$row['newsid']."' class='newslink' href='#'>[...]</a>";
?>
            <h4><?php echo $row['title']; ?></h4>
            <p><?php echo $row['content']; ?></p>
<?php
    }
    if ($none) {
?>
            No News.
<?php
    }
?>
          </div>
          <div class="sidebar_base"></div>
        </div>


