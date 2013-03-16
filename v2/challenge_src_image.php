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

    function tempnam_sfx($path, $suffix) 
    {
        do 
        { 
            $file = $path."/".mt_rand().$suffix; 
            $fp = @fopen($file, 'x'); 
        } 
        while(!$fp); 
        fclose($fp); 
        return $file; 
    } 
 

    include_once("conn.php");
    $pid = convert_str($_GET['pid']);
    $user = convert_str($_GET['username']);
    $cid = convert_str($_GET['cid']);

    header('Content-Type: image/png');

    if (!db_user_match($nowuser,$nowpass)||(!db_contest_challenging($cid)&&!db_contest_passed($cid))) {
        toimage("Permission Denied.",5);
    }
    $query="select runid,result,source,language from status where contest_belong='$cid' and pid='$pid' and username='$user' order by runid desc";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    if ($row[1]=="Pretest Passed"||$row[1]=="Accepted"||db_contest_passed($cid)) {
        echo file_get_contents("http://202.112.87.15/bnuojimage/getimage.php?runid=".$row[0]);
        //toimage("Challenge End",5);
        /*$file = tempnam_sfx("/tmp", ".".match_shjs($row[3]));
        //$htmlfile = tempnam_sfx("/tmp", ".html");
        $pngfile = tempnam_sfx("/tmp", ".png");
        file_put_contents($file,$row[2]);
        exec("pygmentize -P encoding=utf-8 -P line_numbers=False -P font_name=FreeMono -P font_size=13 -P line_pad=0 -o ".$pngfile." ".$file);

        echo file_get_contents($pngfile);

        unlink($file);
        //unlink($htmlfile);
        unlink($pngfile);*/
    }
    else {
        toimage("Invalid Request!",5);
    }
?>
