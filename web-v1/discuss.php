<?php 
$proid =$_GET[pid];
$pagetitle="Discuss";
if ($proid!="") $pagetitle=$pagetitle." For Problem ".$proid;
include("header.php");
?>
<?php

   function dispreplybbs($id,$mysqlres)
   {
     for($i=0;$i<mysql_num_rows($mysqlres);$i++)
     {
       mysql_data_seek($mysqlres,$i);
       $bbsreply=mysql_fetch_array($mysqlres);
       if($bbsreply[1]==$id)
       {

         echo "<li>"."<a href=reply.php?id=$bbsreply[0] class='bottom_link'>".htmlspecialchars($bbsreply[4])."</a> ";//title
         echo "<font color=gray size=-1>(".strlen($bbsreply[5])." bytes)</font> <a href=userinfo.php?name=$bbsreply[6]><b>".htmlspecialchars($bbsreply[6])."</b></a> ".$bbsreply[3]."\n<ul>";
         dispreplybbs($bbsreply[0],$mysqlres);
         echo "</ul>";
         echo "</li>";

       }
     }
   }

	$page = $_GET['page'];
	if($page == "") $page = 0;
	$start=$page*$discussperpage;
	if($proid != ""){
	$sql_first = "select rid from time_bbs where pid='$proid' order by time desc limit $start ,$discussperpage";
	}else{
	$sql_first = "select rid from time_bbs order by time desc limit $start ,$discussperpage";
	}
	//echo $sql_first."===";
	$que = mysql_query($sql_first);
	echo "<ul>";
    while($bbs = @mysql_fetch_array($que))
    {
     $sql_res = " select * from discuss where rid= ".$bbs[0]." order by time";
    // echo $sql_res."---";
     $res = mysql_query($sql_res);
     $root=mysql_fetch_array($res);
  	 echo "<li>"."<a href=reply.php?id=$root[0] class='bottom_link'>".htmlspecialchars($root[4])."</a>"." ";//title
     echo "<font color=gray size=-1>(".strlen($root[5])." bytes)</font> <a href=userinfo.php?name=$root[6]><b>".$root[6]."</b></a> ".$root[3];

     if($root[7] != 0)
     echo " <a href=problem_show.php?pid=$root[7]><b>Problem ".$root[7]."</b></a>\n"."<ul>";
	 else{
	 	echo " <b>General Topic</b>\n"."<ul>";
	 }
     dispreplybbs($root[0],$res);
     echo "</ul>";
     echo "</li>";
     echo "<hr>";
    }
	echo "<center>";
	$nextp=$page+1;
	$prevp=$page-1;
	if ($prevp<0) $prevp=0;
	echo "<a href=discuss.php?pid=$proid&page=$prevp class='bottom_link'>[Previous Page] </a><a href=discuss.php?pid=$proid&page=$nextp class='bottom_link'>[Next Page]</a></p>";
	echo "</center>";
	 echo "</ul>";
?>

  <form action="check_new_message.php?pid=<?php echo $proid;?>" method="post">
  <input type="text" name="title" value="" size="40"/><br/>
  <textarea name="content" rows="10" cols="50"></textarea>
  <br/>
  <input type="submit" name="name" value="Post"/>
  </form>

<?php include("footer.php"); ?>
