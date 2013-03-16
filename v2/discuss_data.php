<?php
   include_once("conn.php");
   $proid = convert_str($_GET['pid']);
   function dispreplybbs($id,$mysqlres,$flag)
   {
     for($i=0;$i<mysql_num_rows($mysqlres);$i++)
     {
       //mysql_data_seek($mysqlres,$i);
       $bbsreply=mysql_fetch_array($mysqlres);
       if($bbsreply[1]==$id)
       {
         if ($flag) echo "<ul style='display:none'>";
         else echo "<ul>";
         echo "<li>"."<a class='topicshow' href='#' title='".htmlspecialchars($bbsreply[4])."' name='$bbsreply[0]'>".htmlspecialchars($bbsreply[4])."</a> ";//title
         echo "<span style='color:#AAA; font-size:smaller'>(".strlen($bbsreply[5])." bytes)</span> <a href='userinfo.php?name=$bbsreply[6]' target='_blank'><b>".$bbsreply[6]."</b></a> ".$bbsreply[3]."\n";
         dispreplybbs($bbsreply[0],$mysqlres,false);
//         echo "</ul>";
         echo "</li>";
         echo "</ul>";
       }
     }
   }
   $page = convert_str($_GET['page']);
   if($page == "") $page = 0;
   $start=$page*$discussperpage;
   if($proid != ""){
      $sql_first = "select distinct(rid) from time_bbs where pid='$proid' order by time desc limit $start ,$discussperpage";
   }else{
     $sql_first = "select distinct(rid) from time_bbs order by time desc limit $start ,$discussperpage";
   }
   $que = mysql_query($sql_first);
   echo "<ul>";
   while($bbs = @mysql_fetch_array($que))
   {
     $sql_res = " select * from discuss where rid= ".$bbs[0]." order by time";
     $res = mysql_query($sql_res);
     $root=mysql_fetch_array($res);
     echo "<li class='tsubject'><a href='#' class='tnone'>+</a><a href='#' class='texpand' style='display:none'>+</a><a href='#' class='thide' style='display:none'>-</a>";
     echo "<a class='topicshow' href='#' title='".htmlspecialchars($root[4])."' name='$root[0]'>".htmlspecialchars($root[4])."</a>"." ";//title
     echo "<span style='color:#AAA; font-size:smaller'>(".strlen($root[5])." bytes)</span> <a href='userinfo.php?name=$root[6]' target='_blank'><b>".$root[6]."</b></a> ".$root[3];
     if($root[7] != 0)
     echo " <a href='problem_show.php?pid=$root[7]'><b>Problem ".$root[7]."</b></a>\n";
     else{
       echo " <b>General Topic</b>\n";
     }
//     echo "<a href='#' class='texpand' style='display:none'>+</a><a href='#' class='thide' style='display:none'>-</a>";//<ul style='display:none'>";
     dispreplybbs($root[0],$res,true);
//     echo "</ul>";
     echo "</li>";
     echo "<hr>";
   }
   echo "</ul>";
?>
<div class="dcontrol center" style='margin-bottom:0;margin-top:0;'>
  <a href='#' class="button" id='disfirst' style='display:none'>First</a>
  <a href='#' class="button" id='disprev' style='display:none'>Prev</a>
  <a href='#' class="button" id='disnext'>Next</a>
  <a href='#' class="button" id='disnew'>New Topic</a>
</div>

    <div class="topdialog" id="newtopic" style='display:none'>
      <div class="center">
        <form id="newtopicform" action="#" name="newtopic.php?pid=<?php echo $proid;?>" method="post">
          Title: <input type="text" name="title" value="" style="width:600px" /><br />
          <div>Content: </div>
          <textarea name="content" style='width:700px;height:500px;'></textarea>
          <div>
            <span>&nbsp;</span><span id="newtmsgbox" style="display:none; z-index:300;width:120px"></span><br />
            <input type="submit" name="name" value="Post" class="submit" />
          </div>
        </form>
      </div>
    </div>
    <div class="topdialog" id="showtopic" style='display:none'></div>
