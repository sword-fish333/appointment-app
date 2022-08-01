<?php

namespace App\Http\Controllers;

use App\Models\AppointmentType;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MainPageController extends Controller
{
    public function index(AppointmentService $appointmentService)
    {
        $appointment_types = AppointmentType::get();
        $appointment_dates = $appointmentService->appointmentDates();
        $first_appointment = $appointment_types->first();
        if ($first_appointment) {

            $disable_times = $appointmentService->getDisableTimes($first_appointment,today());
        }else{
            $disable_times=[];
        }
        return view('index', compact('appointment_types', 'appointment_dates','disable_times'));
    }


}
