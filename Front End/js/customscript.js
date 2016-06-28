 $(document).ready(function(){  

			 $('.btn-timein').click(function(e){
			        e.preventDefault();


			        $('.time-in-container').css({'display' : 'none'});
			        $('.time-in').css({'display' : 'none'});
			        $('.timein-notif-container').css({'margin-top':'0px'})

			          setTimeout(function(){
		            $('.timein-notif-container').css({'margin-top' : '-55px'});
		         }, 5000);

			       
			 });


 			$('.modal-reason').leanModal();


});