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




/**
 * Created by Hazel on 22/02/2017.
 */
// SCRIPT VARIABLES
const DOC = $(document);
const CURRENT_YEAR  = (new Date()).getFullYear();

var data, callback, done, always;

// LOADER VARIABLES
var loadingBar = $('.loader-notif');
var successBar = $('.successful-popup');
var errorBar = $('.error-popup');
var topNotificationBar = $('.top-general-notification');

function showLoadingBar() {
    loadingBar.css({'top': '0px'});
}

function hideLoadingBar() {
    loadingBar.css({'top': '-55px'});
}

function showSuccessBar(message) {
    successBar.find("#successful").html(message);
    successBar.css({'top': '0px'});
    setTimeout(function () {
        successBar.css({'top': '-55px'});
        loadingBar.css({'top': '-55px'});
    }, 2000);
}

function showErrorBar(message) {
    console.log(message);
    errorBar.find("#error").html(message);
    errorBar.css({'top': '0px'});
    setTimeout(function () {
        errorBar.css({'top': '-55px'});
        loadingBar.css({'top': '-55px'});
    }, 2000);
}

function showNotificationBar(message, state) {
    topNotificationBar.addClass('green').removeClass('red');
    if(state=='error')
        topNotificationBar.addClass('red').removeClass('green');

    topNotificationBar.find('h5').text(message);
    topNotificationBar.css({'top': '0px'});
    setTimeout(function () {
        topNotificationBar.css({'top': '-55px'});
        loadingBar.css({'top': '-55px'});
    }, 5000);
}

// AJAX POST
function post(button, path, data, callback, done, always) {
    showLoadingBar();
    var btnText = button.text();

    if(buttonIsReadyPlr(button)) {
        buttonLoadingPlr(button);

        $.post(path, data, callback, "json").done(function (data) {
            if (typeof done === 'function') done(data);
        }).always(function (data) {
            if (typeof always === 'function') always(data);

            buttonRemoveLoadPlr(button, btnText);
            hideLoadingBar();
        }).fail(function (data, err) {
            if (data.status == 401) {
                alert("You're not login!");
                window.location = '/';
            }

            showErrorBar("Server Error. Please try again.");
        });
    }
}

function buttonIsReadyPlr(btn) {
    return btn.hasClass('post-loading') ? false : true;
}

function buttonLoadingPlr(btn, text) {
    var btnText = text ? text : btn.data('loading-text');

    disableEnableButton(btn, true);
    btn.addClass('disabled').text(btnText||'Saving...');
}

function buttonRemoveLoadPlr(btn, text) {
    disableEnableButton(btn, false);
    btn.text(text||'Create');
}

function disableEnableButton(button, val) {
    if(val) button.addClass('disabled');
    else button.removeClass('disabled');

    button.prop("disabled", val);
}

function enableElement(el) {
    el.removeClass('disabled').prop("disabled", false);
}

function disableElement(el) {
    el.addClass('disabled').prop("disabled", true);
}

// INPUT FIELD CHECKER
function checkFields(elements) {
    var hasRequired = 0;
    var element = null;
    for(var i = 0; i < elements.length; i++) {
        element = elements[i];
        if(element.val() == '') {
            notifyInvalid(element);
            hasRequired++;
        }
    }

    // return !hasRequired

    if (hasRequired > 0)
        return true;
    else return false;
}

function notifyInvalid(element) {
    element.css({'border-color': 'red'});
    element.focus();
    setTimeout(function () {
        element.css({'border-color': 'gray'})
    }, 1000);
}

function errorBorder(el) {
    for(var i in el) el[i].addClass('border-red').removeClass('border-gray');
}

function defaultBorder(el) {
    for(var i in el) el[i].addClass('border-gray').removeClass('border-red');
}

function hideElements(els) {
    for( i in els) {
        els[i].hide();
    }
}
function showElements(els) {
    for( i in els) {
        els[i].show();
    }
}

function convertStatusToString(statusId) {
    if(statusId==STATUS_PENDING) {
        return 'pending';
    } else if(statusId==STATUS_ACTIVE) {
        return 'active';
    } else if(statusId==STATUS_INACTIVE) {
        return 'inactive';
    } else if(statusId==STATUS_APPROVED) {
        return 'approved';
    } else if(statusId==STATUS_DECLINED) {
        return 'declined';
    }
}

/** filters */
function removeSpace(str) {
    return str.replace(/\s/g, '');
}

/**
 * PAGES
 * ------------------------------
 */
function paginate(total, page, limit) {
    $pnav = $('.-pagination-section');
    $pjump = $pnav.find('select.-page-limit');
    $pnav.find('.prev').removeClass('disabled').removeAttr('disabled');
    $pnav.find('.next').removeClass('disabled').removeAttr('disabled');

    var remaining = total - (page*limit);

    $pnav.find('.-page-number').val(page);

    if(page==1) $pnav.find('.prev').addClass('disabled').attr('disabled', 'disabled');
    if(remaining <= limit) $pnav.find('.next').addClass('disabled').attr('disabled', 'disabled');

    if(total==0) {
        $pnav.hide();
    } else {
        $pnav.show();
    }
}


/**
 * PLUGINS
 * ------------------------------
 */
/** init Date Picker */
var currentYear = (new Date()).getFullYear();
function initPicker(el, minDate, maxDate, onselect, param) {
    var pikael = 'pik_'+el;
    if(window[pikael] !== undefined && window[pikael].calendars !== undefined) {
        window[pikael].destroy();
    }

    window[pikael] = new Pikaday({
        field: document.getElementById(el),
        firstDay: 1,
        minDate: minDate,
        maxDate: maxDate,
        yearRange: [currentYear - 20, currentYear + 20],
        format: param && param.format ? param.format : 'YYYY-MM-DD',
        onSelect: onselect,
        showTime: param && param.showTime ? param.showTime : false,
        autoClose:  param && param.autoClose ? param.autoClose : true,
        use24hour: param && param.use24hour ? param.use24hour : false
    });

    return window[pikael];
}

function setPikadayData(info, el, value) {
    var pikael = 'pik_'+el;
    if(window[pikael] !== undefined && window[pikael].calendars !== undefined) {
        if(info=='end-range') window[pikael].setMaxDate(value);
        if(info=='start-range') window[pikael].setMinDate(value);
        if(info=='set-date') window[pikael].setDate(value);

        return window[pikael];
    }
}

function initDropDown(param) {
    param = param ? param: {};
    $('.dropdown-button').dropdown({
            inDuration: 300,
            outDuration: 225,
            constrainWidth: false, // Does not change width of dropdown to that of the activator
            hover: param.hover||false, // Activate on hover
            gutter: 0, // Spacing from edge
            belowOrigin: false, // Displays dropdown below the button
            alignment: 'left', // Displays dropdown with edge aligned to the left of button
            stopPropagation: false // Stops event propagation
        }
    );
}