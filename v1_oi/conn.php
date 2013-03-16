<?php
	include("config.php");
	include("cookie.php");
	function db_connect() {
        	global $db_addr, $db_user, $db_pass, $db_table;
		$con = mysql_connect($db_addr,$db_user,$db_pass);
		mysql_query('SET NAMES "utf8"',$con);
		if (!$con) 	{
			return false;
		}
		$sql = mysql_select_db($db_table,$con);
		if (!$sql) return false;
	}
	function change_in($str){
		$change = array('<'=>'&lt;',);
		$s = strtr($str,$change);
		return $s;
	}
	/*nction change_out($str){
		$change = array('&lt;img'=>'<img','&lt;a href'=>'<a href','&lt;center>'=>'<center>','&lt;strong>'=>'<strong>','&lt;/'=>'</','  '=>' &nbsp;','&lt;font'=>'<font','\t'=>'&nbsp;&nbsp;&nbsp;&nbsp;','&lt;div>'=>'<div>','&lt;div '=>'<div ','&lt;p>'=>'<p>','&lt;sub>'=>'<sub>','&lt;sup>'=>'<sup>','&lt;i>'=>'<i>','&lt;b>'=>'<b>','&lt;tt>'=>'<tt>',
'&lt;ul>'=>'<ul>','&lt;li>'=>'<li>','&lt;td'=>'<td','&lt;th'=>'<th','&lt;table'=>'<table','&lt;tr'=>'<tr','&lt;thead'=>'<thead','&lt;tbody'=>'<tbody','&lt;p '=>'<p ','&lt;span '=>'<span ','&lt;pre>'=>'<pre>','&lt;blockquote>'=>'<blockquote>','&lt;!--'=>'<!--','&lt;ol>'=>'<ol>','&lt;ol '=>'<ol ','&lt;nobr>'=>'<nobr>','&lt;h4>'=>'<h4>','&lt;sup '=>'<sup ','&lt;sub '=>'<sub ');
		$s = strtr($str,$change);
	$s=nl2br($s);	return $s;
	}*/
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
	function db_user_match($user, $password) {
		$result = mysql_query("select password from user where username = '$user'");
		$row = mysql_fetch_row($result);
		if ($row[0] == $password) return true;
		else return false;
	}
	function db_mail_user_match($user,$mailid) {
		$res=mysql_query("select reciever from mail where mailid='$mailid'");
		list($rec)=mysql_fetch_array($res);
		if ($user==$rec) return true;
		else return false;
	}
	function db_change_last_login_time($name) {
		$now = time();
		$today=date("Y-m-d G:i:s",$now);
		$ip=getenv('REMOTE_ADDR');
		mysql_query("update user set last_login_time='$today', ipaddr='$ip' where username='$name' ");
	}
	function db_insert_submit_time($runid) {
		$now = time();
		$today=date("Y-m-d G:i:s",$now);
		mysql_query("update status set time_submit='$today' where runid=$runid ");
	}
	function db_contest_exist($cid) {
		$result = mysql_query("select * from contest where cid = $cid");
		$row = @mysql_num_rows($result);
		if ($row==1) return true;
		else return false;
	}
	function db_contest_private($cid) {
		$result = mysql_query("select isprivate from contest where cid = $cid");
		$row = mysql_fetch_row($result);
		if ($row[0]==1) return true;
		else return false;
	}
	function db_user_in_contest($cid,$user) {
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
	function db_contest_running($cid) {
		if (!db_contest_exist($cid)) return false;
		$nowtime=time();
		$result = mysql_query("select unix_timestamp(start_time),unix_timestamp(end_time) from contest where cid = '$cid'");
		$row = mysql_fetch_row($result);
		if ($nowtime>=$row[0]&&$nowtime<=$row[1]) return true;
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
		$result = mysql_query("select unix_timestamp(end_time) from contest where cid = '$cid'");
		$row = mysql_fetch_row($result);
		if ($nowtime>=$row[0]) return true;
		else return false;
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
	function match_lang($lang) {
		switch ($lang) {
			case "1":
				$lang="G++";
				break;
			case "2":
				$lang="GCC";
				break;
			case "3":
				$lang="JAVA";
				break;
			case "4":	
				$lang="Pascal";
				break;
			case "5":
				$lang="Python";
				break;
			case "6":
				$lang="C#";
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
		}
		return $lang;
    }
    function convert_str ($str) { 
       if ($str===null) return "";  
       if (get_magic_quotes_gpc()) 
       { 
          return $str; 
       } 
       return mysql_real_escape_string ($str); 
    }
    function convert_all_str($arr) {
        if ($arr==null) return null;
        if (!is_array($arr)) {
            return convert_str($arr);
        }
        foreach ($arr as $k=>$a) {
            if (is_array($a)) $arr[$k]=convert_all_str($a);
            else $arr[$k]=convert_str($a);
        }
        return $arr;
    }
   	db_connect();
    $_COOKIE=convert_all_str($_COOKIE);
    $_GET=convert_all_str($_GET);
    $_POST=convert_all_str($_POST);
?>
