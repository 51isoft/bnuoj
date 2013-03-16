<?
  include_once("conn.php");
  $cid = convert_str($_GET['cid']);
  if (db_contest_exist($cid)) $pagetitle=strip_tags(db_get_contest_title($cid));
  else $pagetitle="No Such Contest.";
  include_once("header.php");
  include_once("menu.php");
?>
    <div id="site_content">
<?
    if (db_contest_exist($cid)&&(db_contest_ispublic($cid)||db_user_in_contest($cid,$nowuser))) {
?>

      <div id="contest_nav" class="center">
        <a href="javascript:void(0)" class="button" id="cinfo_a">Informations</a>
        <a href="javascript:void(0)" class="button" id="cprob_a">Problems</a>
        <a href="javascript:void(0)" class="button" id="cstatus_a">Status</a>
        <a href="javascript:void(0)" class="button" id="cstand_a">Standing</a>
        <a href="javascript:void(0)" class="button" id="cclar_a">Clarify</a>
        <a href="javascript:void(0)" class="button" id="creport_a">Report</a>
      </div>
      <div id="contest_content">
        <div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>
      </div>
<?
    } else {
?>
      <div id="one_content_container">
        <div id="one_content_top"></div>
        <div id="one_content">
          <p>
            <div class="error"><b>Contest Unavailable!</b></div>
          </p>
        </div>
        <div id="one_content_base"></div>
      </div>

<?
    }
?>
    </div>

<?
    include("footer.php");
?>
<script type="text/javascript">
$("a.button").button();
$("#contest").addClass("tab_selected");

var oTable;

jQuery.fn.dataTableExt.oSort['split-num-asc'] = function(a,b) {
    var x = a.split( "/" );
    var y = b.split( "/" );
    x[0] = parseInt( x[0] );
    y[0] = parseInt( y[0] );
    x[1] = parseInt( x[1] );
    y[1] = parseInt( y[1] );
    if (x[0]!=y[0]) return x[0]>y[0]? 1 : -1;
    else return ((x[1] < y[1]) ?  1 : ((x[1] > y[1]) ? -1 : 0));
};


jQuery.fn.dataTableExt.oSort['split-num-desc'] = function(a,b) {
    var x = a.split( "/" );
    var y = b.split( "/" );
    x[0] = parseInt( x[0] );
    y[0] = parseInt( y[0] );
    x[1] = parseInt( x[1] );
    y[1] = parseInt( y[1] );
    if (x[0]!=y[0]) return x[0]<y[0]? 1 : -1;
    else return ((x[1] > y[1]) ?  1 : ((x[1] < y[1]) ? -1 : 0));
};

var submitfunc=function()
{
        var tform=this;
        $("input:submit",tform).attr("disabled","disabled");
        $("input:submit",tform).addClass("ui-state-disabled");
        $("#submitmsgbox").removeClass().addClass('normalmessagebox').html('<img height="15px" src="style/ajax-loader.gif" />Validating....').fadeIn(500);
        $.post("contest_action.php", $(this).serialize() ,function(data)
        {
          if($.trim(data)=='Submitted.') //if correct login detail
          {
                $("#submitmsgbox").fadeTo(100,0.1,function()  //start fading the messagebox
                {
                  $(this).html('Success!').addClass('normalmessageboxok').fadeTo(500,1,function() {
                    $("#submitmsgbox").hide();
                    $("input:submit",tform).removeAttr("disabled");
                    $("input:submit",tform).removeClass("ui-state-disabled");
                    $("#submitdialog").dialog("close");
                    $.get("contest_status.php",{cid: "<? echo $cid; ?>", randomid: Math.random()},statusfunc);
                  });
                });
//                window.location ='status.php';
          }
          else if($.trim(data)=='Transmitted.') //if correct login detail
          {
                $("#submitmsgbox").fadeTo(100,0.1,function()  //start fading the messagebox
                {
                  $(this).html('Transfered!').addClass('normalmessageboxok').fadeTo(100,1);
                });
                window.location ='status.php';
          }
          else
          {
                $("#submitmsgbox").fadeTo(100,0.1,function() //start fading the messagebox
                {
                  $(this).html(data).addClass('normalmessageboxerror').fadeTo(300,1);
                });
                $("input:submit",tform).removeAttr("disabled");
                $("input:submit",tform).removeClass("ui-state-disabled");
          }
       });
       return false;//not to post the  form physically
};

