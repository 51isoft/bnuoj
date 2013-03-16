<?php
  include_once("header.php");
  include_once("menu.php");
?>
    <div id="site_content">
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <!-- insert the page content here -->
<?php
//  mysql_query("update problem set title=trim(title)");
  $res=mysql_query("select cid from contest");
  while ($row=mysql_fetch_array($res)) {
      $cres=mysql_query("select problem.title from contest_problem,problem where cid=".$row[0]." and contest_problem.pid=problem.pid");
      $str=array();
      while ($crow=mysql_fetch_array($cres)) {
          $str[]=trim(strtolower($crow[0]));
      }
      sort($str);
      mysql_query("update contest set allp='".md5(implode($str,"[-,-]"))."' where cid=".$row[0]);
  }
  echo "Converted.";
?>
        </div>
        <div id="one_content_base"></div>
      </div>
    </div>

<?php
    include("footer.php");
?>
<script type="text/javascript">
</script>
<?php
    include("end.php");
?>
