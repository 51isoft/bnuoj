<?php
  function get_week($str) {
      if ($str=="MON") return "Monday";
      if ($str=="TUE") return "Tuesday";
      if ($str=="WED") return "Wednesday";
      if ($str=="THU") return "Thursday";
      if ($str=="FRI") return "Friday";
      if ($str=="SAT") return "Saturday";
      if ($str=="SUN") return "Sunday";
  }
  include('ext/simple_html_dom.php');
  $cnt[0]=$cnt[1]=0;
  $html=str_get_html(iconv("gbk","utf-8//ignore",file_get_contents('http://acm.nankai.edu.cn/recent_contests.php')));
  $resrow=$html->find('table tr.HC');
  $rrow=array();
  $output = array(
      "sEcho" => $_GET['sEcho'],
      "iTotalRecords" => sizeof($resrow) ,
      "iTotalDisplayRecords" => sizeof($resrow),
      "aaData" => array()
  );
  foreach ($resrow as $row) {
    $rrow[0]=$row->find('td',0)->innertext;
    if (trim($rrow[0])=="BNU") continue;
    $title=$row->find('td',1);
    $src=htmlspecialchars_decode($title->find('a',0)->href);
    $title=$title->plaintext;
    $rrow[1]="<a href='$src' target='_blank'>".$title."</a>";
    $rrow[2]=$row->find('td',2)->innertext;
    $rrow[3]=$row->find('td',3)->innertext;
    $type=$row->find('td',4)->innertext;
    if ($type=="") $type="Public";
    $rrow[4]=$type;
    $output['aaData'][]=$rrow;
//    echo "<tr><td>".$oj."</td><td><a href='$src' target='_blank'>".$title."</a></td><td>".$start."</td><td>".$dow."</td><td>".$type."</td></tr>\n";
  }
  echo json_encode($output);

?>

