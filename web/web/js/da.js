$(document).ready(function(){  

$('.btn-addevent').click(function(e){
            e.preventDefault();
            $validation = false;

            if(!$("#event").val() == ''){
                $validate = true;
                $("#event").closest('.form-group').find('.required').css({'display' : 'none'})
                $("#event").closest('.form-group').find('.success-addevent').css({'display' : 'block'})
            } else {
                $("#event").closest('.form-group').find('.required').css({'display' : 'block'})
                e.preventDefault();
            }

            if (!$("#reason").val() ==''){
                $validate = true;
                $("#reason").closest('.form-group').find('.required').css({'display' : 'none'})
                $("#reason").closest('.form-group').find('.success-sendreq').css({'display' : 'block'})
            } else {
                $("#reason").closest('.form-group').find('.required').css({'display' : 'block'})
                e.preventDefault();
            }

        });

});