<?php

    $pagetitle="Problem List";
    include("header.php");
	echo "<center>";

	echo "<table class='display' id='problist'>\n<thead>\n<tr>";
    echo "<th width='60px'> Flag </th>";
	echo "<th width='80px'> PID </th>";
	echo "<th width='40%'> Title </th>";
    echo "<th width='25%'> Source </th>";
	echo "<th width='70px'> AC </th>";
	echo "<th width='70px'> All </th>";
    echo "<th width='120px' id='oj'> OJ </th>";
    echo "<th width='70px'> VID </th>";
	echo "</tr>\n</thead>\n<tbody>\n";
    echo "</tbody>\n<tfoot>\n<tr>\n";
    echo "<th> Flag </th>";
    echo "<th> PID </th>";
    echo "<th> Title </th>";
    echo "<th> Source </th>";
    echo "<th> AC </th>";
    echo "<th> All </th>";
    echo "<th> OJ </th>";
    echo "<th> ID </th>";
    echo "</tr>\n";
    echo "</tfoot>\n";
    echo "\n</table>\n";
	echo "</center>";
    if ($_GET["page"]!="") $stp=$problemperpage*(intval($_GET["page"])-1);
    else $stp="0";
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
$(document).ready(function() {
    function fnCreateSelect( )
    {
        var aData=new Array,aValue=new Array;
        aData[0]="All";aValue[0]="";
        aData[1]="BNU";aValue[1]="BNU";
        aData[2]="PKU";aValue[2]="PKU";
        aData[3]="CodeForces";aValue[3]="CodeForces";
        aData[4]="HDU";aValue[4]="HDU";
        aData[5]="UVALive";aValue[5]="UVALive";
        var r='<select>', i, iLen=aData.length;
        for ( i=0 ; i<iLen ; i++ )
        {
            if (aData[i]=="All") r += '<option value="'+aValue[i]+'" selected>'+aData[i]+'</option>';
            else r += '<option value="'+aValue[i]+'">'+aData[i]+'</option>';
        }
        return r+'</select>';
    }
    var oTable = $('#problist').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "bJQueryUI": true,
        "sDom": '<"H"pf>rt<"F"ilp>',
//        "bStateSave": true,
//        "sCookiePrefix": "bnu_datatable_",        
//        "sDom": '<"H"pf>rt<"F"il>',
        "sAjaxSource": "problem_list_data.php",
        "aaSorting": [ [1,'asc'] ],
        "sPaginationType": "input" ,
        "aLengthMenu": [[25, 50, 100, 150, 200], [25, 50, 100, 150, 200]] ,
        "iDisplayLength": <? echo $problemperpage;?>,
        "iDisplayStart": <? echo $stp; ?>,
        "oSearch": {"sSearch": "<? echo $_GET['search']; ?>"},
        "aoColumnDefs": [ 
            { "bSortable": false, "aTargets": [ 0, 6 ] }
        ],
//        "asStripClasses": [ 'odd', 'even' ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
/*            if (aData[0]=="Yes") $(nRow).addClass("gradeA");
            else if (aData[0]=="No") $(nRow).addClass("gradeX");
            else $(nRow).addClass("gradeC");
            return nRow;*/
            if (aData[0]=="Yes") $(nRow).children().each(function(){$(this).addClass('gradeA');});
            else if (aData[0]=="No") $(nRow).children().each(function(){$(this).addClass('gradeX');});
            else $(nRow).children().each(function(){$(this).addClass('gradeC');});
            return nRow;
        },
        "fnDrawCallback": function(){
            $('#problist td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
            $('#problist td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
            $('#problist .sorting_1').each(function(){$(this).removeClass('gradeA');$(this).removeClass('gradeB');$(this).removeClass('gradeC');
                                                      $(this).removeClass('gradeU');$(this).removeClass('gradeX');$(this).addClass('gradeCC');});
            if ($.cookie("username")==null) {
                $("#problist tr td:nth-child(1)").hide();
                $("#problist tr th:nth-child(1)").hide();
            };
        }
    } );
    $("#oj").each( function ( i ) {
        this.innerHTML = fnCreateSelect(  );
        $('select', this).change( function () {
            oTable.fnFilter( $(this).val(), 6 );
        } );
    } );
//    new FixedHeader( oTable );
//    oTable.fnFilter( "BNU", 6 );
<?
    if ($_GET['search']!="") echo "oTable.fnFilter('".$_GET['search']."' );\n";
?>
    
} );
</script>
<?
	include("footer.php");
?>
