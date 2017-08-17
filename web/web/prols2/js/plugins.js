/**
 * Full Calendar
 */
var calendarWrap = $('#calendar');

var _fullCalendar = function(){

    var calendar = function(){

        if(calendarWrap.length > 0){

            function prepare_external_list(){
                $('#external-events .external-event').each(function() {
                    var eventObject = {title: $.trim($(this).text())};

                    $(this).data('eventObject', eventObject);
                    $(this).draggable({
                        zIndex: 999,
                        revert: true,
                        revertDuration: 0
                    });
                });
            }

            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            prepare_external_list();

            calendarWrap.fullCalendar({
                events: {
                    url: DASHBOARD_URL_GET_EVENT,
                    data: function() {
                        var date = calendarWrap.fullCalendar('getDate');
                        var requests = [];
                        var events = [];

                        $('.legend-list li').each( function() {
                            if($(this).hasClass('active') && $(this).data('type')=='request') requests.push($(this).data('id'));
                            if($(this).hasClass('active') && $(this).data('type')=='event') events.push($(this).data('id'));
                        });

                        console.log(requests, events);

                        return {
                            date: date.format('YYYY-MM-DD'),
                            viewType: calendarWrap.fullCalendar('getView').name,
                            requests: requests,
                            events: events
                        };
                    }
                },
                loading: function(isLoading) {
                    if(isLoading)
                        showLoadingBar();
                    else {
                        // enableElement($(btnViewLeaveRequest));
                        hideLoadingBar();
                    }
                },
                eventLimit: 10,

                eventClick: function(calEvent){
                    var date = calEvent.start;

                    _fullCalendar.showEventsModal(date, calEvent.id);
                },

                dayClick: function(date){
                    _fullCalendar.showEventsModal(date);
                },

                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: true,
                droppable: true,
                selectable: true,

                selectHelper: true,
                fixedWeekCount: false,
                height: $(window).height()*0.83,
                eventStartEditable: false,

                select: function(start, end, allDay) {

                },

                drop: function(date, allDay) { },
            });

            $("#new-event").on("click",function(){
                var et = $("#new-event-text").val();
                if(et != ''){
                    $("#external-events").prepend('<a class="list-group-item external-event">'+et+'</a>');
                    prepare_external_list();
                }
            });

        }
    };

    return {
        init: function(){
            calendar();

            return this;
        },

        bindEvents: function() {
            $(document).on('click', '.btn-event-details-mo', function() {
                if($(this).hasClass('details-open')) {
                    $(this).removeClass('details-open');
                    $(this).closest('li').find('.sect-event-details-mo').hide();
                } else {
                    $(this).addClass('details-open');
                    $(this).closest('li').find('.sect-event-details-mo').show();
                }
            });

            $(document).on('click', '.legend-list li', function() {
                setTimeout( function() {
                    calendarWrap.fullCalendar('refetchEvents');
                }, 200);
            });

            return this;
        },

        showEventsModal: function(date, single) {
            $('.agenda-list ul').remove();
            $(".agenda-list").append('<ul class="collection agenda-list"></ul>');

            calendarWrap.fullCalendar('clientEvents', function (event) {
                if(!single || (single && single==event.id)) {
                    if (moment(date).format('MM-DD-YYYY') >= moment(event.start).format('MM-DD-YYYY') &&
                        moment(date).format('MM-DD-YYYY') <= moment(event.end).format('MM-DD-YYYY')) {
                        if (event.type == "request") {
                            $(".agenda-list ul").append('<li class="collection-item avatar"><i class="material-icons circle">perm_identity</i><span>' + event.requesttype + '</span><p>' + event.empname + '</p></li>');
                        } else if (event.type = "event") {
                            var details = '   <div class="mb2 display-none sect-event-details-mo">  ' +
                                '      <h5 class="colorBlack"><img src="/images/qoute.png" height="20" width="25"> [TITLE]</h5>  ' +
                                '      <span style="padding: 4px 0;color: #333;display: inline;"><span style="padding: 30px;-webkit-box-decoration-break: clone;box-decoration-break: clone;">[DESC]</span></span><br>  ' +
                                '      <hr style="border-top:1px dotted #ccc;margin-bottom:10px">  ' +
                                '      <strong class="font20 mr2">When&nbsp;&nbsp;&nbsp;:&nbsp;</strong> [DATE]<br>  ' +
                                '      <strong class="font20 mr2">Where&nbsp;&nbsp;:</strong> [VENUE]<br>  ' +
                                (event.eventTypeId != EVENT_TYPE_HOLIDAY && _.size(event.eventTags) ? '      <strong class="font20">Who&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> <br> ' +
                                '      <span style="">[TAGS]</span>  ' : ''  ) +
                                '  </div>  ';

                            var details = details.replace('[TITLE]', event.title)
                                    .replace('[DESC]', !_.isNull(event.eventDesc) ? event.eventDesc : '')
                                    .replace('[VENUE]', !_.isNull(event.eventVenue) ? event.eventVenue : '')
                                    .replace('[DATE]', event.eventFromDate + (event.eventFromDate != event.eventToDate ? ' to ' + event.eventToDate : ''))
                                    .replace('[TAGS]', event.eventTags)
                                ;

                            var subTitle = 'Organizer - ' + event.eventOwnerName;
                            var icon = 'query_builder';
                            var title = event.eventType + ': <strong>' + event.eventName + '</strong>';

                            if (event.eventTypeId == EVENT_TYPE_HOLIDAY) {
                                icon = 'grade';
                                subTitle = event.eventName;
                                title = event.eventType;
                            } else if (event.eventTypeId == EVENT_TYPE_INTERNAL)
                                icon = 'today';

                            $(".agenda-list ul").append('<li class="collection-item avatar"><i class="material-icons circle">' + icon + '</i><span>' + title + '</span><p class="colorLightGrey">' + subTitle + (event.eventTypeId != EVENT_TYPE_HOLIDAY ? '<a href="javascript:void(0);" class="pull-right btn-event-details-mo">Details</a>' : '') + ' <div>' + details + '</div></p></li>');
                        }
                    }
                }
            });

            _dashboardModal
                .openAgendaModal(date);

            return this;
        }
    }
}();

