/**
 * Created by Hazel on 22/02/2017.
 */
// SCRIPT VARIABLES
var data, callback, done, always;

// LOADER VARIABLES
var loadingBar = $('.loader-notif');
var successBar = $('.successful-popup');
var errorBar = $('.error-popup');

function showLoadingBar() {
    loadingBar.css({'top': '0px'});
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

// AJAX POST
function post(button, path, data, callback, done, always) {
    console.log("enter post");
    showLoadingBar();
    console.log("done loading   ");
    disableEnableButton(button, true);
    $.post(path, data, callback, "json").done(done).always(always)
        .fail(function() {
            showErrorBar("Server Error. Please try again.");
        });
    disableEnableButton(button, false);
}

function disableEnableButton(button, val) {
    button.prop("disabled", val);
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
    setTimeout(function () {
        element.css({'border-color': 'gray'})
    }, 1000);
}
