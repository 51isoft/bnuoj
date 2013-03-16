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


function resetpdetail() {
    $("#pdetail")[0].reset();
    CKEDITOR.instances.tdescription.setData("");
    CKEDITOR.instances.tinput.setData("");
    CKEDITOR.instances.toutput.setData("");
    CKEDITOR.instances.thint.setData("");
    $("textarea[name='sample_in']").text("");
    $("textarea[name='sample_out']").text("");
    $("textarea[name='hint']").text("");
    $("textarea[name='source']").text("");
}

function resetcdetail() {
    $("#cdetail")[0].reset();
    CKEDITOR.instances.treport.setData("");
}

function resetndetail() {
    $("#ndetail")[0].reset();
    CKEDITOR.instances.tncontent.setData("");
}

function probload(pid) {
    $.get('fetch_problem.php?pid='+pid+"&rand="+Math.random(),function(data) {
//      alert(data);
        if ($.trim(data)=='Error!') {
            alert(data);
        }
        else {
            data=eval('('+data+')');
            $("input[name='p_id']").val(data.pid);
            $("input[name='p_name']").val(data.title);
            $("input[name='time_limit']").val(data.tl);
            $("input[name='case_time_limit']").val(data.ctl);
            $("input[name='memory_limit']").val(data.ml);
            $("input[name='noc']").val(data.noc);
            $("textarea[name='sample_in']").text(data.sinp);
            $("textarea[name='sample_out']").text(data.sout);
            $("textarea[name='hint']").text(data.hint);
            $("textarea[name='source']").text(data.source);
            $("textarea[name='author']").text(data.author);
            $("input[name='p_hide']").each(function() {
                if (this.value==data.p_hide) this.checked=true;
            });
            $("input[name='p_ignore_noc']").each(function() {
                if (this.value==data.p_ignore_noc) this.checked=true;
            });
            $("input[name='special_judge_status']").each(function() {
                if (this.value==data.spj) this.checked=true;
            });
            $("input[name='hide']").each(function() {
                if (this.value==data.hide) this.checked=true;
            });
            CKEDITOR.instances.tdescription.setData(data.desc);
            CKEDITOR.instances.tinput.setData(data.inp);
            CKEDITOR.instances.toutput.setData(data.oup);
            CKEDITOR.instances.thint.setData(data.hint);
        }
    });
}

$("#pload").submit(function() {
    probload($("#npid").val());
    return false;
});

$("#pdetail").submit(function() {
    $("button:submit",this).attr("disabled", true).addClass("ui-state-disabled");
    CKEDITOR.instances.tdescription.updateElement();
    CKEDITOR.instances.tinput.updateElement();
    CKEDITOR.instances.toutput.updateElement();
    CKEDITOR.instances.thint.updateElement();
    $.post('admin_deal_problem.php',$("#pdetail").serialize(),function(data) {
        alert(data);
        $("#pdetail button:submit").attr("disabled", false).removeClass("ui-state-disabled");
        if ($.trim(data)!='Failed.') resetpdetail();
    });
    return false;
});

$("#notiform").submit(function() {
    $.post('admin_deal_notify.php',$("#notiform").serialize(),function(data) {
        alert(data);
    });
    return false;
});

function conload(cid) {
    $.get('fetch_contest.php?cid='+cid+"&rand="+Math.random(),function(data) {
        if ($.trim(data)=='Error!') {
            alert(data);
        }
        else {
            data=eval('('+data+')');
            $("#cdetail").populate(data);
            CKEDITOR.instances.treport.setData(data.report);
            var ctp=$("input[name='ctype']:checked").val();
            if (ctp=='0') {
                $(".selptype , .selpara").hide();
            } else if (ctp=='1') {
                $(".tc").hide();
                $(".cf").show();
                $(".paraa").val('2');
                $(".parab").val('50');
                $(".selptype , .selpara").show();
                $(".typenote").text("In CF, Parameter A represents the points lost per minute. Parameter B represents the points lost for each incorrect submit.");
            }
            if ($("input[name='has_cha']:checked").val()=="1") $(".chatimerow").show();
            else $(".chatimerow").hide();
        }
    });
}

$("#cload").submit(function() {
    conload($("#ncid").val());
    return false;
});

$("#clockp").click(function() {
    $.get('admin_deal_lock.php?hide=1&cid='+$("#ncid").val()+"&rand="+Math.random(),function(data) {
        alert(data);
    });
});
$("#culockp").click(function() {
    $.get('admin_deal_lock.php?hide=0&cid='+$("#ncid").val()+"&rand="+Math.random(),function(data) {
        alert(data);
    });
});
$("#cshare").click(function() {
    $.get('admin_deal_share.php?share=1&cid='+$("#ncid").val()+"&rand="+Math.random(),function(data) {
        alert(data);
    });
});
$("#cunshare").click(function() {
    $.get('admin_deal_share.php?share=0&cid='+$("#ncid").val()+"&rand="+Math.random(),function(data) {
        alert(data);
    });
});

