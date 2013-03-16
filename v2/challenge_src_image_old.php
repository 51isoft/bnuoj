<?php

    function toimage($text,$fsize) {
        $text = str_replace("\r", "", $text);
        $text = str_replace("\t", "    ", $text);
        $lines=explode("\n",$text);
        $width=0;
        foreach ($lines as $line) {
            $width=max($width,strlen($line)*imagefontwidth($fsize)+10);
        }
        $height=sizeof($lines)*imagefontheight($fsize)+10;
        $im = imagecreatetruecolor($width,$height);
        $bg = imagecolorallocate($im, 201, 201, 201);
        imagefilledrectangle($im, 0, 0, $width, $height, $bg);
        
        $text_color = imagecolorallocate($im, 51 , 51 , 51 );
        $cnt=0;
        foreach ($lines as $line) {
            imagestring($im, $fsize, 5, 5+$cnt*imagefontheight($fsize),  $line, $text_color);
            $cnt++;
        }
        imagepng($im);
        imagedestroy($im);
        die();
    }
	include_once("conn.php");
    $pid = convert_str($_GET['pid']);
    $user = convert_str($_GET['username']);
    $cid = convert_str($_GET['cid']);
    header('Content-Type: image/png');
    
    if (!db_user_match($nowuser,$nowpass)||(!db_contest_challenging($cid)&&!db_contest_passed($cid))) {
        toimage("Permission Denied.",5);
    }
    $query="select runid,result,source from status where contest_belong='$cid' and pid='$pid' and username='$user' order by runid desc";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    if ($row[1]=="Pretest Passed"||$row[1]=="Accepted"||db_contest_passed($cid)) {
        toimage($row[2],3);
    }
    else {
        toimage("Invalid Request!",5);
    }
?>
