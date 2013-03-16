        function striptags(a) {
            return a.replace(/(<([^>]+)>)/ig,"");
        }
$("#problem").addClass("tab_selected");
$( "a, button", ".functions" ).button();
$( "input:submit, input:reset", "#submitdialog" ).button();
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
adjustlist(pvid,pvname);
$(".submitprob").click(function() {
    if ($.cookie("username")==null) $("#logindialog").dialog("open");
    else $("#submitdialog").dialog("open");
    return false;
});


var serverdate=new Date(currenttime);

function padlength(what){
    var output=(what.toString().length==1)? "0"+what : what;
    return output;
}

function displaytime(){
    serverdate.setSeconds(serverdate.getSeconds()+1);
    var datestring=serverdate.getFullYear()+"-"+padlength(serverdate.getMonth()+1)+"-"+padlength(serverdate.getDate());
    var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds());
    document.getElementById("servertime").innerHTML=datestring+" "+timestring;
}
window.onload=function(){
    setInterval("displaytime()", 1000);
}

if ($.cookie("defaultshare")=="0") $("input[name='isshare']:nth(1)").attr("checked",true);
else $("input[name='isshare']:nth(0)").attr("checked",true);

var oris=100;

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

$("#ptags").click(function(){$("#ptagdetail").toggle()});
$("#utags").combobox().attr("name","utags");
$("#utags").next().css("padding","0.48em 0 0.47em 0.45em").css("width","450px");

$("#tagform").submit(function() {
    $.post("deal_problem_tag.php",$(this).serialize(),function(data) {
        alert(data);
    });
    return false;
});
