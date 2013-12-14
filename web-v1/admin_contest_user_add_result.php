<?php
include("header.php");
echo '<center>';
$cid=$_GET['cid'];
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
	$names = $_POST['names'];
	$len=strlen($names);
	$st=$fi=0;
	if (!db_contest_exist($cid)) {
		echo "Contest $cid does NOT exist!<br>";
	}
	else {
		while ($fi<=$len) {
			if ($names[$fi]=='|'||$fi==$len) {
				$tmp=substr($names,$st,$fi-$st);
				if (!db_user_exist($tmp)) {
					echo "User $tmp does NOT exists.<br>";
				}
				else if (db_contest_user_has($cid,$tmp)) {
					echo "User $tmp has already been in this contest.<br>";
				}
				else {
					$que="insert into contest_user set cid=$cid, username='$tmp'";
					$res=mysql_query($que);
					if (!res) {
						echo "Failed when add $tmp.<br>";
					}
					else echo "Successfully added $tmp into contest $cid.<br>";
				}
				$st=$fi+1;
			}
			$fi++;
		}
	}
}
echo '</center>';
include("footer.php");

?>
