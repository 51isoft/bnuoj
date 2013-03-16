//$(document).ready(function() {
function striptags(a) {
    return a.replace(/(<([^>]+)>)/ig,"");
}
$("a.button").button();
    var oTable = $('#contestlist').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "bJQueryUI": true,
        "sDom": '<"H"pf>rt<"F"ilp>',
//        "bStateSave": true,
//        "sCookiePrefix": "bnu_datatable_",        
//        "sDom": '<"H"pf>rt<"F"il>',
        "oLanguage": {
            "sEmptyTable": "No contests found.",
            "sZeroRecords": "No contests found.",
            "sInfoEmpty": "No entries to show"
        },
        "sAjaxSource": "contest_data.php",
        "aaSorting": [ [2,'desc'] ],
        "sPaginationType": "input" ,
        "aLengthMenu": [[25, 50, 100, 150, 200], [25, 50, 100, 150, 200]] ,
        "iDisplayLength": conperpage,
        "iDisplayStart": 0,
//        "oSearch": {"sSearch": searchstr},
        "aoColumnDefs": [ 
            { "bSortable": false, "aTargets": [ 4,5 ] },
           { "bVisible": false, "aTargets": [ 6,7,8 ] }
        ],
//        "asStripClasses": [ 'odd', 'even' ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (striptags(aData[4])=="Passed") $(nRow).children().each(function(){$(this).addClass('gradeA');});
            else if (striptags(aData[4])=="Running"||striptags(aData[4])=="Challenging") $(nRow).children().each(function(){$(this).addClass('gradeX');});
            else $(nRow).children().each(function(){$(this).addClass('gradeC');});
            return nRow;
        },
        "fnDrawCallback": function(){
            $('#contestlist td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
            $('#contestlist td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
            $('#contestlist .sorting_1').each(function(){$(this).removeClass('gradeA');$(this).removeClass('gradeB');$(this).removeClass('gradeC');
                                                      $(this).removeClass('gradeU');$(this).removeClass('gradeX');$(this).addClass('gradeCC');});
/*            $(".source_search").each(function(i) {
                $(this).click( function() {
                    oTable.fnFilter( $(this).text() );
                });
            });*/
        }
    } );
//    new FixedHeader( oTable );
// after status , column count -1
   
$("#contest").addClass("tab_selected");
oTable.fnFilter( '0', 6 );
