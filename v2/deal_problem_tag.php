<?php
	include_once("conn.php");
    $pid=convert_str($_POST["tagpid"]);
    $tagid=convert_str($_POST["utags"]);
    $weight=10;
    if (!db_user_match($nowuser,$nowpass)) {
        echo "Please Login.";
        die();
    }
    if (db_user_isroot($nowuser)) {
        $num=1;
        $force=convert_str($_POST["force"]);
        $weight=intval(convert_str($_POST["weight"]));
    }
    else $num=@mysql_num_rows(mysql_query("select runid from status where username='$nowuser' and pid='$pid' and result='Accepted' limit 0,1"));
    if ($num==0) {
        echo "You haven't solved this problem.";
        die();
    }

    $num=@mysql_num_rows(mysql_query("select id from category where id='$tagid' limit 0,1"));
    if ($tagid==""||$num==0) {
        echo "No such type.";
        die();
    }

    if ($force!=1) {
        $num=@mysql_num_rows(mysql_query("select id from usertag where username='$nowuser' and pid='$pid' and catid='$tagid' limit 0,1"));
        if ($num!=0) {
            echo "You have already tagged this type or one of its sub-types.";
            die();
        }
    }
    function tagit($pid,$tagid,$weight) {
        global $force,$nowuser;

        if ($force!=1) mysql_query("insert into usertag set username='$nowuser', pid='$pid', catid='$tagid'");

        if (mysql_num_rows(mysql_query("select pcid from problem_category where pid='$pid' and catid='$tagid'"))==0) {
            mysql_query("insert into problem_category set pid='$pid', catid='$tagid', weight='$weight'");
        }
        else mysql_query("update problem_category set weight=weight+$weight where pid='$pid' and catid='$tagid'");
        list($tagid)=mysql_fetch_array(mysql_query("select parent from category where id='$tagid'"));
        if ($tagid>0) tagit($pid,$tagid,0);
    }
    tagit($pid,$tagid,$weight);
    echo "Tag success!";
?>
