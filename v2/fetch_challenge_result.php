<?php
    include_once("conn.php");
    $cha_id = convert_str($_GET['cha_id']);
    $query="select * from challenge where cha_id='$cha_id'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    $cpassed=db_contest_passed($row['cid']);
    if (!db_user_match($nowuser,$nowpass)||(!$cpassed&&$nowuser!=$row['username'])) {
        echo "Permission Denied.";die();
    }
    $ret=$row['cha_result']." for ID: ".$cha_id.".";
    if ($row['cha_result']=="Challenge Success") {
        $sql="select count(*) from challenge where cha_id<'$cha_id' and cha_result='Challenge Success'";
        list($num)=mysql_fetch_array(mysql_query($sql));
        if ($num==0) $ret.=" First success, points gained.";
        else $ret.=" But someone also did that before you.";
    }
    echo $ret;
?>
