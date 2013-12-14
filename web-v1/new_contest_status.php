<?php
if ( isset($_GET['start']) ) $start = $_GET['start'];
else $start = "0";
if ( isset($_GET['cid']) ) $cid = $_GET['cid'];
else $cid = "0";
if ( isset($_GET['only']) ) $only = $_GET['only'];
else $only = "0";
$pagetitle="Status of Contest ".$cid;
include("cheader.php"); 
$query2="select unix_timestamp(end_time),hide_others from contest where cid='$cid'";
$result2=mysql_query($query2);
$row2=@mysql_fetch_row($result2);
$nowtime=time();
$fitimeu=$row2[0];
if ($row2[1]=='1'&&!(db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser))) $only="1";
echo "<center>";
include("cmenu.php");
if ($only=="0") $query = "select count(runid) from status where contest_belong=$cid";
else $query = "select count(runid) from status where contest_belong=$cid and username='$nowuser'";
//$sql = @mysql_query($query);
//$row = @mysql_fetch_array($sql);
$end = $start+$numperrow;
if (db_user_match($nowuser,$nowpass)&&db_user_isroot($nowuser)) echo "<a href='admin_contest_status.php?start=0&cid=$cid'> <strong>Only Show ACs (Admin)</strong> </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
if ($only=="0") echo "<a href='contest_status.php?start=0&cid=$cid&only=1'> <strong>Only View My Submits</strong> </a><br>";
else if ($row2[1]=='0') echo "<a href='contest_status.php?start=0&cid=$cid&only=0'> <strong>View All Submits</strong> </a><br>";
	else echo "<strong>In this contest, you can only view the submits of yourself.</strong><br>";
