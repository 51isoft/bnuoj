<?php
function tosecond($str) {
    $h=intval(strstr($str,':',true));
    $str=substr(strstr($str,':'),1);
    $m=intval(strstr($str,':',true));
    $str=substr(strstr($str,':'),1);
    $s=intval($str);
    return $h*3600+$m*60+$s;
}

function addcontest() {
    global $pnum,$_POST,$mcid;
    $sql="insert into contest (title,description,start_time,end_time,type) values ('".$_POST['name']."','".$_POST['description']."','".$_POST['start_time']."','".$_POST['end_time']."','99')";
    mysql_query($sql);
    for ($i=0;$i<$pnum;$i++) {
        $sql="insert into contest_problem (lable,pid,cid) values ('".$_POST['lable'.$i]."','".$_POST['pid'.$i]."','$mcid')";
        mysql_query($sql);
    }
    $csql="select pid from contest_problem where cid='$mcid'";
	$cres=mysql_query($csql);
	$all=array();
	while ($crow=mysql_fetch_array($cres)) array_push($all,$crow[0]);
	sort($all);
	$tot="";
	foreach ($all as $num) {
	    $tot= $tot.$num.",";
	}
	$csql="update contest set allp='$tot' where cid='$mcid'";
    mysql_query($csql);
}

function insone($pid,$res,$dtime,$cid,$name) {
    $sql="insert into replay_status (pid,result,time_submit,contest_belong,username) values 
        ('".$pid."','".$res."','".$dtime."','".$cid."','".$name."')";
    mysql_query($sql);
}

function inswa($tnum,$sttime,$edtime,$pid,$name,$mcid,$pert=10) {
    for ($q=$tnum;$q>=1;$q--) {
        $inst=$edtime-$pert*$q;
        if ($inst<=$sttime+5) $inst=$sttime+5;
        insone($pid,'No',date("Y-m-d H:i:s",$inst),$mcid,$name);
    }
}

function insac($tnum,$sttime,$act,$pid,$name,$mcid,$pert=10) {
    for ($q=$tnum;$q>=1;$q--) {
        $inst=$sttime+$act-$pert*$q;
        if ($inst<=$sttime+5) $inst=$sttime+5;
        insone($pid,'No',date("Y-m-d H:i:s",$inst),$mcid,$name);
    }
    insone($pid,'Accepted',date("Y-m-d H:i:s",$sttime+$act),$mcid,$name);
}

function deal_hdu($data) {
    global $_POST,$sttime,$edtime,$mcid,$pnum,$sfreq;
    $unum=$data->rowcount()-2;
    for ($i=3;$i<=$unum;$i++) {
        $uname=$data->val($i,2);
        for ($j=0;$j<$pnum;$j++) {
            $value=trim($data->val($i,$j+5));
            if ($value=="") continue;
            if ($value[0]=='(') {
                $tnum=intval(substr($value,2,-1));
//                echo $uname." ".$_POST['pid'.$j]." ".$tnum." * ".date("Y-m-d H:i:s",$edtime-10)."<br />\n";
                inswa($tnum,$sttime,$edtime,$_POST['pid'.$j],convert_str($uname),$mcid,$sfreq);
            }
            else {
                $tnum=0;
//                echo $uname." ".$_POST['pid'.$j]." ".$value."<br />\n";
                if (strstr($value,'(')) {
                    $act=strstr($value,'(',true);
                    $tnum=substr(strstr($value,'('),2,-1);
                }
                else $act=$value;
//                echo $uname." ".$_POST['pid'.$j]." ".date("Y-m-d H:i:s",$sttime+tosecond($act)-10)." * $tnum + ".date("Y-m-d H:i:s",$sttime+tosecond($act))."<br />\n";
                insac($tnum,$sttime,tosecond($act),$_POST['pid'.$j],convert_str($uname),$mcid,$sfreq);
            }
        }
    }
}

function deal_zju($data) {
    global $_POST,$sttime,$edtime,$mcid,$pnum,$sfreq;
    $unum=$data->rowcount();
    for ($i=7;$i<=$unum;$i++) {
        $uname=$data->val($i,2);
        for ($j=0;$j<$pnum;$j++) {
            $value=trim($data->val($i,$j+5));
            if ($value=="") continue;
            if (strstr($value,'(')==null) {
                $tnum=intval($value);
//                echo $uname." ".$_POST['pid'.$j]." ".$tnum." * ".date("Y-m-d H:i:s",$edtime-10)."<br />\n";
                inswa($tnum,$sttime,$edtime,$_POST['pid'.$j],convert_str($uname),$mcid,$sfreq);
            }
            else {
                $tnum=0;
                $act=intval(strstr($value,'(',true))*60;
                $tnum=intval(substr(strstr($value,'('),1,-1))-1;
//                echo $uname." ".$_POST['pid'.$j]." ".date("Y-m-d H:i:s",$sttime+$act-10)." * $tnum + ".date("Y-m-d H:i:s",$sttime+$act)."<br />\n";
                insac($tnum,$sttime,$act,$_POST['pid'.$j],convert_str($uname),$mcid,$sfreq);
            }
        }
    }
}

