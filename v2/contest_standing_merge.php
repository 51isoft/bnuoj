<?php
$nowtime=time();
//echo date("Y-m-d G:i:s",$nowtime);
include_once ("conn.php");
$cid = convert_str($_GET['cid']);
$pagetitle="Standing of Contest ".$cid;

if (db_contest_exist($cid))
{
    $nowtime=time();
    list($locktu,$sttimeu,$fitimeu,$t,$allp) = @mysql_fetch_array(mysql_query("SELECT unix_timestamp(lock_board_time),unix_timestamp(start_time),unix_timestamp(end_time),unix_timestamp(mboard_make),allp FROM contest WHERE cid = '$cid'"));
    $targ = "standings/merge_contest_standing_".$cid.".html";
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
        if ($nowtime<$sttimeu) echo "Not Started";
        else if ($nowtime>$fitimeu) echo "Contest Finished";
        else if ($nowtime>$locktu)  echo "Board Locked";
        else echo "Contest Running";
?>
            </b>
          </div> 
          <div class="rankcontainer">
<?php
        if ($locktu==0) $locktu=$fitimeu+1;
        if ($nowtime>=$sttimeu+$srefresh) {
            if ($locktu<$sttimeu||$nowtime>=$fitimeu) $locktu=$fitimeu;

//	$cid = $_GET['cid'];
            $num_of_problem = 0; //题目个数

            $sql = " SELECT * FROM `contest_problem` WHERE `cid` = ".$cid." order by cpid";  //创建label和题目的对应
            $res = mysql_query($sql);//执行mysql查询

            $map2 = array();
            $map3 = array();
            while ($row = mysql_fetch_array($res))
            {
                $map[$row["pid"]] =$row["lable"];
                $map2[$row["lable"]] = $row["cpid"];
                $map3[$row["lable"]] = $row;
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
            //	联合查询，带出名字
            $ary = array();//初始化二维表

            $csql="select * from contest where allp = '$allp'";
//            echo $csql;
            $cres=mysql_query($csql);
            while ($crow=mysql_fetch_array($cres)) {
                $ccid=$crow[0];
                //echo $ccid;
                //echo $needtime." ";
                $corrt=$needtime+strtotime($crow[4]);
                //echo date("Y-m-d G:i:s",$corrt);
                $clocktu=strtotime($crow[4])+$locktu-$basetime;
                //echo date("Y-m-d G:i:s",$clocktu);
                $cbase=strtotime($crow[4]);
                if ($corrt>=$clocktu&&$corrt<strtotime($crow[5])) $corrt=$clocktu;
                else if($corrt>=$clocktu)  $corrt=strtotime($crow[4])+strtotime($info_of_contest[5])-$basetime;
                $sql = " SELECT status.pid,status.result,status.time_submit,status.username,user.nickname,contest_belong FROM status,user WHERE `status`.`contest_belong` =".$ccid." AND status.username=user.username  AND unix_timestamp(status.time_submit)<=$corrt" ;
                $res = mysql_query($sql);//执行mysql查询

                while ($row = mysql_fetch_array($res) )
                {
                    $row[3]=strtolower($row[3]);
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
            for ($i = 0 ; $i < $id ; $i++)
            {
                $totnum[$map[$ary[$i]['pid']]]++;
                $tot_num++;
                if ($ary[$i]['result'] == "Accepted" )  {
                    $acnum[$map[$ary[$i]['pid']]]++;
                    $tot_ac++;
                    if ($fb[$map[$ary[$i]['pid']]]==""||intval($fb[$map[$ary[$i]['pid']]])>intval($ary[$i]['time_submit']))
                        $fb[$map[$ary[$i]['pid']]]=$ary[$i]['time_submit'];
                }
                $k = 0;
                $get = 0;
                for ($j = 0 ; $j < $iid ; $j++)
                {
                    if ( strtolower($ary[$i]['username']) == strtolower($Name_ary[$j]['username']) )
                    {
                        $k = 1;
                        $get = $j;
                        break;
                    }
                }
                if ($k == 1)
                {
                    //echo "Yes<br/>";
                    if ($ary[$i]['result'] == "Accepted" )
                    {
                        if ( $Name_ary[$get][$map[$ary[$i]['pid']]]!=-1 )
                        {
                            continue;
                        }
                        else
                        {
                            $Name_ary[$get][$map[$ary[$i]['pid']]] = $ary[$i]['time_submit'];
                            $Name_ary[$get][6] += $ary[$i]['time_submit'];
                        }
                    }
                    else
                    {
                        if ( $Name_ary[$get][$map[$ary[$i]['pid']]] == -1 )
                        {   
                            $Name_ary[$get][$map[$ary[$i]['pid']]._wci]--;
                        }
                    }
                }
                else
                {
                    //echo "No<br/>";
                    $iid = array_push($Name_ary,$ary[$i]);
                    if ($ary[$i]['result'] == "Accepted" )
                    {
                        array_push($Name_ary[$iid-1],$ary[$i]['time_submit']);
                        foreach ($map as $value)
                        {
                            $Name_ary[$iid-1][$value] = -1;
                            $Name_ary[$iid-1][$value._wci] = 0;
                        }
                        $Name_ary[$iid-1][$map[$ary[$i]['pid']]] = $ary[$i]['time_submit'];
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
                }
            }
//            print_r($Name_ary[0]);
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
                        if ($ctype==0) $Name_ary[$i][6]+=$Name_ary[$i][$value]-20*60*$Name_ary[$i][$value._wci];
                        else if ($ctype==1) {
                            $Name_ary[$i][$value]=cal_point($map3[$value],$Name_ary[$i][$value]);
                            if ($map3[$value]['type']==1) $Name_ary[$i][$value]+=$Name_ary[$i][$value._wci]*$map3[$value]['para_b'];
                            else if ($map3[$value]['type']==2) $Name_ary[$i][$value]=intval($Name_ary[$i][$value]*pow(1.0-doubleval($map3[$value]['para_e'])/100.0,-$Name_ary[$i][$value._wci]));
                            if ($Name_ary[$i][$value]<$map3[$value]['minp']) $Name_ary[$i][$value]=$map3[$value]['minp'];
                            $Name_ary[$i][6]+=$Name_ary[$i][$value];
                        }
                    }
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

            function cmp1($a,$b) {
//                print_r($a);
                if ($a[6]>$b[6]) return -1;
                if ($a[6]<$b[6]) return 1;
                return 0;
            }

            
            usort($Name_ary,"cmp".$ctype);

            function get_time ($unix_time)
            {
                global $ctype;
                if ($ctype==1) return $unix_time;
                $first = floor($unix_time/3600);
                $mid = floor( ($unix_time-$first*3600)/60 );
                $last = $unix_time%60;
                return $first.":".$mid.":".$last;
            }
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
                echo "<a class='standingp' href='#' name='".$map2[$value]."'>".$value."</a><br />";
                if ($acnum[$value]=="") $acnum[$value]="0";
                if ($totnum[$value]=="") $totnum[$value]="0";
                echo $acnum[$value]."/".$totnum[$value]."<br />";
                if (intval($totnum[$value])>0) echo round(intval($acnum[$value])/intval($totnum[$value])*100,2)."%";
                else echo "0%";
                echo "</th>";
            }
            if ($ctype==0) echo "<th class='tpenal'>Penalty</th>";//Penalty
            else if ($ctype==1) echo "<th class='tpenal'>Score</th>";//Penalty
            echo "<th class='anim:constant tcid'>CID</th>";
            echo "<th class='tidentii anim:id' style='display:none'>ID</th>";
            echo "</tr></thead>\n<tbody>";
            for ($i = 0 ; $i < $iid ; $i++)
            {
                //  print_r($Name_ary[$i]);

                $nick = change_out_nick($Name_ary[$i][4]);
                if ($nick == '')
                {
                    $nick = "No nickname.";
                }
                if (strlen($nick)>20 )
                {
                    $nick = mb_strcut($nick,0,20,'UTF-8')."...";
                }
//        if ($i%2==0) echo "<tr class=even>";
//        else echo "<tr class=odd>";
                $cuser=strstr($Name_ary[$i][3], '(', true);

//                echo "<tr name='".$Name_ary[$i]['contest_belong']."' class='rowclick'>";
                echo "<tr>";
                echo //"<th>".$Name_ary[$i][0]."</th>" . //pid
                // "<th>".$Name_ary[$i][1]."</th>" . //result
                // "<th>".$Name_ary[$i][2]."</th>".//time_submit
                "<td>".($i+1)."</td>".
                "<td class='tnickname' style='display:none'><a href='userinfo.php?name=$cuser'>".$nick."</a></td>".//nickname
                "<td class='tusername'><a target='_blank' href='userinfo.php?name=$cuser'>".$cuser."</a></td>".//username
                "<td>".$Name_ary[$i][sum]."</td>";//ac_num


                foreach ($map as $value)
                {
                    if ($Name_ary[$i][$value] != -1 && array_key_exists($value,$Name_ary[$i])) {
                        if ($fb[$value]!=$Name_ary[$i][$value]) echo "<td class='ac_stat'>".get_time($Name_ary[$i][$value])."(".(-$Name_ary[$i][$value._wci]).")</td>";
                        else echo "<td class='acfb_stat'>".get_time($Name_ary[$i][$value])."(".(-$Name_ary[$i][$value._wci]).")</td>";
                    }
                    else if ($Name_ary[$i][$value._wci])
                        echo "<td class='notac_stat'>(".$Name_ary[$i][$value._wci].")</td>";
                    else
                    {
                        echo "<td></td>";
                    }
                }
                echo "<td>".get_time($Name_ary[$i][6])."</td>";//Penalty
                echo "<td><a target='_blank' href='contest_show.php?cid=".$Name_ary[$i]['contest_belong']."'>".$Name_ary[$i]['contest_belong']."</a></td>";//Contest
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
        if (!function_exists("file_put_contents"))
        {
            function file_put_contents($fn,$fs)
            {
                $fp=fopen($fn,"w+");
                fputs($fp,$fs);
                fclose($fp);
            }
        }
        file_put_contents($targ,$content);
    }

}
else
{
    echo "Invalid Contest!";
}
?>




