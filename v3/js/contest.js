function escapeHtml(unsafe) {
  return unsafe
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}
$(document).ready(function() {

    var oTable = $('#contestlist').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sDom": '<"row-fluid"pf>rt<"row-fluid"<"span8"i><"span4"l>>',
        "oLanguage": {
            "sEmptyTable": "No contests found.",
            "sZeroRecords": "No contests found.",
            "sInfoEmpty": "No entries to show"
        },
        "sAjaxSource": "ajax/contest_data.php",
        "aaSorting": [ [2,'desc'] ],
        "sPaginationType": "input" ,
        "aLengthMenu": [[25, 50, 100, 150, 200], [25, 50, 100, 150, 200]] ,
        "iDisplayLength": conperpage,
        "iDisplayStart": 0,
        "aoColumnDefs": [ 
            { "bSortable": false, "aTargets": [ 4,5 ] },
            { "bVisible": false, "aTargets": [ 6,7,8 ] },
            {
                "mRender": function ( data, type, full ) {
                    return "<a href='contest_show.php?cid="+full[0]+"' title='"+escapeHtml(striptags(data))+"'>"+data+"</a>";
                },
                "aTargets": [ 1 ]
            },
            {
                "mRender": function ( data, type, full ) {
                    return "<a href='contest_show.php?cid="+data+"'>"+data+"</a>";
                },
                "aTargets": [ 0 ]
            },
            {
                "mRender": function ( data, type, full ) {
                    if (data!="") return "<a href='userinfo.php?name="+data+"' target='_blank'>"+data+"</a>";
                    else return "-";
                },
                "aTargets": [ 6 ]
            }
        ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (striptags(aData[4])=="Passed") $(nRow).addClass('success');
            else if (striptags(aData[4])=="Running"||striptags(aData[4])=="Challenging") $(nRow).addClass('error');
            else $(nRow).addClass('info')
            $("td:eq(0)",nRow).html("<a href='contest_show.php?cid="+aData[0]+"'>"+aData[0]+"</a>");
            return nRow;
        }
    } );
       
    $("#contest").addClass("active");

    $("#arrangevirtual").click(function() {
    	$("#arrangevdialog").modal("show");
    });

    $("#arrangevdialog").bind("shown",function(){
        $("input[name='title']",this).focus();
    });

    $(".datepick").datetimepicker({
    	format: 'yyyy-mm-dd hh:ii:ss'
    });

    if ($.cookie(cookie_prefix+"username")!=null) $("#arrangevirtual").show();

    $("#arrangeform").bind("correct",function() {
        window.location.href="contest.php?virtual=1";
    });


    function deal(id,oj,$target) {
        $.get("ajax/get_problem_basic.php?vid="+id+"&vname="+oj+"&randomid="+Math.random(),function(data) {
            var p=eval('('+data+')');
            if (p.code!=0) {
                //$target.prev().val(id);
                if (id==$target.prev().val()) {
                    $target.val("");
                    $target.next().next().html("Error!");
                }
            }
            else {
                var p=eval('('+data+')');
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

    $("#showall").click(function() {
        $(".btn",$(this).parent()).removeClass("active");
        $(this).addClass("active");
        oTable.fnSetColumnVis( 6, true );
        oTable.fnFilter( '', 7 );
    });
    $("#showstandard").click(function() {
        $(".btn",$(this).parent()).removeClass("active");
        $(this).addClass("active");
        oTable.fnSetColumnVis( 6, false );
        oTable.fnFilter( '0', 7 );
    });
    $("#showvirtual").click(function() {
        $(".btn",$(this).parent()).removeClass("active");
        $(this).addClass("active");
        oTable.fnSetColumnVis( 6, true );
        oTable.fnFilter( '1', 7 );
    });

    $("#showtall").click(function(){
        $(".btn",$(this).parent()).removeClass("active");
        $(this).addClass("active");
        oTable.fnFilter( '', 5 );
    });
    $("#showtpublic").click(function(){
        $(".btn",$(this).parent()).removeClass("active");
        $(this).addClass("active");
        oTable.fnFilter( '0', 5 );
    });
    $("#showtprivate").click(function(){
        $(".btn",$(this).parent()).removeClass("active");
        $(this).addClass("active");
        oTable.fnFilter( '1', 5 );
    });
    $("#showtpassword").click(function(){
        $(".btn",$(this).parent()).removeClass("active");
        $(this).addClass("active");
        oTable.fnFilter( '2', 5 );
    });

    $("#showcall").click(function(){
        $(".btn",$(this).parent()).removeClass("active");
        $(this).addClass("active");
        oTable.fnFilter( '', 8 );
    });
    $("#showcicpc").click(function(){
        $(".btn",$(this).parent()).removeClass("active");
        $(this).addClass("active");
        oTable.fnFilter( '0', 8 );
    });
    $("#showccf").click(function(){
        $(".btn",$(this).parent()).removeClass("active");
        $(this).addClass("active");
        oTable.fnFilter( '1', 8 );
    });
    $("#showcreplay").click(function(){
        $(".btn",$(this).parent()).removeClass("active");
        $(this).addClass("active");
        oTable.fnFilter( '99', 8 );
    });
    $("#showcnonreplay").click(function(){
        $(".btn",$(this).parent()).removeClass("active");
        $(this).addClass("active");
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

    if (getURLPara("open")==1) $("#arrangevdialog").modal("show");
    if (getURLPara("virtual")==1) {
        $("#showvirtual").click();
    }

});