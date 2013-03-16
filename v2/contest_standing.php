<?php
//var_dump($_POST);
//exit;
//$nowtime=time();
//echo date("Y-m-d G:i:s",$nowtime);
include_once ("conn.php");

function get_time ($unix_time,$force=false)
{
    global $ctype;
    if ($ctype==1&&!$force) return number_format($unix_time,2,".","");
    $first = floor($unix_time/3600);
    $mid = floor( ($unix_time-$first*3600)/60 );
    $last = $unix_time%60;
    return $first.":".$mid.":".$last;
}


$cid = convert_str($_POST['cid']);
$maxrank=1000000000;
if ($_POST['shownum']!=0) $maxrank=$_POST['shownum'];
if ($_POST['anim']=="on") $maxrank=20;
//$imerge=convert_str($_POST['merge']);
$imerge=1;
if ($_POST['cid_'.$cid]=='on'&&$maxrank==1000000000) $csingle=1;
else $csingle=0;
$pagetitle="Standing of Contest ".$cid;
$realnow=time();
$chaing=false;
$ccpassed=false;
$cidtype=array();

if (db_contest_exist($cid))
{
    $nowtime=time();
    list($locktu,$sttimeu,$fitimeu,$cstarttimeu,$cendtimeu,$has_cha,$t,$allp) = @mysql_fetch_array(mysql_query("SELECT unix_timestamp(lock_board_time),unix_timestamp(start_time),unix_timestamp(end_time),unix_timestamp(challenge_start_time),unix_timestamp(challenge_end_time),has_cha,unix_timestamp(mboard_make),allp FROM contest WHERE cid = '$cid'"));
    $targ = "standings/contest_standing_".$cid.".html";
    if (isset($_GET['passtime'])&&is_numeric($_GET['passtime'])&&db_contest_passed($cid)) {
        $tmptime=intval($_GET['passtime'])+$sttimeu;
        if ($tmptime<$nowtime) $nowtime=$tmptime;
    }
    if ($has_cha==1&&$nowtime>$cendtimeu) $nowtime=$cendtimeu;
    if ($has_cha==0&&$nowtime>$fitimeu) $nowtime=$fitimeu;
    if ($nowtime>$realnow) $nowtime=$realnow;
    $pastsec=$nowtime-$t;
    $needtime=$nowtime-$sttimeu;
   // echo $nowtime." ".$sttime." ";

//$srefresh=0;

/*    if ($pastsec<$srefresh&&!($nowtime>$locktu&&$t<$locktu+10)&&!($nowtime>$fitimeu&&$t<$fitimeui+120)&&file_exists($targ))//60秒更新一次
    {
//        Header("Location: merge_contest_standing_".$cid.".html");
        echo file_get_contents($targ);
        die();
    }

    else*/
    {

//        $maketime=date("Y-m-d G:i:s",$nowtime);
//        $sql_update = "update contest set mboard_make='$maketime' where cid = '$cid'";
//        $que_update = mysql_query($sql_update);
//$t=0;
        if ($locktu<$sttimeu||$nowtime>=$fitimeu) $locktu=$fitimeu;
        ob_start(); //打开缓冲区
?>

<?php
        if (db_contest_passed($cid)) {
?>
          <div class="slidediv" style="width:960px;margin:5px auto">
            <span class="passtime"><?php echo get_time($nowtime-$sttimeu,true); ?></span>
            <div class="timeslider" style="z-index:0" name="<?php echo $nowtime-$sttimeu; ?>"></div>
            <span class="maxval" style="display:none" name="<?php if ($has_cha==0) echo $fitimeu-$sttimeu; else echo $cendtimeu-$sttimeu; ?>"></span>
          </div>
<?php
        }
?>


      <div id="cstandingcontainer">
<!--        <div id="one_content_top"></div>
        <div id="one_content">-->
          <!-- insert the page content here -->
          <h1 class="pagetitle" style="display:none"><?php echo $pagetitle ?></h1>
<?php
//        if ($srefresh>5) echo "Generated at $maketime, will be updated every $srefresh seconds. <br>\n";
?>

          <div class="center currentstat" style="margin-top:0px">
            <input type="radio" id="stat_dis_nick" />Display Nickname
            <input type="radio" id="stat_dis_user" checked="checked" />Display Username<br />
            <b>

<?php
        if ($realnow<$sttimeu) echo "Not Started";
        else if ($has_cha==1&&$realnow>$fitimeu&&$realnow<$cstarttimeu) {
            echo "Intermission Phase";
        }
        else if ($has_cha==1&&$realnow>$cstarttimeu&&$realnow<$cendtimeu) {
            echo "Challenge Phase!";
            $chaing=true;
        }
        else if ($has_cha==1&&$realnow>$cendtimeu) {
            echo "Contest Finished";
            $ccpassed=true;
        }
        else if ($has_cha==0&&$realnow>$fitimeu) echo "Contest Finished";
        else if ($realnow>$locktu)  echo "Board Locked";
        else echo "Contest Running";
?>
            </b>
          </div> 
          <div class="rankcontainer">
<?php
        if ($locktu==0) $locktu=$fitimeu+1;
        if ($nowtime>=$sttimeu+$srefresh) {
            if ($locktu<$sttimeu||$nowtime>=$fitimeu||db_contest_passed($cid)) $locktu=$fitimeu;

//	$cid = $_POST['cid'];
            $num_of_problem = 0; //题目个数

            $sql = " SELECT * FROM `contest_problem` WHERE `cid` = ".$cid." order by cpid";  //创建label和题目的对应
            $res = mysql_query($sql);//执行mysql查询

            list($usernum)=mysql_fetch_array(mysql_query("select count(distinct(username)) from status where contest_belong='$cid'"));

            $map2 = array();
            $map3 = array();
            $titles = array();
            while ($row = mysql_fetch_array($res))
            {
                if ($row['type']==3) {
                    list($pacnum)=mysql_fetch_array(mysql_query("select count(distinct(username)) from status where contest_belong='$cid' and pid='".$row['pid']."' and result='Accepted'"));
                    $row['type']=1;
                    if ($usernum==0) $rto=0;
                    else $rto=$pacnum/$usernum;
                    if ($rto>1/2) $mult=1;
                    else if ($rto>1/4) $mult=2;
                    else if ($rto>1/8) $mult=3;
                    else $mult=4;
                    $row['base']=$mult*intval($row['base'])/4;
                    $row['minp']=$mult*intval($row['minp'])/4;
                    $row['para_a']=$mult*intval($row['para_a'])/4;
                }
                $map[$row["pid"]] =$row["lable"];
                $map2[$row["lable"]] = $row["cpid"];
                $map3[$row["lable"]] = $row;
                list($titles[$row["pid"]])=mysql_fetch_array(mysql_query("select title from problem where pid=".$row["pid"]));
                $num_of_problem++;
            }
            //print_r($map);

            $sql = "SELECT * FROM `contest` WHERE `cid` =".$cid;
            $res = mysql_query($sql);//执行mysql查询
            $info_of_contest = mysql_fetch_array($res);
            /*
             * 比赛信息
             * [0] =>[cid]
             * [1] =>[title]
             * [2] =>[description]
             * [3] =>[isprivate]
             * [4] =>[start_time]
             * [5] =>[end_time]
             * [6] =>[lock_board_time]
             * [7] =>[hide_others]
             * [8] => [board_make]
             * [9] =>[isvirtual]
             * [10] => [owner]
             * [11] => [report]
             * */
            $basetime = strtotime($info_of_contest[4]);
            $ctype=$info_of_contest['type'];


            // 查询 并存入二维表 OK
            //	联合查询，带出名字uu
            $ary = array();//初始化二维表

            if ($imerge&&$has_cha=="0") $csql="select * from contest where allp = '$allp'";
            else $csql="select * from contest where cid = '$cid'";
//            echo $csql;
            $cres=mysql_query($csql);
            while ($crow=mysql_fetch_array($cres)) {
                $ccid=$crow[0];
                if ($_POST['cid_'.$ccid]!="on") continue;
                if ($ccid!=$cid) $csingle=0;

                $map4=array();
                foreach ($map as $pid=>$lable) {
                    $msql = " SELECT problem.pid FROM contest_problem,problem WHERE `cid` = ".$ccid." and contest_problem.pid=problem.pid
                        and title like '".mysql_real_escape_string($titles[$pid])."'";
                    $mres = mysql_fetch_array(mysql_query($msql));
                    if ($ccid!=$cid) $map4[$mres[0]]=$pid;
                    else $map4[$pid]=$pid;
                }
                //var_dump($map4);

                //echo $ccid;
                //echo $needtime." ";
                $corrt=$needtime+strtotime($crow[4]);
                //echo date("Y-m-d G:i:s",$corrt);
                $clocktu=strtotime($crow[4])+$locktu-$basetime;
                //echo date("Y-m-d G:i:s",$clocktu);
                $cbase=strtotime($crow[4]);
                if ($corrt>=$clocktu&&$corrt<strtotime($crow[5])) $corrt=$clocktu;
                else if($corrt>=$clocktu)  $corrt=strtotime($crow[4])+strtotime($info_of_contest[5])-$basetime;
                $cidtype[$ccid]=$crow['type'];
                if ($crow['type']==99) $sql = " SELECT pid,result,time_submit,username,username,contest_belong FROM replay_status WHERE contest_belong =".$ccid." AND unix_timestamp(time_submit)<=$corrt  order by runid asc" ;
                else $sql = " SELECT status.pid,status.result,status.time_submit,status.username,user.nickname,contest_belong FROM status,user WHERE `status`.`contest_belong` =".$ccid." AND status.username=user.username  AND unix_timestamp(status.time_submit)<=$corrt order by runid asc" ;
                $res = mysql_query($sql);//执行mysql查询

                while ($row = mysql_fetch_array($res) )
                {
                    $row[0]=$row["pid"]=$map4[$row[0]];
                    $row[3]=trim(strtolower($row[3]));
                    $row[3]=$row['username']=$row[3]."(".$row[5].")";
                    $row[2]=$row['time_submit']=strtotime($row[2])-$cbase;
                    //print_r( $row);
                    //echo "<br>";
                    $id = array_push($ary,$row); //$id 为行数
                }//将查询结果存入
            }
            // 扫描一遍查询结果 生成名称序二维表
            $Name_ary = array(); //初始化名称序二维表
            $tot_num=$tot_ac=0;
            $namemap=array();

            $charessuc=array();
            $charesfal=array();

            for ($i = 0 ; $i < $id ; $i++)
            {
                $totnum[$map[$ary[$i]['pid']]]++;
                $tot_num++;
                $lowername=strtolower($ary[$i]['username']);
                if ($ary[$i]['result'] == "Accepted"|| $ary[$i]['result'] == "Pretest Passed")  {
                    $acnum[$map[$ary[$i]['pid']]]++;
                    $tot_ac++;
                    if ($fb[$map[$ary[$i]['pid']]]==""||intval($fb[$map[$ary[$i]['pid']]])>intval($ary[$i]['time_submit']))
                        $fb[$map[$ary[$i]['pid']]]=$ary[$i]['time_submit'];
                }
                $k = 0;
                $get = 0;

                if (isset($namemap[$lowername])) {
                    $k = 1;
                    $get = $namemap[$lowername];
                }
                else $k=0;
                if ($k == 1)
                {
                    //echo "Yes<br/>";
                    if ($ary[$i]['result'] == "Accepted" || $ary[$i]['result'] == "Pretest Passed" )
                    {
                        if ( $Name_ary[$get][$map[$ary[$i]['pid']]]!=-1 )
                        {
                            if ($has_cha) {
                                $Name_ary[$get][$map[$ary[$i]['pid']]] = $ary[$i]['time_submit'];
                                $Name_ary[$get][$map[$ary[$i]['pid']].'_ori'] = $ary[$i]['time_submit'];
                                $Name_ary[$get][$map[$ary[$i]['pid']]._wci]--;
                            }
                            continue;
                        }
                        else
                        {
                            $Name_ary[$get][$map[$ary[$i]['pid']]] = $ary[$i]['time_submit'];
                            $Name_ary[$get][$map[$ary[$i]['pid']].'_ori'] = $ary[$i]['time_submit'];
                        }
                    }
                    else
                    {
                        if ( $Name_ary[$get][$map[$ary[$i]['pid']]] == -1 )
                        {
                            $Name_ary[$get][$map[$ary[$i]['pid']]._wci]--;
                        }
                        else {
                            if ($has_cha) {
                                $Name_ary[$get][$map[$ary[$i]['pid']]._wci]-=2;
                                $Name_ary[$get][$map[$ary[$i]['pid']]] = -1;
                            }
                        }
                    }
                }
                else
                {
                    //echo "No<br/>";
                    $iid = array_push($Name_ary,$ary[$i]);
                    $namemap[$lowername]=$iid-1;
                    if ($ary[$i]['result'] == "Accepted" || $ary[$i]['result'] == "Pretest Passed" )
                    {
                        array_push($Name_ary[$iid-1],$ary[$i]['time_submit']);
                        foreach ($map as $value)
                        {
                            $Name_ary[$iid-1][$value] = -1;
                            $Name_ary[$iid-1][$value._wci] = 0;
                        }
                        $Name_ary[$iid-1][$map[$ary[$i]['pid']]] = $ary[$i]['time_submit'];
                        $Name_ary[$iid-1][$map[$ary[$i]['pid']].'_ori'] = $ary[$i]['time_submit'];
                        $Name_ary[$iid-1][$map[$ary[$i]['pid']]._wci] = 0;
                    }
                    else
                    {
                        array_push($Name_ary[$iid-1],0);
                        foreach ($map as $value)
                        {
                            $Name_ary[$iid-1][$value] = -1;
                            $Name_ary[$iid-1][$value._wci] = 0;
                        }
                        $Name_ary[$iid-1][$map[$ary[$i]['pid']]] = -1;
                        $Name_ary[$iid-1][$map[$ary[$i]['pid']]._wci] = -1;
                    }
                    $charessuc[$iid-1]=0;
                    $charesfal[$iid-1]=0;
                }
            }

//            print_r($Name_ary[0]);
            
            $totsuc=0;
            $totfal=0;
            if ($has_cha) {
                $ssql="select username,cha_result,runid from challenge where cid='$cid' and unix_timestamp(cha_time)<=$nowtime order by cha_id asc";
                $sres=mysql_query($ssql);
                $chaed=array();
                while ($srow=mysql_fetch_array($sres)) {
                    $lowername=strtolower($srow['username'].'('.$cid.')');
                    if (isset($namemap[$lowername])) $get = $namemap[$lowername];
                    else {
                        $namemap[$lowername]=$iid;
                        $Name_ary[$iid]=array();
                        $Name_ary[$iid][3]=$lowername;
                        $Name_ary[$iid]['contest_belong']=$cid;
                        $iid++;
                        for ($i = 0 ; $i < $id ; $i++) {
                            array_push($Name_ary[$iid-1],0);
                            foreach ($map as $value)
                            {
                                $Name_ary[$iid-1][$value] = -1;
                                $Name_ary[$iid-1][$value._wci] = 0;
                            }
                        }
                        $get = $iid-1;
                        $charessuc[$get]=0;
                        $charesfal[$get]=0;
                    }
                    if (strstr($srow['cha_result'],"Success")) {
                        if (!isset($chaed[$srow['runid']])) {
                            $chaed[$srow['runid']]=true;
                            $charessuc[$get]++;
                            $totsuc++;
                        }
                    }
                    else if (strstr($srow['cha_result'],"Failed")) {
                        $charesfal[$get]++;
                        $totfal++;
                    }
                }
            }            
            
// 扫描计算罚时与题数 然后排序

            for ($i = 0 ; $i < $iid ; $i++)
            {
                $fs = 0;
                $Name_ary[$i]['sum'] = 0;
                $Name_ary[$i][6] = 0;
                foreach ($map as $value)
                {
                    if ( $Name_ary[$i][$value]!=-1 )
                    {
                        $Name_ary[$i]['sum']++;
                        //$fs -= 20*60*$Name_ary[$i][$value._wci];
                        if ($ctype==0||$ctype==99) $Name_ary[$i][6]+=$Name_ary[$i][$value]-20*60*$Name_ary[$i][$value._wci];
                        else if ($ctype==1) {
                            $Name_ary[$i][$value]=cal_point($map3[$value],$Name_ary[$i][$value]);
                            if ($map3[$value]['type']==1) $Name_ary[$i][$value]+=$Name_ary[$i][$value._wci]*$map3[$value]['para_b'];
                            else if ($map3[$value]['type']==2) $Name_ary[$i][$value]=intval($Name_ary[$i][$value]*pow(1.0-doubleval($map3[$value]['para_e'])/100.0,-$Name_ary[$i][$value._wci]));
                            if ($Name_ary[$i][$value]<$map3[$value]['minp']) $Name_ary[$i][$value]=$map3[$value]['minp'];
                            $Name_ary[$i][6]+=$Name_ary[$i][$value];
                        }
                    }
                }
                if ($has_cha) {
                    if ($ctype==0||$ctype==99) $Name_ary[$i][6] += 20*60*$charesfal[$i] - 40*60*$charessuc[$i];
                    else if ($ctype==1) $Name_ary[$i][6] += -25*$charesfal[$i] + 50*$charessuc[$i];
                }
                //$Name_ary[$i][6] += $fs;
            }

            function cmp0($a,$b) {
//                print_r($a);
                if ($a['sum']==$b['sum']) {
                    if ($a[6]<$b[6]) return -1;
                    if ($a[6]>$b[6]) return 1;
                    return 0;
                }
                else {
                    if ($a['sum']>$b['sum']) return -1;
                    return 1;
                }
            }

            function cmp99($a,$b) {
//                print_r($a);
                if ($a['sum']==$b['sum']) {
                    if ($a[6]<$b[6]) return -1;
                    if ($a[6]>$b[6]) return 1;
                    return 0;
                }
                else {
                    if ($a['sum']>$b['sum']) return -1;
                    return 1;
                }
            }


            function cmp1($a,$b) {
//                print_r($a);
                if ($a[6]>$b[6]) return -1;
                if ($a[6]<$b[6]) return 1;
                return 0;
            }

            
            usort($Name_ary,"cmp".$ctype);

// 显示
//            echo "<table width='98%'>";
        echo "<table class='cstanding cbody'>";
//        $ro=intval(100/($num_of_problem+5));
        echo "\n".'<thead><tr>';
        echo "<th class='trank anim:position'> Rank </th>";
            echo "<th class='anim:constant tname tnickname' style='display:none'> Nick </th>";
            echo "<th class='anim:constant tname tusername'> User </th>";
            echo "<th class='tac'> AC <br />";
            echo $tot_ac."/".$tot_num."<br />";
            if (intvaL($tot_num)>0) echo round(intval($tot_ac)/intvaL($tot_num)*100,2)."%";
            else echo "0%";
            echo "</th>";

            foreach ($map as $value)
            {
                echo "<th class='tprob'>";
                echo "<a href='#problem/".$map2[$value]."'>".$value."</a><br />";
                if ($acnum[$value]=="") $acnum[$value]="0";
                if ($totnum[$value]=="") $totnum[$value]="0";
                echo $acnum[$value]."/".$totnum[$value]."<br />";
                if (intval($totnum[$value])>0) echo round(intval($acnum[$value])/intval($totnum[$value])*100,2)."%";
                else echo "0%";
                echo "</th>";
            }
            if ($has_cha) echo "<th class='tpenal'>Sum<br />+$totsuc : -$totfal</th>";
            if ($ctype==0||$ctype==99) echo "<th class='tpenal'>Penalty</th>";//Penalty
            else if ($ctype==1) echo "<th class='tpenal'>Score</th>";//Penalty
            if ($imerge) echo "<th class='anim:constant tcid'>CID</th>";
            else echo "<th style='display:none' class='anim:constant tcid'>CID</th>";
            echo "<th class='tidentii anim:id' style='display:none'>ID</th>";
            echo "</tr></thead>\n<tbody>";
            if ($iid>$maxrank) $iid=$maxrank;
            for ($i = 0 ; $i < $iid ; $i++)
            {
                //  print_r($Name_ary[$i]);

                $nick = change_out_nick($Name_ary[$i][4]);
                if ($nick == '')
                {
                    $nick = "No nickname.";
                }
                $rnick=$nick;
                if (strlen($nick)>20 )
                {
                    $nick = mb_strcut($nick,0,20,'UTF-8')."...";
                }
//        if ($i%2==0) echo "<tr class=even>";
//        else echo "<tr class=odd>";
                $cduser=$cuser=substr($Name_ary[$i][3],0,strrpos($Name_ary[$i][3], '('));
                if (strlen($cduser)>20 ) $cduser = mb_strcut($cuser,0,20,'UTF-8')."...";

//                echo "<tr name='".$Name_ary[$i]['contest_belong']."' class='rowclick'>";
                echo "<tr>";
                if ($cidtype[$Name_ary[$i]['contest_belong']]==0||$cidtype[$Name_ary[$i]['contest_belong']]==1)
                    echo //"<th>".$Name_ary[$i][0]."</th>" . //pid
                    // "<th>".$Name_ary[$i][1]."</th>" . //result
                    // "<th>".$Name_ary[$i][2]."</th>".//time_submit
                    "<td>".($i+1)."</td>".
                    "<td class='tnickname' style='display:none'><a href='userinfo.php?name=$cuser' title='$rnick'>".$nick."</a></td>".//nickname
                    "<td class='tusername'><a target='_blank' href='userinfo.php?name=$cuser' title='$cuser'>".$cduser."</a></td>".//username
                    "<td>".$Name_ary[$i][sum]."</td>";//ac_num
                else
                    echo //"<th>".$Name_ary[$i][0]."</th>" . //pid
                    // "<th>".$Name_ary[$i][1]."</th>" . //result
                    // "<th>".$Name_ary[$i][2]."</th>".//time_submit
                    "<td>".($i+1)."</td>".
                    "<td class='tnickname' style='display:none' title='$rnick'>".$nick."</td>".//nickname
                    "<td class='tusername' title='$cuser'>".$cduser."</td>".//username
                    "<td>".$Name_ary[$i][sum]."</td>";//ac_num


                foreach ($map as $value)
                {
                    if ($Name_ary[$i][$value] != -1 && array_key_exists($value,$Name_ary[$i])) {
                        if ($ctype==0||$ctype==99) $cont=get_time($Name_ary[$i][$value])."(".(-$Name_ary[$i][$value._wci]).")";
                        else if ($ctype==1) $cont=get_time($Name_ary[$i][$value])."<br />".get_time($Name_ary[$i][$value.'_ori'],true)."(".(-$Name_ary[$i][$value._wci]).")";
                        if ($fb[$value]!=$Name_ary[$i][$value.'_ori']) {
                            if ($chaing||$ccpassed) $cont="<a class='cha_click' chauname='$cuser' chaprob='".$map3[$value]['pid']."'>".$cont."</a>";
                            echo "<td class='ac_stat'>".$cont."</td>";
                        }
                        else {
                            if ($chaing||$ccpassed) $cont="<a class='cha_click' chauname='$cuser' chaprob='".$map3[$value]['pid']."'>".$cont."</a>";
                            echo "<td class='acfb_stat'>".$cont."</td>";
                        }
                    }
                    else if ($Name_ary[$i][$value._wci])
                        if ($ccpassed) echo "<td class='notac_stat'><a class='cha_click' chauname='$cuser' chaprob='".$map3[$value]['pid']."'>(".$Name_ary[$i][$value._wci].")</a></td>";
                        else echo "<td class='notac_stat'>(".$Name_ary[$i][$value._wci].")</td>";
                    else
                    {
                        echo "<td></td>";
                    }
                }
                if ($has_cha) echo "<td><a class='user_cha' chauname='$cuser'>+".$charessuc[$namemap[$Name_ary[$i][3]]]." : -".$charesfal[$namemap[$Name_ary[$i][3]]]."</a></td>";
                echo "<td>".get_time($Name_ary[$i][6])."</td>";//Penalty
                if ($imerge) echo "<td>";
                else  echo "<td style='display:none'>";
                echo "<a target='_blank' href='contest_show.php?cid=".$Name_ary[$i]['contest_belong']."'>".$Name_ary[$i]['contest_belong']."</a></td>";//Contest
                echo "<td style='display:none'>".$Name_ary[$i][3]."</td>";//ID
                echo "</tr>\n";
            }
        echo "</tbody></table>\n";
/*        echo "<table class='cstanding cfoot'><tfoot><tr><th class='trank'></th><th class='tname tnickname' style='display:none'>Total</th><th class='tname tusername'>Total</th><th class='tac'></th>";
        foreach ($map as $value) {
            if ($acnum[$value]=="") $acnum[$value]="0";
            if ($totnum[$value]=="") $totnum[$value]="0";
            echo "<th class='tprob'>".$acnum[$value]."/".$totnum[$value]."<br />";
            if (intvaL($totnum[$value])>0) echo round(intval($acnum[$value])/intvaL($totnum[$value])*100,2)."%";
            else echo "0%";
            echo "</th>";
        }
        echo "<th class='tpenal'></th><th class='tcid'></th><th class='tidenti' style='display:none'></th></tr></tfoot>";
        echo "</table>";*/
    }
?>
<!--        </div>
        <div id="one_content_base"></div>-->
<?php
/*        echo "<script>sortAble('standing',$num_of_problem+4);</script>";
        include("footer.php");*/
?>
        </div>
      </div>
      <div id="trypos" style="clear:both">&nbsp;</div>
<?php

        $content=ob_get_contents(); //得到缓冲区的内容
//        echo $csingle;
        if (!function_exists("file_put_contents"))
        {
            function file_put_contents($fn,$fs)
            {
                $fp=fopen($fn,"w+");
                fputs($fp,$fs);
                fclose($fp);
            }
        }
        if ($csingle==0&&!isset($_GET['passtime'])) file_put_contents($targ,$content);
    }

}
else
{
    echo "Invalid Contest!";
}
?>




