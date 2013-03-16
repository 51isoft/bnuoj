<?php
	include_once("conn.php");
    $runid = convert_str($_GET['runid']);
    $isshare = convert_str($_GET['type']);
    $query="select username from status where runid='$runid'";
    $result = mysql_query($query);
    list($user)=mysql_fetch_array($result);
    if (db_user_match($nowuser,$nowpass)&&(db_user_iscodeviewer($nowuser)||strcasecmp($user,$nowuser)==0)) {
        $sql="update status set isshared='$isshare' where runid='$runid'";
        $result = mysql_query($sql);
        echo "Success!";
    }
    else echo "Failed!";
?>
