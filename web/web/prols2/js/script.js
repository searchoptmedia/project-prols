DATE_FORMAT = 'YYYY-MM-DD';
DATE_FORMAT_FULL = 'YYYY-MM-DD HH:mm:ss';
TIME_FORMAT = 'hh:mm a';
TIME_24HRFORMAT = 'HH:mm a';
TIME_24HRFORMAT_B = 'HH:mm:ss a';
DATE_FORMAT_FORMAL = 'MMM DD, YYYY';

//left widget
var btnWidgetTimeout = $('.-btn-widget-timeout');
var btnWidgetChip = $('.-btn-widget-timechip');
var noExistButton = $('.-button-not-exist');
var plrModal = $('.plr-modal');

MAIN_APP = function() {
    var _d = { slug: '' };
    var _nowDate = null;
    var _nowDateFull = null;
    var _self = this;

    return {
        setActiveSidebar: function() {
            $('.link-sidebar-'+_d.slug).addClass('active');

            return this;
        },

        start: function(d) {
            _d = $.extend(true, _d, d); _self = this;

            _self
                .setup()
        },

        setup: function() {
            moment.tz.add('Asia/Manila');
            console.log('Today is '+ moment().format(DATE_FORMAT_FULL));
            _nowDate = moment().format(DATE_FORMAT);
            _nowDateFull = moment().format(DATE_FORMAT_FULL);

            plrModal.find('.-modal-loader-img').addClass('display-none');
            plrModal.find('.button-action').removeClass('display-none');

            this
                .setActiveSidebar()
                .bindClicks()
                .renderScreenModal();

            return this;
        },

        renderScreenModal: function() {
            var txtGreet = $('.-modal-greetings-text');
            //set greetings
            if(moment().format('H') >= 12) txtGreet.text('Good Afternoon!');
            else txtGreet.text('Good Morning!');

            //if has timein, get last
            if(_.size(_d.lastTimein) && _d.lastTimein.Status!=-1) {
                var d = _d.lastTimein;

                if(moment(d.Date.date).format(DATE_FORMAT)!=_nowDate && d.TimeOut!=null) {
                    var timeLogout = moment(d.TimeOut.date).format(TIME_24HRFORMAT_B);
                    if(timeLogout!='00:00:00 am') {
                        if (in_array(_d.ip, _d.ips)) _self.toggleModal('.modal-time-in-container.allowed', 'show');
                        else _self.toggleModal('.modal-time-in-container.deny', 'show');
                    } else {
                        $('.-txt-autotimeout-message-in').text(moment(d.TimeIn.date).format(DATE_FORMAT_FORMAL+' '+TIME_FORMAT))
                        $('.-txt-autotimeout-message-out').text(moment(d.TimeOut.date).format(DATE_FORMAT_FORMAL+' '+TIME_FORMAT))
                        _self.toggleModal('.modal-auto-timeout-notice', 'show');
                    }
                } else {
                    if(d.TimeOut==null) {
                        _self.toggleTimeWidget('show');
                    } else {
                        _self.toggleTimeWidget('hide');
                    }

                    btnWidgetChip.html("Timed-in at " + moment(d.TimeIn.date).format(TIME_FORMAT));
                }
            } else {
                if (in_array(_d.ip, _d.ips)) _self.toggleModal('.modal-time-in-container.allowed', 'show');
                else _self.toggleModal('.modal-time-in-container.deny', 'show');
            }

            return this;
        },
        /** show/hide */
        toggleModal: function(el, state) {
            if(state=='show') $(el).removeClass('display-none');
            else $(el).addClass('display-none');
        },

        toggleTimeWidget: function (state) {
            if(state=='show') {
                btnWidgetTimeout.removeClass('display-none');
                btnWidgetChip.removeClass('display-none');
            } else {
                btnWidgetTimeout.addClass('display-none');
                btnWidgetChip.addClass('display-none');
            }
        },

        bindClicks: function() {
            /**** TIMEIN **/
            $('.-btn-timein-submit').click(function(){

                var txtReason = $('.-txt-timein-reason'),
                    error = false, button = $(this),
                    is_message = undefined, action = $(this).data('action');

                var message = txtReason.val();
                console.log(message)

                if(!in_array(_d.ip, _d.ips)){
                    var m = message.replace(/(?:\r\n|\r|\n)/g, '')
                    if(removeSpace(m)==''){
                        txtReason.focus();
                        errorBorder([txtReason]);
                        error = true;
                    }

                    is_message = true;
                    if(error){
                        return false;
                    }
                }

                button.hide();
                showLoadingBar();

                post(noExistButton, _d.uri_timein, {is_message : is_message, message : message.replace(/(?:\r\n|\r|\n)/g, '<br>') }, function(data) {
                    _self.toggleTimeWidget('show');
                    var state = 'error';
                    if(data.code==200) {
                        if(_d.birthdays.length)
                            _self.toggleBirthday('show');

                        btnWidgetChip.html("Timed-in at " + moment().format(TIME_FORMAT));
                        state = 'success';
                    }

                    if(in_array(data.code, [403,200])) {
                        if (in_array(_d.ip, _d.ips)) {
                            _self.toggleModal('.modal-time-in-container.allowed', 'hide');
                        } else {
                            _self.toggleModal('.modal-time-in-container.deny', 'hide');
                        }
                    }

                    showNotificationBar(data.message, state);
                }, null, null, function() {
                    button.show();
                });
            });

            /**** TIMEOUT **/
            $('.-btn-widget-timeout').click( function() {
                _self.toggleModal('.modal-timeout-container', 'show');
            });

            $('.-btn-timeout-cancel').click( function() {
                _self.toggleModal('.modal-timeout-container', 'hide');
            });

            $('.-btn-timeout-confirm').click(function() {
                var pass = $('.-txt-timeout-password');
                var errDiv = $('.-timeout-error-message');
                var btnActions = $(this).closest('.plr-modal').find('.button-action');

                defaultBorder([pass]);
                errDiv.hide();
                //check if password is empty
                if (removeSpace(pass.val()) == '') {
                    errorBorder([pass]);
                    errDiv.show().find('strong').text('Password is required!');
                } else {
                    showLoadingBar();
                    btnActions.hide();


                    post(noExistButton, _d.uri_timeout, { encpas: base64EncodeUnicode(pass.val())  }, function(data) {
                        if(data.code==200) {
                            showNotificationBar(data.message, 'success');
                        } else {
                            if(data.code==501) {
                                errDiv.show().find('strong').text('Wrong Password!');
                                errorBorder([pass]);
                                showLoadingBar();
                                btnActions.show();
                            } else {
                                showNotificationBar(data.message + ' <a href="">Reload</a>', 'error');
                            }
                        }

                        if(data.code!=501) {
                            _self.toggleModal('.modal-timeout-container', 'hide');
                            _self.toggleTimeWidget('hide');
                        }

                    }, null, null, function(data) {
                        btnActions.show();
                        showNotificationBar('Error. Something went wrong! <a href="">Reload</a>', 'error');
                    });
                }
            });

            $('.-btn-auto-timeout-timein').click( function() {
                _self.toggleModal('.modal-auto-timeout-notice', 'hide');
                setTimeout( function() {
                    if (in_array(_d.ip, _d.ips)) _self.toggleModal('.modal-time-in-container.allowed', 'show');
                    else _self.toggleModal('.modal-time-in-container.deny', 'show');
                }, 100);
            });

            /** REMOVE BIRTHDAY */
            $('.-btn-close-birthday').click( function() {
                _self.toggleModal('.modal-birthday-notify-container', 'hide');
                toggleBirthday('hide');
            });

            return this;
        },

        toggleBirthday: function(state) {
            if(state=='show') {
                _self.toggleModal('.modal-birthday-notify-container', 'show');
                $('.-txt-bday-names').html(_d.birthdays.join(', '));
                toggleBirthday();
            }

            return this;
        }
    }
}();



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

    topNotificationBar.find('h5').html(message);
    topNotificationBar.css({'top': '0px'});
    setTimeout(function () {
        topNotificationBar.css({'top': '-55px'});
        loadingBar.css({'top': '-55px'});
    }, 5000);
}

// AJAX POST
function post(button, path, data, callback, done, always, fail) {
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

            if (typeof fail === 'function') fail(data);
            else showErrorBar("Server Error. Please try again.");
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

function in_array(needle, haytack) {
    if(haytack.indexOf(needle) > -1) {
        return true;
    }
    return false;
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

    var remaining = total - ((page-1)*limit);

    $pnav.find('.-page-number').val(page);

    if(page==1) $pnav.find('.prev').addClass('disabled').attr('disabled', 'disabled');
    if(remaining <= limit) $pnav.find('.next').addClass('disabled').attr('disabled', 'disabled');

    if(total==0) {
        $pnav.hide();
    } else {
        $pnav.show();
    }
}

function base64EncodeUnicode(str) {
    // first we use encodeURIComponent to get percent-encoded UTF-8,
    // then we convert the percent encodings into raw bytes which
    // can be fed into btoa.
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode('0x' + p1);
        }));
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