<?php $pagetitle="Online Status"; ?>
<?php include("header.php"); ?>
<?php
echo "<center>";
if ( isset($_GET['start']) ) $start = $_GET['start'];
else $start = "0";
if ( isset($_GET['showname']) ) $showname = $_GET['showname'];
else $showname = "";
if ( isset($_GET['showpid']) ) $showpid = $_GET['showpid'];
else $showpid = "";
if ( isset($_GET['showres']) ) $showres = $_GET['showres'];
else $showres = "";
if ( isset($_GET['showlang']) ) $showlang = $_GET['showlang'];
else $showlang = "";
/*if ( isset($_GET['only']) ) $only = $_GET['only'];
else $only = "0";
if ($only=="0") $query = "select count(runid) from status where contest_belong=0";
else $query = "select count(runid) from status where contest_belong=0 and username='$nowuser'";*/
$query = "select count(*) from status where (contest_belong=0 or contest_belong=any(select cid from contest where end_time<now())) ";
if ($showname!="") $query=$query."and username='$showname' ";
if ($showpid!="") $query=$query."and pid='$showpid' ";
if ($showres!="") $query=$query."and result='$showres' ";
if ($showlang!="") $query=$query."and language='$showlang' ";
//$sql = @mysql_query($query);
//$row = mysql_fetch_array($sql);
$end = $start+$numperrow;
if ($showname=="") $showname2=$nowuser; else $showname2=$showname;
/*if ($only=="0") echo "<a href='status.php?start=0&only=1'> <strong>Only View My Submits</strong> </a><br>";
else echo "<a href='status.php?start=0&only=0'> <strong>View All Submits</strong> </a><br>";*/
echo "<form action='status.php' method=get>";
echo "<table class='status'>";
echo "<tr><td class='status'><strong>Only Show:</strong></td><td class='status'>Username：</td><td class='status'><input type='text' style='width:100px;height:24px;font:14px' name='showname' value='$showname2'></td><td class='status'>Problem：</td><td class='status'><input type='text' style='width:100px;height:24px;font:14px' name='showpid' value='$showpid'></td><td class='status'>Result：</td><td class='status'>";
echo "<select size=1 name=showres style='font:14px' accesskey=l>
<option value='' selected>All</option>
<option value='Accepted'>Accepted</option>
<option value='Wrong Answer'>Wrong Answer</option>
<option value='Runtime Error'>Runtime Error</option>
<option value='Time Limit Exceed'>Time Limit Exceed</option>
<option value='Memory Limit Exceed'>Memory Limit Exceed</option>
<option value='Output Limit Exceed'>Output Limit Exceed</option>
<option value='Presentation Error'>Presentation Error</option>
<option value='Restricted Function'>Restricted Function</option>
<option value='Compile Error'>Compile Error</option>
</select>";
echo "</td><td class='status'>Language：</td><td class='status'>";
echo "<select size=1 name=showlang style='font:14px' accesskey=l>
<option value='' selected>All</option>
<option value=1>GNU C++</option>
<option value=2>GNU C</option>
<option value=3>Oracle Java</option>
<option value=4>Free Pascal</option>
<option value=5>Python</option>
<option value=6>C# (Mono)</option>
<option value=7>Fortran</option>
<option value=8>Perl</option>
<option value=9>Ruby</option>
<option value=10>Ada</option>
<option value=11>SML</option>
<option value=12>Visual C++</option>
<option value=13>Visual C</option>
</select>";
echo "<td class='status'><input type='submit' size=10 value='Show'></td></tr>";
echo "</table>";
echo "</form>";
if ($start!=0) {
	if ($start<$numperrow) echo "<a href='status.php?showname=$showname&showpid=$showpid&showres=$showres&showlang=$showlang&start=0'> <strong>&lt;&lt;Previous</strong> </a>";
	else {
		$prev=$start-$numperrow;
		echo "<a href='status.php?start=$prev&showname=$showname&showpid=$showpid&showres=$showres&showlang=$showlang'> <strong>&lt;&lt;Previous</strong> </a>";
	}
}
$row[0]=$maxrunid;
if ($end < $row[0]) {
	echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='status.php?start=$end&showname=$showname&showpid=$showpid&showres=$showres&showlang=$showlang'><strong>Next&gt;&gt;</strong> </a>";
}
$query="select username,runid,pid,result,language,time_used,memory_used,time_submit,isshared from status where (contest_belong=0 or contest_belong=any(select cid from contest where end_time<now())) ";
/*if ($only=="0") $query="select username,runid,pid,result,language,time_used,memory_used,time_submit from status where contest_belong=0 order by runid desc limit $start,$numperrow";
else $query="select username,runid,pid,result,language,time_used,memory_used,time_submit from status where contest_belong=0 and username='$nowuser' order by runid desc limit $start,$numperrow";*/
if ($showname!="") $query=$query."and username='$showname' ";
if ($showpid!="") $query=$query."and pid='$showpid' ";
if ($showres!="") $query=$query."and result='$showres' ";
if ($showlang!="") $query=$query."and language='$showlang' ";
$query=$query."order by runid desc limit $start,$numperrow";
$result = @mysql_query($query);
echo "<table class=display id=status>";
echo "\n<thead><tr>";
echo "<th width='11%'>Username</th>";
echo "<th width='6%'>RunID</th>";
echo "<th width='6%'>Problem</th>";
echo "<th width='20%'>Result</th>";
echo "<th width='8%'>Language</th>";
echo "<th width='8%'>Time</th>";
echo "<th width='8%'>Memory</th>";
echo "<th width='8%'>Length</th>";
echo "<th width='14%'>Time Submit</th>";
echo "</tr></thead>\n<tbody>\n";
while (list($uname,$runid,$pid,$res,$lang,$timeused,$memused,$timesubmit,$isshared)=@mysql_fetch_row($result)) {
	$lang=match_lang($lang);
	echo "<tr>";
	echo "<td><center><a href=userinfo.php?name=$uname>$uname</a></center></td>";
	if ($isshared==TRUE||$nowuser==$uname||db_user_iscodeviewer($nowuser)) echo "<td><center><a href=show_source.php?runid=$runid class='runid_link'>$runid</a></center></td>";
	else echo "<td><center>$runid</center></td>";
	echo "<td><center><a href=problem_show.php?pid=$pid>$pid</a></center></td>";
	switch ($res) {
		case "Compile Error":
			echo "<td><center><strong><a href=show_ce_info.php?runid=$runid class='ce'><span class='ce'>$res</span></a></strong></center></td>";
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
    if ($memused!=0) {
        $memused.=" KB";
        $timeused.=" ms";
    }
    else {
        $memused="";
        if ($timeused!=0) $timeused.=" ms"; else $timeused="";
    }

	echo "<td><center>$lang</center></td>";
	echo "<td><center>$timeused</center></td>";
	echo "<td><center>$memused</center></td>";
	$query="select length(source) from status where runid=$runid";
	$tempresult=mysql_query($query);
	list($clength)=@mysql_fetch_row($tempresult);
	echo "<td><center>$clength Bytes</center></td>";
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
$(document).ready(function() {

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
            else if (striptags(aData[3])=="Judge Error"||striptags(aData[3])=="Judging"||striptags(aData[3])=="Rejudging"||striptags(aData[3])=="Waiting") $(nRow).children().each(function(){$(this).addClass('gradeU');});
            else if (striptags(aData[3])!="Accepted") $(nRow).children().each(function(){$(this).addClass('gradeX');});
            else $(nRow).children().each(function(){$(this).addClass('gradeC');});
            if (striptags(aData[3])=="Judge Error"||striptags(aData[3])=="Judge Error (Vjudge Failed)") {
                $(nRow).children("td:nth-child(4)").addClass("able");
                $(nRow).children("td:nth-child(4)").click(function(){
                    if ($(this).hasClass("able")==false) return;
                    $.ajax({
                        type:"POST",
                        url: "error_rejudge.php",
                        data: "runid="+striptags(aData[1]),
                        cache: false,
                        success: function(html){
                            alert(html);
                        }
                    });
                    $(this).removeClass("able")
                });
            }
            return nRow;
        },
        "fnDrawCallback": function(){
            $('#status td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
            $('#status td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
        },
        "iDisplayStart": 0,
        "oSearch": {"sSearch": ""}
    } );
});

</script>

<?php include("footer.php"); ?>
