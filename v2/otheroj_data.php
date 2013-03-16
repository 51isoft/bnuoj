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
  $resrow=json_decode(file_get_contents('external/contests.json'),true);
  $output = array(
      "sEcho" => $_GET['sEcho'],
      "iTotalRecords" => sizeof($resrow) ,
      "iTotalDisplayRecords" => sizeof($resrow),
      "aaData" => array()
  );
  foreach ($resrow as $row) {
    $rrow[0]=$row['oj'];
    $title=$row['name'];
    $src=htmlspecialchars_decode($row['link']);
    $rrow[1]="<a href='$src' target='_blank'>".$title."</a>";
    $rrow[2]=$row['start_time'];
    $rrow[3]=$row['week'];
    $type=$row['access'];
    if ($type=="") $type="Public";
    $rrow[4]=$type;
    $output['aaData'][]=$rrow;
//    echo "<tr><td>".$oj."</td><td><a href='$src' target='_blank'>".$title."</a></td><td>".$start."</td><td>".$dow."</td><td>".$type."</td></tr>\n";
  }
  echo json_encode($output);

?>