if ($start!=0) {
	if ($start<$numperrow) echo "<a href='contest_status.php?start=0&cid=$cid&only=$only'> <strong>&lt;&lt;Previous</strong> </a>";
	else {
		$prev=$start-$numperrow;
		echo "<a href='contest_status.php?start=$prev&cid=$cid&only=$only'> <strong>&lt;&lt;Previous</strong> </a>";
	}
}
$row[0]=$maxrunid;
if ($end < $row[0]) {
	echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='contest_status.php?start=$end&cid=$cid&only=$only'><strong>Next&gt;&gt;</strong> </a>";
}
$query="select isroot from user where username='$nowuser'";
$result = @mysql_query($query);
list($isroot)=@mysql_fetch_row($result);
if ($only=="0") $query="select status.username,status.runid,contest_problem.lable,status.result,status.language,status.time_used,status.memory_used,status.time_submit,contest_problem.cpid,status.isshared from status,contest_problem where status.contest_belong='$cid' and contest_problem.cid=status.contest_belong and contest_problem.pid=status.pid order by runid desc limit $start,$numperrow";
else $query="select status.username,status.runid,contest_problem.lable,status.result,status.language,status.time_used,status.memory_used,status.time_submit,contest_problem.cpid,status.isshared from status,contest_problem where status.contest_belong='$cid' and status.username='$nowuser' and contest_problem.cid=status.contest_belong and contest_problem.pid=status.pid order by runid desc limit $start,$numperrow";
$result = @mysql_query($query);
echo "<table class=display id=status>";
echo "\n<thead><tr>";
echo "<th width='11%'>User Name</th>";
echo "<th width='6%'>Run ID</th>";
echo "<th width='6%'>Problem</th>";
echo "<th width='20%'>Result</th>";
echo "<th width='8%'>Language</th>";
echo "<th width='8%'>Time</th>";
echo "<th width='8%'>Memory</th>";
echo "<th width='8%'>Length</th>";
echo "<th width='14%'>Time Submit</th>";
echo "</tr></thead>\n<tbody>\n";
list($locktu,$sttimeu,$fitimeu) = @mysql_fetch_array(mysql_query("SELECT unix_timestamp(lock_board_time),unix_timestamp(start_time),unix_timestamp(end_time) FROM contest WHERE cid = '$cid'"));
$nowtime=time();
while (list($uname,$runid,$lable,$res,$lang,$timeused,$memused,$timesubmit,$cpid,$isshared)=@mysql_fetch_row($result)) {
	$lang=match_lang($lang);
	echo "<tr>";
	echo "<td><center><a href=userinfo.php?name=$uname>$uname</a></center></td>";
	if ($isshared==true||$isroot==TRUE||strcasecmp($nowuser,$uname)==0) echo "<td><center><a href=show_source.php?runid=$runid&cid=$cid class='runid_link'>$runid</a></center></td>";
	else echo "<td><center>$runid</center></td>";
	echo "<td><center><a href=contest_problem_show.php?cpid=$cpid>$lable</a></center></td>";
	switch ($res) {
		case "Compile Error":
			echo "<td><center><strong><a href=show_ce_info.php?runid=$runid&cid=$cid class='ce'><span class='ce'>$res</span></a></strong></center></td>";
			break;
		case "Accepted":
			echo "<td><center><strong><span class='ac'>$res</span></strong></center></td>";
			break;
		case "Wrong Answer":
			echo "<td><center><strong><span class='wa'>$res</span></strong></center></td>";
			break;
		case "Runtime Error":
			echo "<td><center><strong><span class='re'>$res</span></strong></center></td>";
			break;
		case "Time Limit Exceed":
			echo "<td><center><strong><span class='tle'>$res</span></strong></center></td>";
			break;
		case "Memory Limit Exceed":
			echo "<td><center><strong><span class='mle'>$res</span></strong></center></td>";
			break;
		case "Output Limit Exceed":
			echo "<td><center><strong><span class='ole'>$res</span></strong></center></td>";
			break;
		case "Presentation Error":
			echo "<td><center><strong><span class='pe'>$res</span></strong></center></td>";
			break;
		case "Restricted Function":
			echo "<td><center><strong><span class='rf'>$res</span></strong></center></td>";
			break;
		default:
			echo "<td><center><strong>$res</strong></center></td>";
	}
	echo "<td><center>$lang</center></td>";
	if ($nowtime<=$fitimeu&&!($isroot==TRUE||(strcasecmp($uname,$nowuser)==0&&db_user_match($nowuser, $nowpass)))) echo "<td><center></center></td>";
	else echo "<td><center>$timeused ms</center></td>";
	if ($nowtime<=$fitimeu&&!($isroot==TRUE||(strcasecmp($uname,$nowuser)==0&&db_user_match($nowuser, $nowpass)))) echo "<td><center></center></td>";
	else echo "<td><center>$memused KB</center></td>";
	$query="select length(source) from status where runid=$runid";
	$tempresult=mysql_query($query);
	list($clength)=@mysql_fetch_row($tempresult);
	if ($nowtime<=$fitimeu&&!($isroot==TRUE||(strcasecmp($uname,$nowuser)==0&&db_user_match($nowuser, $nowpass)))) echo "<td><center></center></td>";
	else echo "<td><center>$clength Bytes</center></td>";
	echo "<td><center>$timesubmit</center></td>";
	echo "</tr>\n";
}
echo "</tbody>\n<tfoot></tfoot>\n</table>";
echo "</center>";
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

function striptags(a) {
    return a.replace(/(<([^>]+)>)/ig,"");
}

    var oTable = $('#status').dataTable( {
        "bProcessing": true,
        "bJQueryUI": true,
        "sDom": 'rt',
//        "sPaginationType": "full_numbers" ,
        "iDisplayLength": <?echo $numperrow;?>,
        "bLengthChange": false,
        "aaSorting": [ [1,'desc'] ],
        "aoColumnDefs": [
            { "sType": "num-html", "aTargets": [ 1,2 ] },
            { "sType": "html", "aTargets": [ 3 ] },
            { "bSortable": false, "aTargets": [ 0,1,2,3,4,5,6,7,8 ] }
        ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (striptags(aData[3])=="Compile Error") $(nRow).children().each(function(){$(this).addClass('gradeA');});
            else if (striptags(aData[3])=="Judge Error"||striptags(aData[3])=="Restricted Function") $(nRow).children().each(function(){$(this).addClass('gradeU');});
            else if (striptags(aData[3])!="Accepted") $(nRow).children().each(function(){$(this).addClass('gradeX');});
            else $(nRow).children().each(function(){$(this).addClass('gradeC');});
            return nRow;
        },
        "fnDrawCallback": function(){
            $('#status td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
            $('#status td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
        },
        "iDisplayStart": 0,
        "oSearch": {"sSearch": ""}
    } );

</script>

<?php include("footer.php"); ?>
