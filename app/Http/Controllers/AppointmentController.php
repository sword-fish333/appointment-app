<?php

namespace App\Http\Controllers;

use App\Models\AppointmentType;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{

    public function makeAppointment(){
        request()->validate([
            'date'=>'required|date_format:Y-m-d|after:yesterday',
            'appointment_time'=>'required|date_format:H:i',
            'appointment_type_id'=>'required'
        ]);
        $appointment_type=AppointmentType::find(request('appointment_type_id'));
        if(!$appointment_type){
            return response()->json(['success'=>false,'message'=>'Appointment type can not be found']);
        }
        try {
            $booking_time=Carbon::createFromFormat('Y-m-d H:i',request('date').' '.request('appointment_time'));
        }catch(\Exception $e){
            return response()->json(['success'=>false,'message'=>'Sorry, something went wrong. Invalid booking time']);

        }
        if($appointment_type->Bookings()->whereBetween('booking_date',
            [$booking_time->subMinutes(29)->toDateTimeLocalString(),$booking_time->addMinutes(29)->toDateTimeLocalString()])->exists()){

            return response()->json(['success'=>false,'message'=>'Sorry, booking already made for that time. Please try another one']);

        }
        $appointment_type->Bookings()->create([
            'booking_date'=>$booking_time,
            'user_id'=>auth()->id(),
        ]);
        $disable_time1=implode('_',explode(':',$booking_time->format('H:i')));
        $disable_time2=implode('_',explode(':',Carbon::parse($booking_time)->addMinutes(30)->format('H:i')));
        $disable_time3=implode('_',explode(':',Carbon::parse($booking_time)->addHour()->format('H:i')));

        return response()->json(['success'=>true,'message'=>'Appointment made successfully','disable_time1'=>$disable_time1,'disable_time2'=>$disable_time2,'disable_time3'=>$disable_time3]);

    }

    public function getAppointmentTimes(appointmentService $appointmentService){
        request()->validate([
            'date'=>'required|date_format:Y-m-d|after:yesterday',
            'appointment_type_id'=>'required'
        ]);
        $appointment_type=AppointmentType::find(request('appointment_type_id'));
        if(!$appointment_type){
            return response()->json(['success'=>false,'message'=>'Appointment type can not be found']);
        }
        try {
            $booking_time=Carbon::createFromFormat('Y-m-d',request('date'));
        }catch(\Exception $e){
            return response()->json(['success'=>false,'message'=>'Sorry, something went wrong. Invalid booking time']);

        }
        $appointment_dates = $appointmentService->appointmentDates();
        $disable_times = $appointmentService->getDisableTimes($appointment_type,$booking_time);
        $appointment_times=view('partials._appointment_times',compact('appointment_dates','disable_times'))->render();
        return response()->json(['success'=>true,'message'=>'Available appointment times','appointment_times'=>$appointment_times]);
    }
}
