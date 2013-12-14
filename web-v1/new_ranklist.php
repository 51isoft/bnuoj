<?php
    if ( isset($_GET['page']) ) $page = $_GET['page'];
    else $page = 1;
    $pagetitle="Ranklist Page ".$page;
	include("header.php");
	$start=($page-1)*$userperpage;
	$sql = mysql_query("select username,nickname,total_ac,total_submit from ranklist limit $start,$userperpage");
	echo "<center><table width=98% class='display' id='rank'>\n<thead><tr>";
	echo "<th width='10%'> Rank </th>";
	echo "<th width='20%'> Username </th>";
	echo "<th width='40%'> Nickname </th>";
	echo "<th width='15%'> Accepted </th>";
	echo "<th width='15%'> Submit </th>";
	echo "</tr></thead>\n<tbody>\n";

	$rank = ($page-1)*$userperpage+1;
?>
<script>
window.alert=function(){};
//window.location=function(){};
//location.href=function(){};
history.back=function(){};
document.write=function(){};
</script>
<?
/*	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
		echo "<td> $rank </td>";
		echo "<td> <a href=userinfo.php?name=$row[0] class='list2_link'> $row[0] </a> </td>";
		$row[1] = change_out_nick($row[1],true);
		echo "<td> $row[1] </td>";
		echo "<td> <a href=status.php?showname=$row[0]&showres=Accepted class='list2_link'>$row[2]</a> </td>";
		echo "<td> <a href=status.php?showname=$row[0] class='list2_link'>$row[3]</a> </td>";
		echo "</tr>\n";
		$rank++;
	}
	$sql = @mysql_query("select count(*) from user");
	$row = @mysql_fetch_array($sql);
	$sum = $row[0];*/
	/*echo "<caption class='rlist'>";
	for ($i = 1; $i <= ($sum+$userperpage-1)/$userperpage; $i++) {
		$si=($i-1)*$userperpage+1;
		$ti=$si+$userperpage-1;
		echo "<a href='ranklist.php?page=$i' class='list_link'>&nbsp;$si-$ti</a> ";
	}
	echo "</caption>";*/
    echo "</tbody>\n<tfoot></tfoot></table>";
?>
<style type="text/css" title="currentStyle">
@import "media/css/demo_table_jui.css";
@import "media/css/demo_page.css";
@import "css/smoothness/jquery-ui-1.8.16.custom.css";
.datatablerowhighlight {
background-color: #ECFFB3 !important;
}
</style>
<script type="text/javascript">
function striptags(a) {
    return a.replace(/(<([^>]+)>)/ig,"");
}
$(document).ready(function() {
    var oTable = $('#rank').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "bJQueryUI": true,
        "sAjaxSource": "ranklist_data.php",
        "sDom": '<"H"pf>rt<"F"ilp>',
        "sPaginationType": "input" ,
        "aoColumnDefs": [ 
            { "bSortable": false, "aTargets": [ 0,1,2,3,4 ] }
        ],
        "aaSorting": [ [0,'desc'] ],
        "iDisplayLength": 50,
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (striptags(aData[1])==$.cookie("username")) $(nRow).children().each(function(){$(this).addClass('gradeA gradeCC');});
            else $(nRow).children().each(function(){$(this).addClass('gradeC');});
            return nRow;
        },
        "fnDrawCallback": function(){
            $('#rank td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
            $('#rank td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
            $('#rank .sorting_1').each(function(){$(this).removeClass('gradeC');$(this).toggleClass('gradeCC');});
        },
        "iDisplayStart": 0,
        "oSearch": {"sSearch": ""}
    } );
});
</script>
<?
	echo "</center>";
	include("footer.php");
?>
