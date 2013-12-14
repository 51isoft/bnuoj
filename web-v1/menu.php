<table class="menu" width="98%">
<tr>
<th width="14%" class='menu'><a href='index.php' class='menu'>Home </a></th>
<th width="14%" class='menu'><a href='ranklist.php' class='menu'>Ranklist </a></th>
<th width="14%" class='menu'><a href='status.php' class='menu'>Status </a></th>
<? if ($ppage==""||$ppage==0) $ppage=1; ?>
<th width="14%" class='menu'><a href='problem_list.php?page=<?php echo $ppage; ?>' class='menu'>Problem </a></th>
<th width="14%" class='menu'><a href='contest_list.php' class='menu'>Contest </a></th>
<th width="14%" class='menu'><a href='submit.php' class='menu'>Submit </a></th>
<!--<th width="14%" class='menu'><a href='http://www.oiegg.com/forumdisplay.php?fid=407' target='_blank' class='menu'>BBS </a></th>-->
<th width="14%" class='menu'><a href='discuss.php'class='menu'>Discuss </a></th>
</tr>
</table>
</center><marquee direction="left" behavior="alternate" scrollamount="1"><span class="substitle"><?php echo $substitle; ?></span></marquee>
<script type="text/javascript">
$('marquee').marquee();
</script>
