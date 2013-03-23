function escapeHtml(unsafe) {
  return unsafe
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

//$(document).ready(function() {
function striptags(a) {
    return a.replace(/(<([^>]+)>)/ig,"");
}
$("a.button, button").button();
$(".ui-buttonset").buttonset();
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
            { "bVisible": false, "aTargets": [ 6,7,8 ] },
            {
                "fnRender": function ( o, val ) {
                    return "<a href='contest_show.php?cid="+o.aData[0]+"' title='"+escapeHtml(striptags(o.aData[1]))+"'>"+o.aData[1]+"</a>";
                },
                "aTargets": [ 1 ]
            }
        ],
//        "asStripClasses": [ 'odd', 'even' ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (striptags(aData[4])=="Passed") $(nRow).children().each(function(){$(this).addClass('gradeA');});
            else if (striptags(aData[4])=="Running"||striptags(aData[4])=="Challenging") $(nRow).children().each(function(){$(this).addClass('gradeX');});
            else $(nRow).children().each(function(){$(this).addClass('gradeC');});
            $("td:eq(0)",nRow).html("<a href='contest_show.php?cid="+aData[0]+"'>"+aData[0]+"</a>");
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

$("#arrangevdialog").dialog({
    autoOpen: false,
    //modal:true,
    show: 'clip',
    hide: 'clip',
    resizable:true,
    draggable:true,
//    height: 800,
    width: 1000
});
$("#arrangevirtual").click(function() {
	$("#arrangevdialog").dialog("open");
});
$("input:submit","#arrangevdialog").button();
$('.datepick').datetimepicker({
	showSecond: true,
	dateFormat: 'yy-mm-dd',
	timeFormat: 'hh:mm:ss'
});
if ($.cookie("username")!=null) $("#arrangevirtual").show();
$("#arrangeform").submit(function() {
    var tform=$("#arrangeform");
    $("input:submit",tform).attr("disabled","disabled");
    $("input:submit",tform).addClass("ui-state-disabled");
    $("#arrangemsgbox").removeClass().addClass('normalmessagebox').html('<img height="15px" src="style/ajax-loader.gif" /> Validating....').fadeIn(500);
    $.post("arrange_vcontest.php", tform.serialize() ,function(data)
    {
      if($.trim(data)=='Success!')
      {
            $("#arrangemsgbox").fadeTo(100,0.1,function()
            {
              $(this).html('Success!').addClass('normalmessageboxok').fadeTo(800,1, function() {
                 window.location.href="contest.php?virtual=1";
              });
            });
      }
      else
      {
            $("#arrangemsgbox").fadeTo(100,0.1,function()
            {
               $(this).html(data).addClass('normalmessageboxerror').fadeTo(300,1);
            });
            $("input:submit",tform).removeAttr("disabled");
            $("input:submit",tform).removeClass("ui-state-disabled");
      }
   });
   return false;
});

function deal(id,oj,$target) {
    $.get("api/get_pinfo.php?vid="+id+"&vname="+oj+"&randomid="+Math.random(),function(data) {
        if ($.trim(data)=="Error!") {
            //$target.prev().val(id);
            if (id==$target.prev().val()) {
                $target.val("");
                $target.next().next().html("Error!");
            }
        }
        else {
            var p=eval('('+data+')');
            //$target.prev().val(id);
            if (id==$target.prev().val()) {
                $target.val(p.pid);
                $target.next().next().html("<a href='problem_show.php?pid="+p.pid+"' target='_blank'>"+p.title+"</a>");
            }
        }
    });
}

$(".vpid").keyup(function() {
    var vid=$(this).val();
    var vname=$(this).prev().val();
    var $target=$(this).next();
    deal(vid,vname,$target);
});
$(".vpname").change(function() {
    var vid=$(this).next().val();
    var vname=$(this).val();
    var $target=$(this).next().next();
    deal(vid,vname,$target);
});

$(".ptype").change(function() {
    var ptp=$(this).val();
//    alert(ptp);
    if (ptp=='0') {
        $(this).nextAll("div").hide();
    } else if (ptp=='1'||ptp=='3') {
        var aa=$(this).parent().nextAll(".selpara").children(".cf");
        $(this).parent().nextAll(".selpara").children().hide();
        aa.children(".paraa").val("2");
        aa.children(".parab").val("50");
        aa.show();
        $(this).parent().nextAll(".selpara").show();
    } else if (ptp=='2') {
        var aa=$(this).parent().nextAll(".selpara").children(".tc");
        $(this).parent().nextAll(".selpara").children().hide();
        aa.children(".paraa").val("0.3");
        aa.children(".parab").val("0.7");
        aa.children(".parac").val("4500");
        aa.children(".parad").val("10");
        aa.children(".parae").val("10");
        aa.show();
        $(this).parent().nextAll(".selpara").show();

    }
});

$("input[name='ctype']").change(function() {
    var ctp=$(this).val();
    //alert(ctp);
    if (ctp=='0') {
        $(".selptype , .selpara, .typenote").hide();
        $(".pextra").show();
    } else if (ctp=='1') {
        $(".tc").hide();
        $(".pextra").hide();
        $(".cf").show();
        $(".paraa").val('2');
        $(".parab").val('50');
        $(".selptype , .selpara, .typenote").show();
    }
});

//$("#problist tr th:nth-child(1)").hide();

$("#showall").click(function() {
    oTable.fnSetColumnVis( 6, true );
    oTable.fnFilter( '', 7 );
});
$("#showstandard").click(function() {
    oTable.fnSetColumnVis( 6, false );
    oTable.fnFilter( '0', 7 );
});
$("#showvirtual").click(function() {
    oTable.fnSetColumnVis( 6, true );
    oTable.fnFilter( '1', 7 );
});

$("#showtall").click(function(){
    oTable.fnFilter( '', 5 );
});
$("#showtpublic").click(function(){
    oTable.fnFilter( '0', 5 );
});
$("#showtprivate").click(function(){
    oTable.fnFilter( '1', 5 );
});
$("#showtpassword").click(function(){
    oTable.fnFilter( '2', 5 );
});

$("#showcall").click(function(){
    oTable.fnFilter( '', 8 );
});
$("#showcicpc").click(function(){
    oTable.fnFilter( '0', 8 );
});
$("#showccf").click(function(){
    oTable.fnFilter( '1', 8 );
});
$("#showcreplay").click(function(){
    oTable.fnFilter( '99', 8 );
});
$("#showcnonreplay").click(function(){
    oTable.fnFilter( '-99', 8 );
});


if (cshowtype==='0') {
    $("#showcicpc").click();
}
else if (cshowtype==1) {
    $("#showccf").click();
}
else if (cshowtype==99) {
    $("#showstandard").click();
    $("#showcreplay").click();
}
else {
    $("#showstandard").click();
    $("#showcnonreplay").click();
}

if (getURLPara("open")==1) $("#arrangevdialog").dialog("open");
if (getURLPara("virtual")==1) {
    $("#showvirtual").click();
}

