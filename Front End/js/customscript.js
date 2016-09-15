$(document).ready(function(){  

 	$('.btn-timein').click(function(e){
 		e.preventDefault();
 		$('.time-in-container').css({'display' : 'none'});
 		$('.time-in').css({'display' : 'none'});
 		$('.timein-notif-container').css({'top':'0px'})
 		$('.timed-in').css({'display' : 'block'});

 		setTimeout(function(){
 			$('.timein-notif-container').css({'top' : '-55px'});
 		}, 5000);  
 	});

 	$('.btn-timeout').click(function(e){
 		e.preventDefault();
 		$('.timeout-container').css({'display' : 'block'});
 		$('.confirm-timeout').css({'display' : 'block'});
 		$('.timeout').css({'display' : 'block'});
 	});

 	$('.btn-yes').click(function(e){
 		e.preventDefault();

 		if( !$("#confirmpw-timeout").val() == '') {
	 		$('.timeout-container').css({'display' : 'none'});
	 		$('.btn-timeout').css({'display' : 'none'});
	 		$('.timeout-notif-container').css({'margin-top':'50px'})
	 		$('.timed-in').css({'display' : 'none'});
	 		$('.timed-out').css({'display' : 'block'});

	 		setTimeout(function(){
	 			$('.timeout-notif-container').css({'margin-top' : '-55px'});
	 		}, 5000);
 		}
 		else{
 			$('#confirmpw-timeout').closest('.timeout').find('input[type=password]').css({'border-bottom-color' : '#f44336'})

 		}
 	});

 	// $('.btn-change').click(function(e){
 	// 	e.preventDefault();

 	// 	  if(!$("#oldpw").val() == '' && $validate == true && !$("#newpw").val() == '' && !$("#confirmpw").val() == '') {
  //          setTimeout(function(){
	 // 			$('.changepw-notif-container').css({'margin-top' : '0px'});
	 // 		}, 5000);
  //       } else {
  //           e.preventDefault();
  //       }
 	// });

 	$('.btn-cancel').click(function(e){
 		e.preventDefault();
 		$('.timeout-container, .logout-container').css({'display' : 'none'});
 		$('.confirm-timeout, .confirm-logout').css({'display' : 'none'});
 		$('.btn-yes').css({'display' : 'none'});
 		$('.btn-cancel').css({'display' : 'none'});
 	});

 	$('.btn-timeout').click(function(e){
 		e.preventDefault();
 		$('.timeout-container').css({'display' : 'block'});
 		$('.confirm-timeout').css({'display' : 'block'});
 		$('.btn-yes').css({'display' : 'inline-block'});
 		$('.btn-cancel').css({'display' : 'inline-block'});

 	});


 	$('.timeout-notif-container').click(function(){
 		$('.timeout-notif-container').css({'top' : '-55px'});

 	});

 	$('.timein-notif-container').click(function(){
 		$('.timein-notif-container').css({'top' : '-55px'});
 	});

 	$('.close-modal-agenda').click(function(){
 		$('#daily_agenda').closeModal();
 		$('.form-reqmeeting').css({'display':'none'});
 		$('.form-reqleave').css({'display':'none'});
 		$('.required-field').css({'display':'none'});
 		$('.lean-overlay').css({'display':'none'});
 		$('.sent').css({'display':'none'});
 		$('#meeting').val('');
 		$('#start-date').val('');
		$('#end-date').val('');
		$('#reason-leave').val('');

 	});

 	$('.btn-logout').click(function(e){
 		e.preventDefault();
 		$('.logout-container').css({'display' : 'block'});
 		$('.confirm-logout').css({'display' : 'block'});
 		$('.btn-yes').css({'display' : 'inline-block'});
 		$('.btn-cancel').css({'display' : 'inline-block'});
 	});

 	$('.form-reqmeeting').hide();
 	$('.form-reqleave').hide();

 	$('.btn-reqmeeting').click(function(){
 		$('.form-reqmeeting').show();
 		$('.form-reqleave').hide();
 	});

 	$('.btn-reqleave').click(function(){
 		$('.form-reqleave').show();
 		$('.form-reqmeeting').hide();
 	});

// MODALS
 	$('.modal-reason').leanModal();
 	$('.modal-emp').leanModal();
 	$('.modal-dept').leanModal();
 	$('.modal-pos').leanModal();
 	$('.modal-change').leanModal();
 	$('.modal-export').leanModal();

 // END-MODALS


 	$('.responsive-nav').click(function(){
 		if($(this).hasClass('open')) {
 			$(this).removeClass('open');
 			$('.nav-right').css({'left':'-250px'});
 			$('.page-content').css({'left':'0'})

 		} else {
 			$(this).addClass('open');
 			$('.nav-right').css({'left':'0px'});
 			$('.page-content').css({'left':'250px'})
 		}

 	});

	$('.adsasdasdasd').pickadate({
		selectMonths: true,
		selectYears: 15
	});

	$('.asdasdasd').pickadate({
		selectMonths: true,
		selectYears: 15
	});

	$('.birth-date').pickadate({
		selectMonths: true,
		selectYears: 15
	});


	$('.btn-edit-profile').click(function(e){
    	e.preventDefault();

        console.log('test');

        $('.profile-container2 input').attr("disabled", false); 
        $('.edit-pdata').css({'display' : 'inline-block'});
        $('.btn-save-profile').css({'display' : 'inline-block'});
        $('.btn-cancel-profile').css({'display' : 'inline-block'});
        $('.btn-edit-profile').css({'display' : 'none'});
    });

	$(".edit-pdata").click(function(e) {
        e.preventDefault();
        $(this).parent('.p-dta').find('input').val('').focus();
        // $(this).parent('.collection-item').find('.btn-edit').css({'display':'none'})
    });

	$('.btn-save-profile').click(function(e){
        e.preventDefault();
        $validate = false;

        $('.btn-edit-profile').css({'display' : 'none'});

        if( !$("#cel-p").val() == '') {
            $validate = true;
            $("#cel-p").closest('.p-dta').find('.btn-edit').css({'display' : 'block'});
            $("#cel-p").closest('.p-dta').find('.required-field').css({'display' : 'none'})
            

        } else {
            $("#cel-p").closest('.p-dta').find('input[type=text]').css({'border-bottom-color' : '#f44336'})
            $("#cel-p").closest('.p-dta').find('.btn-edit').css({'display':'none'})
            $("#cel-p").closest('.p-dta').find('input').val('').focus();

        }


        if( !$("#tel-p").val() == '') {
            $validate = true;
            $("#tel-p").closest('.p-dta').find('.btn-edit').css({'display' : 'block'});
            $("#tel-p").closest('.p-dta').find('.required-field').css({'display' : 'none'})
        } else {
            $("#tel-p").closest('.p-dta').find('input[type=text]').css({'border-bottom-color' : '#f44336'})
            $("#tel-p").closest('.p-dta').find('.btn-edit').css({'display':'none'})
            $("#tel-p").closest('.p-dta').find('input').val('').focus();
            
        }

        if( !$("#addr").val() == '') {
            $validate = true;
            $("#addr").closest('.p-dta').find('.btn-edit').css({'display' : 'block'});
            $("#addr").closest('.p-dta').find('.required-field').css({'display' : 'none'})
        } else {
            $("#addr").closest('.p-dta').find('input[type=text]').css({'border-bottom-color' : '#f44336'})
            $("#addr").closest('.p-dta').find('.btn-edit').css({'display':'none'})
            $("#addr").closest('.p-dta').find('input').val('').focus();
          
        }


        if(!$("#cel-p").val() == '' && $validate == true && !$("#tel-p").val() == '' && !$("#addr").val() == '') {
            $('.btn-save-profile').css({'display' : 'none'});
            $('.btn-cancel-profile').css({'display' : 'none'});
            $('.edit-pdata').css({'display' : 'none'});
            $('.btn-edit-profile').css({'display' : 'inline-block'});
            $('.profile-container2 input').attr("disabled", true); 
        } else {
            e.preventDefault();
        }

    });

 

  $('.btn-cancel-profile').click(function(e){
        e.preventDefault();

           	$('.profile-container input').attr("disabled", true);  
            $('.edit-pdata').css({'display' : 'none'});
            $('.btn-save-profile').css({'display' : 'none'});
            $('.btn-cancel-profile').css({'display' : 'none'});
            $('.btn-edit-profile').css({'display' : 'inline-block'});

    });




	$('.btn-accept').click(function(e){
		e.preventDefault();

		$('.accept-notif-container').css({'margin-top':'50px'})

		setTimeout(function(){
			$('.accept-notif-container').css({'margin-top' : '-55px'});
		}, 5000);

	});

	$('.btn-decline').click(function(e){
		e.preventDefault();

		$('.reject').css({'display':'block'})
	});

	$('.btn-submitleave').click(function(e){
		e.preventDefault();

		if($("#reason-leave").val() == '') {
			$("#reason-leave").closest('.form-reqleave').find('.required-field').css({'display' : 'block'})
			$("#reason-leave").closest('.form-reqleave').find('.sent').css({'display' : 'none'})
		} else {
			$("#reason-leave").closest('.form-reqleave').find('.required-field').css({'display' : 'none'})
			$("#reason-leave").closest('.form-reqleave').find('.sent').css({'display' : 'block'})
			$("#reason-leave").val('');
		}
 	});

 	$('.btn-sendtoemail').click(function(e){
 		e.preventDefault();

	 	if($("#reject-reason").val() == ''){
	 		 $("#reject-reason").closest('.reject').find('.required-field').css({'display' : 'block'})

	 	} else {
	 		$("#reject-reason").closest('.reject').find('.required-field').css({'display' : 'none'})
	 		

	 		$("#reject-reason").css({'display':'none'});
	 		$('.sendtoemail-notif-container').css({'margin-top':'50px'})
	 		$('.btn-reasoncancel').css({'display':'none'})
	 		$('.btn-sendtoemail').css({'display':'none'})


	 		setTimeout(function(){
	 			$('.sendtoemail-notif-container').css({'margin-top' : '-55px'});
	 		}, 5000);
 		}
 	});

	$('.btn-add-emp').click(function(e){
	 		e.preventDefault();

	 		if($('.input-field').val() == ''){
	 			$('.input-field').closest('.profile-data').find('input[type=text]').css({'border-bottom-color' : '#f44336'})
	 		}

	 			else{
	 				$('.add-success-container').css({'margin-top' : '0px'});
	 				$('.input-field').closest('.profile-data').find('input[type=text]').css({'border-bottom-color' : '#9e9e9e'})
	 				$('.input-field').closest('.profile-data').find('input[type=text]').val('');
	 				$('#modal-emp').closeModal();
			 		setTimeout(function(){
			 			$('.add-success-container').css({'margin-top' : '-55px'});
			 		}, 5000);
	 			}
	 	});

 		$('.btn-add-pos').click(function(e){
 		e.preventDefault();

 			if($('#input-position').val() == ''){
 				$('#input-position').closest('#modal-pos').find('.required-field').css({'display' : 'block'})
 			}

 			else{
 				$('.add-success-container').css({'margin-top' : '0px'});
 				$("#input-position").closest('#modal-pos').find('.required-field').css({'display' : 'none'})
 				$('#input-position').val('');
 				$('#modal-pos').closeModal();
		 		setTimeout(function(){
		 			$('.add-success-container').css({'margin-top' : '-55px'});
		 		}, 5000);
 			}
 	});


 		$('.btn-add-dept').click(function(e){
 		e.preventDefault();

 			if($('#input-department').val() == ''){
 				$("#input-department").closest('#modal-dept').find('.required-field').css({'display' : 'block'})
 			}

 			else{
 				$('.add-success-container').css({'margin-top' : '0px'});
 				$("#input-department").closest('#modal-dept').find('.required-field').css({'display' : 'none'})
 				$('#input-department').val('');
 				$('#modal-dept').closeModal();
		 		setTimeout(function(){
		 			$('.add-success-container').css({'margin-top' : '-55px'});
		 		}, 5000);
 			}
 	});



 		$('.btn-cancel-input').click(function(e){
 		e.preventDefault();

 				$('#input-department').val('');
 				$('#input-position').val('');
 				$('.input-field').closest('.profile-data').find('input[type=text]').val('');
 				$('.input-field').closest('.profile-data').find('input[type=text]').css({'border-bottom-color' : '#9e9e9e'})
 		});

			
		$("#upload-photo").change(function () {
	        var fileExtension = ['jpeg', 'jpg', 'png'];
    });

 		$('.initialized').material_select();

 	//$('#calendar').fullCalendar('option', 'height', 700);
 		$('select').material_select();

// $( window ).resize(function() {
//   $( ".fc-view-container" ).prepend( "<div>" + $( window ).width() + "</div>" );
// });



 		$(".modal-content").closest("#exportchoice").find("#select-dept").change(function(){
        	$(".selectDept").css({'display':'block'})
    	});
 		$(".modal-content").closest("#exportchoice").find("#all-emp").change(function(){
 			$(".selectDept").css({'display':'none'})
 		});
 			





});  