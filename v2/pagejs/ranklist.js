function striptags(a) {
    return a.replace(/(<([^>]+)>)/ig,"");
}
    var oTable = $('#rank').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "bJQueryUI": true,
        "sAjaxSource": "ranklist_data.php",
        "sDom": '<"H"pf>rt<"F"ilp>',
        "oLanguage": {
            "sEmptyTable": "No users found.",
            "sZeroRecords": "No such user!",
            "sInfoEmpty": "No entries to show"
        },
        "sPaginationType": "input" ,
        "aoColumnDefs": [ 
            { "bSortable": false, "aTargets": [ 2 ] },
            // {
            //     "fnRender": function ( o, val ) {
            //         return "<a href='userinfo.php?name="+o.aData[1]+"'>"+o.aData[1]+"</a>";
            //     },
            //     "aTargets": [ 1 ]
            // },
            {
                "fnRender": function ( o, val ) {
                    return "<a href='status.php?showname="+o.aData[1]+"&showres=Accepted'>"+o.aData[3]+"</a>";
                },
                "aTargets": [ 3 ]
            },
            {
                "fnRender": function ( o, val ) {
                    return "<a href='status.php?showname="+o.aData[1]+"'>"+o.aData[4]+"</a>";
                },
                "aTargets": [ 4 ]
            }
        ],
        "aaSorting": [ [0,'asc'] ],
        "iDisplayLength": userperpage,
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (aData[1]==$.cookie("username")) $(nRow).children().each(function(){$(this).addClass('gradeA gradeCC');});
            else $(nRow).children().each(function(){$(this).addClass('gradeC');});
            $("td:eq(1)",nRow).html("<a href='userinfo.php?name="+aData[1]+"'>"+aData[1]+"</a>");
            // $("td:eq(3)",nRow).html("<a href='status.php?showname="+aData[1]+"&showres=Accepted'>"+aData[3]+"</a>");
            // $("td:eq(4)",nRow).html("<a href='status.php?showname="+aData[1]+"'>"+aData[4]+"</a>");
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
$("#ranklist").addClass("tab_selected");
