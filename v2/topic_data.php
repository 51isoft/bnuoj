<?php
    include("conn.php");
   function dispreplybbs($id,$mysqlres,$rid)
   {
     for($i=0;$i<mysql_num_rows($mysqlres);$i++)
     {
       mysql_data_seek($mysqlres,$i);
       $bbsreply=mysql_fetch_array($mysqlres);
       if($bbsreply[1]==$id)
       {
         echo "<ul>";
         if ($rid==$bbsreply[0]) $bbsreply[4]="<span style='color:#000;font-weight:bolder'>".htmlspecialchars($bbsreply[4])."</span>";
         else $bbsreply[4]=htmlspecialchars($bbsreply[4]);
         echo "<li>"."<a class='topicshow' href='#' name='$bbsreply[0]'>".$bbsreply[4]."</a> ";//title
         echo "<span style='color:#AAA; font-size:smaller'>(".strlen($bbsreply[5])." bytes)</span> <a href='userinfo.php?name=$bbsreply[6]' target='_blank'><b>".$bbsreply[6]."</b></a> ".$bbsreply[3]."\n";
         dispreplybbs($bbsreply[0],$mysqlres,$rid);
//         echo "</ul>";
         echo "</li>";
         echo "</ul>";
       }
     }
   }

	$id = convert_str($_GET['id']);
	if($id!=""){
    	$sql_res = " select * from discuss where id='".$id."'";
    	$res = mysql_query($sql_res);
        $now=mysql_fetch_array($res); 
        $pagetitle=htmlspecialchars($now[4]);
        if ($now[7] != 0) $pagetitle=$pagetitle." - Problem ".$now[7];
        echo "<h1 style='display:none' id='topictitle'>$pagetitle</h1>";
        echo "<div class='center'><h2 style='margin:0'>".htmlspecialchars($now[4])."</h2>";
        echo " by <a href='userinfo.php?name=$now[6]'><b>".$now[6]."</b></a> ".$now[3];
        if($now[7] != 0)  echo " At <a href='problem_show.php?pid=$now[7]' target='_blank'><b>Problem ".$now[7]."</b></a>";
    echo "</div><hr />";
    echo "<div class='content-wrapper ui-corner-all' style='margin:20px 0'><pre>".htmlspecialchars($now[5])."</pre></div>";

?>
<hr />
<?php

    echo "<ul>";
    $sql_res = " select * from discuss where rid= ".$now[2]." order by time";
     $res = mysql_query($sql_res);
     $root=mysql_fetch_array($res);
     echo "<li>";
	 if ($id==$root[0]) $root[4]="<span style='color:#000;font-weight:bolder'>".htmlspecialchars($root[4])."</span>";
     else $root[4]=htmlspecialchars($root[4]);
	 echo "<a href='#' name='$root[0]' class='topicshow'>".$root[4]."</a>"." ";//title
	 echo "<span style='color:#AAA; font-size:smaller'>(".strlen($root[5])." bytes)</span> <a href='userinfo.php?name=$root[6]' target='_blank'><b>".$root[6]."</b></a> ".$root[3]."\n";
/*	 if($root[7] != 0)
	 echo " <b><a href=problem_show.php?pid=$root[7]>Problem ".$root[7]."</a></b>"."<ul>";
	 else{
	       echo " <b>General Topic</b>"."<ul>";
	 }*/
    dispreplybbs($root[0],$res,$id);
     echo "</li></ul>";
?>

<hr />

  <form action="#" name="reply.php?id=<?php echo $id;?>&rid=<?php echo $now[2];?>&pid=<?php echo $now[7];?>" method="post" id='replybox'>
    <input type="text" name="title" value="RE: <?php echo htmlspecialchars($now[4]);?>" style="width:600px"/><br/>
    <textarea name="content" style='width:700px;height:200px;'></textarea>
    <div class="center">
      <span>&nbsp;</span><span id="replymsgbox" style="display:none; z-index:300;width:120px"></span><input type="submit" name="name" value="Reply" />
    </div>
  </form>




<?php
	}
?>