_fullCalendar
    .init()
    .bindEvents();

/**
 * Birthday
 */

function toggleBirthday(state) {

    var id = 'anim-birthday-fireworks';
    console.log(state)
    if(state=='hide') {
        $('canvas#'+id).remove();
        return;
    }


    var SCREEN_WIDTH = window.innerWidth,
        SCREEN_HEIGHT = window.innerHeight,
        mousePos = {
            x: 400,
            y: 300
        },

        // create canvas
        canvas = document.createElement('canvas'),
        context = canvas.getContext('2d'),
        particles = [],
        rockets = [],
        MAX_PARTICLES = 400,
        colorCode = 0;


    // init
    $(document).ready(function() {
        document.body.appendChild(canvas);
        canvas.id = id;
        canvas.width = SCREEN_WIDTH;
        canvas.height = SCREEN_HEIGHT;
        setInterval(launch, 800);
        setInterval(loop, 1000 / 50);
    });

    // update mouse position
    $(document).mousemove(function(e) {
        e.preventDefault();
        mousePos = {
            x: e.clientX,
            y: e.clientY
        };
    });

    // launch more rockets!!!
    $(document).mousedown(function(e) {
        for (var i = 0; i < 5; i++) {
            launchFrom(Math.random() * SCREEN_WIDTH * 2 / 3 + SCREEN_WIDTH / 6);
        }
    });

    function launch() {
        launchFrom(mousePos.x);
    }

    function launchFrom(x) {
        if (rockets.length < 10) {
            var rocket = new Rocket(x);
            rocket.explosionColor = Math.floor(Math.random() * 360 / 10) * 10;
            rocket.vel.y = Math.random() * -3 - 4;
            rocket.vel.x = Math.random() * 6 - 3;
            rocket.size = 8;
            rocket.shrink = 0.999;
            rocket.gravity = 0.01;
            rockets.push(rocket);
        }
    }

    function loop() {
        // update screen size
        if (SCREEN_WIDTH != window.innerWidth) {
            canvas.width = SCREEN_WIDTH = window.innerWidth;
        }
        if (SCREEN_HEIGHT != window.innerHeight) {
            canvas.height = SCREEN_HEIGHT = window.innerHeight;
        }

        // clear canvas
        context.fillStyle = "rgba(59,89,152,0.2)";
        context.fillRect(0, 0, SCREEN_WIDTH, SCREEN_HEIGHT);
        context.fillStyle = "rgba(0, 0, 200, 0.2)";
        context.globalAlpha = 0.8;

        var existingRockets = [];

        for (var i = 0; i < rockets.length; i++) {
            // update and render
            rockets[i].update();
            rockets[i].render(context);

            // calculate distance with Pythagoras
            var distance = Math.sqrt(Math.pow(mousePos.x - rockets[i].pos.x, 2) + Math.pow(mousePos.y - rockets[i].pos.y, 2));

            // random chance of 1% if rockets is above the middle
            var randomChance = rockets[i].pos.y < (SCREEN_HEIGHT * 2 / 3) ? (Math.random() * 100 <= 1) : false;

            /* Explosion rules
             - 80% of screen
             - going down
             - close to the mouse
             - 1% chance of random explosion
             */
            if (rockets[i].pos.y < SCREEN_HEIGHT / 5 || rockets[i].vel.y >= 0 || distance < 50 || randomChance) {
                rockets[i].explode();
            } else {
                existingRockets.push(rockets[i]);
            }
        }

        rockets = existingRockets;

        var existingParticles = [];

        for (var i = 0; i < particles.length; i++) {
            particles[i].update();

            // render and save particles that can be rendered
            if (particles[i].exists()) {
                particles[i].render(context);
                existingParticles.push(particles[i]);
            }
        }

        // update array with existing particles - old particles should be garbage collected
        particles = existingParticles;

        while (particles.length > MAX_PARTICLES) {
            particles.shift();
        }
    }

    function Particle(pos) {
        this.pos = {
            x: pos ? pos.x : 0,
            y: pos ? pos.y : 0
        };
        this.vel = {
            x: 0,
            y: 0
        };
        this.shrink = .97;
        this.size = 2;

        this.resistance = 1;
        this.gravity = 0;

        this.flick = false;

        this.alpha = 1;
        this.fade = 0;
        this.color = 0;
    }

    Particle.prototype.update = function() {
        // apply resistance
        this.vel.x *= this.resistance;
        this.vel.y *= this.resistance;

        // gravity down
        this.vel.y += this.gravity;

        // update position based on speed
        this.pos.x += this.vel.x;
        this.pos.y += this.vel.y;

        // shrink
        this.size *= this.shrink;

        // fade out
        this.alpha -= this.fade;
    };

    Particle.prototype.render = function(c) {
        if (!this.exists()) {
            return;
        }

        c.save();

        c.globalCompositeOperation = 'lighter';

        var x = this.pos.x,
            y = this.pos.y,
            r = this.size / 2;

        var gradient = c.createRadialGradient(x, y, 0.1, x, y, r);
        gradient.addColorStop(0.1, "rgba(255,255,255," + this.alpha + ")");
        gradient.addColorStop(0.8, "hsla(" + this.color + ", 100%, 50%, " + this.alpha + ")");
        gradient.addColorStop(1, "hsla(" + this.color + ", 100%, 50%, 0.1)");

        c.fillStyle = gradient;

        c.beginPath();
        c.arc(this.pos.x, this.pos.y, this.flick ? Math.random() * this.size : this.size, 0, Math.PI * 2, true);
        c.closePath();
        c.fill();

        c.restore();
    };

    Particle.prototype.exists = function() {
        return this.alpha >= 0.1 && this.size >= 1;
    };

    function Rocket(x) {
        Particle.apply(this, [{
            x: x,
            y: SCREEN_HEIGHT}]);

        this.explosionColor = 0;
    }

    Rocket.prototype = new Particle();
    Rocket.prototype.constructor = Rocket;

    Rocket.prototype.explode = function() {
        var count = Math.random() * 10 + 80;

        for (var i = 0; i < count; i++) {
            var particle = new Particle(this.pos);
            var angle = Math.random() * Math.PI * 2;

            // emulate 3D effect by using cosine and put more particles in the middle
            var speed = Math.cos(Math.random() * Math.PI / 2) * 15;

            particle.vel.x = Math.cos(angle) * speed;
            particle.vel.y = Math.sin(angle) * speed;

            particle.size = 10;

            particle.gravity = 0.2;
            particle.resistance = 0.92;
            particle.shrink = Math.random() * 0.05 + 0.93;

            particle.flick = true;
            particle.color = this.explosionColor;

            particles.push(particle);
        }
    };

    Rocket.prototype.render = function(c) {
        if (!this.exists()) {
            return;
        }

        c.save();

        c.globalCompositeOperation = 'lighter';

        var x = this.pos.x,
            y = this.pos.y,
            r = this.size / 2;

        var gradient = c.createRadialGradient(x, y, 0.1, x, y, r);
        gradient.addColorStop(0.1, "rgba(255, 255, 255 ," + this.alpha + ")");
        gradient.addColorStop(1, "rgba(0, 0, 0, " + this.alpha + ")");

        c.fillStyle = gradient;

        c.beginPath();
        c.arc(this.pos.x, this.pos.y, this.flick ? Math.random() * this.size / 2 + this.size / 2 : this.size, 0, Math.PI * 2, true);
        c.closePath();
        c.fill();

        c.restore();
    };
}




