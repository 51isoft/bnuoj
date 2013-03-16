<?php
	include("header.php");
	echo "<center>";
	if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
		$query="select username from user";
		$result=mysql_query($query);
		while ($row=mysql_fetch_row($result)) {
			$qa="select runid from status where username='$row[0]'";
			$ra=mysql_query($qa);
			$na=mysql_num_rows($ra);
			$qua=mysql_query("update user set total_submit=$na where username='$row[0]'");
			if ($ra) echo "Update Submits for $row[0], OK! Submits: $na<br>";
			else echo "Update Submits for $row[0], Failed!<br>";
			$qa="select distinct pid from status where username='$row[0]' and result='Accepted'";
			$ra=mysql_query($qa);
			$na=mysql_num_rows($ra);
			$qua=mysql_query("update user set total_ac=$na where username='$row[0]'");
			if ($qua) echo "Update ACs for $row[0], OK! ACs: $na<br>";
			else echo "Update ACs for $row[0], Failed!<br>";
		}
	}
	echo "</center>";
	include("footer.php");
?>
