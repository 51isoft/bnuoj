<?php
    include_once("conn.php");
?>
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
        <div class="sidebar">
          <div class="sidebar_top"></div>
          <div class="sidebar_item">
            <h1>VJudge Status</h1>
            By checking remote status page every 10 minutes.
            <table id='stat_info' style="margin-bottom:0px">
                <thead>
                    <tr>
                        <th>OJ</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
<?php
    $sql="select * from ojinfo where name not like 'BNU'";
    $res=mysql_query($sql);
    while ($row=mysql_fetch_array($res)) {
        $statinfo="";
        if ($row['status']=="Normal") $statinfo="<img src='style/green_light.png' title='Last Check: ".$row['lastcheck'].", ".$row['status']."' />";
        else if (substr($row['status'],0,4)=="Down") $statinfo="<img src='style/red_light.png' title='Last Check: ".$row['lastcheck'].", ".$row['status']."' />";
        else $statinfo="<img src='style/yellow_light.png' title='Last Check: ".$row['lastcheck'].", ".$row['status']."' />";
?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $statinfo; ?></td>
                    </tr>
<?php
    }
?>
                </tbody>
            </table>
          </div>
          <div class="sidebar_base"></div>
        </div>


