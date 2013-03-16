<table class="menu" width="98%">
<tr>
<th width="14%" class='menu'><a href='index.php' class='menu'>Home </a></th>
<th width='14%' class='menu'><a href='standings/contest_standing.php?cid=<?php echo $cid; ?>' class='menu'>Standing </a></th>
<th width='14%' class='menu'><a href='standings/merge_contest_standing.php?cid=<?php echo $cid; ?>' class='menu'>Merge Standing</a></th>
<th width="14%" class='menu'><a href='contest_status.php?cid=<?php echo $cid; ?> 'class='menu'>Status </a></th>
<th width="14%" class='menu'><a href='contest_show.php?cid=<?php echo $cid; ?>' class='menu'>Problem </a></th>
<?
if (!db_user_isroot($nowuser)) {
?>
<th width="14%" class='menu'><a href='contest_clarify.php?cid=<?php echo $cid; ?>' class='menu'>Clarify </th>
<?
}
else {
?>
<th width="14%" class='menu'><a href='admin_contest_clarify.php?cid=<?php echo $cid; ?>' class='menu'>Clarify(Admin) </th>

<?
}
?>
</tr>
</table>
<marquee direction="left" behavior="alternate" scrollamount="1"><span class="substitle"><?php echo $substitle; ?></span></marquee>
<script type="text/javascript">
$('marquee').marquee();
</script>

