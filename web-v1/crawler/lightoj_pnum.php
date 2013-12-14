<?php
include("header.php");
include("simple_html_dom.php");
?>
<center>
<?php
//error_reporting(E_ALL);
$ojuser="youruser";
$ojpass="yourpass";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://www.lightoj.com/login_check.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_COOKIEJAR, "lightoj.cookie");
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, "myuserid=".urlencode($ojuser)."&mypassword=".urlencode($ojpass)."&Submit=Login");
$content = curl_exec($ch);
for ($i=10;$i<15;$i++) {
    $url="http://www.lightoj.com/volume_problemset.php?volume=$i";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_COOKIEFILE, "lightoj.cookie");
    $content = curl_exec($ch); 
    curl_close($ch); 
    $html=str_get_html($content);
    $table=$html->find("table",1);
    $rows=$table->find("tr");
    for ($j=1;$j<sizeof($rows);$j++) {
        $row=$rows[$j];
        //echo htmlspecialchars($row);
        $pid=trim($row->find("td",1)->plaintext);
        $temp=trim($row->find("td",3)->find("div.pertext",0)->innertext);
        $acnum=trim(strstr($temp,"/",true));
        $totnum=trim(substr(strstr($temp,"/"),1));
        //echo "$pid $acnum $totnum<br>";
        mysql_query("update problem set vacpnum='$acnum', vtotalpnum='$totnum' where vname='LightOJ' and vid='$pid'");
    }
}
?>
</center>
<br>
<?php include("footer.php"); ?>

