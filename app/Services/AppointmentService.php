<?php

namespace App\Services;

use Carbon\Carbon;

class AppointmentService
{

    public function getDisableTimes($appointment_type,$booking_date): array
    {

        $disable_times= $appointment_type->Bookings()->whereDate('booking_date', $booking_date)->get()->map(function ($booking) {
            return Carbon::parse($booking->booking_date)->format('H:i');
        })->toArray();
        $index=0;
        $initial_count=count($disable_times);
       foreach($disable_times as $k=>$disable){

           if( $k===$initial_count-1){
               $after_30_mins= Carbon::createFromFormat('H:i', $disable)->addMinutes(30)->format('H:i');
               $after_1_hour= Carbon::createFromFormat('H:i', $disable)->addHour()->format('H:i');
                array_push($disable_times,$after_30_mins,$after_1_hour);
           }
           if(($index===1 || $index===2)){
               continue;
           }
           if($index===3 ){
               $index=0;
           }
           $after_30_mins= Carbon::createFromFormat('H:i', $disable)->addMinutes(30)->format('H:i');
           $after_1_hour= Carbon::createFromFormat('H:i', $disable)->addHour()->format('H:i');
           $disable_times = array_merge(array_slice($disable_times, 0, $k+1), array($after_30_mins), array_slice($disable_times, $k+1));
           $disable_times = array_merge(array_slice($disable_times, 0, $k+2), array($after_1_hour), array_slice($disable_times, $k+2));
           $index+=1;
       }
       return $disable_times;
    }


    public function appointmentDates(): array
    {
        $dates['start_hour1'] = Carbon::createFromFormat('H:i', '09:00');
        $dates['end_hour1'] = Carbon::createFromFormat('H:i', '13:00');
        $dates['start_hour2'] = Carbon::createFromFormat('H:i', '15:30');
        $dates['end_hour2'] = Carbon::createFromFormat('H:i', '21:00');
        return $dates;
    }
}
