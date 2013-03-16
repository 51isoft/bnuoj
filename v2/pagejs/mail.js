$("#sendmail").click(function() {
    $("#newmailwindow").dialog("title","New Mail");
    $("#newmailwindow input#reciever").attr("value","");
    $("#newmailwindow input#mailtitle").attr("value","New Mail");
    $("#newmailwindow textarea#newmailcontent").val("");
    $("#newmailwindow #sendmailmsgbox").hide();
    $("#newmailwindow").dialog("open");
    $("#newmailwindow #newmailcontent").focus();
});

function escapeHTML(unsafe) {
    return unsafe
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}


$("#mailwindow").dialog({
    autoOpen: false,
    width:850,
    modal:true,
//    show:'clip',
//    position: 'top',
    resizable:false,
    draggable:false
});
$("#newmailwindow").dialog({
    autoOpen: false,
    minWidth: 500,
    maxWidth:850,
    minHeight:450,
    maxHeight:800,
    show: 'clip',
    hide: 'clip',
    modal:true,
    resizable:true,
    draggable:false
});
$("button, input:submit, input:reset").button();
    var oTable = $('#maillist').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "bJQueryUI": true,
        "sDom": '<"H"pf>rt<"F"ilp>',
//        "bStateSave": true,
//        "sCookiePrefix": "bnu_datatable_",        
//        "sDom": '<"H"pf>rt<"F"il>',
        "oLanguage": {
            "sEmptyTable": "No mails found.",
            "sZeroRecords": "No mails found.",
            "sInfoEmpty": "No entries to show"
        },
        "sAjaxSource": "mail_data.php?username="+$.cookie('username'),
        "aaSorting": [ [0,'desc'] ],
        "sPaginationType": "input" ,
        "aLengthMenu": [[25, 50, 100, 150, 200], [25, 50, 100, 150, 200]] ,
        "iDisplayLength": mailperpage,
        "aoColumnDefs": [ 
            { "bSortable": false, "aTargets": [ 1, 2, 3, 4 ] },
            { "bVisible": false, "aTargets": [ 0,2 ] }
                    ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            $(nRow).children().each(function(){$(this).addClass('gradeC');});
            return nRow;
        },
        "fnDrawCallback": function(){
            $('#maillist td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
            $('#maillist td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
            $("a.getmail").click(function() {
                var target=$("div#mailwindow");
                target.dialog("option","title",escapeHTML($(this).text()));
                target.html('<div style="text-align:center"><img src="style/ajax-loader.gif" /> Loading...</div>');
                $.get('fetch_mail.php',{ mailid: $(this).attr("name") }, function(data) {
                    target.html(data);
                    $("#mailwindow").dialog("close");
                    $("#mailwindow").dialog("open");
                    $("#mailwindow a.button").button();
                    $("a.replybutton").click(function() {
                        $("#newmailwindow input#reciever").attr("value",escapeHTML($("#mailwindow label#mailsender").text()));
                        $("#newmailwindow input#mailtitle").attr("value","RE: "+escapeHTML($("#mailwindow label#mailtitle").text()));
                        $("#newmailwindow textarea#newmailcontent").val("\n--------------------------------\n"+escapeHTML($("#mailwindow pre#mailcontent").text()));
                        $("#mailwindow").dialog("close");
                        $("#newmailwindow #sendmailmsgbox").hide();
                        $("#newmailwindow").dialog("open");
                        $("#newmailwindow #newmailcontent").focus();
                    });
                });
                $("#mailwindow").dialog("open");
                $(this).css("font-weight","normal");
                return false;
            });
        }
    } );

oTable.fnFilter($.cookie('username'),2);

$("#showoutbox").click(function() {
    oTable.fnFilter("",2);
    oTable.fnFilter($.cookie('username'),1);
    oTable.fnSetColumnVis( 2, true );
    oTable.fnSetColumnVis( 1, false );
    $(this).hide();
    $("#showinbox").show();
});

$("#showinbox").click(function() {
    oTable.fnFilter("",1);
    oTable.fnFilter($.cookie('username'),2);
    oTable.fnSetColumnVis( 1, true );
    oTable.fnSetColumnVis( 2, false );
    $(this).hide();
    $("#showoutbox").show();
});

$("#userspace").addClass("tab_selected");
