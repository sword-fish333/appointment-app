@extends('template.master')
@section('head')
    <link rel="stylesheet" type="text/css" href="{{asset('css/vanilla-calendar.min.css')}}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

@endsection
@section('content')
    <div class="container-fluid">
        <div class="calendar-container">
            <h1 class="text-center mt-4 custom-underline" style="font-size:40px;position: relative;">Book now</h1>
            <div class="main-appointment-container">
                <div class="appointment-column">
                    <div class="calendar-wrapper">
                    <p style="align-self:flex-start">Date</p>
                    <div class="vanilla-calendar" >
                    </div>
                    </div>
                </div>
                <div class="appointment-column" style="padding-left: 20px">
                    <label for="appointment_type">Appointment Type</label>
                    <select name="appointment_type"  id="appointment_type" class="form-control">
                        @foreach($appointment_types as $appointment_type)
                            <option value="{{$appointment_type->id}}">{{$appointment_type->name}}</option>
                        @endforeach
                    </select>
                    <hr style="margin-top:20px">
                    <div class="time-intervals-wrapper">
                        <p>Appointment time:</p>
                        <div id="time_intervals_main_wrapper">
                        @include('partials._appointment_times')
                        </div>
                    </div>

                </div>
            </div>
            <button class="btn btn-lg  mt-5 submit-btn" style="margin:0 auto;display:block">
                Submit
            </button>
        </div>
    </div>
@endsection
@section('javascript')
    <script src="{{asset('js/vanilla-calendar.min.js')}}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let calendar_date = '{{now()->format('Y-m-d')}}'
        calendar = new VanillaCalendar({
            HTMLElement: document.querySelector('.vanilla-calendar'),
            date: new Date(),
            settings: {
                range: {
                    min: today(),
                    max: after60Days(),
                    // disabled: disabled_dates,

                },
            },
            actions: {
                clickDay(e) {
                    calendar_date = e.target.dataset.calendarDay
                    updateBookingTimes(calendar_date)
                },
            },


        });

        calendar.init();

        function today() {
            return convertDate(new Date());
        }

        function convertDate(date) {
            let month = (date.getMonth() + 1);
            if (month < 10) {
                month = '0' + month
            }
            let day = date.getDate();
            if (day < 10) {
                day = '0' + day;
            }

            return date.getFullYear() + '-' + month + '-' + day;
        }

        function after60Days() {
            let date = new Date(); // Now
            date = new Date(date.setDate(date.getDate() + 60));
            return convertDate(date)
        }

        function bookAppointment(appointment_time, disable_time) {
            const appointment_type_id = $('#appointment_type').val()
            if (!appointment_type_id) {
                showErrorToast('Please select an appointment type', 2500)
                return;
            }
            $.ajax({

                type: "POST",
                url: "{{route('make-appointment')}}",
                data: {
                    date: calendar_date,
                    appointment_time,
                    appointment_type_id
                },
                success: function (data) {
                    if (!data.success) {
                        showErrorToast(data.message, 5500);
                        return;
                    }
                    showSuccess(data.message);
                    $(`#booking_time_${disable_time}`).addClass('disabled-time')
                },
                error: function (error) {
                    if (error.responseJSON.message) {
                        showErrorToast(error.responseJSON.message)
                        return;
                    }
                    showErrorToast('Sorry, something went wrong. Please try again', 2500)

                }

            });

        }

        $('#appointment_type').change(function(){
            updateBookingTimes();
        })
        function updateBookingTimes(){
            const appointment_type_id = $('#appointment_type').val()
            if (!appointment_type_id) {
                showErrorToast('Please select an appointment type', 2500)
                return;
            }
            $.ajax({

                type: "GET",
                url: "{{route('get-appointment-times')}}",
                data: {
                    date: calendar_date,
                    appointment_type_id
                },
                success: function (data) {
                    if (!data.success) {
                        showErrorToast(data.message, 5500);
                        return;
                    }
                    $('#time_intervals_main_wrapper').html(data.appointment_times)
                  console.log('data',data)
                },
                error: function (error) {
                    if (error.responseJSON.message) {
                        showErrorToast(error.responseJSON.message)
                        return;
                    }
                    showErrorToast('Sorry, something went wrong. Please try again', 2500)

                }

            });
        }
    </script>
@endsection
