/**
 * Created by admin on 1/19/2017.
 */
function computeTime(){
    //time out
    var getTimeIn = $('.time-logs-table').find('tbody').find('tr').data('time-in');
    var d = new Date(getTimeIn);
    var past_hour = d.getHours();
    var past_minutes = d.getMinutes();

//                    var duration = past_hour + ':' + past_minutes;

    //Date Today
    var date = new Date();
    var present_hour = date.getHours();
    var present_minute = date.getMinutes();

    //compute for duration

    var duration_hours = present_hour - past_hour;
    var duration_min =  present_minute - past_minutes;
    var num_of_hours = 'hr';
    if(duration_hours > 1)
    {
        num_of_hours = 'hrs';
        var total_duration =  duration_hours + num_of_hours + " " + duration_min + "min";
    }
    var total_duration =  duration_hours + num_of_hours + " " + duration_min + "min";
    console.log(total_duration);
    if(!total_duration)
    {
        total_duration = 0;
    }
    $("#duration-time").html(total_duration);
}
computeTime();