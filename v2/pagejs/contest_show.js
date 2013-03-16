$("a.button, button").button();
$("#contest").addClass("tab_selected");

var oTable;
var reftable;
var adminreftable;
var oris=100;
var cookiename='cstandset_'+$.cookie('username')+'_'+getURLPara('cid');

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

$("#cpasssub").click(function() {
    $.post("deal_contest_pass.php", {"cid": gcid, "password": $("#contest_password").val() } ,function(data) {
        if($.trim(data)=='Right') window.location.reload();
        else alert(data);
    });
});

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
                    $.get("contest_status.php",{cid: gcid, randomid: Math.random()},function(data) {
                        statusfunc(data);
                    });
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

var showpfunc=function(gcpid) {
//    alert(gcpid==null);
//    return;
//    alert(getURLPara('cid'));
    if (gcpid==null) gcpid="0";
    self.document.location.hash="#problem/"+gcpid;
    $("#cprob_a").attr("name",gcpid);
    $("#contest_nav button,#contest_nav a.button").attr("disabled",true).addClass("ui-state-disabled");
//    $("#submitdialog").dialog("destroy");
    $.get("contest_prob.php",{cid: getURLPara('cid'),cpid: gcpid, randomid: Math.random()},function(data) {
        $("#submitdialog").dialog("destroy");
        $("#submitdialog").remove();
        $("#contest_content").html(data);
        adjustlist("",$("#submitdialog").attr("name"));
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
        if ($.cookie("defaultshare")=="0") $("input[name='isshare']:nth(1)").attr("checked",true);
        else $("input[name='isshare']:nth(0)").attr("checked",true);
        $("a.button, input:submit, input:reset").button();
        $(".submitprob").click(function() {
            if ($.cookie("username")==null) $("#logindialog").dialog("open");
            else $("#submitdialog").dialog("open");
            return false;
        });
        $("#cprobsubmit").submit(submitfunc);
        $(".error").errorStyle();
        document.title=$("#contest_content h1.pagetitle").text();
        $("#contest_nav button,#contest_nav a.button").attr("disabled",false).removeClass("ui-state-disabled");

        oris=100;
        $( "#font-plus", ".functions" ).button({
            icons: {
                primary: "ui-icon-plus"
            },
            text: false
        }).click(function() {
            oris+=10;
            $("#showproblem .content-wrapper").css("font-size",oris+"%");
            return false;
        });
        $( "#font-minus", ".functions" ).button({
            icons: {
                primary: "ui-icon-minus"
            },
            text: false
        }).click(function() {
            oris-=10;
            $("#showproblem .content-wrapper").css("font-size",oris+"%");
            return false;
        });
    });
    if (!$.browser.msie||parseInt($.browser.version)>7) $("#contest_content").html('<div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>');
    return false;
}



var defaultfunc=function(data){
//    alert(self.document.location.hash);
    if (self.document.location.hash!="") self.document.location.hash="#info";
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
            $("#cplist a.cprob_a").click(function() {
                showpfunc($(this).attr("name"));
                return false;
            });
            $("#cprob_a").attr("name",$("#cplist a.cprob_a:nth-child(1)").attr("name"));
        }
    });
    $(".error").errorStyle();
    document.title=$("#contest_content h1.pagetitle").text();
    $("#contest_nav button,#contest_nav a.button").attr("disabled",false).removeClass("ui-state-disabled");
};

