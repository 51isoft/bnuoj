<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <style>
html
{ height: 100%;}

*
{ margin: 0;
  padding: 0;
}

body
{ font-family:  arial, sans-serif;
  font-size: .80em;}

p
{ padding: 0 0 20px 0;
  line-height: 1.7em;}

img
{ border: 0;}

a
{ text-decoration: underline;}

a:hover
{ text-decoration: none;}

table {
border-collapse:collapse;
border-spacing:0px;
}

table td, table th {
border:solid 1px black;
padding: 5px;
}

  </style>
</head>
<body>

<?php
  include_once('conn.php');
  $cid=convert_str($_GET['cid']);
  if (!db_contest_exist($cid)||!db_user_match($nowuser,$nowpass)||(db_user_isroot($nowuser)==false&&strcasecmp($nowuser,db_contest_owner($cid))!=0)) {
      echo "<h1>You are not allowed to view this page.</h1>";
      die();
  }
  $query = "select pid,lable from contest_problem where cid='$cid' order by lable";
  $res=mysql_query($query);

  while ($row=mysql_fetch_array($res)) {
      $query="select title,description,input,output,sample_in,sample_out,hint,source,time_limit,case_time_limit,memory_limit,total_submit,total_ac,special_judge_status,hide,vid,vname,ignore_noc,author from problem where pid='".$row[0]."'";
      $result = mysql_query($query);
      list($ptitle,$desc,$inp,$oup,$sin,$sout,$hint,$source,$tl,$ctl,$ml,$ts,$tac,$spj,$hide,$vid,$vname,$ignoc,$author)=@mysql_fetch_row($result);
      $html="";
      $html.="<center><h1>".$row[1].". ".$ptitle."</h1></center>";
      if ($desc!="") $html.=latex_content($desc);
      if ($inp!="") $html.="<h2 style='margin-top:10px'>Input</h2>".latex_content($inp);
      if ($oup!="") $html.="<h2 style='margin-top:10px'>Output</h2>".latex_content($oup);
      if ($sin!="") {
          $html.="<h2 style='margin-top:10px'>Sample Input</h2>";
          if (stristr($sin,'<br')==null&&stristr($sin,'<pre')==null&&stristr($sin,'<p>')==null) $html.="<pre>".$sin."</pre>";
          else $html.=$sin;
      }
      if ($sout!="") {
          $html.="<h2 style='margin-top:10px'>Sample Output</h2>";
          if (stristr($sout,'<br')==null&&stristr($sout,'<pre')==null&&stristr($sout,'<p>')==null) $html.="<pre>".$sout."</pre>";
          else $html.=$sout;
      }
      if (trim(strip_tags($hint))!=""||strlen($hint)>50) $html.="<h2 style='margin-top:10px'>Hint</h2>".latex_content($hint);
      echo $html.'<div style="PAGE-BREAK-AFTER: always"></div>';
  }
?>
</body>
</html>