$("#ctestall").click(function() {
    $.get('admin_deal_testall.php?cid='+$("#ncid").val()+"&rand="+Math.random(),function(data) {
        alert(data);
    });
});

$("#cdetail").submit(function() {
    $("button:submit",this).attr("disabled", true).addClass("ui-state-disabled");
    CKEDITOR.instances.treport.updateElement();
    $.post('admin_deal_contest.php',$("#cdetail").serialize(),function(data) {
        alert(data);
        $("#cdetail button:submit").attr("disabled", false).removeClass("ui-state-disabled");
        if ($.trim(data)!='Failed.') resetcdetail();
    });
    return false;
});

function newsload(nnid) {
    $.get('fetch_news.php?nnid='+nnid+"&rand="+Math.random(),function(data) {
        if ($.trim(data)=='Error!') {
            alert(data);
        }
        else {
            data=eval('('+data+')');
            $("#ndetail").populate(data);
            CKEDITOR.instances.tncontent.setData(data.ncontent);
        }
    });
}

$("#nload").submit(function() {
    newsload($("#nnid").val());
    return false;
});

$("#ndetail").submit(function() {
    $("button:submit",this).attr("disabled", true).addClass("ui-state-disabled");
    CKEDITOR.instances.tncontent.updateElement();
    $.post('admin_deal_news.php',$("#ndetail").serialize(),function(data) {
        alert(data);
        $("#ndetail button:submit").attr("disabled", false).removeClass("ui-state-disabled");
        if ($.trim(data)!='Failed.') resetndetail();
    });
    return false;
});

$("#crej").submit(function() {
    $.get('admin_deal_rejudge.php?type=1&cid='+$("#rejcid").val()+'&pid='+$("#rejpid").val()+"&rac="+$("input[name='rejac']:checked").val()+"&rand="+Math.random(),function(data) {
        alert(data);
    });
    return false;
});
$("#cprej").submit(function() {
    $.get('admin_deal_rejudge.php?type=2&cid='+$("#rcid").val()+'&pid='+$("#rpid").val()+"&rac="+$("input[name='rac']:checked").val()+"&rand="+Math.random(),function(data) {
        alert(data);
    });
    return false;
});
$("#runrej").submit(function() {
    $.get('admin_run_rejudge.php?runid='+$("#runid").val()+"&rand="+Math.random(),function(data) {
        alert(data);
    });
    return false;
});
$("#cha_crej").submit(function() {
    $.get('admin_deal_rejudge_challenge.php?type=all&cid='+$("#rcha_cid").val()+"&rand="+Math.random(),function(data) {
        alert(data);
    });
    return false;
});


$("#spinfo").click(function() {
    $(".syncbutton").attr("disabled", true).addClass("ui-state-disabled");
    $("#syncwait").html('<img src="style/ajax-loader.gif" /> Loading...').show();
    $.get('admin_sync_problem.php',function(data) {
        $("#syncwait").html(data);
        $(".syncbutton").attr("disabled", false).removeClass("ui-state-disabled");
    });
});

$("#suinfo").click(function() {
    $(".syncbutton").attr("disabled", true).addClass("ui-state-disabled");
    $("#syncwait").html('<img src="style/ajax-loader.gif" /> Loading...').show();
    $.get('admin_sync_user.php',function(data) {
        $("#syncwait").html(data);
        $(".syncbutton").attr("disabled", false).removeClass("ui-state-disabled");
    });
});

$(".ptype").change(function() {
    var ptp=$(this).val();
//    alert(ptp);
    if (ptp=='0') {
        $(this).nextAll("div").hide();
    } else if (ptp=='1') {
        var aa=$(this).parent().nextAll(".selpara").children(".cf");
        $(this).parent().nextAll(".selpara").children().hide();
        aa.children(".paraa").val("2");
        aa.children(".parab").val("50");
        aa.show();
        $(this).parent().nextAll(".selpara").children(".typenote").text("In CF, Parameter A represents the points lost per minute. Parameter B represents the points lost for each incorrect submit.").show();
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
        $(this).parent().nextAll(".selpara").children(".typenote").html("In TC, parameters defined as below. A + B must equal to 1. Parameter C is the length of this contest in TopCoder. Parameter E is the percentage of penalty for each incorrect submit.<br /><img src='tcpoint.png' />").show();
        $(this).parent().nextAll(".selpara").show();

    }
});

