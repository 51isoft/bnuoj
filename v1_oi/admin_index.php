<?php include("header.php");
	if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) {
?>
<center>
<br>
<h2>管理员界面</h2>
<br>
<strong><a href="admin_problem_add.php">点击：增加题目</a></strong>
<br><br>
<form id="pm" name="pm" method="get" action="admin_problem_add.php">
	<strong>修改题目, 题目ID号为:</strong><input type="text" name="pid" value=""/><input type="submit" name="Submit" value="修改" />
</form>
<a href="admin_rejudge.php"><strong>点击：进入重新判题功能模块</strong></a>
<br><br>
<strong><a href="admin_sync_info.php">同步用户的过题信息</a>, <font color='red'>该操作需要大量计算，除非判题系统出现异常造成用户信息不同步，否则请勿使用。</font></strong>
<br><br>
<strong><a href="admin_sync_problem.php">同步题目的统计数据</a>, <font color='red'>该操作需要大量计算，除非判题系统出现异常造成用户信息不同步，否则请勿使用。</font></strong>
<br><br>
</center>

<?php }
include("footer.php"); ?>
