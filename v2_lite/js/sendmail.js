$("#mailsend").submit(function()
{
        var tform=this;
        $("input:submit",tform).attr("disabled","disabled");
        $("input:submit",tform).addClass("ui-state-disabled");
        //remove all the class add the messagebox classes and start fading
        $("#sendmailmsgbox").removeClass().addClass('normalmessagebox').html('<img height="15px" src="style/ajax-loader.gif" />Validating....').fadeIn(500);
        //check the username exists or not from ajax
        $.post("mail_send.php", $(this).serialize() ,function(data)
        {
          if($.trim(data)=='Success.') //if correct login detail
          {
                $("#sendmailmsgbox").fadeTo(500,0.1,function()  //start fading the messagebox
                {
                  //add message and change the class of the box and start fading
                  $(this).html('Success!').addClass('normalmessageboxok').fadeTo(800,1,function() {
                    $("#newmailwindow").dialog("close");
                    $("#sendmailmsgbox").hide();
                    $("input:submit",tform).removeAttr("disabled");
                    $("input:submit",tform).removeClass("ui-state-disabled");
                  });
                });
          }
          else
          {
                $("#sendmailmsgbox").fadeTo(100,0.1,function() //start fading the messagebox
                {
                  //add message and change the class of the box and start fading
                  $(this).html(data).addClass('normalmessageboxerror').fadeTo(300,1);
                });
                $("input:submit",tform).removeAttr("disabled");
                $("input:submit",tform).removeClass("ui-state-disabled");
          }
       });
       oTable.fnFilter("");
       return false;//not to post the  form physically
});

