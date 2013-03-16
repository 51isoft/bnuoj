<?php include("header.php");
	if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
<center>
<br>
<h2>Admin Index Page, Please Be Aware.</h2>
<br><br>
<strong><a href="admin_substitle_modify.php">Notification Modify</a></strong>
<br><br>
<strong><a href="admin_problem_add.php">Problem Add</a></strong>
<br><br>
<form id="pm" name="pm" method="get" action="admin_problem_add.php">
	<strong>Problem Modify, PID:</strong><input type="text" name="pid" value=""/><input type="submit" name="Submit" value="Modify" />
</form>
<strong><a href="admin_unlock_contest_problem.php">Unlock Contest Problem</a></strong>
<br><br>
<strong><a href="admin_lock_contest_problem.php">Lock Contest Problem</a></strong>
<br><br>
<strong><a href="admin_share_contest_code.php">Share Contest Code</a></strong>
<br><br>
<strong><a href="admin_unshare_contest_code.php">Unshare Contest Code</a></strong>
<br><br>
<strong><a href="admin_contest_add.php">Contest Add</a></strong>
<br><br>
<form id="cm" name="cm" method="get" action="admin_contest_add.php">
	<strong>Contest Information Modify, CID:</strong><input type="text" name="cid" value=""/><input type="submit" name="Submit" value="Modify" />
</form>
<form id="ca" name="ca" method="get" action="admin_contest_problem_add.php">
	<strong>Contest Problem Add, CID:</strong><input type="text" name="cid" value=""/><input type="submit" name="Submit" value="Add" />
</form>
<form id="cu" name="cu" method="get" action="admin_contest_user_add.php">
	<strong>Contest User Add, CID:</strong><input type="text" name="cid" value=""/><input type="submit" name="Submit" value="Add" />
</form>
<form id="cc" name="cc" method="get" action="admin_contest_clarify.php">
	<strong>Contest Clarify, CID:</strong><input type="text" name="cid" value=""/><input type="submit" name="Submit" value="Go" />
</form>
<a href="admin_rejudge.php"><strong>Rejudge</strong></a>
<br><br>
<strong><a href="admin_sync_info.php">Sync Userinfo</a>, <font color='red'>DON'T use it unless you're clear about what you are doing.</font></strong>
<br><br>
<strong><a href="admin_sync_problem.php">Sync Problem Info</a>, <font color='red'>DON'T use it unless you're clear about what you are doing.</font></strong>
<br><br>
</center>

<?php }
include("footer.php"); ?>
