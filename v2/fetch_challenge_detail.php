<?php
    include_once("conn.php");
    $cha_id = convert_str($_GET['cha_id']);
    $query="select * from challenge where cha_id='$cha_id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    $cpassed=db_contest_passed($row['cid']);
    if ($row==null||!db_user_match($nowuser,$nowpass)||(!$cpassed&&$nowuser!=$row['username'])) {
        echo "Permission Denied.";die();
    }
    echo "Challenger: <b>".$row['username']."</b><br />\n";
    echo "Challenge ID: <b>".$row['cha_id']."</b><br />\n";
    echo "Challenge Time: <b>".$row['cha_time']."</b><br />\n";
    if ($row['data_type']==1) echo "Data Type: <b>Source Code</b><br />\n";
    else if ($row['data_type']==0) echo "Data Type: <b>Raw Data</b><br />\n";
    echo "Challenge Data: <br /><pre>".htmlspecialchars($row['data_detail'])."</pre>\n";
    echo "Checker Returned: <br /><pre>".htmlspecialchars($row['cha_detail'])."</pre>\n";
    echo "Result: <b>".$row['cha_result']."</b><br />\n";
?>
