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
    $.get('ajax/admin_get_problem.php?pid='+pid+"&rand="+Math.random(),function(data) {
        data=eval('('+data+')');
        if (data.code!=0) {
            alert(data.msg);
        }
        else {
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
function conload(cid) {
    $.get('ajax/admin_get_contest.php?cid='+cid+"&rand="+Math.random(),function(data) {
        data=eval('('+data+')');
        if (data.code!=0) {
            alert(data.msg);
        }
        else {
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

function newsload(nnid) {
    $.get('ajax/get_news.php?nnid='+nnid+"&rand="+Math.random(),function(data) {
        data=eval('('+data+')');
        if (data.code!=0) {
            alert(data.msg);
        }
        else {
            $("#ndetail").populate(data);
            CKEDITOR.instances.tncontent.setData(data.ncontent);
        }
    });
}


$(document).ready(function() {

    $("option[value=BNU]","select[name=pcoj]").remove();

    $("#notiform").bind("correct",function() {
        $("input:submit,button:submit,.btn",this).removeAttr("disabled").removeClass("disabled");
    });

    $("#pdetail").bind("preprocess",function() {
        CKEDITOR.instances.tdescription.updateElement();
        CKEDITOR.instances.tinput.updateElement();
        CKEDITOR.instances.toutput.updateElement();
        CKEDITOR.instances.thint.updateElement();
    });
    $("#pdetail").bind("correct",function() {
        $("input:submit,button:submit,.btn",this).removeAttr("disabled").removeClass("disabled");
        resetpdetail();
    });

    $("#pload").submit(function() {
        probload($("#npid").val());
        return false;
    });


    $("#cdetail").bind("preprocess",function() {
        CKEDITOR.instances.treport.updateElement();
    });
    $("#cdetail").bind("correct",function() {
        $("input:submit,button:submit,.btn",this).removeAttr("disabled").removeClass("disabled");
        resetcdetail();
    });

    $("#cload").submit(function() {
        conload($("#ncid").val());
        return false;
    });

    $("#clockp").click(function() {
        $.get('ajax/admin_deal_lock.php?hide=1&cid='+$("#ncid").val()+"&rand="+Math.random(),function(data) {
            data=eval('('+data+')');
            alert(data.msg);
        });
    });
    $("#culockp").click(function() {
        $.get('ajax/admin_deal_lock.php?hide=0&cid='+$("#ncid").val()+"&rand="+Math.random(),function(data) {
            data=eval('('+data+')');
            alert(data.msg);
        });
    });
    $("#cshare").click(function() {
        $.get('ajax/admin_deal_share.php?share=1&cid='+$("#ncid").val()+"&rand="+Math.random(),function(data) {
            data=eval('('+data+')');
            alert(data.msg);
        });
    });
    $("#cunshare").click(function() {
        $.get('ajax/admin_deal_share.php?share=0&cid='+$("#ncid").val()+"&rand="+Math.random(),function(data) {
            data=eval('('+data+')');
            alert(data.msg);
        });
    });

    $("#ctestall").click(function() {
        $.get('ajax/admin_deal_testall.php?cid='+$("#ncid").val()+"&rand="+Math.random(),function(data) {
            data=eval('('+data+')');
            alert(data.msg);
        });
    });

    $(".ptype").change(function() {
        var ptp=$(this).val();
    //    alert(ptp);
        if (ptp=='0') {
            $(this).nextAll("div").hide();
        } else if (ptp=='1') {
            var aa=$(this).parent().parent().nextAll(".selpara").children(".cf");
            $(this).parent().parent().nextAll(".selpara").children().hide();
            $(".paraa",aa).val("2");
            $(".parab",aa).val("50");
            aa.show();
            $(this).parent().parent().nextAll(".selpara").children(".typenote").text("In CF, Parameter A represents the points lost per minute. Parameter B represents the points lost for each incorrect submit.").show();
            $(this).parent().parent().nextAll(".selpara").show();
        } else if (ptp=='2') {
            var aa=$(this).parent().parent().nextAll(".selpara").children(".tc");
            $(this).parent().parent().nextAll(".selpara").children().hide();
            $(".paraa",aa).val("0.3");
            $(".parab",aa).val("0.7");
            $(".parac",aa).val("4500");
            $(".parad",aa).val("10");
            $(".parae",aa).val("10");
            aa.show();
            $(this).parent().parent().nextAll(".selpara").children(".typenote").html("In TC, parameters defined as below. A + B must equal to 1. Parameter C is the length of this contest in TopCoder. Parameter E is the percentage of penalty for each incorrect submit.<br /><img src='img/tcpoint.png' />").show();
            $(this).parent().parent().nextAll(".selpara").show();

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


    $("#nload").submit(function() {
        newsload($("#nnid").val());
        return false;
    });
    $("#ndetail").bind("preprocess",function() {
        CKEDITOR.instances.tncontent.updateElement();
    });
    $("#ndetail").bind("correct",function() {
        $("input:submit,button:submit,.btn",this).removeAttr("disabled").removeClass("disabled");
        resetndetail();
    });

    $("#crej").submit(function() {
        $.get('ajax/admin_deal_rejudge.php?type=1&cid='+$("#rejcid").val()+'&pid='+$("#rejpid").val()+"&rac="+$("input[name='rejac']:checked").val()+"&rand="+Math.random(),function(data) {
            data=eval('('+data+')');
            alert(data.msg);
        });
        return false;
    });
    $("#cprej").submit(function() {
        $.get('ajax/admin_deal_rejudge.php?type=2&cid='+$("#rcid").val()+'&pid='+$("#rpid").val()+"&rac="+$("input[name='rac']:checked").val()+"&rand="+Math.random(),function(data) {
            data=eval('('+data+')');
            alert(data.msg);
        });
        return false;
    });
    $("#runrej").submit(function() {
        $.get('ajax/admin_deal_rejudge_run.php?runid='+$("#runid").val()+"&rand="+Math.random(),function(data) {
            data=eval('('+data+')');
            alert(data.msg);
        });
        return false;
    });
    $("#cha_crej").submit(function() {
        $.get('ajax/admin_deal_rejudge_challenge.php?type=all&cid='+$("#rcha_cid").val()+"&rand="+Math.random(),function(data) {
            data=eval('('+data+')');
            alert(data.msg);
        });
        return false;
    });


    $("#spinfo").click(function() {
        $(".syncbutton").attr("disabled", true).addClass("disabled");
        $("#syncwait").html('<img src="img/ajax-loader.gif" /> Loading...').show();
        $.get('ajax/admin_sync_problem.php',function(data) {
            data=eval('('+data+')');
            $("#syncwait").html(data.msg);
            $(".syncbutton").attr("disabled", false).removeClass("disabled");
        });
    });

    $("#suinfo").click(function() {
        $(".syncbutton").attr("disabled", true).addClass("disabled");
        $("#syncwait").html('<img src="img/ajax-loader.gif" /> Loading...').show();
        $.get('ajax/admin_sync_user.php',function(data) {
            data=eval('('+data+')');
            $("#syncwait").html(data.msg);
            $(".syncbutton").attr("disabled", false).removeClass("disabled");
        });
    });

    function deal(id,oj,$target) {
        $.get("ajax/admin_get_problem_basic.php?vid="+id+"&vname="+oj+"&randomid="+Math.random(),function(data) {
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


    $("#replaycrawl").ajaxForm({
      beforeSubmit: function (formData, tform, options) {
        tform.trigger("preprocess");
        $("input:submit,button:submit,.btn",tform).attr("disabled","disabled").addClass("disabled");
        $("#msgbox",tform).removeClass().addClass('alert').html('<img style="height:20px" src="img/ajax-loader.gif" /> Validating....').fadeIn(500);
        return true;
      },
      success: function(responseText, statusText, xhr, form) {
        responseText=eval("("+responseText+")");
        if (responseText.code=='0') {
          $("#msgbox",form).fadeTo(100,0.1,function() {
            $(this).html(responseText.msg).removeClass().addClass('alert alert-success').fadeTo(100,1,function(){
                $("#replayform").populate(responseText);
                $("input:submit,button:submit,.btn",form).removeAttr("disabled").removeClass("disabled");
                $(".vpid").keyup();
            });
          });
        }
        else {
          $("#msgbox",form).fadeTo(100,0.1,function() {
            $(this).html(responseText.msg).removeClass().addClass('alert alert-error').fadeTo(300,1);
          });
          $("input:submit,button:submit,.btn",form).removeAttr("disabled").removeClass("disabled");
        }
      }
    });

    $("#replayform").bind("correct",function() {
        $("input:submit,button:submit,.btn",this).removeAttr("disabled").removeClass("disabled");
        resetcdetail();
    });


    $("#cclonecid").click(function() {
        var val=$("#clcid").val();
        $(this).attr("disabled", true).addClass("disabled");
        $.get("ajax/admin_get_contest_problems.php?type=cid&value="+val,function(data) {
            //alert(data);
            data=eval('('+data+')');
            if (data.result==0) $("#cdetail").populate(data,{resetForm:false});
            else alert("Error Occured!");
            $("#cclonecid").attr("disabled", false).removeClass("disabled");
        });
        return false;
    });

    $("#cclonesrc").click(function() {
        var val=$("#clsrc").val();
        $(this).attr("disabled", true).addClass("disabled");
        $.get("ajax/admin_get_contest_problems.php?type=src&value="+val,function(data) {
            //alert(data);
            data=eval('('+data+')');
            if (data.result==0) $("#cdetail").populate(data,{resetForm:false});
            else alert("Error Occured!");
            $("#cclonesrc").attr("disabled", false).removeClass("disabled");
        });
        return false;
    });

    $("#vclonecid").click(function() {
        var val=$("#vclcid").val();
        $(this).attr("disabled", true).addClass("disabled");
        $.get("ajax/admin_get_contest_problems.php?out=v&type=cid&value="+val,function(data) {
            //alert(data);
            data=eval('('+data+')');
            if (data.result==0) $("#replayform").populate(data,{resetForm:false});
            else alert("Error Occured!");
            $("#vclonecid").attr("disabled", false).removeClass("disabled");
            $(".vpid").keyup();
        });
        return false;
    });

    $("#vclonesrc").click(function() {
        var val=$("#vclsrc").val();
        $(this).attr("disabled", true).addClass("disabled");
        $.get("ajax/admin_get_contest_problems.php?out=v&type=src&value="+val,function(data) {
            //alert(data);
            data=eval('('+data+')');
            if (data.result==0) $("#replayform").populate(data,{resetForm:false});
            else alert("Error Occured!");
            $("#vclonesrc").attr("disabled", false).removeClass("disabled");
            $(".vpid").keyup();
        });
        return false;
    });

    //$("#admintab").tabs();
    //$("input:submit, button").button();
    $('.datepick').datetimepicker({
        format: 'yyyy-mm-dd hh:ii:ss'
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

    $("#userspace").addClass("active");
    var dest=self.document.location.hash;
    //alert(dest);
    if (dest!="#") $("[href='"+dest+"']","#admintab").click();
});