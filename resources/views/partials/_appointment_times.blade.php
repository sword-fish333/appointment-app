<div class="booking-times-main-container">
    <div class="time-wrapper">
        <h4>Morning</h4>
        <div class="time-intervals-container" >
            @while($appointment_dates['start_hour1']->lte($appointment_dates['end_hour1']))
                @php
                    $time=$appointment_dates['start_hour1']->format('H:i');
                    $disable_time=implode('_',explode(':',$time));

                @endphp
                <span
                    class="time-interval    @if(in_array($time,$disable_times))  disabled-time @endif"
                    id="booking_time_{{implode('_',explode(':',$time))}}"
                    @if(!in_array($time,$disable_times))    onclick="bookAppointment('{{$time}}','{{$disable_time}}')" @endif>{{$appointment_dates['start_hour1']->format('H:i')}}</span>
                @php
                    $appointment_dates['start_hour1']->addMinutes(30);
                @endphp
            @endwhile
        </div>
    </div>
    <div class="time-wrapper">
        <h4>Evening</h4>
        <div class="time-intervals-container">

            @while($appointment_dates['start_hour2']->lte($appointment_dates['end_hour2']))
                @php
                    $time=$appointment_dates['start_hour1']->format('H:i');
                $disable_time=implode('_',explode(':',$time));
                @endphp
                <span
                    class="time-interval  @if(in_array($time,$disable_times))  disabled-time @endif"
                    id="booking_time_{{$disable_time}}"
                    @if(!in_array($time,$disable_times))       onclick="bookAppointment('{{$appointment_dates['start_hour1']->format('H:i')}}','{{$disable_time}}')" @endif>{{$appointment_dates['start_hour2']->format('H:i')}}</span>
                @php
                    $appointment_dates['start_hour2']->addMinutes(30);
                @endphp
            @endwhile
        </div>
    </div>
</div>
