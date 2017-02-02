/**
 * Created by admin on 1/13/2017.
 */


var i = 0;
function pickDay(i) {
    console.log('pikass');
    var picker;
    var picker2;
    picker = new Pikaday({field: document.querySelector('#start_date'+i)});
    picker2 = new Pikaday({field: document.querySelector('#end_date'+i)});
    //$('.start-date').val('');
   // $('.end-date').val('');
    picker.destroy();
    picker2.destroy();
    picker = new Pikaday({
        field: document.querySelector('#start_date'+i), onSelect: function () {
            picker2.hide();
            //$('.end-date').val('');
            if (picker.getDate != '') {
                picker2.destroy();
                picker2 = new Pikaday({
                    field: document.querySelector('#end_date'+i), onSelect: function () {
                        picker2.setEndRange(picker2.getDate());
                        picker.setStartRange(picker.getDate());
                        picker.setEndRange(picker2.getDate());
                    }
                });
                picker.setStartRange(picker.getDate());
                picker.setEndRange(picker2.getDate());
                picker2.setStartRange(picker.getDate());
                picker2.show();
                picker2.setMinDate(picker.getDate());
            }
        }
    });
    picker.setMinDate(new Date());

}

pickDay('');