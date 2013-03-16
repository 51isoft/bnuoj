$("button").button();
$("#addc").submit(function(data) {
    $("#control button").attr("disabled", true).addClass("ui-state-disabled");
    var ulist="";
    var unum=0;
    $("#stat_info tbody th").each(function() {
        ulist+="uname"+unum+"="+$(this).text()+"&";
        unum++;
    });
    var ccid=$("#cnum").val();
    $("#control input").val("");
    $.post("fetch_training_contest.php",ulist+"cid="+ccid,function(data) {
        if ($.trim(data)!="Error!") {
            $("<th class='real'><a href='contest_show.php?cid="+ccid+"' target='_blank'>"+ccid+"</a></th><th>*</th>").insertBefore($("#stat_info thead th.mylast"));
            var tres=eval("("+data+")");
            for (i=0;i<unum;i++) {
                $("<td>"+tres[i*2]+"</td><td>"+tres[i*2+1]+"</td>").insertBefore($("#stat_info tbody tr:nth-child("+(i+1)+") td.mylast"));
                $("#stat_info tbody tr:nth-child("+(i+1)+") td.mylast").html(parseInt($("#stat_info tbody tr:nth-child("+(i+1)+") td.mylast").html())+parseInt(tres[i*2]));
                $("#stat_info tbody tr:nth-child("+(i+1)+") td.mylast").next().html(parseInt($("#stat_info tbody tr:nth-child("+(i+1)+") td.mylast").next().html())+parseInt(tres[i*2+1]));
            }
            $("#stat_info").trigger("destroy");
            $("#stat_info").tablesorter(); 
        } 
        else alert("No such contest!");
        $("#control button").attr("disabled", false).removeClass("ui-state-disabled");
    });
    return false;
});
$("#addu").submit(function(data) {
    $("#control button").attr("disabled", true).addClass("ui-state-disabled");
    var clist="";
    var cnum=0;
    var uname=$("#uname").val();
    $("#control input").val("");
    $("#stat_info thead th.real").each(function() {
        clist+="cid"+cnum+"="+$(this).text()+"&";
        cnum++;
    });
    $.post("fetch_training_user.php",clist+"username="+uname,function(data) {
        if ($.trim(data)!="Error!") {
            $("#stat_info tbody").append("<tr><th>"+uname+"</th></tr>");
            var tres=eval("("+data+")");
            var t1=0,t2=0;
            for (i=0;i<cnum*2;i++) {
                if (i%2) t2+=parseInt(tres[i]);
                else t1+=parseInt(tres[i]);
                $("#stat_info tbody tr:last-child").append("<td>"+tres[i]+"</td>");
            }
            $("#stat_info tbody tr:last-child").append("<td class='mylast'>"+t1+"</td>");
            $("#stat_info tbody tr:last-child").append("<td>"+t2+"</td>");
            $("#stat_info").trigger("destroy");
            $("#stat_info").tablesorter(); 
        }
        else alert("No such user!");
        $("#control button").attr("disabled", false).removeClass("ui-state-disabled");
    });
    return false;
})
$("#stat_info").tablesorter();