function deal_licstar($standtable) {
    global $_POST,$sttime,$edtime,$mcid,$pnum,$sfreq;
    $rows=$standtable->find("tr");
    $unum=sizeof($rows);
    for ($i=1;$i<$unum-1;$i++) {
        $crow=$rows[$i]->children();
        $uname=strip_tags($crow[3]->innertext." ".$crow[1]->innertext);
        for ($j=0;$j<$pnum;$j++) {
            $value=trim($crow[$j+6]->innertext);
            if ($value=="") continue;
            if (strstr($value,'/')) {
                $tnum=strstr($value,'/',true);
                $act=intval(substr(strstr($value,'/'),1));
//                echo $uname." ".$_POST['pid'.$j]." ".$tnum." * ".date("Y-m-d H:i:s",$sttime+$act*60)."<br />\n";
                insac($tnum-1,$sttime,intval($act)*60,$_POST['pid'.$j],convert_str($uname),$mcid,$sfreq);
            }
            else {
                $tnum=intval($value);
//                echo $uname." ".$_POST['pid'.$j]." ".$tnum." * ".date("Y-m-d H:i:s",$edtime-10)."<br />\n";
                inswa($tnum,$sttime,$edtime,$_POST['pid'.$j],convert_str($uname),$mcid,$sfreq);
            }
        }
    }
}

function deal_ctu($standtable) {
    global $_POST,$sttime,$edtime,$mcid,$pnum;
    $extinfo=array();
    for ($i=0;$i<$pnum;$i++) $extinfo[strtolower($_POST['extrainfo'][$i])]=$i;
//    var_dump($extinfo);die();
    $rows=$standtable->find("tr");
    $unum=sizeof($rows);
    for ($i=0;$i<$unum-1;$i++) {
        if ($rows[$i]->find("th",0)!=null) continue;
        $crow=$rows[$i]->children();
        $uname=$crow[3]->innertext;
        $pid=$_POST['pid'.$extinfo[strtolower(substr(strstr($crow[0]->find("a",0)->href,"#"),1,1))]];
        $act=date("Y-m-d H:i:s",$sttime+tosecond($crow[2]->innertext));
        $res=$crow[5]->innertext;
        if (stristr($res,"accepted")) $res="Accepted";
        else if (stristr($res,"ignored")) continue;
        else $res="No";
//        echo "$uname $pid $res $act <br />";
        insone($pid,$res,$act,$mcid,$uname);
    }
}

function deal_ural($standtable) {
    global $_POST,$sttime,$edtime,$mcid,$pnum,$sfreq;
    $rows=$standtable->find("tr");
    $unum=sizeof($rows);
    for ($i=1;$i<$unum-2;$i++) {
        $crow=$rows[$i]->children();
        $uname=strip_tags($crow[2]->innertext);
        for ($j=0;$j<$pnum;$j++) {
            $value=trim($crow[$j+3]->innertext);
            if ($value=="") continue;
            if (strstr($value,'+')) {
                $tnum=substr(strstr($value,'<',true),1);
                $act=$crow[$j+3]->find("i",0)->innertext.":0";
                //echo $uname." ".$_POST['pid'.$j]." ".$tnum." * ".date("Y-m-d H:i:s",$sttime+tosecond($act))."<br />\n";
                insac($tnum,$sttime,tosecond($act),$_POST['pid'.$j],convert_str($uname),$mcid,$sfreq);
            }
            else {
                //echo $value;
                $tnum=substr(strstr($value,'<',true),3);
                $wat=$crow[$j+3]->find("i",0)->innertext.":0";
                //echo $uname." ".$_POST['pid'.$j]." ".$tnum." * ".date("Y-m-d H:i:s",$sttime+tosecond($wat))."<br />\n";
                inswa($tnum,$sttime,$sttime+tosecond($wat),$_POST['pid'.$j],convert_str($uname),$mcid,$sfreq);
            }
        }
    }
}

function movefile($filename) {
    global $_FILES;
    move_uploaded_file($_FILES["file"]["tmp_name"], "uploadstand/" . $filename);
    echo "Stored in: " . "uploadstand/" . $filename."<br />";
}

?>

