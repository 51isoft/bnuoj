$(document).ready(function() {
    var oTable = $('#rank').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "ajax/ranklist_data.php",
        "sDom": '<"row-fluid"pf>rt<"row-fluid"<"span8"i><"span4"l>>',
        "oLanguage": {
            "sEmptyTable": "No users found.",
            "sZeroRecords": "No such user!",
            "sInfoEmpty": "No entries to show"
        },
        "sPaginationType": "input" ,
        "aoColumnDefs": [ 
            { "bSortable": false, "aTargets": [ 2 ] },
            {
                "mRender": function ( data, type, full ) {
                    return "<a href='userinfo.php?name="+data+"'>"+data+"</a>";
                },
                "aTargets": [ 1 ]
            },
            {
                "mRender": function ( data, type, full ) {
                    return "<a href='status.php?showname="+full[1]+"&showres=Accepted'>"+data+"</a>";
                },
                "aTargets": [ 3,4 ]
            },
            {
                "mRender": function ( data, type, full ) {
                    return "<a href='status.php?showname="+full[1]+"'>"+data+"</a>";
                },
                "aTargets": [ 5 ]
            }
        ],
        "aaSorting": [ [0,'asc'] ],
        "iDisplayLength": userperpage,
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (aData[1]==$.cookie(cookie_prefix+"username")) $(nRow).addClass('success');
            return nRow;
        },
        "iDisplayStart": 0,
        "oSearch": {"sSearch": ""}
    } );

    $("#ranklist").addClass("active");
});
