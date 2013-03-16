<?php
$nowtime=time();
//echo date("Y-m-d G:i:s",$nowtime);
$cid = $_GET['cid'];
$pagetitle="Standing of Contest ".$cid;
include_once "../conn.php";
$cid = $_GET['cid'];

if (db_contest_exist($cid))
{
    $nowtime=time();
    list($locktu,$sttimeu,$fitimeu,$t) = @mysql_fetch_array(mysql_query("SELECT unix_timestamp(lock_board_time),unix_timestamp(start_time),unix_timestamp(end_time),unix_timestamp(board_make) FROM contest WHERE cid = '$cid'"));
    $scr = "contest_standing_".$cid.".html";
    $pastsec=$nowtime-$t;
    if ($pastsec<$srefresh&&!($nowtime>$locktu&&$t<$locktu)&&!($nowtime>$fitimeu+100&&$t<$fitimeu+100))//60秒更新一�?
    {
        Header("Location: contest_standing_".$cid.".html");
    }

    else
    {

        $maketime=date("Y-m-d G:i:s",$nowtime);
        $sql_update = "update contest set board_make='$maketime' where cid = '$cid'";
        $que_update = mysql_query($sql_update);


        ob_start(); //打开缓冲�?
        include("cheader.php");
        echo "<center>";
        include("cmenu.php");
        if ($srefresh>5) echo "Generated at $maketime, will be updated every $srefresh seconds. <br>\n";
?>
        <input type="radio" name="hide" value="0" checked="checked" onclick=changeDisplay('standing',0) />Display Nickname
        <input type="radio" name="hide" value="1" onclick=changeDisplay('standing',1) />Display Username <br>

<?


        echo "<table width=98% boder=0 class=stdtb id=standing>";
        if ($locktu==0) $locktu=$fitimeu+1;
        if ($nowtime<$sttimeu+$srefresh)
        {
            echo "<caption class='standing'>Not Started</caption>";
        }
        else
        {
            if ($locktu<$sttimeu||$nowtime>=$fitimeu) $locktu=$fitimeu;
            if ($nowtime>$fitimeu)
            {
                if ($t>$fitimeu)
                {
                    echo "<script>window.location ='contest_standing_$cid.html';</script>";
                    exit;
                }
                //echo $nowtime."f".$fitimeu;
                echo "<caption class='standing'>Contest Finished</caption>";
            }
            else if ($nowtime>$locktu )
            {
                if ($t>$locktu)
                {
                    echo "<script>window.location ='contest_standing_$cid.html';</script>";
                    exit;
                }
                echo "<caption class='standing'>Board Locked</caption>";
            }
            else echo "<caption class='standing'>Contest Running</caption>";


            function get_time ($unix_time)
            {
                $first = floor($unix_time/3600);
                $mid = floor( ($unix_time-$first*3600)/60 );
                $last = $unix_time%60;
                return $first.":".$mid.":".$last;
            }

//	$cid = $_GET['cid'];
            $num_of_problem = 0; //题目个数

            $sql = " SELECT * FROM `contest_problem` WHERE `cid` = ".$cid." order by cpid";  //创建label和题目的对应
            $res = mysql_query($sql);//执行mysql查询

            $map2 = array();
            while ($row = mysql_fetch_array($res))
            {
                $map[$row[pid]] =$row[lable];
                $map2[$row[lable]] = $row[cpid];
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


            // 查询 并存入二维表 OK
            //	联合查询，带出名�?
            $sql = " SELECT status.pid,status.result,status.time_submit,status.username,user.nickname FROM status,user WHERE `status`.`contest_belong` =".$cid." AND status.username=user.username  AND unix_timestamp(status.time_submit)<=$locktu" ;
            $res = mysql_query($sql);//执行mysql查询

            $ary = array();//初始化二维表
            while ($row = mysql_fetch_array($res) )
            {
                $row[3]=strtolower($row[3]);
                $id = array_push($ary,$row); //$id 为行�?
            }//将查询结果存�?



            // 扫描一遍查询结�?生成名称序二维表
            $Name_ary = array(); //初始化名称序二维�?

            for ($i = 0 ; $i < $id ; $i++)
            {
                $k = 0;
                $get = 0;
                for ($j = 0 ; $j < $iid ; $j++)
                {
                    if ( strtolower($ary[$i][username]) == strtolower($Name_ary[$j][username]) )
                    {
                        $k = 1;
                        $get = $j;
                        break;
                    }
                }
                if ($k == 1)
                {
                    //echo "Yes<br/>";
                    if ($ary[$i][result] == "Accepted" )
                    {
                        if ( $Name_ary[$get][$map[$ary[$i][pid]]]!=-1 )
                        {
                            continue;
                        }
                        else
                        {
                            $Name_ary[$get][$map[$ary[$i][pid]]] = strtotime($ary[$i][time_submit])-$basetime;
                            $Name_ary[$get][5] += strtotime($ary[$i][time_submit])-$basetime;
                        }
                    }
                    else
                    {
                        if ( $Name_ary[$get][$map[$ary[$i][pid]]] == -1 )
                        {
                            $Name_ary[$get][$map[$ary[$i][pid]]._wci]--;
                        }
                    }
                }
                else
                {
                    //echo "No<br/>";
                    $iid = array_push($Name_ary,$ary[$i]);
                    if ($ary[$i][result] == "Accepted" )
                    {
                        array_push($Name_ary[$iid-1],strtotime($ary[$i][time_submit])-$basetime);
                        foreach ($map as $value)
                        {
                            $Name_ary[$iid-1][$value] = -1;
                            $Name_ary[$iid-1][$value._wci] = 0;
                        }
                        $Name_ary[$iid-1][$map[$ary[$i][pid]]] = strtotime($ary[$i][time_submit])-$basetime;
                        $Name_ary[$iid-1][$map[$ary[$i][pid]]._wci] = 0;
                    }
                    else
                    {
                        array_push($Name_ary[$iid-1],0);
                        foreach ($map as $value)
                        {
                            $Name_ary[$iid-1][$value] = -1;
                            $Name_ary[$iid-1][$value._wci] = 0;
                        }
                        $Name_ary[$iid-1][$map[$ary[$i][pid]]] = -1;
                        $Name_ary[$iid-1][$map[$ary[$i][pid]]._wci] = -1;
                    }
                }
            }

// 扫描计算罚时与题�?然后排序

            for ($i = 0 ; $i < $iid ; $i++)
            {
                $fs = 0;
                $Name_ary[$i][sum] = 0;
                foreach ($map as $value)
                {
                    if ( $Name_ary[$i][$value]!=-1 )
                    {
                        $Name_ary[$i][sum]++;
                        $fs -= 20*60*$Name_ary[$i][$value._wci];
                    }
                }
                $Name_ary[$i][5] += $fs;
            }
/*            for ($i = 0 ; $i < $iid ; $i++ )
            {
                for ($j = 0 ; $j < $iid ; $j++ )
                {
                    if ($Name_ary[$i][sum] > $Name_ary[$j][sum] )
                    {
                        $temp = array();
                        $temp = $Name_ary[$i];
                        $Name_ary[$i] = $Name_ary[$j];
                        $Name_ary[$j] = $temp;
                    }
                    else if ($Name_ary[$i][sum] == $Name_ary[$j][sum])
                    {
                        if ($Name_ary[$i][5]<$Name_ary[$j][5])
                        {
                            $temp = array();
                            $temp = $Name_ary[$i];
                            $Name_ary[$i] = $Name_ary[$j];
                            $Name_ary[$j] = $temp;
                        }
                    }
                }
            }
*/
// 显示
//            echo "<table width='98%'>";

        $ro=intval(100/($num_of_problem+4));
        echo "\n".'<tr class=even>';
        echo "<th width='$ro%' class='stdti'> Rank </th>";
            echo "<th width='$ro%' class='stdti'> Nickname </th>";
            echo "<th width='$ro%' class='stdti' style='display:none'> Username </th>";
            echo "<th width='$ro%' class='stdti'> Accepts </th>";

            foreach ($map as $value)
            {
                echo "<th width='$ro%' class='stdti'><a href=../contest_problem_show.php?cpid=".$map2[$value].">".$value."</a></th>";
            }
            echo "<th width='$ro%' class='stdti'> Penalty </th>";//Penalty
//            echo "<th width='$ro%' class='stdti'>Contest</th>";
            echo "</tr>\n";
            for ($i = 0 ; $i < $iid ; $i++)
            {
                //  print_r($Name_ary[$i]);

                $nick = strip_tags(change_out_nick($Name_ary[$i][4]));
                if ($nick == '')
                {
                    $nick = "这家伙很懒，什么都没有留下";
                }
                else if (strlen($nick)>50 )
                {
                    $nick = substr($nick,0,50)."...";
                }
//        if ($i%2==0) echo "<tr class=even>";
//        else echo "<tr class=odd>";
                $cuser=$Name_ary[$i][username];

                echo "<tr onclick=hide('standing',".$Name_ary[$i][contest_belong].",$num_of_problem+5)>";
                echo //"<th>".$Name_ary[$i][0]."</th>" . //pid
                // "<th>".$Name_ary[$i][1]."</th>" . //result
                // "<th>".$Name_ary[$i][2]."</th>".//time_submit
                "<td width='$ro%'>".($i+1)."</td>".//username
                "<td width='$ro%'><a href='../userinfo.php?name=$cuser'>".$nick."</a></td>".//nickname
                "<td width='$ro%' style='display:none'><a href='../userinfo.php?name=$cuser'>".$cuser."</a></td>".//nickname
                "<td width='$ro%'>".$Name_ary[$i][sum]."</td>";//ac_num

                foreach ($map as $value)
                {
                    if ($Name_ary[$i][$value] != -1 && array_key_exists($value,$Name_ary[$i]))
                        echo "<td width='$ro%' class='ac'>".$Name_ary[$i][$value]."(".(-$Name_ary[$i][$value._wci]).")</td>";
                    else if ($Name_ary[$i][$value._wci])
                        echo "<td width='$ro%' class='try'>(".$Name_ary[$i][$value._wci].")</td>";
                    else
                    {
                        echo "<td width='$ro%' class='nottry'></td>";
                    }
                }
                echo "<td width='$ro%'>".$Name_ary[$i][5]."</td>";//Penalty
//                echo "<td width='$ro%'><a href='contest_standing.php?cid=".$Name_ary[$i][contest_belong]."'>".$Name_ary[$i][contest_belong]."</a></td>";//Contest
                echo "</tr>\n";
            }
        echo "</table>";
        echo "<script>sortAble('standing',$num_of_problem+4);</script>";
        include("footer.php");
    }


?>
<script type="text/javascript">
<!--
var tables = document.getElementsByTagName('table');
table_Init(tables[2]);
-->
</script>
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
        file_put_contents("contest_standing_".$cid.".html",$content);
    }

}
else
{
    include("cheader.php");
    include("cmenu.php");
    echo "<center><p class=warn>Invalid Contest!</p></center>";
    include("footer.php");
}
?>




