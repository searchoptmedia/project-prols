$(document).ready(function(){        
 
   $('.btn-confirm-timeout').click(function(e){
        e.preventDefault();
        $('.alert-message-timeout').show();
        $('#mb-timeout').removeClass('open');
   });

   $('.btn-confirm-timein').click(function(e){
        e.preventDefault();
        $('.alert-message-timein').show();
        $('#mb-timein').removeClass('open');
   });
});   