$("input[name='ctype']").change(function() {
    var ctp=$(this).val();
    //alert(ctp);
    if (ctp=='0') {
        $(".selptype , .selpara").hide();
    } else if (ctp=='1') {
        $(".tc").hide();
        $(".cf").show();
        $(".paraa").val('2');
        $(".parab").val('50');
        $(".selptype , .selpara").show();
        $(".typenote").text("In CF, Parameter A represents the points lost per minute. Parameter B represents the points lost for each incorrect submit.");
    }
});

var replayoptions = { 
    beforeSubmit:  showRequest,  // pre-submit callback 
    success:       showResponse  // post-submit callback
}; 

function showRequest(data){
    $("#dealreplay").show();
    $("#replaysubmit").attr("disabled", true).addClass("ui-state-disabled");
    return true;
}

function showResponse(data){
    $("#dealreplay").html(data);
    $("#replaysubmit").attr("disabled", false).removeClass("ui-state-disabled");
}

function deal(id,oj,$target) {
    $.get("api/get_pinfo_admin.php?vid="+id+"&vname="+oj+"&randomid="+Math.random(),function(data) {
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

$("#replayform").ajaxForm(replayoptions);

$("#replaycrawl").click(function() {
    $(this).attr("disabled", true).addClass("ui-state-disabled");
    $.get("admin_deal_crawl_replay.php?oj="+$("#vcojname").val()+"&cid="+$("#vcid").val(),function(data) {
        //alert(data);
        data=eval('('+data+')');
        if (data.result==0) $("#replayform").populate(data);
        else alert("Error Occured!");
        $("#replaycrawl").attr("disabled", false).removeClass("ui-state-disabled");
        $(".vpid").keyup();
    });
    return false;
});

$("#cclonecid").click(function() {
    var val=$("#clcid").val();
    $(this).attr("disabled", true).addClass("ui-state-disabled");
    $.get("api/get_cprob_admin.php?type=cid&value="+val,function(data) {
        //alert(data);
        data=eval('('+data+')');
        if (data.result==0) $("#cdetail").populate(data,{resetForm:false});
        else alert("Error Occured!");
        $("#cclonecid").attr("disabled", false).removeClass("ui-state-disabled");
    });
    return false;
});

$("#cclonesrc").click(function() {
    var val=$("#clsrc").val();
    $(this).attr("disabled", true).addClass("ui-state-disabled");
    $.get("api/get_cprob_admin.php?type=src&value="+val,function(data) {
        //alert(data);
        data=eval('('+data+')');
        if (data.result==0) $("#cdetail").populate(data,{resetForm:false});
        else alert("Error Occured!");
        $("#cclonesrc").attr("disabled", false).removeClass("ui-state-disabled");
    });
    return false;
});

$("#vclonecid").click(function() {
    var val=$("#vclcid").val();
    $(this).attr("disabled", true).addClass("ui-state-disabled");
    $.get("api/get_cprob_admin.php?out=v&type=cid&value="+val,function(data) {
        //alert(data);
        data=eval('('+data+')');
        if (data.result==0) $("#replayform").populate(data,{resetForm:false});
        else alert("Error Occured!");
        $("#vclonecid").attr("disabled", false).removeClass("ui-state-disabled");
        $(".vpid").keyup();
    });
    return false;
});

$("#vclonesrc").click(function() {
    var val=$("#vclsrc").val();
    $(this).attr("disabled", true).addClass("ui-state-disabled");
    $.get("api/get_cprob_admin.php?out=v&type=src&value="+val,function(data) {
        //alert(data);
        data=eval('('+data+')');
        if (data.result==0) $("#replayform").populate(data,{resetForm:false});
        else alert("Error Occured!");
        $("#vclonesrc").attr("disabled", false).removeClass("ui-state-disabled");
        $(".vpid").keyup();
    });
    return false;
});

$("#admintab").tabs();
$("input:submit, button").button();
$('.datepick').datetimepicker({
    showSecond: true,
    dateFormat: 'yy-mm-dd',
    timeFormat: 'hh:mm:ss'
});

if (getURLPara('cid')!=null) {
    conload(getURLPara('cid'));
}

if (getURLPara('pid')!=null) {
    probload(getURLPara('pid'));
}

if (getURLPara('newsid')!=null) {
    newsload(getURLPara('newsid'));
}

$("input[name='has_cha']").change(function() {
    var hc=$(this).val();
    if (hc==1) $(".chatimerow").show();
    else $(".chatimerow").hide();
});

$("#userspace").addClass("tab_selected");
