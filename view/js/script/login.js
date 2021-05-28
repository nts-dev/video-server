$('document').ready(function() { 
	/* handling form validation */
	$("#login-form").validate({
		rules: {
			user_id: {
				required: true
			},
			graDuration: {
				required: true
			}
		},
		messages: {
			graDuration:{
			  required: "please enter your password"
			 },
			user_email: "please enter your email address"
		},
		submitHandler: submitForm	
	});	   
	/* Handling login functionality */

	const IDENTIFIER_DATA = $("#login-form").serialize();
	function submitForm() {		
		var data = $("#login-form").serialize();
		$.ajax({				
			type : 'POST',
			// url  : '/app/Stream/data.php?action=default',
			url  : '/api/session/auth.php',
			data : data,
			beforeSend: function(){	
				$("#error").fadeOut();
				$("#login_button").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; sending ...');
			},
			success : function(response){	
                              var parsedJSON = eval('(' + response + ')');
				if(parsedJSON.state == 'success'){
                                    var id = parsedJSON.userId;
					$("#login_button").html('<img src="ajax-loader.gif" /> &nbsp; Signing In ...');
					setTimeout(' window.location.href = "home.php?eid='+ id +' "; ',4000);
				} else {									
					$("#error").fadeIn(1000, function(){						
						$("#error").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; '+parsedJSON.message+' !</div>');
						$("#login_button").html('<span class="glyphicon glyphicon-log-in"></span> &nbsp; Sign In');
					});
				}
			}
		});
		return false;
	}   
        
//        $("#guest-access").click(function(){
//            
//        });
});