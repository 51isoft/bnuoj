<?php
include("header.php");
include("problem_category_init.php");
foreach ($key as $name => $id) {
    if (mysql_num_rows(mysql_query("select id from category where name='".convert_str($name)."'"))==0) {
        mysql_query("insert into category set name='".convert_str($name)."', id='$id', parent='".$fa[$name]."'");
    }
}

include("simple_html_dom.php");
$ojuser="youruser";
$ojpass="yourpass";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://www.lightoj.com/login_check.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_COOKIEJAR, "lightoj.cookie");
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, "myuserid=".urlencode($ojuser)."&mypassword=".urlencode($ojpass)."&Submit=Login");
$content = curl_exec($ch);

$url="http://www.lightoj.com/volume_problemcategory.php";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_COOKIEFILE, "lightoj.cookie");
$content = curl_exec($ch); 
curl_close($ch); 
$html=str_get_html($content);

$ta=$html->find("a");
foreach ($ta as $a) {
    set_time_limit(40);
    if (strstr($a->href,"volume_problemcategory.php?user_id=")==null) continue;
    $cat=$key[$map[trim($a->plaintext)]];
    //echo $cat;die();
    $url="http://www.lightoj.com/".strtr($a->href," ","+");
    //echo $url;die();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_COOKIEFILE, "lightoj.cookie");
    $content = curl_exec($ch); 
    curl_close($ch); 
    $html=str_get_html($content);
    //echo htmlspecialchars($html);
    //echo "http://acm.timus.ru/".html_entity_decode($a->href);die();
    $rows=$html->find("a");
    for ($i=0;$i<sizeof($rows);$i++) {
        if (strstr($rows[$i]->href,"forum_showproblem.php?problem=")==null) continue;
        $id=trim($rows[$i]->plaintext);
        list($pid)=mysql_fetch_array(mysql_query("select pid from problem where vname='LightOJ' and vid='$id'"));
        //echo $id;die();
        $g=$cat;
        while ($g!=-1) {
            if (mysql_num_rows(mysql_query("select pcid from problem_category where pid='$pid' and catid='$g'"))==0) {
               mysql_query("insert into problem_category set pid='$pid', catid='$g'");
            }
            $g=$fa[$g];
        }
    }
}



include("footer.php");
?>
