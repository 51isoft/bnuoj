<table class="menu" width="98%">
<tr>
<th width="14%" class='menu'><a href='index.php' class='menu'>首页 </a></th>
<th width="14%" class='menu'><a href='ranklist.php' class='menu'>排名 </a></th>
<th width="14%" class='menu'><a href='status.php' class='menu'>提交记录 </a></th>
<? if ($ppage==""||$ppage==0) $ppage=1; ?>
<th width="14%" class='menu'><a href='problem_list.php?page=<?php echo $ppage; ?>' class='menu'>题目列表 </a></th>
<th width="14%" class='menu'><a href='submit.php' class='menu'>提交代码 </a></th>
</tr>
</table>
</center><MARQUEE DIRECTION=LEFT BEHAVIOR=alternate SCROLLAMOUNT=3 SCROLLDELAY=100 class="substitle"><?php echo $substitle; ?></MARQUEE>
