function escapeHtml(unsafe) {
  return unsafe
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

//$(document).ready(function() {
    function fnCreateSelect( )
    {
        return '<select><option value="">All</option>'+ojoptions+'</select>';
    }
    var oTable = $('#problist').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "bJQueryUI": true,
        "sDom": '<"H"pf>rt<"F"ilp>',
//        "bStateSave": true,
//        "sCookiePrefix": "bnu_datatable_problemlist_",        
//        "sDom": '<"H"pf>rt<"F"il>',
        "oLanguage": {
            "sEmptyTable": "No problems found.",
            "sZeroRecords": "No problems found.",
            "sInfoEmpty": "No entries to show"
        },
        "sAjaxSource": "problem_data.php",
        "aaSorting": [ [1,'asc'] ],
        "sPaginationType": "input" ,
        "aLengthMenu": [[25, 50, 100, 150, 200], [25, 50, 100, 150, 200]] ,
        "iDisplayLength": probperpage,
        "iDisplayStart": pstart,
        "oSearch": {"sSearch": searchstr},
        "aoColumnDefs": [ 
            { "bSortable": false, "aTargets": [ 0, 10 ] },
            { "bVisible": false , "aTargets": [ 6, 7, 8, 9 ] },
            { "bVisible": $.cookie('username')==null?false:true, "aTargets": [ 0 ] } ,
            {
                "fnRender": function ( o, val ) {
                    return "<a href='status.php?showpid="+o.aData[1]+"&showres=Accepted'>"+o.aData[4]+"</a>";
                },
                "aTargets": [ 4 ]
            },
            {
                "fnRender": function ( o, val ) {
                    return "<a href='status.php?showpid="+o.aData[1]+"'>"+o.aData[5]+"</a>";
                },
                "aTargets": [ 5 ]
            },
            {
                "fnRender": function ( o, val ) {
                    return "<a href='problem_show.php?pid="+o.aData[1]+"' title='"+escapeHtml(o.aData[2])+"' >"+o.aData[2]+"</a>";
                },
                "aTargets": [ 2 ]
            },
            {
                "fnRender": function ( o, val ) {
                    //return "<a href='problem_show.php?pid="+o.aData[1]+"'>"+o.aData[2]+"</a>";
                    return "<a class='source_search' href='#' title='"+escapeHtml(o.aData[3])+"'>"+o.aData[3]+"</a>";
                },
                "aTargets": [ 3 ]
            }
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
//            if ($.cookie("username")==null) $("td:nth-child(1)",nRow).hide();
            var sig=1;
            if ($.cookie('username')==null) sig=0;
            $("td:eq("+sig+")",nRow).html("<a href='problem_show.php?pid="+aData[1]+"'>"+aData[1]+"</a>");
            return nRow;
        },
        "fnDrawCallback": function(){
            $('#problist td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
            $('#problist td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
            $('#problist .sorting_1').each(function(){$(this).removeClass('gradeA');$(this).removeClass('gradeB');$(this).removeClass('gradeC');
                                                      $(this).removeClass('gradeU');$(this).removeClass('gradeX');$(this).addClass('gradeCC');});
            $(".source_search").each(function(i) {
                $(this).click( function() {
                    oTable.fnFilter( $(this).text() );
                    return false;
                });
            });
/*            if (($.browser.msie==false||parseInt($.browser.version)!= 8)&&$.cookie("username")==null) {
                $("#problist tr td:nth-child(1)").hide();
                $("#problist tr th:nth-child(1)").hide();
            };*/
        }
    } );
    $(".selectoj").each( function ( i ) {
        this.innerHTML = fnCreateSelect(  );
        $('select', this).change( function () {
            var sel=$(this).val();
            $("#showallp").show();
            $("#showlocalp").show();
            $(".selectoj select option[selected]").removeAttr("selected");
            $(".selectoj select option[value='"+sel+"']").attr("selected","selected");
            oTable.fnFilter( sel, 10 );
        } );
    } );

$("a.button, button").button();
$(".ui-buttonset").buttonset();
//    new FixedHeader( oTable );
if (getURLPara('page')==""||getURLPara('page')==null) {
    oTable.fnFilter( "BNU", 10 );
    $("#showlocalp").click();
    $(".selectoj select option[selected]").removeAttr("selected");
    $(".selectoj select option[value='BNU']").attr("selected","selected");
}
else {
    $("#showallp").click();
}
    
//} );

$("#showunsolve").click(function() {
    oTable.fnFilter("1",0);
    return false;
});

$("#showall").click(function() {
    oTable.fnFilter("0",0);
    return false;
});

$("#showremote").click(function() {
    oTable.fnSetColumnVis( 4, false, false );
    oTable.fnSetColumnVis( 5, false, false );
    oTable.fnSetColumnVis( 8, false, false );
    oTable.fnSetColumnVis( 9, false, false );
    oTable.fnSetColumnVis( 6, true, false );
    oTable.fnSetColumnVis( 7, true );
    return false;
});

$("#showlocal").click(function() {
    oTable.fnSetColumnVis( 6, false, false );
    oTable.fnSetColumnVis( 7, false, false );
    oTable.fnSetColumnVis( 8, false, false );
    oTable.fnSetColumnVis( 9, false, false );
    oTable.fnSetColumnVis( 4, true, false );
    oTable.fnSetColumnVis( 5, true );
    return false;
});

$("#showremu").click(function() {
    oTable.fnSetColumnVis( 6, false, false );
    oTable.fnSetColumnVis( 7, false, false );
    oTable.fnSetColumnVis( 4, false, false );
    oTable.fnSetColumnVis( 5, false, false );
    oTable.fnSetColumnVis( 8, true, false );
    oTable.fnSetColumnVis( 9, true );
    return false;
});

$("#showlocalp").click(function() {
    oTable.fnFilter( "BNU", 10 );
    $(".selectoj select option[selected]").removeAttr("selected");
    $(".selectoj select option[value='BNU']").attr("selected","selected");
    return false;
});

$("#showallp").click(function() {
    oTable.fnFilter( "", 10 );
    $(".selectoj select option[selected]").removeAttr("selected");
    $(".selectoj select option[value='']").attr("selected","selected");
    return false;
});

if (searchstr!="") {
    oTable.fnFilter( "", 10 );
    oTable.fnFilter(searchstr);
    $(".selectoj select option[selected]").removeAttr("selected");
    $(".selectoj select option[value='']").attr("selected","selected");
} 
$("#problem").addClass("tab_selected");
//$("#problist tr th:nth-child(1)").hide();
