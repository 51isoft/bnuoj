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

         echo "<li>";
		 if ($rid==$bbsreply[0]) echo "<b>";
		 echo "<a href=reply.php?id=$bbsreply[0] class='bottom_link'>".htmlspecialchars($bbsreply[4])."</a> ";//title
		 if ($rid==$bbsreply[0]) echo "</b>";
		 echo "<font color=gray size=-1>(".strlen($bbsreply[5])." bytes)</font> <b><a href=userinfo.php?name=$bbsreply[6]>".$bbsreply[6]."</a></b> ".$bbsreply[3]."\n<ul>";
         dispreplybbs($bbsreply[0],$mysqlres,$rid);
         echo "</ul>";
         echo "</li>";

       }
     }
   }
	$id = $_GET[id];
	if($id!=""){
	$sql_res = " select * from discuss where id='".$id."'";
	$res = mysql_query($sql_res);
    $now=mysql_fetch_array($res); 
$pagetitle=htmlspecialchars($now[4]);
if ($now[7] != 0) $pagetitle=$pagetitle." Problem ".$now[7];
include("header.php");
    echo "<center><h2>".htmlspecialchars($now[4])."</h2><br>";
    echo " by <b><a href=userinfo.php?name=$now[6]>".$now[6]."</a></b> ".$now[3];
    if($now[7] != 0)  echo " At <b><a href=problem_show.php?pid=$now[7]>Problem ".$now[7]."</a></b>";
    echo "</center><hr>";
	echo '<script type="text/javascript" src="js/sh_cpp.js"></script>';
    echo "<pre class=discuss>".htmlspecialchars($now[5])."</pre>";

?>
<hr>
<?php

echo "<ul>";
$sql_res = " select * from discuss where rid= ".$now[2]." order by time";
     $res = mysql_query($sql_res);
     $root=mysql_fetch_array($res);
     echo "<li>";
	 if ($id==$root[0]) echo "<b>";
	 echo "<a href=reply.php?id=$root[0] class='bottom_link'>".htmlspecialchars($root[4])."</a>"." ";//title^M
	 if ($id==$root[0]) echo "</b>";
	 echo "<font color=gray size=-1>(".strlen($root[5])." bytes)</font> <b><a href=userinfo.php?name=$root[6]>".$root[6]."</a></b> ".$root[3]."\n";
/*	 if($root[7] != 0)
	 echo " <b><a href=problem_show.php?pid=$root[7]>Problem ".$root[7]."</a></b>"."<ul>";
	 else{
	       echo " <b>General Topic</b>"."<ul>";
	 }*/
echo "<ul>";
dispreplybbs($root[0],$res,$id);
echo "</ul>";
     echo "</li></ul>";
?>

<hr>

  <form action="reply_check.php?id=<?php echo $id;?>&rid=<?php echo $now[2];?>&pid=<?php echo $now[7];?>" method="post">

  <input type="text" name="title" value="RE: <?php echo $now[4];?>" size="40"/><br/>


  <textarea name="content" rows="10" cols="50"></textarea>
  <br/>
  <input type="submit" name="name" value="Reply"/>
  </form>




<?php
	}
?>

<?php include("footer.php"); ?>
