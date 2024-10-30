(function($){
  //alert();
  $(document).on('click','.sendfox-form .et_pb_button',function(e){
    e.preventDefault();

    var btnSubmit = $(this);
    //alert("sssss");

    var captch = false;
    if( btnSubmit.closest(".sendfox-form").hasClass("resolveCaptcha") )
    {
      alert("Resolve reCaptcha");
      return false;
    }

    //return false;

    var input_name = "";
    var lsf_fname = btnSubmit.closest("form").find("#lsf_first_name");
    if( lsf_fname.length == 1 )
    {
      if( lsf_fname.val() == '' )
      {
        inputRequired = lsf_fname.attr("data-lsfname");
        alert(inputRequired + ' is required');
        lsf_fname.focus();
        return false;
      }else{
        input_name = lsf_fname.val();
      }
    }else{
      //alert();
    }

    var input_last_name = "";
    var lsf_lname = btnSubmit.closest("form").find('#lsf_last_name');
    if( lsf_lname.length == 1 )
    {
      if( lsf_lname.val() == '' ){
        inputRequired = lsf_lname.attr("data-lsflastname");
        alert(inputRequired + ' is required');
        lsf_lname.focus();
        return false;
      }else{
        input_last_name = lsf_lname.val();
      }
    }

    var input_email = "";
    var lsf_email = btnSubmit.closest("form").find('#lsf_email');
    if( lsf_email.length == 1)
    {
      if( lsf_email.val() == '' ){
        inputRequired = lsf_email.attr("data-lsfemail");
        alert(inputRequired + ' is required');
        lsf_email.focus();
        return false;
      }else{
        input_email = lsf_email.val();
      }
    }

    var lst_lists = btnSubmit.closest("form").find("#lst_lists").val();
    var lst_after_submit = btnSubmit.closest("form").find("#lst_after_submit").val();
    var lst_redirect = btnSubmit.closest("form").find("#lst_redirect").val();


    var tokenCaptcha = "";
    if( btnSubmit.closest("form").find("#g-recaptcha-response").size() == 1 && $("#g-recaptcha-response").val() != "" )
    {
      tokenCaptcha = btnSubmit.closest("form").find("#g-recaptcha-response").val();
    }

	 	//link = $(this);
	 	//id   = link.attr('href').replace(/^.*#more-/,'');

		$.ajax({
			url : dcms_vars.ajaxurl,
			type: 'post',
			data: {
				action : 'dcms_ajax_suscribe_sendfox',
        first_name: input_name,
        last_name: input_last_name,
        email: input_email,
        lists: lst_lists,
        tokenCaptcha: tokenCaptcha
			},
			beforeSend: function(){
        btnSubmit.closest("form").hide();
        btnSubmit.closest(".lsf_right").find("#lsf_holder_loading").show();
			},
			success: function(resultado){
        
        if( resultado == 1 || resultado == "1")
        {
          if( lst_after_submit == 1 )
          {
            window.location.replace(lst_redirect);
          }else{

            btnSubmit.closest(".lsf_right").find("#lsf_holder_msgbox").show();
            btnSubmit.closest(".lsf_right").find("#lsf_holder_loading").hide();
          }
        }else{
            alert(""+resultado);
            btnSubmit.closest("form").show();
            btnSubmit.closest(".lsf_right").find("#lsf_holder_msgbox").hide();
            btnSubmit.closest(".lsf_right").find("#lsf_holder_loading").hide();
        }
        
      },
      error: function(resultado){
        alert("There was an error: "+resultado);
        btnSubmit.closest("form").show();
        btnSubmit.closest(".lsf_right").find("#lsf_holder_loading").hide();
      }

		});

	});

})(jQuery);