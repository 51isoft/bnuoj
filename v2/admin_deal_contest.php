<?php
include("conn.php");
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
    $title = htmlspecialchars(convert_str($_POST['title']));
    $sql_cn = "select max(cid) from contest";
    $que_cn = mysql_query($sql_cn);
    $num = mysql_fetch_array($que_cn);
    $cid=convert_str($_POST['cid']);
    $maxcid=$num[0]+1;
    $isupd=true;
    if ($cid=="") {
        $cid=$maxcid;
        $isupd=false;
    }
    $isprivate=convert_str($_POST['isprivate']);
    $description =htmlspecialchars(convert_str($_POST['description']));
    $lock_board_time=convert_str($_POST['lock_board_time']);
    $start_time=convert_str($_POST['start_time']);
    $end_time=convert_str($_POST['end_time']);
    $report=convert_str($_POST['report']);
    $ctype=convert_str($_POST['ctype']);
    $has_cha=convert_str($_POST['has_cha']);
    $challenge_start_time=convert_str($_POST['challenge_start_time']);
    $challenge_end_time=convert_str($_POST['challenge_end_time']);
    $hide_others=convert_str($_POST['hide_others']);
    $n = $problemcontestadd;
    for($i=0;$i<$n;$i++){
        $ccid[$i] = $cid;
        $pid[$i] = convert_str($_POST['pid'.$i]);
        $lable[$i] = convert_str($_POST['lable'.$i]);
        $ptype[$i] = convert_str($_POST['ptype'.$i]);
        $base[$i] = convert_str($_POST['base'.$i]);
        $minp[$i] = convert_str($_POST['minp'.$i]);
        $paraa[$i] = convert_str($_POST['paraa'.$i]);
        $parab[$i] = convert_str($_POST['parab'.$i]);
        $parac[$i] = convert_str($_POST['parac'.$i]);
        $parad[$i] = convert_str($_POST['parad'.$i]);
        $parae[$i] = convert_str($_POST['parae'.$i]);
    }
    
    $stt=strtotime($start_time);
    $edt=strtotime($end_time);
    $lbt=strtotime($lock_board_time);
    $nt=time();
    
    //echo "$title $start_time $end_time $pid[0] $stt $edt $lbt $nt ";
    //echo $_POST['submit'];
    
    if ($title==""||$start_time==""||$end_time==""||$stt==0||$edt==0||($lbt!=0&&($lbt<$stt&&$lbt>$edt))) {
        echo "Not Correctly Filled.";
    }
    else {
        if ($isupd) $sql_con="update contest set 
            title='$title',
            description='$description',
            isprivate='$isprivate',
            start_time='$start_time',
            end_time='$end_time',
            lock_board_time='$lock_board_time',
            hide_others='$hide_others',
            isvirtual='0',
            report='$report',
            type='$ctype',
            has_cha='$has_cha',
            challenge_start_time='$challenge_start_time',
            challenge_end_time='$challenge_end_time'
            where cid='$cid'";
        else $sql_con = "insert into contest (title,cid,description,isprivate,lock_board_time,start_time,end_time,hide_others,isvirtual,report,type,has_cha,challenge_start_time,challenge_end_time) values
            ('$title','$cid','$description','$isprivate','$lock_board_time','$start_time','$end_time','$hide_others',0,'$report','$ctype','$has_cha','$challenge_start_time','$challenge_end_time')";
        //$sql_con = change_in($sql_con);
        $que_con = mysql_query($sql_con);
        //echo $sql_con."\n";
        if($que_con){
            $pd=false;
            for($i=0;$i<$n;$i++){
                if($pid[$i] == "")continue;
                if (!db_problem_exist($pid[$i])) {
                    echo "Failed to add Problem ".$lable[$i].", pid: ".$pid[$i]." not exists.\n";
                }
                else echo "Problem ".$lable[$i]." added, pid: ".$pid[$i].".\n";
            }
            /*if ($pd) {
                echo "Invalid Problem!";
                die();
            }*/
            for($i=0;$i<$n;$i++){
                if($pid[$i] == "") continue;
                if ($ctype==0) $sql= "insert into contest_problem (cid ,pid,lable) values ('".$ccid[$i]."','".$pid[$i]."','".$lable[$i]."')";
                else $sql = "insert into contest_problem (cid ,pid,lable,type,base,minp,para_a,para_b,para_c,para_d,para_e) values
                    ('".$ccid[$i]."','".$pid[$i]."','".$lable[$i]."','".$ptype[$i]."','".$base[$i]."','".$minp[$i]."','".$paraa[$i]."','".$parab[$i]."','".$parac[$i]."','".$parad[$i]."','".$parae[$i]."')";
                $sql = change_in($sql);
                $que = mysql_query($sql);
            }
            $cres=mysql_query("select problem.title from contest_problem,problem where cid=".$cid." and contest_problem.pid=problem.pid");
            $str=array();
            while ($crow=mysql_fetch_array($cres)) {
                $str[]=trim(strtolower($crow[0]));
            }
            sort($str);
            mysql_query("update contest set allp='".md5(implode($str,"[-,-]"))."' where cid=".$cid);
            echo "Success!\n";
            $names = convert_str($_POST['names']);
            $len=strlen($names);
            $st=$fi=0;
            if ($len>0) {
                while ($fi<=$len) {
                    if ($names[$fi]=='|'||$fi==$len) {
                        $tmp=substr($names,$st,$fi-$st);
                        if (!db_user_exist($tmp)) {
                            echo "No Such User $tmp.\n";
                        }
                        else if (db_contest_user_has($cid,$tmp)) {
                            echo "User $tmp already in Contest $cid.\n";
                        }
                        else {
                            $que="insert into contest_user set cid=$cid, username='$tmp'";
                            $res=mysql_query($que);
                            if (!res) {
                                echo "Failed when add $tmp.\n";
                            }
                            else echo "Added $tmp to contest $cid.\n";
                        }
                        $st=$fi+1;
                    }
                    $fi++;
                }
            }
        }
        else{
            echo "Failed.";
        }
    }
    
}
    
else {
    echo "Not Admin.";
}

?>
