function striptags(a) {
    return a.replace(/(<([^>]+)>)/ig,"");
}
$("input:submit").button();
$('.datepick').datetimepicker({
	showSecond: true,
	dateFormat: 'yy-mm-dd',
	timeFormat: 'hh:mm:ss'
});
  $("#cmodifyform").submit(function() {
    var tform=$("#cmodifyform");
    $("input:submit",tform).attr("disabled","disabled");
    $("input:submit",tform).addClass("ui-state-disabled");
    $("#arrangemsgbox").removeClass().addClass('normalmessagebox').html('<img height="15px" src="style/ajax-loader.gif" /> Validating....').fadeIn(500);
    $.post("modify_vcontest.php", tform.serialize() ,function(data)
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
        aa.show();
        $(this).parent().nextAll(".selpara").show();
    } else if (ptp=='2') {
        var aa=$(this).parent().nextAll(".selpara").children(".tc");
        $(this).parent().nextAll(".selpara").children().hide();
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
        $(".selptype , .selpara, .typenote").show();
    }
});

$(".ptype:checked").change();
$("input[name='ctype']:checked").change();
