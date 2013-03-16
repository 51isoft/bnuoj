<?php
    include_once("config.php");
    include_once("cookie.php");
//    include_once('latexrender/latex.php');
    function latex_content($a) { return $a; }
    function pwd($a) {
        return sha1("fdsoijfdows".md5($a."8943udo1=_*()3e2"));
    }
    function db_connect() {
            global $db_addr, $db_user, $db_pass, $db_table;
        $con = mysql_connect($db_addr,$db_user,$db_pass);
        mysql_query('SET NAMES "utf8"',$con);
        if (!$con)     {
            return false;
        }
        $sql = mysql_select_db($db_table,$con);
        if (!$sql) return false;
    }
    function db_user_insert($row) {
        $now = time();
        $today=date("Y-m-d G:i:s",$now);
        $row[1] = sha1(md5($row[1]));

        $row[2] = change_in($row[2]);
        $row[3] = change_in($row[3]);
        $row[4] = change_in($row[4]);

        $sql = mysql_query("insert into user (username,password,nickname,school,email,register_time) values ('$row[0]','$row[1]','$row[2]','$row[3]','$row[4]','$today')");
        if (!$sql)
            return false;
        else
            return true;
    }
    function db_user_exist($username) {
        $result = mysql_query("select username from user where username = '$username'");
        $row = @mysql_num_rows($result);
        if ($row==1) return true;
        else return false;
    }
    function db_get_unread_mail_number($username) {
        $result = mysql_query("select count(*) from mail where status=false and reciever='$username'");
        $row = mysql_fetch_array($result);
        return $row[0];
    }
    function db_user_isroot($username) {
        if (!db_user_exist($username)) return false;
        $result = mysql_query("select isroot from user where username = '$username'");
        $row = mysql_fetch_array($result);
        if ($row[0]==1) return true;
        else return false;
    }
    function db_user_iscodeviewer($username) {
        if (!db_user_exist($username)) return false;
        if (db_user_isroot($username)) return true;
        $result = mysql_query("select isroot from user where username = '$username'");
        $row = mysql_fetch_array($result);
        if ($row[0]==2) return true;
        else return false;
    }
    function db_user_match($user, $password) {
        //if ($user==""||$password="") return false;
        $result = mysql_query("select * from user where username = '$user' and password='$password'");
        $row = @mysql_num_rows($result);
        if ($row == 1) return true;
        else return false;
    }
    function db_mail_user_match($user,$mailid) {
        $res=mysql_query("select count(*) from mail where mailid='$mailid' and (reciever='$user' or sender='$user')");
        list($rec)=mysql_fetch_array($res);
        if ($rec) return true;
        else return false;
    }
    function db_change_last_login_time($name) {
        $now = time();
        $today=date("Y-m-d G:i:s",$now);
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        if($ip=="") $ip = $_SERVER['REMOTE_ADDR'];
        //$ip=getenv('REMOTE_ADDR');
        mysql_query("update user set last_login_time='$today', ipaddr='$ip' where username='$name' ");
    }
    function db_insert_submit_time($runid) {
        $now = time();
        $today=date("Y-m-d G:i:s",$now);
        mysql_query("update status set time_submit='$today' where runid='$runid' ");
    }
    function db_contest_exist($cid) {
        $result = mysql_query("select * from contest where cid = '$cid'");
        $row = @mysql_num_rows($result);
        if ($row==1) return true;
        else return false;
    }
    function db_get_contest_title($cid) {
        $result = mysql_query("select title from contest where cid = '$cid'");
        $row = @mysql_fetch_array($result);
        return $row[0];
    }
    function db_contest_private($cid) {
        $result = mysql_query("select isprivate from contest where cid = '$cid'");
        $row = mysql_fetch_row($result);
        if ($row[0]==1) return true;
        else return false;
    }
    function db_contest_password($cid) {
        $result = mysql_query("select isprivate from contest where cid = '$cid'");
        $row = mysql_fetch_row($result);
        if ($row[0]==2) return true;
        else return false;
    }
    function db_contest_pass_match($cid) {
        $result = mysql_query("select password from contest where cid = '$cid'");
        $row = mysql_fetch_row($result);
        if ($row[0]==$_COOKIE["contest_pass_$cid"]) return true;
        else return false;
    }
    function db_user_in_contest($cid,$user) {
        global $nowuser,$nowpass;
        if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) return true;
        if (db_contest_ispublic($cid)) return true;
        if (db_contest_password($cid)) {
            if (db_contest_pass_match($cid)) return true;
            else return false;
        }
        $result = mysql_query("select * from contest_user where cid = '$cid' and username='$user'");
        $row = mysql_num_rows($result);
        if ($row>=1) return true;
        else return false;
    }
    function db_lable_in_contest($cid,$lable) {
        $result = mysql_query("select * from contest_problem where cid = '$cid' and lable='$lable'");
        $row = mysql_num_rows($result);
        if ($row==1) return true;
        else return false;
    }
    function db_contest_has_cha($cid) {
        $result = mysql_query("select has_cha from contest where cid = '$cid'");
        $row = @mysql_fetch_array($result);
        if ($row[0]=="0") return false;
        else return true;
    }
    function db_contest_ispublic($cid) {
        if (!db_contest_exist($cid)) return false;
        $result = mysql_query("select isprivate from contest where cid = '$cid'");
        $row = mysql_fetch_row($result);
        if ($row[0]==0) return true;
        else return false;
    }
    function db_contest_type($cid) {
        if (!db_contest_exist($cid)) return false;
        $result = mysql_query("select type from contest where cid = '$cid'");
        $row = mysql_fetch_row($result);
        return $row[0];
    }
    function db_contest_running($cid) {
        if (!db_contest_exist($cid)) return false;
        $nowtime=time();
        $result = mysql_query("select unix_timestamp(start_time),unix_timestamp(end_time),has_cha,unix_timestamp(challenge_end_time) from contest where cid = '$cid'");
        $row = mysql_fetch_row($result);
        if ($nowtime>=$row[0]&&$nowtime<=$row[1]) return true;
        else if ($row[2]==1&&$nowtime>=$row[0]&&$nowtime<=$row[3]) return true;
        else return false;
    }
    function db_contest_started($cid) {
        if (!db_contest_exist($cid)) return false;
        $nowtime=time();
        $result = mysql_query("select unix_timestamp(start_time) from contest where cid = '$cid'");
        $row = mysql_fetch_row($result);
        if ($nowtime>=$row[0]) return true;
        else return false;
    }
    function db_contest_passed($cid) {
        if (!db_contest_exist($cid)) return false;
        $nowtime=time();
        $result = mysql_query("select unix_timestamp(end_time),has_cha,unix_timestamp(challenge_end_time) from contest where cid = '$cid'");
        $row = mysql_fetch_row($result);
        if ($row[1]==1&&$nowtime>=$row[2]) return true;
        else if ($row[1]==0&&$nowtime>=$row[0]) return true;
        else return false;
    }
    function db_contest_challenging($cid) {
        if (!db_contest_exist($cid)||!db_contest_has_cha($cid)) return false;
        $nowtime=time();
        $result = mysql_query("select unix_timestamp(challenge_start_time),has_cha,unix_timestamp(challenge_end_time) from contest where cid = '$cid'");
        $row = mysql_fetch_row($result);
        if ($row[1]==1&&$nowtime>=$row[0]&&$nowtime<=$row[2]) return true;
        else return false;
    }
    function db_contest_intermission($cid) {
        if (!db_contest_exist($cid)||!db_contest_has_cha($cid)) return false;
        $nowtime=time();
        $result = mysql_query("select unix_timestamp(end_time),has_cha,unix_timestamp(challenge_start_time) from contest where cid = '$cid'");
        $row = mysql_fetch_row($result);
        if ($row[1]==1&&$nowtime>=$row[0]&&$nowtime<=$row[2]) return true;
        else return false;
    }
    function db_contest_user_has($cid,$username) {
        $result = mysql_query("select * from contest_user where username = '$username' and cid='$cid'");
        $row = @mysql_num_rows($result);
        if ($row==1) return true;
        else return false;
    }
    function db_contest_isvirtual($cid) {
        list($result) = mysql_fetch_array(mysql_query("select isvirtual from contest where cid='$cid'"));
        return $result;
    }
    function db_contest_owner($cid) {
        list($result) = mysql_fetch_array(mysql_query("select owner from contest where cid='$cid'"));
        return $result;
    }
    function db_problem_hide($pid) {
        $result = mysql_query("select hide from problem where pid = '$pid'");
        $row = mysql_fetch_row($result);
        if ($row[0]=='0') return false;
        else return true;
    }
    function db_problem_exist($pid) {
        $result = mysql_query("select * from problem where pid = '$pid'");
        if (mysql_num_rows($result)==0) return false;
        else return true;
    }
    function db_problem_isvirtual($pid) {
        list($result) = mysql_fetch_array(mysql_query("select isvirtual from problem where pid='$pid'"));
        return $result;
    }
    function sc ($ta,$tb) {
        $diff = $ta-$tb;
        if($diff <0){
            $diff = -$diff;
        }
        $diff_hour  = (int)($diff/3600);
        $diff_minute = (int)(($diff-$diff_hour*3600)/60);
        $diff_second = $diff-$diff_hour*3600-$diff_minute*60;
        return $diff_hour.":".$diff_minute.":".$diff_second;
    }

    function so_cmp ($ac_a,$ac_b,$pt_a,$pt_b) {
        if($ac_a > $ac_b){
            return 0 ;
        }
        else if($ac_a<$ac_b){
            return 1 ;
        }
        else if($pt_a>$pt_b){
            return 1;
        }
        else{
            return 0;
        }
    }
    function match_shjs($lang) {
        switch ($lang) {
            case "1":
                $lang="cpp";
                break;
            case "2":
                $lang="c";
                break;
            case "3":
                $lang="java";
                break;
            case "4":    
                $lang="pascal";
                break;
            case "5":
                $lang="python";
                break;
            case "6":
                $lang="csharp";
                break;
            case "7":
                $lang="cpp";
                break;
            case "8":
                $lang="perl";
                break;
            case "9":
                $lang="ruby";
                break;
            case "10":
                $lang="cpp";
                break;
            case "11":
                $lang="sml";
                break;
            case "12":
                $lang="cpp";
                break;
            case "13":
                $lang="c";
                break;
            case "14":
                $lang="c";
                break;
            case "15":
                $lang="cpp";
                break;
        }
        return $lang;
    }
    function match_lang($lang) {
        switch ($lang) {
            case "1":
                $lang="GNU C++";
                break;
            case "2":
                $lang="GNU C";
                break;
            case "3":
                $lang="Oracle Java";
                break;
            case "4":   
                $lang="Free Pascal";
                break;
            case "5":
                $lang="Python";
                break;
            case "6":
                $lang="C# (Mono)";
                break;
            case "7":
                $lang="Fortran";
                break;
            case "8":
                $lang="Perl";
                break;
            case "9":
                $lang="Ruby";
                break;
            case "10":
                $lang="Ada";
                break;
            case "11":
                $lang="Standard ML";
                break;
            case "12":
                $lang="Visual C++";
                break;
            case "13":
                $lang="Visual C";
                break;
            case "14":
                $lang="CLang";
                break;
            case "15":
                $lang="CLang++";
                break;
        }
        return $lang;
    }

    function db_get_substitle() {
        global $substitle;
        $que="select substitle from config";
        list($substitle)=mysql_fetch_array(mysql_query($que));
    }
    function change_in($str){
        $change = array('<'=>'&lt;',);
        $s = strtr($str,$change);
        return $s;
    }
    function change_out_nick($str){
        $change = array(
            '&lt;'=>'<',
        );
        $s=strtr($str,$change);
        $s=strip_tags(nl2br($s));
        return htmlspecialchars($s);
    }
    function convert_str ($str) { 
       /* Automatic escaping is highly deprecated, but many sites do it 
          anyway to protect themselves from stupid customers. */ 
       if ($str===null) return "";  
       if (get_magic_quotes_gpc()) 
       { 
          /* Apache automatically escaped the string already. */ 
          return $str; 
       } 
       /* Replace the following line with whatever function you prefer 
           to call to escape a string. */ 
       return mysql_real_escape_string ($str); 
    }
    function getshort($res) {
      switch ($res) {
        case "Compile Error":
            return "ce";
            break;
        case "Accepted":
            return "ac";
            break;
        case "Wrong Answer":
            return "wa";
            break;
        case "Runtime Error":
            return "re";
            break;
        case "Time Limit Exceed":
            return "tle";
            break;
        case "Memory Limit Exceed":
            return "mle";
            break;
        case "Output Limit Exceed":
            return "ole";
            break;
        case "Presentation Error":
            return "pe";
            break;
        case "Challenged":
            return "wa";
            break;
        case "Pretest Passed":
            return "ac";
            break;
        case "Restricted Function":
            return "rf";
            break;
        default:
            return "";
      }

    }
    function cal_point($row,$t) {
        if ($t<0) $t=0;
        if ($row['type']==1) {
            $pt=$row['base']-intval($t)/60*$row['para_a'];
            if ($pt<$row['minp']) $pt=$row['minp'];
        }
        else if ($row['type']==2) {
            //$t=intval(intval($t)/60);
            $t=intval($t);
            $pt=($row['base']*(
                    doubleval($row['para_a'])+
                    doubleval($row['para_b'])*doubleval($row['para_c'])*doubleval($row['para_c'])
                    /
                    (doubleval($row['para_d'])*$t*$t+doubleval($row['para_c'])*doubleval($row['para_c']))
                )
            );
            if ($pt<$row['minp']) $pt=$row['minp'];
        }
        return round($pt,2);
    }

    db_connect();
    db_get_substitle();
?>
