$("#status").addClass("tab_selected");
$("#sourcewindow").dialog({
    autoOpen: false,
    width:1000
//    minHeight:450,
//    show: 'clip',
//    hide: 'clip',
//    modal:true,
//    resizable:false,
//    draggable:false
});
$( "input:submit, button, a.button" ).button();
$("input:submit","#filterform").attr("disabled","disabled");
$("input:submit",this).addClass("ui-state-disabled");
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
    var oTable = $('#statustable').dataTable( {
        "bProcessing": true,
        "bJQueryUI": true,
        "bServerSide": true,
        "sDom": '<"H"p>rt<"F"p>',
        "sAjaxSource": "status_data.php",
        "sPaginationType": "full_numbers" ,
        "iDisplayLength": statperpage,
        "bLengthChange": false,
        "oLanguage": {
            "sEmptyTable": "No status found.",
            "sZeroRecords": "No status found.",
            "sInfoEmpty": "No entries to show"
        },
        "aaSorting": [ [1,'desc'] ],
        "aoColumnDefs": [
            { "sType": "num-html", "aTargets": [ 1,2 ] },
            { "sType": "html", "aTargets": [ 3 ] },
            { "bSortable": false, "aTargets": [ 0,1,2,3,4,5,6,7,8 ] }
        ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (striptags(aData[3]).substr(0,7)=="Compile") $(nRow).children().each(function(){$(this).addClass('gradeA');});
            else if (striptags(aData[3]).substr(0,4)=="Judg"||striptags(aData[3])=="Rejudging"||striptags(aData[3])=="Waiting") $(nRow).children().each(function(){$(this).addClass('gradeU');});
            else if (striptags(aData[3])!="Accepted"&&striptags(aData[3]).substr(0,7)!="Pretest") $(nRow).children().each(function(){$(this).addClass('gradeX');});
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
            $('#statustable td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
            $('#statustable td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
            $('#statustable tr td:nth-child(1)').css("overflow","hidden");
            $('#statustable tr td:nth-child(1)').css("text-overflow","ellipsis");
            $("a.ceinfo").click(function() {
                var target=$(" + div",this);
                if (target.hasClass("fetched")==false) {
                    $.get('get_ceinfo.php',{ runid: $(this).attr("title") }, function(data) {
                        target.html(data);
                    });
                    target.addClass("fetched");
                }
                if (target.is(":hidden")) {
                    $("div.ceinfo:visible").hide("blind",100);
                    target.show("blind",100);
                }
                else {
                    $("div.ceinfo:visible").hide("blind",100);
                }
                return false;
            });
            $("a.showsource").click(function() {
                var target=$("div#sourcewindow");
                var trunid=$(this).attr("name");
                target.dialog("option","title","Source of Runid: "+$(this).attr("name"));
                target.html('<div style="text-align:center"><img src="style/ajax-loader.gif" /> Loading...</div>');
                $.get('get_source.php',{ runid: trunid, randomid: Math.random() }, function(data) {
                    target.html(data);
                    $("#source_code").snippet($("#source_code").attr("class"),{
                        style: "typical",
                        showNum: true
                    });
                    $("#sourcewindow").dialog("close");
                    $("#sourcewindow").dialog("open");
                    $("input[name='tisshare']").change(function() {
                        var sel=$(this).val();
                        $.get("deal_share.php", {randomid: Math.random(), runid: trunid, type: sel} ,function(data) {
                            if (sel=="0") $("#sharenote").hide();
                            else $("#sharenote").show();
                        });
                    });
                });
                $("#sourcewindow").dialog("open");
                return false;
            });
            $("input:submit","#filterform").removeAttr("disabled");
            $("input:submit","#filterform").removeClass("ui-state-disabled")
            $(".dataTables_paginate .last").hide();
            $(".dataTables_paginate .next").addClass("ui-corner-tr ui-corner-br");
        },
        "iDisplayStart":spstart
    } );
$("#filterform").submit(function() {
    $("input:submit",this).attr("disabled","disabled");
    $("input:submit",this).addClass("ui-state-disabled");
    oTable.fnFilter($("#filterform [name='showname']").val(),0);
    oTable.fnFilter($("#filterform [name='showpid']").val(),2);
    oTable.fnFilter($("#filterform [name='showres']").val(),3);
    oTable.fnFilter($("#filterform [name='showlang']").val(),4);
    return false;
});


var showname=getURLPara('showname');
var showpid=getURLPara('showpid');
var showres=getURLPara('showres');
var showlang=getURLPara('showlang');

if ( showname!=null ) {
    oTable.fnFilter(showname,0);
}
if ( showpid!=null ) {
    oTable.fnFilter(showpid,2);
}
if ( showres!=null ) {
    oTable.fnFilter(showres,3);
    $("#showres option[value='"+showres+"']").attr("selected","selected");
}
if ( showlang!=null ) {
    oTable.fnFilter(showlang,4);
    $("#showlang option[value='"+showlang+"']").attr("selected","selected");
}

$( "#showres" ).combobox().attr("name","showres");
$( "#showlang" ).combobox().attr("name","showlang");