/**
 *
 * @type {{init}}
 */

var formElements = function(){
    //Daterangepicker
    var feDaterangepicker = function(){
        if($(".daterange").length > 0)
           $(".daterange").daterangepicker({format: 'YYYY-MM-DD',startDate: '2013-01-01',endDate: '2013-12-31'});
    }
    // END Daterangepicker

    //Bootstrap colopicker
    var feColorpicker = function(){
        // Default colorpicker hex
        if($(".colorpicker").length > 0)
            $(".colorpicker").colorpicker({format: 'hex'});

        // RGBA mode
        if($(".colorpicker_rgba").length > 0)
            $(".colorpicker_rgba").colorpicker({format: 'rgba'});

        // Sample
        if($("#colorpicker").length > 0)
            $("#colorpicker").colorpicker();

    }// END Bootstrap colorpicker

    //Bootstrap select
    var feSelect = function(){
        if($(".select").length > 0){
            $(".select").selectpicker();

            $(".select").on("change", function(){
                if($(this).val() == "" || null === $(this).val()){
                    if(!$(this).attr("multiple"))
                        $(this).val("").find("option").removeAttr("selected").prop("selected",false);
                }else{
                    $(this).find("option[value="+$(this).val()+"]").attr("selected",true);
                }
            });
        }
    }//END Bootstrap select


    //Validation Engine
    var feValidation = function(){
        if($("form[id^='validate']").length > 0){

            // Validation prefix for custom form elements
            var prefix = "valPref_";

            //Add prefix to Bootstrap select plugin
            $("form[id^='validate'] .select").each(function(){
               $(this).next("div.bootstrap-select").attr("id", prefix + $(this).attr("id")).removeClass("validate[required]");
            });

            // Validation Engine init
            $("form[id^='validate']").validationEngine('attach',
                {promptPosition : "bottomLeft", scroll: false,
                    onValidationComplete: function(form, status){
                        form.validationEngine("updatePromptsPosition");
                    },
                    prettySelect : true,
                    usePrefix: prefix
                });
        }
    }//END Validation Engine

    //Masked Inputs
    var feMasked = function(){
        if($("input[class^='mask_']").length > 0){
            $("input.mask_tin").mask('99-9999999');
            $("input.mask_ssn").mask('999-99-9999');
            $("input.mask_date").mask('9999-99-99');
            $("input.mask_product").mask('a*-999-a999');
            $("input.mask_phone").mask('99 (999) 999-99-99');
            $("input.mask_phone_ext").mask('99 (999) 999-9999? x99999');
            $("input.mask_credit").mask('9999-9999-9999-9999');
            $("input.mask_percent").mask('99%');
        }
    }//END Masked Inputs

    //Tagsinput
    var feTagsinput = function(){
        if($(".tagsinput").length > 0){

            $(".tagsinput").each(function(){

                if($(this).data("placeholder") != ''){
                    var dt = $(this).data("placeholder");
                }else
                    var dt = 'add a tag';

                $(this).tagsInput({width: '100%',height:'auto',defaultText: dt});
            });

        }
    }// END Tagsinput

    //iCheckbox and iRadion - custom elements
    var feiCheckbox = function()
    {
        if($(".icheckbox").length > 0)
        {
             $(".icheckbox,.iradio").iCheck({checkboxClass: 'icheckbox_minimal-grey',radioClass: 'iradio_minimal-grey'});
        }
    }
    // END iCheckbox

    //Bootstrap file input
    var feBsFileInput = function(){

        if($("input.fileinput").length > 0)
            $("input.fileinput").bootstrapFileInput();

    }
    //END Bootstrap file input

    return {// Init all form element features
    init: function(){
            //feDatepicker();
            //feTimepicker();
            feColorpicker();
            feSelect();
            feValidation();
            feMasked();
            // feTooltips();
            // fePopover();
            feTagsinput();
            feiCheckbox();
            feBsFileInput();
            feDaterangepicker();
        }
    }
}();

