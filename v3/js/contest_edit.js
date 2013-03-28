$(document).ready(function() {
    $('.datepick').datetimepicker({
        format: 'yyyy-mm-dd hh:ii:ss'
    });

    $("#cmodifyform").bind("correct",function(){
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

    $(".ptype:checked").change();
    $("input[name='ctype']:checked").change();
});