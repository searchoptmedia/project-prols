$(document).ready(function(){        

    $('.btn-time').on('click', function(e){
        e.preventDefault();

        if($(this).hasClass('time-out')) {
            $('#mb-timeout').addClass('open');

            
        } else {
            $('#mb-timein').addClass('open');
        }
    });   

    $('.btn-confirm-timein').click(function(e){
        e.preventDefault();

        $('#mb-timein').removeClass('open');
        $('.btn-time').addClass('time-out').find('.xn-text').html('Time Out');


    
    });

    $('.btn-confirm-timeout').click(function(e){
        e.preventDefault();


        $('#mb-timeout').removeClass('open');
        $('.btn-time').removeClass('time-out').find('.xn-text').html('Time In');
    });

    // $('.btn-edit-profile').click(function(e){
    //     e.preventDefault();
    //
    //     // console.log('test');
    //
    //     $('.list-group-item input').attr("readonly", false);
    //     $('.edit-this').css({'display' : 'block'});
    //     $('.btn-save-profile').css({'display' : 'inline'});
    //     $('.btn-cancel-profile').css({'display' : 'inline'});
    //     $('.btn-edit-profile').css({'display' : 'none'});
    // });


    // $('.btn-save-profile').click(function(e){
    //     e.preventDefault();
    //     $validate = false;
    //
    //     $('.btn-edit-profile').css({'display' : 'none'});
    //
    //     if( !$("#cellP").val() == '') {
    //         $validate = true;
    //         $("#cellP").closest('.list-group-item').find('.edit-this').css({'display' : 'block'});
    //         $("#cellP").closest('.list-group-item').find('.required').css({'display' : 'none'})
    //     } else {
    //         $("#cellP").closest('.list-group-item').find('.required').css({'display' : 'inline'})
    //     }
    //
    //     if( !$("#telP").val() == '') {
    //         $validate = true;
    //         $("#telP").closest('.list-group-item').find('.edit-this').css({'display' : 'block'});
    //         $("#telP").closest('.list-group-item').find('.required').css({'display' : 'none'})
    //     } else {
    //         $("#telP").closest('.list-group-item').find('.required').css({'display' : 'inline'})
    //     }
    //
    //     if( !$("#address").val() == '') {
    //         $validate = true;
    //         $("#address").closest('.list-group-item').find('.edit-this').css({'display' : 'block'});
    //         $("#address").closest('.list-group-item').find('.required').css({'display' : 'none'})
    //     } else {
    //         $("#address").closest('.list-group-item').find('.required').css({'display' : 'inline'})
    //     }
    //
    //
    //     if(!$("#cellP").val() == '' && $validate == true && !$("#telP").val() == '' && !$("#address").val() == '') {
    //         $('.btn-save-profile').css({'display' : 'none'});
    //         $('.btn-cancel-profile').css({'display' : 'none'});
    //         $('.edit-this').css({'display' : 'none'});
    //         $('.btn-edit-profile').css({'display' : 'block'});
    //     } else {
    //         e.preventDefault();
    //     }
    //
    // });


    // $('.btn-cancel-profile').click(function(e){
    //     e.preventDefault();
    //
    //         $('.list-group-item input').attr("readonly", false);
    //         $('.edit-this').css({'display' : 'none'});
    //         $('.btn-save-profile').css({'display' : 'none'});
    //         $('.btn-cancel-profile').css({'display' : 'none'});
    //         $('.btn-edit-profile').css({'display' : 'block'});
    //         $('.required').css({'display' : 'none'});
    // });

    $( ".edit-this" ).click(function(e) {
        e.preventDefault();
        $(this).parent('.list-group-item').find('input').val('').focus();
    });


    $('#close-alert2').click(function(){
            $('#close-alert2').addClass('anim');
            $('.alert-message-timein').css({'top' : '-55px'});
    });

    $('#close-alert1').click(function(){
          $('.alert-message-timeout').css({'top' : '-55px'});

    });

     $('#close-alert4').click(function(){
            $('#close-alert2').addClass('anim');
            $('.alert-message-reject').css({'top' : '-55px'});
    });

    $('#close-alert3').click(function(){
          $('.alert-message-accept').css({'top' : '-55px'});

    });


    $('.btn-confirm-timeout').click(function(e){
        e.preventDefault();
            $('.alert-message-timeout').css({'top' : '0px'});
            $('#mb-timeout').removeClass('open');
            $('.alert-message-timein').css({'top' : '-55px'});

         setTimeout(function(){
          $('.alert-message-timeout').css({'top' : '-55px'});
        }, 5000);
    });


    $('.btn-confirm-timein').click(function(e){
        e.preventDefault();
        $('.alert-message-timein').css({'top' : '0px'});
        $('#mb-timein').removeClass('open');
        $('.alert-message-timeout').css({'top' : '-55px'});

         setTimeout(function(){
            $('.alert-message-timein').css({'top' : '-55px'});
         }, 5000);
    });

    $('.btn-confirm-accept').click(function(e){
        e.preventDefault();
        $('.alert-message-accept').css({'top' : '0px'});
        $('#mb-accept').removeClass('open');
        $('.alert-message-reject').css({'top' : '-55px'});

        setTimeout(function(){
            $('.alert-message-accept').css({'top' : '-55px'});
         }, 5000);
    });


    $('.btn-confirm-reject').click(function(e){
        e.preventDefault();
        // $('.alert-message-reject').css({'top' : '0px'});
        $('#mb-reject').removeClass('open');
        $('.alert-message-accept').css({'top' : '-55px'});

         // setTimeout(function(){
         //    $('.alert-message-reject').css({'top' : '-55px'});
         // }, 5000);
    });

    $("#sendtoemail").click(function(e){
        e.preventDefault();

         if(!$('.form-control').val() == ''){
            $('.alert-message-reject').css({'top' : '0px'});
            $('.required').css({'display' : 'none'});

            setTimeout(function(){
            $('.alert-message-reject').css({'top' : '-55px'});
          }, 5000);

        } else {
            $('.required').css({'display' : 'block'});

        }

    });


    // $('.btn-addevent').click(function(e){
    //     e.preventDefault();
        

    //     if ($("#event").val() == '') {
    //             $('.required').css({'display' : 'inline'})
    //         }
    //          else {
               
    //             $('.success-addevent').css({'display' : 'block'})
    //             $('#event').val('');
    //         }           
    // });


    // $('#accept-vl').click(function(e){

    //     e.preventDefault();
    //     $("#accept-vl").css({'display' : 'none'});
    //     $("#reject-vl").css({'display' : 'none'});

    // });

    //  $('#reject-vl').click(function(e){

    //     e.preventDefault();
    //     $("#accept-vl").css({'display' : 'none'});
    //     $("#reject-vl").css({'display' : 'none'});

    // });


     $('.btn-addevent').click(function(e){
            e.preventDefault();
            $validation = false;
            $("#event").closest('.form-group').find('.success-addevent').css({'display' : 'none'})

            if(!$("#event").val() == ''){
                $validate = true;
                $("#event").closest('.form-group').find('.required').css({'display' : 'none'})
                // $("#event").closest('.form-group').find('.success-addevent').css({'display' : 'block'})
                $("#addTest").css({'display' : 'block'})
                


            } else {
                $("#event").closest('.form-group').find('.required').css({'display' : 'block'})

                e.preventDefault();
            }

            

        });


     $('.btn-sendreq').click(function(e){
            e.preventDefault();
            $validation = false;
            $("#reason").closest('.card-block').find('.success-sendreq').css({'display' : 'none'})

            if (!$("#reason").val() == ''){
                $validate = true;
                $("#reason").closest('.card-block').find('.required').css({'display' : 'none'})
                $("#reason").closest('.card-block').find('.success-sendreq').css({'display' : 'inline'})
            } else {
                $("#reason").closest('.card-block').find('.required').css({'display' : 'block'})
                e.preventDefault();

               
            }


            // if (!$("#startDate").val() == ''){
            //     $validate = true;
            //     $("#startDate").closest('.card-block').find('.required').css({'display' : 'none'})
            //     $("#startDate").closest('.card-block').find('.success-sendreq').css({'display' : 'inline'})
            // } else {
            //     $("#startDate").closest('.card-block').find('.required').css({'display' : 'block'})
            //     e.preventDefault();

               
            // }


            $('.modal-reason').leanModal();

        });


      // $('select').material_select();

      // pagination

      // $('.page-2').click(function(e){
      //       e.preventDefault();
      //       $('#page2').css({'display':'block'})

      //        });


 $('ul.tabs').tabs('select_tab', 'tab_id');




});