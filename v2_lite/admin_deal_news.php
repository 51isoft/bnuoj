<?php
include("conn.php");
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
    $title = convert_str($_POST['ntitle']);
    $newsid = convert_str($_POST['newsid']);
    $content = convert_str($_POST['ncontent']);
    if($newsid == ""){
        list($sql_nid)=mysql_fetch_array(mysql_query("select max(newsid) from news"));
        $newsid = $sql_nid+1;
        $sql_add_pro = "insert into news (title,content,author,time_added) values ('$title','$content','$nowuser', NOW())";
    }
    else{
        $sql_add_pro = "update news set title='$title',content='$content',author='$nowuser',time_added=NOW() where newsid='$newsid'";
    }
    $que_in = mysql_query($sql_add_pro);
    if($que_in){
        echo "Success!";
        if ($newsid=='') list($currnid)=mysql_fetch_array(mysql_query("select max(newsid) from news"));
        else $currnid=$newsid;
        echo " ID: $currnid.";
    }
    else{
        echo "Failed.";
    }
}
?>
