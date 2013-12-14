<?PHP
function check($a, $b) {
    $arr = array("\t", "\n", " ", "\r");
    if(str_replace($arr, "", strval($a)) == str_replace($arr, "", strval($b))) {
        return true;
    }
    return false;
}

mysql_connect("localhost", "bnuoj", "bnuo[j-d]ebian") or die("connect failed!");
mysql_select_db("bnuoj");
for($pid=4343; $pid<4351; $pid++) {
    echo "<h1>Problem $pid</h1><br>";
    $sql = "SELECT time_submit, username, runid, source FROM status WHERE pid=$pid AND result='Accepted' AND contest_belong='208' ORDER BY time_submit";
    $submits = array();
    $result = mysql_query($sql);
    while($arr = mysql_fetch_array($result)) {
        array_push($submits, $arr);
    }
    foreach($submits as $k1 => $v1) {
        foreach($submits as $k2 => $v2) {
            if($v1[username] == $v2[username]) continue;
            if(check($v1['source'], $v2['source'])) echo "runid1: ". $v1['runid']." runid2: ".$v2['runid']." username1:".$v1['username']." username2: ".$v2['username']."<br>";
        }
    }
}
echo "<pre>";
//print_r($submits);
echo "</pre>";