var uiElements = function(){

    //Datatables
    var uiDatatable = function(){
        if($(".datatable").length > 0){
            $(".datatable").dataTable();
            $(".datatable").on('page.dt',function () {
                onresize(100);
            });
        }

        if($(".datatable_simple").length > 0){
            $(".datatable_simple").dataTable({"ordering": false, "info": false, "lengthChange": false,"searching": false});
            $(".datatable_simple").on('page.dt',function () {
                onresize(100);
            });
        }
    }//END Datatable

    //RangeSlider // This function can be removed or cleared.
    var uiRangeSlider = function(){

        //Default Slider with start value
        if($(".defaultSlider").length > 0){
            $(".defaultSlider").each(function(){
                var rsMin = $(this).data("min");
                var rsMax = $(this).data("max");

                $(this).rangeSlider({
                    bounds: {min: 1, max: 200},
                    defaultValues: {min: rsMin, max: rsMax}
                });
            });
        }//End Default

        //Date range slider
        if($(".dateSlider").length > 0){
            $(".dateSlider").each(function(){
                $(this).dateRangeSlider({
                    bounds: {min: new Date(2012, 1, 1), max: new Date(2015, 12, 31)},
                    defaultValues:{min: new Date(2012, 10, 15),max: new Date(2014, 12, 15)}
                });
            });
        }//End date range slider

        //Range slider with predefinde range
        if($(".rangeSlider").length > 0){
            $(".rangeSlider").each(function(){
                var rsMin = $(this).data("min");
                var rsMax = $(this).data("max");

                $(this).rangeSlider({
                    bounds: {min: 1, max: 200},
                    range: {min: 20, max: 40},
                    defaultValues: {min: rsMin, max: rsMax}
                });
            });
        }//End

        //Range Slider with custom step
        if($(".stepSlider").length > 0){
            $(".stepSlider").each(function(){
                var rsMin = $(this).data("min");
                var rsMax = $(this).data("max");

                $(this).rangeSlider({
                    bounds: {min: 1, max: 200},
                    defaultValues: {min: rsMin, max: rsMax},
                    step: 10
                });
            });
        }//End

    }//END RangeSlider

    //Start Knob Plugin
    var uiKnob = function(){

        if($(".knob").length > 0){
            $(".knob").knob();
        }

    }//End Knob

    // Start Smart Wizard
    var uiSmartWizard = function(){

        if($(".wizard").length > 0){

            //Check count of steps in each wizard
            $(".wizard > ul").each(function(){
                $(this).addClass("steps_"+$(this).children("li").length);
            });//end

            // This par of code used for example
            if($("#wizard-validation").length > 0){

                var validator = $("#wizard-validation").validate({
                        rules: {
                            login: {
                                required: true,
                                minlength: 2,
                                maxlength: 8
                            },
                            password: {
                                required: true,
                                minlength: 5,
                                maxlength: 10
                            },
                            repassword: {
                                required: true,
                                minlength: 5,
                                maxlength: 10,
                                equalTo: "#password"
                            },
                            email: {
                                required: true,
                                email: true
                            },
                            name: {
                                required: true,
                                maxlength: 10
                            },
                            adress: {
                                required: true
                            }
                        }
                    });

            }// End of example

            $(".wizard").smartWizard({
                // This part of code can be removed FROM
                onLeaveStep: function(obj){
                    var wizard = obj.parents(".wizard");

                    if(wizard.hasClass("wizard-validation")){

                        var valid = true;

                        $('input,textarea',$(obj.attr("href"))).each(function(i,v){
                            valid = validator.element(v) && valid;
                        });

                        if(!valid){
                            wizard.find(".stepContainer").removeAttr("style");
                            validator.focusInvalid();
                            return false;
                        }

                    }

                    return true;
                },// <-- TO

                //This is important part of wizard init
                onShowStep: function(obj){
                    var wizard = obj.parents(".wizard");

                    if(wizard.hasClass("show-submit")){

                        var step_num = obj.attr('rel');
                        var step_max = obj.parents(".anchor").find("li").length;

                        if(step_num == step_max){
                            obj.parents(".wizard").find(".actionBar .btn-primary").css("display","block");
                        }
                    }
                    return true;
                }//End
            });
        }

    }// End Smart Wizard

    //OWL Carousel
    var uiOwlCarousel = function(){

        if($(".owl-carousel").length > 0){
            $(".owl-carousel").owlCarousel({mouseDrag: false, touchDrag: true, slideSpeed: 300, paginationSpeed: 400, singleItem: true, navigation: false,autoPlay: true});
        }

    }//End OWL Carousel

    // Summernote
    var uiSummernote = function(){
        /* Extended summernote editor */
        if($(".summernote").length > 0){
            $(".summernote").summernote({height: 250,
                                         codemirror: {
                                            mode: 'text/html',
                                            htmlMode: true,
                                            lineNumbers: true,
                                            theme: 'default'
                                          }
            });
        }
        /* END Extended summernote editor */

        /* Lite summernote editor */
        if($(".summernote_lite").length > 0){

            $(".summernote_lite").on("focus",function(){

                $(".summernote_lite").summernote({height: 100, focus: true,
                                                  toolbar: [
                                                      ["style", ["bold", "italic", "underline", "clear"]],
                                                      ["insert",["link","picture","video"]]
                                                  ]
                                                 });
            });
        }
        /* END Lite summernote editor */

        /* Email summernote editor */
        if($(".summernote_email").length > 0){

            $(".summernote_email").summernote({height: 400, focus: true,
                                              toolbar: [
                                                  ['style', ['bold', 'italic', 'underline', 'clear']],
                                                  ['font', ['strikethrough']],
                                                  ['fontsize', ['fontsize']],
                                                  ['color', ['color']],
                                                  ['para', ['ul', 'ol', 'paragraph']],
                                                  ['height', ['height']]
                                              ]
                                             });

        }
        /* END Email summernote editor */

    }// END Summernote

    // Custom Content Scroller
    var uiScroller = function(){

        if($(".scroll").length > 0){
            $(".scroll").mCustomScrollbar({axis:"y", autoHideScrollbar: true, scrollInertia: 20, advanced: {autoScrollOnFocus: false}});
        }

    }// END Custom Content Scroller

    // Sparkline
    var uiSparkline = function(){

        if($(".sparkline").length > 0)
           $(".sparkline").sparkline('html', { enableTagOptions: true,disableHiddenCheck: true});

   }// End sparkline

    $(window).resize(function(){
        if($(".owl-carousel").length > 0){
            $(".owl-carousel").data('owlCarousel').destroy();
            uiOwlCarousel();
        }
    });

    return {
        init: function(){
            uiDatatable();
            uiRangeSlider();
            uiKnob();
            uiSmartWizard();
            uiOwlCarousel();
            uiSummernote();
            uiScroller();
            uiSparkline();
        }
    }

}();

