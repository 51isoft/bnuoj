$("#login_form").submit(function()
{
        var tform=this;
        $("input:submit",tform).attr("disabled","disabled");
        $("input:submit",tform).addClass("ui-state-disabled");
        //remove all the class add the messagebox classes and start fading
        $("#loginmsgbox").removeClass().addClass('normalmessagebox').html('<img height="15px" src="style/ajax-loader.gif" />Validating....').fadeIn(500);
        //check the username exists or not from ajax
        $.post("login.php",{ username:$('#username').val(),password:$('#password').val(),cksave:$('#cksave').val() } ,function(data)
        {
          if($.trim(data)=='Yes') //if correct login detail
          {
                $("#loginmsgbox").fadeTo(100,0.1,function()  //start fading the messagebox
                {
                  //add message and change the class of the box and start fading
                  $(this).html('Logging in.....').addClass('normalmessageboxok').fadeTo(100,1,
                  function()
                  {
                     window.location.reload();
//                     $("#loginbar").hide();
//                     $("#logoutbar").show();
//                     $(':input','#login_form').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
//                     $(this).html('').removeClass();
//                     $.cookie('username',$('#username').val());
                  });
                  $("#logindialog").dialog("close");
                });
          }
          else
          {
                $("#loginmsgbox").fadeTo(100,0.1,function() //start fading the messagebox
                {
                  //add message and change the class of the box and start fading
                  $(this).html(data).addClass('normalmessageboxerror').fadeTo(300,1);
                });
                $("input:submit",tform).removeAttr("disabled");
                $("input:submit",tform).removeClass("ui-state-disabled");
          }
       });
       return false;//not to post the  form physically
});

$("#logout").click(function()
{
    $.cookie('username',null);
    $.cookie('password',null);
    window.location.reload();
//    $("#logoutbar").hide();
//    $("#loginbar").show();
});