var statusfunc=function(data,slabel) {
    if (slabel==null) self.document.location.hash="#status";
    else self.document.location.hash="#status/"+slabel;
    $("#sourcewindow").dialog("destroy");
    $("#sourcewindow").remove();
    $("#contest_content").html(data);
    $("#sourcewindow").dialog({
        autoOpen: false,
        width:1000
        //modal:true,
        //resizable:false,
        //draggable:false
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
        "sAjaxSource": "contest_status_data.php?cid="+gcid+"&randomid="+Math.random(),
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
            { "bSortable": false, "aTargets": [ 0,1,2,3,4,5,6,7,8 ] },
            { "bVisible": false, "aTargets": [ 1 ] }
        ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            if (striptags(aData[3]).substr(0,7)=="Compile") $(nRow).children().each(function(){$(this).addClass('gradeA');});
            else if (striptags(aData[3]).substr(0,4)=="Judg"||striptags(aData[3])=="Rejudging"||striptags(aData[3])=="Waiting"||striptags(aData[3])=="Testing") $(nRow).children().each(function(){$(this).addClass('gradeU');});
            else if (striptags(aData[3])!="Accepted"&&striptags(aData[3]).substr(0,7)!="Pretest") $(nRow).children().each(function(){$(this).addClass('gradeX');});
            else $(nRow).children().each(function(){$(this).addClass('gradeC');});
            if (striptags(aData[3])=="Judge Error"||striptags(aData[3])=="Judge Error (Vjudge Failed)") {
                $(nRow).children("td:nth-child(3)").addClass("able");
                $(nRow).children("td:nth-child(3)").click(function(){
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
            $("a.stashowp").click(function() {
                showpfunc($(this).attr('name'));
                return false;
            });
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
    if (slabel!=null) {
        oTable.fnFilter(slabel,2);
        $(".filter #showpid [value="+slabel+"]").attr("selected","selected");
    }
//    $( "#showres" ).combobox().attr("name","showres");
//    $( "#showlang" ).combobox().attr("name","showlang");
//    $( "#showpid" ).combobox().attr("name","showpid");
    document.title=$("#contest_content h1.pagetitle").text();
    $("#contest_nav button,#contest_nav a.button").attr("disabled",false).removeClass("ui-state-disabled");
}

function formtime(t) {
    var str="";
    str+=parseInt(t/3600);
    t%=3600;
    str+=":"+parseInt(t/60)+":"+parseInt(t%60);
    return str;
}

function updaterank(passtime) {
    self.document.location.hash="#standing";
    $("#contest_nav button,#contest_nav a.button").attr("disabled",true).addClass("ui-state-disabled");
    var extstr="";
    if (passtime!="") extstr="&passtime="+passtime;
    $.post("contest_standing.php?randomid="+Math.random()+extstr,$("#csetform").serialize() ,function(data){
        $("#temp_standing").html(data);
//        sortAble($("#temp_standing .cstanding")[0]);
        if ($("#contest_content .cstanding")[0]==null) {
            $("#contest_content").html($("#temp_standing").html());
            $("#trypos").height($("#contest_content #cstandingcontainer").height());
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

            $("#contest_content .slidediv .timeslider").slider({
                range: 'min',
                min: 1,
                max: $("#temp_standing .slidediv .maxval").attr("name"),
                value: $("#temp_standing .slidediv .timeslider").attr("name"),
                slide: function( event, ui ) {
//                    alert(formtime(ui.value));
                    $( "#contest_content .slidediv .passtime" ).text( formtime(ui.value) );
                },
                stop: function( event, ui ) {
                    updaterank(ui.value);
                    $("#contest_content .slidediv .timeslider").slider( "disable" );
                }
            });

            document.title=$("#contest_content h1.pagetitle").text();
            if (!cpass&&(!$.browser.msie||parseInt($.browser.version)>8)&&$("#autoref").attr('checked')!=null) reftable=setTimeout("updaterank()",10000);
        }
        else {
            if ($("#contest_content #stat_dis_nick").attr("checked")) {
                $(".tusername").hide();
                $(".tnickname").show();
            }
//            $("#contest_content .cstanding tbody td").removeClass("ac_stat").removeClass("notac_stat").removeClass("acfb_stat");
            if ($("#animate").attr("checked")!=null) $("#contest_content .cstanding").rankingTableUpdate("#temp_standing .cstanding",{
                onComplete: function(){
                   $("#trypos").height($("#contest_content #cstandingcontainer").height()); 
                   $("#contest_content .currentstat b").html($("#temp_standing .currentstat b").html());
                   $("#contest_content .slidediv .timeslider").slider( "enable" );
                }
            });
            else {
                $("#contest_content .rankcontainer").html($("#temp_standing .rankcontainer").html());
                $("#contest_content .currentstat b").html($("#temp_standing .currentstat b").html());
                $("#trypos").height($("#contest_content #cstandingcontainer").height());
                $("#contest_content .slidediv .timeslider").slider( "enable" );
            }
            if (!cpass&&$("#autoref").attr('checked')!=null) reftable=setTimeout("updaterank()",10000);
        }
        $(".cha_click").click(function() {
            var uname=$(this).attr("chauname");
            var pid=$(this).attr("chaprob");
            $("#chasrcimage").attr("src","style/ajax-loader.gif");
            $("#cchahistory").html("");
            $("#cchadetailcontent").html('<img height="15px" src="style/ajax-loader.gif" />Loading....');
            $("#cchadetail").hide();
            $("#chasrcimage").attr("src","challenge_src_image.php?pid="+pid+"&username="+uname+"&cid="+gcid+"&random="+Math.random());
            $.get("fetch_challenge_history.php?pid="+pid+"&username="+uname+"&cid="+gcid+"&random="+Math.random(),function(data) {
                $("#cchahistory").html(data);
                $(".showchadet").click(function() {
                    $("#cchadetailcontent").html('<img height="15px" src="style/ajax-loader.gif" />Loading....');
                    var chaid=$(this).attr('name');
                    $("#cchadetail").show();
                    $.get("fetch_challenge_detail.php?cha_id="+chaid+"&random="+Math.random(),function(data) {
                        $("#cchadetailcontent").html(data);
                    });
                });
            });
            $("#chaformuser").val(uname);
            $("#chaformpid").val(pid);
            $("#chaformcid").val(gcid);
            $("#chasrcimage").show();
            $("#chamsgbox").hide();
            $("#cchaform").show();
            $("#cchainfo").dialog("open");
            return false;
        });
        $(".user_cha").click(function() {
            var uname=$(this).attr("chauname");
            $("#chasrcimage").hide();
            $("#cchahistory").html("");
            $("#cchadetailcontent").html('<img height="15px" src="style/ajax-loader.gif" />Loading....');
            $("#cchadetail").hide();
            $.get("fetch_challenge_history_user.php?username="+uname+"&cid="+gcid+"&random="+Math.random(),function(data) {
                $("#cchahistory").html(data);
                $(".showchadet").click(function() {
                    $("#cchadetailcontent").html('<img height="15px" src="style/ajax-loader.gif" />Loading....');
                    var chaid=$(this).attr('name');
                    $("#cchadetail").show();
                    $.get("fetch_challenge_detail.php?cha_id="+chaid+"&random="+Math.random(),function(data) {
                        $("#cchadetailcontent").html(data);
                    });
                });
            });
            $("#chamsgbox").hide();
            $("#cchaform").hide();
            $("#cchainfo").dialog("open");
            return false;
        });
        $("a.standingp").click(function() {
            showpfunc($(this).attr("name"));
            return false;
        });
        $('.cstanding td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('normal_stat');}); });
        $('.cstanding td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('normal_stat');}); });
        $("#contest_nav button,#contest_nav a.button").attr("disabled",false).removeClass("ui-state-disabled");
    });
}

function adminupdaterank(passtime) {
    self.document.location.hash="#adminstanding";
    $("#contest_nav button,#contest_nav a.button").attr("disabled",true).addClass("ui-state-disabled");
    var extstr="";
    if (passtime!="") extstr="&passtime="+passtime;
    $.post("admin_contest_standing.php?randomid="+Math.random()+extstr,$("#csetform").serialize() ,function(data){
        $("#temp_standing").html(data);
//        sortAble($("#temp_standing .cstanding")[0]);
        if ($("#contest_content .cstanding")[0]==null) {
            $("#contest_content").html($("#temp_standing").html());
            $("#trypos").height($("#contest_content #cstandingcontainer").height());
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

            $("#contest_content .slidediv .timeslider").slider({
                range: 'min',
                min: 1,
                max: $("#temp_standing .slidediv .maxval").attr("name"),
                value: $("#temp_standing .slidediv .timeslider").attr("name"),
                slide: function( event, ui ) {
//                    alert(formtime(ui.value));
                    $( "#contest_content .slidediv .passtime" ).text( formtime(ui.value) );
                },
                stop: function( event, ui ) {
                    adminupdaterank(ui.value);
                    $("#contest_content .slidediv .timeslider").slider( "disable" );
                }
            });

            document.title=$("#contest_content h1.pagetitle").text();
            if (!cpass&&(!$.browser.msie||parseInt($.browser.version)>8)&&$("#autoref").attr('checked')!=null) adminreftable=setTimeout("adminupdaterank()",10000);
        }
        else {
            if ($("#contest_content #stat_dis_nick").attr("checked")) {
                $(".tusername").hide();
                $(".tnickname").show();
            }
//            $("#contest_content .cstanding tbody td").removeClass("ac_stat").removeClass("notac_stat").removeClass("acfb_stat");
            if ($("#animate").attr("checked")!=null) $("#contest_content .cstanding").rankingTableUpdate("#temp_standing .cstanding",{
                onComplete: function(){
                   $("#trypos").height($("#contest_content #cstandingcontainer").height()); 
                   $("#contest_content .currentstat b").html($("#temp_standing .currentstat b").html());
                   $("#contest_content .slidediv .timeslider").slider( "enable" );
                }
            });
            else {
                $("#contest_content .rankcontainer").html($("#temp_standing .rankcontainer").html());
                $("#contest_content .currentstat b").html($("#temp_standing .currentstat b").html());
                $("#trypos").height($("#contest_content #cstandingcontainer").height());
                $("#contest_content .slidediv .timeslider").slider( "enable" );
            }
            if (!cpass&&$("#autoref").attr('checked')!=null) adminreftable=setTimeout("adminupdaterank()",10000);
        }
        $("a.standingp").click(function() {
            showpfunc($(this).attr("name"));
            return false;
        });
        $('.cstanding td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('normal_stat');}); });
        $('.cstanding td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('normal_stat');}); });
        $("#contest_nav button,#contest_nav a.button").attr("disabled",false).removeClass("ui-state-disabled");
    });
}

var showreportfunc=function() {
    self.document.location.hash="#report";
    $("#contest_nav button,#contest_nav a.button").attr("disabled",true).addClass("ui-state-disabled");
    $.get("contest_report.php",{cid: gcid, randomid: Math.random()},function(data) {
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
        $("#contest_nav button,#contest_nav a.button").attr("disabled",false).removeClass("ui-state-disabled");
    });
    if (!$.browser.msie||parseInt($.browser.version)>7) $("#contest_content").html('<div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>');
}


var clarfunc=function(data) {
    self.document.location.hash="#clarify";
    $("#questiondialog").dialog("destroy");
    $("#questiondialog").remove();
    $("#contest_content").html(data);
    $("#questiondialog").dialog({
        autoOpen: false,
        minWidth: 500,
        maxWidth:850,
        show: 'clip',
        hide: 'clip',
        modal:true,
        resizable:true,
        draggable:false
    });
    $("button, a.button, input:submit, input:reset").button();
    $("#newquestion").click(function() { $("#questiondialog").dialog("open"); return false; });
    $("#questionform").submit(function() {
            var tform=this;
            $("input:submit",tform).attr("disabled","disabled");
            $("input:submit",tform).addClass("ui-state-disabled");
            $("#questionmsgbox").removeClass().addClass('normalmessagebox').html('<img height="15px" src="style/ajax-loader.gif" />Validating....').fadeIn(500);
            $.post("newclarify.php?cid="+gcid, $(this).serialize() ,function(data)
            {
              if($.trim(data)=='Success!') //if correct login detail
              {
                    $("#questionmsgbox").fadeTo(100,0.1,function()  //start fading the messagebox
                    {
                      $(this).html('Success!').addClass('normalmessageboxok').fadeTo(500,1,function() {
                        $("#questionmsgbox").hide();
                        $("input:submit",tform).removeAttr("disabled");
                        $("input:submit",tform).removeClass("ui-state-disabled");
                        $("#questiondialog").dialog("close");
                        $("#questiondialog").dialog("destroy");
                        $("#questiondialog").remove();
                        $.get("contest_clarify.php",{cid: gcid, randomid: Math.random()},clarfunc);
                      });
                    });
              }
              else
              {
                    $("#questionmsgbox").fadeTo(100,0.1,function()
                    {
                      $(this).html(data).addClass('normalmessageboxerror').fadeTo(300,1);
                    });
                    $("input:submit",tform).removeAttr("disabled");
                    $("input:submit",tform).removeClass("ui-state-disabled");
              }
           });
           return false;
    });
    $(".clarform").submit(function() {
        $.post("admin_deal_clarify.php", $(this).serialize() ,function(data) {
            alert(data);
        });
        return false;
    });
    $(".error").errorStyle();
    document.title=$("#contest_content h1.pagetitle").text();
    $("#contest_nav button,#contest_nav a.button").attr("disabled",false).removeClass("ui-state-disabled");
} 


$("#csettable").dataTable({
    "bJQueryUI": true,
    "sDom": 'rt',
//    "bStateSave": true,
    "iDisplayLength": -1,
    "aoColumnDefs": [
        { "bSortable": false, "aTargets": [ 0,1,2 ] }
    ],
    "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
        $(nRow).children().each(function(){$(this).addClass('gradeC');});
        return nRow;
    }
});

$("#csetdlg").dialog({
    autoOpen: false,
    width:850,
//    height:600,
//    modal:true,
    show: 'clip',
    hide: 'clip',
    resizable:false,
    draggable:false
});

$("#csetall").change(function() {
    var t=$(this).attr('checked');
    if (t==null) $("#csettable input.othc:checkbox").attr('checked',false);
    else $("#csettable input.othc:checkbox").attr('checked',true);
});

$("#csetform").submit(function() {
    $("#csetdlg").dialog('close');
    clearTimeout(reftable);
    clearTimeout(adminreftable);
    $("#contest_content").html('<div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>');
    $.cookie(cookiename,$("#csetform").serialize());
    if (self.document.location.hash=="#standing") updaterank();
    else self.document.location.hash="#standing";
    return false;
});

var stp=-1;
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

function displaycountdown(){
    cnt+=stp;
    if (cnt<0) cnt=0;
    var dh=Math.floor(cnt/3600);
    var dm=Math.floor((cnt-dh*3600)/60);
    var ds=cnt-dh*3600-dm*60;
    var timestring=dh+":"+dm+":"+ds;
    $("#counttime").text(timestring);
}

window.onload=function(){
    setInterval("displaycountdown()", 1000);
    setInterval("displaytime()", 1000);
}


if ($.cookie(cookiename)==null) {
    $.cookie(cookiename,$("#csetform").serialize());
}

$("#csetform").deserialize($.cookie(cookiename));

if ($("input.othc:not(:checked)").length==0) $("#csetall").attr('checked',true);
else $("#csetall").attr('checked',false);

$("#csettable input.othc:checkbox").change(function() {
    if ($("input.othc:not(:checked)").length==0) $("#csetall").attr('checked',true);
    else $("#csetall").attr('checked',false);
});

$("#cchainfo input:submit").button();
$("#cchainfo").dialog({
    autoOpen: false,
    width:950,
//    height:600,
    modal:false,
    position: 'top',
    show: 'clip',
    hide: 'clip'
    //resizable:false,
    //draggable:false
});

$("input[name='chadata_type']").change(function() {
    var v=$(this).val();
    if (v==1) $("#cha_lang_select").show();
    else $("#cha_lang_select").hide();
})

var charesfunc=function(chaid) {
    $.get("fetch_challenge_result.php?cha_id="+chaid+"&random="+Math.random(),function(data) {
        if ($.trim(data).substring(0,9)=="Challenge") alert(data);
        else setTimeout("charesfunc("+chaid+")",2000);
    });
}

$("#cchaform").submit(function() {
    var tform=this;
    $("input:submit",tform).attr("disabled","disabled");
    $("input:submit",tform).addClass("ui-state-disabled");
    $("#chamsgbox").removeClass().addClass('normalmessagebox').html('<img height="15px" src="style/ajax-loader.gif" />Validating....').fadeIn(500);
    $.post("deal_challenge.php", $(this).serialize() ,function(data) {
        if($.trim(data).substring(0,9)=='Challenge') {
            $("#chamsgbox").fadeTo(100,0.1,function() {
                $.get("fetch_challenge_history.php?pid="+$("#chaformpid").val()+"&username="+$("#chaformuser").val()+"&cid="+$("#chaformcid").val()+"&random="+Math.random(),function(data) {
                    $("#cchahistory").html(data);
                    $("#cchadetailcontent").html('<img height="15px" src="style/ajax-loader.gif" />Loading....');
                    $(".showchadet").click(function() {
                        var chaid=$(this).attr('name');
                        $("#cchadetail").show();
                        $.get("fetch_challenge_detail.php?cha_id="+chaid+"&random="+Math.random(),function(data) {
                            $("#cchadetailcontent").html(data);
                        });
                    });
                });
                $(this).html(data).addClass('normalmessageboxok').fadeTo(1000,1,function() {
                    $("#cchainfo").dialog("close");
                });
                reg=/[0-9]+/;
                var chaid=data.match(reg);
                charesfunc(chaid[0]);
            });
            $("input:submit",tform).removeAttr("disabled");
            $("input:submit",tform).removeClass("ui-state-disabled");
        }
        else {
            $("#chamsgbox").fadeTo(100,0.1,function() {
                $(this).html(data).addClass('normalmessageboxerror').fadeTo(300,1);
            });
            $("input:submit",tform).removeAttr("disabled");
            $("input:submit",tform).removeClass("ui-state-disabled");
        }
    });
    return false;
});

$("#cinfo_a").click(function() {
    self.document.location.hash="#info";
});

$("#cstand_a").click(function() {
    self.document.location.hash="#standing";
});

$("#cadminstand_a").click(function() {
    self.document.location.hash="#adminstanding";
});

$("#cprob_a").click(function() {
    self.document.location.hash="#problem/"+$(this).attr("name");
});

$("#cstatus_a").click(function() {
    self.document.location.hash="#status";
});

$("#creport_a").click(function() {
    self.document.location.hash="#report";
});

$("#cclar_a").click(function() {
    self.document.location.hash="#clarify";
});

$("#cset_a").click(function() {
    $("#csetdlg input:submit").button();
    $("#csetdlg").dialog('open');
    return false;
});

$(window).hashchange( function(){
    var dest=self.document.location.hash.substring(1);
    clearTimeout(reftable);
    clearTimeout(adminreftable);
    if (dest==""||dest=="info") $.get("contest_info.php",{cid: gcid, randomid: Math.random() },defaultfunc);
    else if (dest=="standing") {
        $("#contest_content").html('<div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>');
        updaterank();
    }
    else if (dest=="adminstanding") {
        $("#contest_content").html('<div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>');
        adminupdaterank();
    }
    else if (dest.substring(0,8)=="problem/") {
        showpfunc(dest.substring(8));
    }
    else if (dest.substring(0,6)=="status") {
        $.get("contest_status.php",{cid: gcid, randomid: Math.random()},function(data) {
            if (dest.length>6) statusfunc(data,dest.substring(7));
            else statusfunc(data);
        });
        if (!$.browser.msie||parseInt($.browser.version)>7) $("#contest_content").html('<div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>');
    }
    else if (dest=="report") {
        showreportfunc();
    }
    else if (dest=="clarify") {
        $.get("contest_clarify.php",{cid: gcid, randomid: Math.random()},clarfunc);
        if (!$.browser.msie||parseInt($.browser.version)>7) $("#contest_content").html('<div class="center" style="margin:0"><img src="style/ajax-loader.gif" />Loading...</div>');
    }
});

$(window).hashchange();