var templatePlugins = function(){

    var tp_clock = function(){

        function tp_clock_time(){
            var now     = new Date();
            var hour    = now.getHours();
            var minutes = now.getMinutes();
            var indicator = 'AM';


            minutes = minutes < 10 ? '0'+minutes : minutes;

            if(hour == 0) {
                hour = 12;
            } else if(hour > 12) {
                indicator ='PM';
                hour = hour - 12;
            } else if (hour == 12) {
                indicator ='PM';
            }

            hour = hour < 10 ? '0'+hour : hour;


            $(".plugin-clock").html(hour+"<span>:</span>"+minutes + " " + indicator);
        }
        if($(".plugin-clock").length > 0){

            tp_clock_time();

            window.setInterval(function(){
                tp_clock_time();
            },10000);

        }
    }

    var tp_date = function(){

        if($(".plugin-date").length > 0){

            var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
            var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

            var now     = new Date();
            var day     = days[now.getDay()];
            var date    = now.getDate();
            var month   = months[now.getMonth()];
            var year    = now.getFullYear();

            $(".plugin-date").html(""+day+", "+month+" "+date+", "+year);
        }

    }

    return {
        init: function(){
            tp_clock();
            tp_date();
        }
    }
}();


formElements.init();
uiElements.init();
templatePlugins.init();


/* My Custom Progressbar */
$.mpb = function(action,options){

    var settings = $.extend({
        state: '',
        value: [0,0],
        position: '',
        speed: 20,
        complete: null
    },options);

    if(action == 'show' || action == 'update'){

        if(action == 'show'){
            $(".mpb").remove();
            var mpb = '<div class="mpb '+settings.position+'">\n\
                           <div class="mpb-progress'+(settings.state != '' ? ' mpb-'+settings.state: '')+'" style="width:'+settings.value[0]+'%;"></div>\n\
                       </div>';
            $('body').append(mpb);
        }

        var i  = $.isArray(settings.value) ? settings.value[0] : $(".mpb .mpb-progress").width();
        var to = $.isArray(settings.value) ? settings.value[1] : settings.value;

        var timer = setInterval(function(){
            $(".mpb .mpb-progress").css('width',i+'%'); i++;

            if(i > to){
                clearInterval(timer);
                if($.isFunction(settings.complete)){
                    settings.complete.call(this);
                }
            }
        }, settings.speed);

    }

    if(action == 'destroy'){
        $(".mpb").remove();
    }

}
/* Eof My Custom Progressbar */


// New selector case insensivity
 $.expr[':'].containsi = function(a, i, m) {
     return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
 };

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};