<?php
$nowtime=time();
//echo date("Y-m-d G:i:s",$nowtime);

include_once "../conn.php";
$cid = $_GET['cid'];
if (db_contest_exist($cid))
{
    $nowtime=time();
    list($locktu,$sttimeu,$fitimeu,$t) = @mysql_fetch_array(mysql_query("SELECT unix_timestamp(lock_board_time),unix_timestamp(start_time),unix_timestamp(end_time),unix_timestamp(board_make) FROM contest WHERE cid = '$cid'"));
    $scr = "contest_standing_".$cid.".html";
    $pastsec=$nowtime-$t;
    if (false && $pastsec<$srefresh&&!($nowtime>$locktu&&$t<$locktu)&&!($nowtime>$fitimeu&&$t<$fitimeu))//60�����һ��
    {
        Header("Location: contest_standing_".$cid.".html");
    }

    else
    {

        $maketime=date("Y-m-d G:i:s",$nowtime);
        $sql_update = "update contest set board_make='$maketime' where cid = '$cid'";
        $que_update = mysql_query($sql_update);


        ob_start(); //�򿪻�����
        include("cheader.php");
        echo "<center>";
        include("cmenu.php");
        if ($srefresh>5) echo "Generated at $maketime, will be updated every $srefresh seconds. <br>\n";
        echo "<table width=98% boder=0 class=stdtb>";
        if ($locktu==0) $locktu=$fitimeu+1;
        if ($nowtime<$sttimeu+$srefresh)
        {
            echo "<caption class='standing'>Not Started</caption>";
        }
        else
        {
            /*
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
        */

            function get_time ($unix_time)
            {
                $first = floor($unix_time/3600);
                $mid = floor( ($unix_time-$first*3600)/60 );
                $last = $unix_time%60;
                return $first.":".$mid.":".$last;
            }

//	$cid = $_GET['cid'];
            $num_of_problem = 0; //��Ŀ����

            $sql = " SELECT * FROM `contest_problem` WHERE `cid` = ".$cid;  //����label����Ŀ�Ķ�Ӧ
            $res = mysql_query($sql);//ִ��mysql��ѯ

            $map2 = array();
            while ($row = mysql_fetch_array($res))
            {
                $map[$row[pid]] =$row[lable];
                $map2[$row[lable]] = $row[cpid];
                $num_of_problem++;
            }
            //print_r($map);

            $sql = "SELECT * FROM `contest` WHERE `cid` =".$cid;
            $res = mysql_query($sql);//ִ��mysql��ѯ
            $info_of_contest = mysql_fetch_array($res);
            /*
             * ������Ϣ
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


            // ��ѯ �������ά�� OK
            //	���ϲ�ѯ����������
            $sql = " SELECT status.pid,status.result,status.time_submit,status.username,user.nickname FROM status,user WHERE `status`.`contest_belong` =".$cid." AND status.username=user.username" ;
            $res = mysql_query($sql);//ִ��mysql��ѯ

            $ary = array();//��ʼ����ά��
            while ($row = mysql_fetch_array($res) )
            {
                $id = array_push($ary,$row); //$id Ϊ����
            }//����ѯ�������



            // ɨ��һ���ѯ��� �����������ά��
            $Name_ary = array(); //��ʼ���������ά��

            for ($i = 0 ; $i < $id ; $i++)
            {
                $k = 0;
                $get = 0;
                for ($j = 0 ; $j < $iid ; $j++)
                {
                    if ( in_array($ary[$i][username],$Name_ary[$j],true) )
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

// ɨ����㷣ʱ������ Ȼ������
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
            for ($i = 0 ; $i < $iid ; $i++ )
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


// ��ʾ
//            echo "<table width='98%'>";
	    $ro=100/($num_of_problem+4);
	    echo "\n".'<tr class="even">';
	    echo "<th width='$ro%' class='stdti nowrap'> Rank </th>";
            echo "<th width='$ro%' class='stdti nowrap'> Nickname </th>";
            echo "<th width='$ro%' class='stdti nowrap'> Accepts </th>";

            foreach ($map as $value)
            {
                echo "<th width='$ro%' class='stdti nowrap'><a href=../contest_problem_show.php?cpid=".$map2[$value].">".$value."</a></th>";
            }
            echo "<th width='$ro%' class='stdti nowrap'> Penalty </th>";//Penalty
            echo "</tr>\n";
            for ($i = 0 ; $i < $iid ; $i++)
            {
                //	print_r($Name_ary[$i]);
                    $nick = $Name_ary[$i][username];
		if ($i%2==0) echo "<tr class=even>";
		else echo "<tr class=odd>";
                echo //"<th>".$Name_ary[$i][0]."</th>" . //pid
                // "<th>".$Name_ary[$i][1]."</th>" . //result
                // "<th>".$Name_ary[$i][2]."</th>".//time_submit
                "<td width='$ro%' class='nowrap'>".($i+1)."</td>".//username
                "<td width='$ro%' class='nowrap'><a alt='".$Name_ary[$i][username]."' href=../userinfo.php?name=".$Name_ary[$i][username].">".$nick."</a></td>".//nickname
                "<td width='$ro%' class='nowrap'>".$Name_ary[$i][sum]."</td>";//ac_num


                foreach ($map as $value)
                {
                    if ($Name_ary[$i][$value] != -1 && array_key_exists($value,$Name_ary[$i]))
                        echo "<td width='$ro%' class='nowrap ac'>".get_time($Name_ary[$i][$value])."(".(-$Name_ary[$i][$value._wci]).")</td>";
                    else if ($Name_ary[$i][$value._wci])
                        echo "<td width='$ro%' class='nowrap try'>(".$Name_ary[$i][$value._wci].")</td>";
                    else
                    {
                        echo "<td width='$ro%' class='nowrap nottry'></td>";
                    }
                }
                echo "<td width='$ro%' class='nowrap'>".get_time($Name_ary[$i][5])."</td>";//Penalty
                echo "</tr>\n";
            }
        }
        echo "</table>";
        include("footer.php");
?>
<script type="text/javascript">
<!--
var tables = document.getElementsByTagName('table');
table_Init(tables[2]);
-->
</script>
<?php
        $content=ob_get_contents(); //�õ�������������
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




