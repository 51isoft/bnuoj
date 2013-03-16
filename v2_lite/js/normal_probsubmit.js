$("#probsubmit").submit(function()
{
        var tform=this;
        $("input:submit",tform).attr("disabled","disabled");
        $("input:submit",tform).addClass("ui-state-disabled");
        $("#submitmsgbox").removeClass().addClass('normalmessagebox').html('<img height="15px" src="style/ajax-loader.gif" />Validating....').fadeIn(500);
        $.post("action.php", $(this).serialize() ,function(data)
        {
          if($.trim(data)=='Submitted.') //if correct login detail
          {
                $("#submitmsgbox").fadeTo(100,0.1,function()  //start fading the messagebox
                {
                  $(this).html('Success!').addClass('normalmessageboxok').fadeTo(100,1);
                });
                window.location ='status.php';
          }
          else
          {
                $("#submitmsgbox").fadeTo(100,0.1,function() //start fading the messagebox
                {
                  $(this).html(data).addClass('normalmessageboxerror').fadeTo(300,1);
                });
                $("input:submit",tform).removeAttr("disabled");
                $("input:submit",tform).removeClass("ui-state-disabled");
          }
       });
       return false;//not to post the  form physically
});

