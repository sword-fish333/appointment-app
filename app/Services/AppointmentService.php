<?php

namespace App\Services;

use Carbon\Carbon;

class AppointmentService
{

    public function getDisableTimes($appointment_type,$booking_date): array
    {

        return $appointment_type->Bookings()->whereDate('booking_date', $booking_date)->get()->map(function ($booking) {
            return Carbon::parse($booking->booking_date)->format('H:i');
        })->toArray();
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
