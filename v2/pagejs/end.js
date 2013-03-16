/*$('.nav').nmcDropDown({
    show: {height: 'show', opacity: 'show'}
});*/

var timeout    = 200;
var closetimer = 0;
var ddmenuitem = 0;

function jsddm_open()
{  jsddm_canceltimer();
   jsddm_close();
   ddmenuitem = $(this).find('ul').css('visibility', 'visible');}

function jsddm_close()
{  if(ddmenuitem) ddmenuitem.css('visibility', 'hidden');}

function jsddm_timer()
{  closetimer = window.setTimeout(jsddm_close, timeout);}

function jsddm_canceltimer()
{  if(closetimer)
   {  window.clearTimeout(closetimer);
      closetimer = null;}}

$(document).ready(function()
{
    $('.nav > li').bind('mouseover', jsddm_open)
    $('.nav > li').bind('mouseout',  jsddm_timer)}
);

document.onclick = jsddm_close;

$(".nav li").hover(
    function() {
        $(this).addClass("open");
    } ,
    function() {
        $(this).removeClass("open");
    }
);

$("#logindialog").dialog({
    autoOpen: false,
    modal:true,
    show: 'clip',
    hide: 'clip',
    resizable:false,
    draggable:false
});
$("#regdialog").dialog({
    autoOpen: false,
    width:540,
    height:330,
    modal:true,
    show: 'clip',
    hide: 'clip',
    resizable:false,
    draggable:false
});
$("#newsshowdialog").dialog({
    autoOpen: false,
    width:800,
    height:600,
    modal:true,
    show: 'clip',
    hide: 'clip'
});

$("#login").click(function() {
    $("#logindialog").dialog("open");
});
$(".toregister").click(function() {
    if ($("#logindialog").dialog("isOpen")) $("#logindialog").dialog("close");
//    $("#regdialog").dialog("moveToTop");
    $("#regdialog").dialog("open");
});

$(".newslink").click(function() {
    var nnid=$(this).attr("name");
    $.get("fetch_news.php",{'nnid':nnid,'rand':Math.random() }, function(data) {
        var gval=eval("("+data+")");
        $("#newsshowdialog #sntitle").html(gval.ntitle);
        $("#newsshowdialog #sncontent").html(gval.ncontent);
        $("#newsshowdialog #sntime").html(gval.time_added);
        $("#newsshowdialog #snauthor").html(gval.author);
        $("#newsshowdialog .newseditbutton").attr("name",gval.newsid);
        $("#newsshowdialog").dialog({"title": gval.ntitle});
        $("#newsshowdialog").dialog("open");
    });
    return false;
});

$(".newseditbutton").button();
$(".newseditbutton").click(function() {
    location.href="admin_index.php?newsid="+$(this).attr("name")+"#newstab";
    return false;
});

$(".error").errorStyle();

$(document).ready(function() {
    if (parseInt($("#header").css("width").substring(0,$("#header").css("width").length-2))<1000) {
        $("#header").css("width","1000px");
        $("#footer").css("width","1000px");
    }
});

$(window).resize(function() {
    $("#header, #footer").css("width","100%");
    if (parseInt($("#header").css("width").substring(0,$("#header").css("width").length-2))<1000) {
        $("#header").css("width","1000px");
        $("#footer").css("width","1000px");
    }
});
