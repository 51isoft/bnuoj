<?php
    $pagetitle="Standard Contest List";
	include("header.php");
	$nowt = time();
	if ( isset($_GET['page']) ) $page = $_GET['page'];
	else $page = 1;
	$start=($page-1)*$conperpage;
?>
<center>
<a href="contest_list.php"><font size="+2" color=red>[Standard Contest]</font></a> <a href="vcontest_list.php"><font size="+2">[Virtual Contest]</font></a>
<table class="display" id="clist">
<thead>
<tr>
<th width='5%'> ID </th>
<th width='45%'> Title </th>
<th width='15%'> Start Time </th>
<th width='15%'> End Time </th>
<th width='10%'> Type </th>
<th width='10%'> Status </th>
</tr>
</thead>
<tbody>
<?php
	$s = "SELECT cid,title,start_time,end_time,isprivate FROM contest WHERE UNIX_TIMESTAMP( start_time ) <= $nowt and UNIX_TIMESTAMP( end_time ) >= $nowt and isvirtual=0 order by cid desc";
	$sql = mysql_query($s);
	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
		echo "<td><a href=contest_show.php?cid=$row[0]> $row[0] </a> </td>";
		echo "<td><a href=contest_show.php?cid=$row[0]> $row[1] </a> </td>";
		echo "<td> $row[2] </td>";
		echo "<td> $row[3] </td>";
		if ($row[4]=="0") echo "<td> <span class='cpublic'>Public</span></td>";
		else echo "<td> <span class='cprivate'>Private</span></td>";
		echo "<td> <span class='crunning'>Running </span></td>";
		echo "</tr>\n";
	}
	$s = "SELECT cid,title,start_time,end_time,isprivate FROM contest WHERE UNIX_TIMESTAMP( start_time ) >$nowt and isvirtual=0 order by cid desc";
	$sql = mysql_query($s);
	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
		echo "<td><a href=contest_show.php?cid=$row[0]> $row[0] </a> </td>";
		echo "<td><a href=contest_show.php?cid=$row[0]> $row[1] </a> </td>";
		echo "<td> $row[2] </td>";
		echo "<td> $row[3] </td>";
		if ($row[4]=="0") echo "<td> <span class='cpublic'>Public</span></td>";
		else echo "<td> <span class='cprivate'>Private</span></td>";
		echo "<td> <span class='cscheduled'>Scheduled </span></td>";
		echo "</tr>\n";
	}
	$s = "SELECT cid,title,start_time,end_time,isprivate FROM contest WHERE UNIX_TIMESTAMP( end_time ) <= $nowt and isvirtual=0 order by cid desc";
	$sql = mysql_query($s);
	while ( $row = @mysql_fetch_array($sql) ) {
		echo "<tr>";
		echo "<td><a href=contest_show.php?cid=$row[0]> $row[0] </a> </td>";
		echo "<td><a href=contest_show.php?cid=$row[0]> $row[1] </a> </td>";
		echo "<td> $row[2] </td>";
		echo "<td> $row[3] </td>";
		if ($row[4]=="0") echo "<td> <span class='cpublic'>Public</span></td>";
		else echo "<td> <span class='cprivate'>Private</span></td>";
		echo "<td> <span class='cpassed'>Passed </span></td>";
		echo "</tr>\n";
	}
	echo "</tbody>\n<tfoot></tfoot>\n</table>";
    echo "</center>\n";
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

jQuery.fn.dataTableExt.oSort['num-html-asc']  = function(a,b) {
    var x = a.replace( /<.*?>/g, "" );
    var y = b.replace( /<.*?>/g, "" );
    x = parseFloat( x );
    y = parseFloat( y );
    return ((x < y) ? -1 : ((x > y) ?  1 : 0));
};

jQuery.fn.dataTableExt.oSort['num-html-desc'] = function(a,b) {
    var x = a.replace( /<.*?>/g, "" );
    var y = b.replace( /<.*?>/g, "" );
    x = parseFloat( x );
    y = parseFloat( y );
    return ((x < y) ?  1 : ((x > y) ? -1 : 0));
};

    var oTable = $('#clist').dataTable( {
        "bProcessing": true,
        "bJQueryUI": true,
        "sDom": '<"H"pf>rt<"F"ilp>',
        "sPaginationType": "full_numbers" ,
        "iDisplayLength": 50,
        "aaSorting": [ [0,'desc'] ],
        "aoColumnDefs": [
            { "sType": "num-html", "aTargets": [ 0,1,4,5 ] }
        ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (aData[5]=="Passed") $(nRow).children().each(function(){$(this).addClass('gradeA');});
            else if (aData[5]=="Running") $(nRow).children().each(function(){$(this).addClass('gradeX');});
            else $(nRow).children().each(function(){$(this).addClass('gradeC');});
            return nRow;
        },
        "fnDrawCallback": function(){
            $('#clist td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
            $('#clist td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
            $('#clist .sorting_1').each(function(){$(this).removeClass('gradeA');$(this).removeClass('gradeB');$(this).removeClass('gradeC');
                                                   $(this).removeClass('gradeU');$(this).removeClass('gradeX');$(this).addClass('gradeCC');});
        },
        "iDisplayStart": 0,
        "oSearch": {"sSearch": ""}
    } );

</script>
<?
	include("footer.php");
?>