var showpfunc=function() {
    $("#cprob_a").attr("name",$(this).attr("name"));
    $.get("contest_prob.php",{cpid: $(this).attr("name")},function(data) {
        $("#submitdialog").dialog("destroy");
        $("#submitdialog").remove();
        $("#contest_content").html(data);
        adjustlist("",$("#submitdialog").attr("name"));
        $("#probpagi a").click(showpfunc);
        $("#submitdialog").dialog({
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
        $("a.button, input:submit, input:reset").button();
        $(".submitprob").click(function() {
            if ($.cookie("username")==null) $("#logindialog").dialog("open");
            else $("#submitdialog").dialog("open");
            return false;
        });
        $(".showstatus").click(function() {
            var label=$(this).attr("name");
            $.get("contest_status.php",{cid: "<? echo $cid; ?>", randomid: Math.random()},function(data) {
                statusfunc(data);
                oTable.fnFilter(label,2);
                $(".filter #showpid [value="+label+"]").attr("selected","selected");
             });
            if (!$.browser.msie||parseInt($.browser.version)>7) $("#contest_content").html('<div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>');
            return false;
        });
        $("#cprobsubmit").submit(submitfunc);
        document.title=$("#contest_content h1.pagetitle").text();
    });
    if (!$.browser.msie||parseInt($.browser.version)>7) $("#contest_content").html('<div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>');
    return false;
}



var defaultfunc=function(data){
    $("#contest_content").html(data);
    $("#cplist").dataTable({
        "bJQueryUI": true,
        "sDom": 'rt',
        "iDisplayLength": -1,
        "aoColumnDefs": [
            { "sType": "split-num", "aTargets": [ 3,4 ] },
            { "sType": "html", "aTargets": [ 1,2 ] }
        ],
        "aaSorting": [  ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (aData[0]=="Yes") $(nRow).children().each(function(){$(this).addClass('gradeA');});
            else if (aData[0]=="No") $(nRow).children().each(function(){$(this).addClass('gradeX');});
            else $(nRow).children().each(function(){$(this).addClass('gradeC');});
            return nRow;
        },
        "fnDrawCallback": function(){
            $('#cplist td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
            $('#cplist td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
            $('#cplist .sorting_1').each(function(){$(this).removeClass('gradeA');$(this).removeClass('gradeB');$(this).removeClass('gradeC');
                                                      $(this).removeClass('gradeU');$(this).removeClass('gradeX');$(this).addClass('gradeCC');});
            $("#cplist a.cprob_a").click(showpfunc);
            $("#cprob_a").attr("name",$("#cplist a.cprob_a:nth-child(1)").attr("name"));
        }
    });
    document.title=$("#contest_content h1.pagetitle").text();
};

var statusfunc=function(data) {
    $("#contest_content").html(data);
    $("#sourcewindow").dialog({
        autoOpen: false,
        width:850,
        modal:true,
        resizable:false,
        draggable:false
    });
    $( "input:submit, button, a.button","#contest_content" ).button();
    $("input:submit","#filterform","#contest_content").attr("disabled","disabled");
    $("#contest_content input:submit",this).addClass("ui-state-disabled");
    function striptags(a) {
        return a.replace(/(<([^>]+)>)/ig,"");
    }
    oTable = $('#statustable').dataTable( {
        "bProcessing": true,
        "bJQueryUI": true,
        "bServerSide": true,
        "sDom": '<"H"p>rt<"F"ilp>',
        "sAjaxSource": "contest_status_data.php?cid=<? echo $cid; ?>&randomid="+Math.random(),
        "sPaginationType": "full_numbers" ,
        "iDisplayLength": <? echo $statusperpage; ?>,
        "bLengthChange": false,
        "oLanguage": {
            "sEmptyTable": "No status found.",
            "sZeroRecords": "No status found.",
            "sInfoEmpty": "No entries to show"
        },
        "aaSorting": [ [1,'desc'] ],
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 0,1,2,3,4,5,6,7,8 ] },
            { "bVisible": false, "aTargets": [ 1 ] }
        ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (striptags(aData[3]).substr(0,7)=="Compile") $(nRow).children().each(function(){$(this).addClass('gradeA');});
            else if (striptags(aData[3])=="Judge Error"||striptags(aData[3])=="Judging"||striptags(aData[3])=="Rejudging"||striptags(aData[3])=="Waiting") $(nRow).children().each(function(){$(this).addClass('gradeU');});
            else if (striptags(aData[3])!="Accepted") $(nRow).children().each(function(){$(this).addClass('gradeX');});
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
                target.dialog("option","title","Source of Runid: "+$(this).attr("name"));
                target.html('<div style="text-align:center"><img src="style/ajax-loader.gif" /> Loading...</div>');
                $.get('get_source.php',{ runid: $(this).attr("name") }, function(data) {
                    target.html(data);
                    $("#source_code").snippet($("#source_code").attr("class"),{
                        style: "typical",
                        showNum: true
                    });
                    $("#sourcewindow").dialog("close");
                    $("#sourcewindow").dialog("open");
                });
                $("#sourcewindow").dialog("open");
                return false;
            });
            $("a.stashowp").click(showpfunc);
            $("input:submit","#filterform").removeAttr("disabled");
            $("input:submit","#filterform").removeClass("ui-state-disabled")
        },
        "iDisplayStart": 0
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
//    $( "#showres" ).combobox().attr("name","showres");
//    $( "#showlang" ).combobox().attr("name","showlang");
//    $( "#showpid" ).combobox().attr("name","showpid");
    document.title=$("#contest_content h1.pagetitle").text();
}


$.get("contest_info.php",{cid: "<? echo $cid; ?>", randomid: Math.random()},defaultfunc);
$("#cinfo_a").click(function() {
    $.get("contest_info.php",{cid: "<? echo $cid; ?>", randomid: Math.random()},defaultfunc);
    if (!$.browser.msie||parseInt($.browser.version)>7) $("#contest_content").html('<div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>');
    return false;
});

$("#cstand_a").click(function() {
    $.get("contest_standing.php",{cid: "<? echo $cid; ?>", randomid: Math.random()},function(data){
        $("#contest_content").html(data);
        $("#trypos").height($("#cstandingcontainer").height());
        $("table.cfoot").width($("table.cbody").width());
        $("a.standingp").click(showpfunc);
        $('.cstanding td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('normal_stat');}); });
        $('.cstanding td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('normal_stat');}); });
        $("#stat_dis_user").change(function() {
            $("#stat_dis_nick").removeAttr("checked");
            $(".tnickname").hide();
            $(".tusername").show();
            $("#trypos").height($("#cstandingcontainer").height());
        });
        $("#stat_dis_nick").change(function() {
            $("#stat_dis_user").removeAttr("checked");
            $(".tusername").hide();
            $(".tnickname").show();
            $("#trypos").height($("#cstandingcontainer").height());
        });
        document.title=$("#contest_content h1.pagetitle").text();
    });
    if (!$.browser.msie||parseInt($.browser.version)>7) $("#contest_content").html('<div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>');
    return false;
});

$("#cprob_a").click(showpfunc);
$("#cstatus_a").click(function() {
    $.get("contest_status.php",{cid: "<? echo $cid; ?>", randomid: Math.random()},statusfunc);
    if (!$.browser.msie||parseInt($.browser.version)>7) $("#contest_content").html('<div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>');
    return false;
});
$("#creport_a").click(function() {
    $.get("contest_report.php",{cid: "<? echo $cid; ?>", randomid: Math.random()},function(data) {
        $("#contest_content").html(data);
        $("#cprobreport").dataTable({
            "bJQueryUI": true,
            "sDom": 'rt',
            "iDisplayLength": -1,
            "aoColumnDefs": [
                { "sType": "split-num", "aTargets": [ 2,3 ] },
                { "sType": "html", "aTargets": [ 0,1 ] }
            ],
            "aaSorting": [  ],
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                $(nRow).children().each(function(){$(this).addClass('gradeC');});
                return nRow;
            },
            "fnDrawCallback": function(){
                $('#cprobreport td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
                $('#cprobreport td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
                $('#cprobreport .sorting_1').each(function(){$(this).removeClass('gradeA');$(this).removeClass('gradeB');$(this).removeClass('gradeC');
                                                          $(this).removeClass('gradeU');$(this).removeClass('gradeX');$(this).addClass('gradeCC');});
            }
        });
        document.title=$("#contest_content h1.pagetitle").text();
    });
    if (!$.browser.msie||parseInt($.browser.version)>7) $("#contest_content").html('<div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>');
});

var currenttime = '<? print date("l, F j, Y H:i:s",time()); ?>' //PHP method of getting server date

var serverdate=new Date(currenttime);

function padlength(what){
    var output=(what.toString().length==1)? "0"+what : what;
    return output;
}

function displaytime(){
    serverdate.setSeconds(serverdate.getSeconds()+1);
    var datestring=serverdate.getFullYear()+"-"+padlength(serverdate.getMonth()+1)+"-"+padlength(serverdate.getDate());
    var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds());
    $("#servertime").text(datestring+" "+timestring);
}
window.onload=function(){
    setInterval("displaytime()", 1000);
}

</script>
<?
    include("end.php");
?>
