//$(document).ready(function() {
    var oTable = $('#newslist').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "bJQueryUI": true,
        "sDom": '<"H"pf>rt<"F"ilp>',
//        "bStateSave": true,
//        "sCookiePrefix": "bnu_datatable_problemlist_",        
//        "sDom": '<"H"pf>rt<"F"il>',
        "oLanguage": {
            "sEmptyTable": "No news found.",
            "sZeroRecords": "No news found.",
            "sInfoEmpty": "No entries to show"
        },
        "sAjaxSource": "news_data.php",
        "aaSorting": [ [2,'desc'] ],
        "sPaginationType": "full_numbers" ,
        "aLengthMenu": [[25, 50, 100, 150, 200], [25, 50, 100, 150, 200]] ,
        "iDisplayLength": 25,
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            $(nRow).children().each(function(){$(this).addClass('gradeC');});
            return nRow;
        },
        "fnDrawCallback": function(){
            $('#newslist td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
            $('#newslist td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
            $('#newslist .sorting_1').each(function(){$(this).removeClass('gradeA');$(this).removeClass('gradeB');$(this).removeClass('gradeC');
                                                      $(this).removeClass('gradeU');$(this).removeClass('gradeX');$(this).addClass('gradeCC');});
            $(".newslink").click(function() {
                var nnid=$(this).attr("name");
                $.get("fetch_news.php",{'nnid':nnid,'rand':Math.random() }, function(data) {
                    var gval=eval("("+data+")");
                    $("#newsshowdialog #sntitle").html(gval.ntitle);
                    $("#newsshowdialog #sncontent").html(gval.ncontent);
                    $("#newsshowdialog #sntime").html(gval.time_added);
                    $("#newsshowdialog #snauthor").html(gval.author);
                    $("#newsshowdialog .newseditbutton").attr("name",gval.newsid);
                    $("#newsshowdialog").dialog({"title": gval.ntitle});
                    $("#newsshowdialog").dialog("open");
                });
                return false;
            });
        }
    } );
$("#more").addClass("tab_selected");
//$("#problist tr th:nth-child(1)").hide();
