var curr_page=0;

$("#discuss").addClass("tab_selected");

function escapeHtml(unsafe) {
  return unsafe
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

var showtfunc=function() {
        var target=$("#showtopic");
        target.html('<div style="text-align:center"><img src="style/ajax-loader.gif" /> Loading...</div>');
        $.get("topic_data.php",{id: $(this).attr('name')},function(data){
            target.html(data);
            target.dialog("close");
            target.dialog("option","title",escapeHtml($("h1#topictitle",target).text()));
            $("a.topicshow",target).click(showtfunc);
            $("input:submit",target).button();

    $("#replybox").submit(function() {
        var tform=$("#replybox");
        $("input:submit",tform).attr("disabled","disabled");
        $("input:submit",tform).addClass("ui-state-disabled");
        $("#replymsgbox").removeClass().addClass('normalmessagebox').html('<img height="15px" src="style/ajax-loader.gif" /> Validating....').fadeIn(500);
        $.post(tform.attr("name"), tform.serialize() ,function(data)
        {
          if($.trim(data)=='Success!')
          {
                $("#replymsgbox").fadeTo(100,0.1,function()
                {
                  $(this).html('Success!').addClass('normalmessageboxok').fadeTo(800,1, function() {
                     window.location.reload();
                  });
                });
          }
          else
          {
                $("#replymsgbox").fadeTo(100,0.1,function()
                {
                   $(this).html(data).addClass('normalmessageboxerror').fadeTo(300,1);
                });
                $("input:submit",tform).removeAttr("disabled");
                $("input:submit",tform).removeClass("ui-state-disabled");
          }
       });
       return false;
    });

            target.dialog("open");
        });
        target.dialog("open");
        return false;
    }

var disfunc=function(data){
    $("#dcontent").html(data);

$("#showtopic").dialog({
    autoOpen: false,
    width:850,
//    minHeight:450,
//    show: 'clip',
//    hide: 'clip',
    modal:true,
    resizable:false,
    draggable:false
});
$("#newtopic").dialog({
    title: "New Topic",
    autoOpen: false,
    width:850,
    show: 'clip',
    hide: 'clip',
    modal:true,
    resizable:false,
    draggable:false
});

    $("#dcontent a.texpand").button({
//        icons: {
//            primary:"ui-icon-triangle-1-w"
//        }
    });
    $("#dcontent a.thide").button({
//        icons: {
//            primary:"ui-icon-triangle-1-s"
//        }
    });
    $("#dcontent a.tnone").button();
//    $("#dcontent a.texpand .ui-button-text").css("height","10");
//    $("#dcontent a.thide .ui-button-text").css("height","10");
    $("#dcontent a.texpand span.ui-button-text").css("padding","0 .6em");
    $("#dcontent a.thide span.ui-button-text").css("padding","0 .6em");
    $("#dcontent a.tnone span.ui-button-text").css("padding","0 .6em");
    $("#dcontent a.tnone").attr("disabled","disabled");
    $("#dcontent a.tnone").addClass("ui-state-disabled");
//    $("#dcontent a.texpand").width(30);
    $("#dcontent a.button").button();
    $("#newtopic").click(function() {
        return false;
    });
    $("li.tsubject:has(ul) > a.texpand").show();
    $("li.tsubject:has(ul) > a.tnone").hide();
    $("a.texpand").click(function() {
        $(this).hide();
        $(this).next().show();
//        $(this).next().next().next().next().next().next().show("blind",300);
        $(this).next().nextAll("ul").show("blind",300);
        return false;
    });
    $("a.thide").click(function() {
        $(this).hide();
        $(this).prev().show();
        $(this).nextAll("ul").hide("blind",300);
        return false;
    });

    $("#disprev").click(function() {
        $.get("discuss_data.php",{pid:ppid, page: curr_page-1, randomid: Math.random() },disfunc);
        $(".dcontrol .button").attr("disabled","disabled");
        $(".dcontrol .button").addClass("ui-state-disabled");
        curr_page--;
        return false;
    });

    $("#disfirst").click(function() {
        $.get("discuss_data.php",{pid:ppid, page: 0, randomid: Math.random() },disfunc);
        $(".dcontrol .button").attr("disabled","disabled");
        $(".dcontrol .button").addClass("ui-state-disabled");
        curr_page=0;
        return false;
    });


    $("#disnext").click(function() {
        $.get("discuss_data.php",{pid:ppid, page: curr_page+1, randomid: Math.random() },disfunc);
        $(".dcontrol .button").attr("disabled","disabled");
        $(".dcontrol .button").addClass("ui-state-disabled");
        curr_page++;
        return false;
    });
    $(".dcontrol .button").removeAttr("disabled");
    $(".dcontrol .button").removeClass("ui-state-disabled");
    if (curr_page>0) {
        $("#disprev").show();
        $("#disfirst").show();
    }
    $("a.topicshow").click(showtfunc);
    $("a#disnew").click(function(){
        $("#newtopic input:submit").button();
        $("#newtopic").dialog("open");
        return false;
    });
    $("#newtopicform input:submit").click(function() {
        var tform=$("#newtopicform");
        $("input:submit",tform).attr("disabled","disabled");
        $("input:submit",tform).addClass("ui-state-disabled");
        $("#newtmsgbox").removeClass().addClass('normalmessagebox').html('<img height="15px" src="style/ajax-loader.gif" /> Validating....').fadeIn(500);
        $.post(tform.attr("name"), tform.serialize() ,function(data)
        {
          if($.trim(data)=='Success!')
          {
                $("#newtmsgbox").fadeTo(100,0.1,function()
                {
                  $(this).html('Success!').addClass('normalmessageboxok').fadeTo(800,1, function() {
                     window.location.reload();
                  });
                });
          }
          else
          {
                $("#newtmsgbox").fadeTo(100,0.1,function()
                {
                   $(this).html(data).addClass('normalmessageboxerror').fadeTo(300,1);
                });
                $("input:submit",tform).removeAttr("disabled");
                $("input:submit",tform).removeClass("ui-state-disabled");
          }
       });
       return false;
    });
}

$.get("discuss_data.php",{pid:ppid, randomid: Math.random()},disfunc);

