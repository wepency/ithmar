@extends('layouts.front-page')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/calendar.css')}}" />

    <script>
        window.currentDate = '{{$current_month}}'
        window.events = '{!! json_encode($dates) !!}'
    </script>

    <script src="{{asset('js/calendar.js')}}" defer></script>

    <style>
        body {
            margin: 40px 10px;
            padding: 0;
            font-size: 14px;
        }

        #calendar {
            max-width: 1100px;
            margin: 0 auto;
        }
        .fc-header-toolbar.fc-toolbar{
            display: none;
        }
        .fc-event-title-container{
            padding: 0 20px;
        }
        .fc-event-time{
            display: none;
        }
        .fc .fc-daygrid-event-harness-abs {
            top: 0 !important;
        }
        #calendar-buttons{
            display: none;
            flex: 1;
            justify-content: center;
            /*opacity: 0;*/
            /*visibility: hidden;*/
        }
        #calendar-buttons .form-inline-style{
            direction: rtl;
            margin-right: 10px;
            flex: 1;
        }
        #calendar-buttons form.form-inline-style{
            display: inline-block;
        }
        #calendar-buttons form button,
        #calendar-buttons form a{
            flex: 1;
            width: 100%;
        }
        #calendar-buttons form.form-inline button{
            width: initial;
        }
        #make-available-modal button.close{
            width: 50px;
            display: flex;
            flex: initial;
            height: 50px;
            margin: -9px;
            justify-content: center;
            align-items: center;
        }
        #edit-price{
            justify-content: center;
            display: none;
        }
    </style>

    <?php $carbon = new \Carbon\Carbon; ?>

{{--    <script>--}}
{{--        let dates = [];--}}

{{--        @foreach($available as $avail)--}}
{{--        dates.push({--}}
{{--            title: 'متاحة للحجز',--}}
{{--            start: '{{$carbon->parse($avail->from)->format('Y-m-d 02:00:00')}}',--}}
{{--            end: '{{$carbon->parse($avail->to)->addDay()->format('Y-m-d 00:00:00')}}',--}}
{{--            color: '#2980b9',--}}
{{--            url: '{{route('availability.edit', [$unit_id, base64_encode(base64_encode($avail->id))])}}',--}}
{{--        })--}}
{{--        @endforeach--}}

{{--        @foreach($dates as $date)--}}
{{--            <?php--}}
{{--                $end_date = $carbon->parse($date->to)->addDay();--}}

{{--                if ($date->type != 'disabled'){--}}
{{--                    $end_date = $end_date->startOfDay()->format('Y-m-d 00:00:00');--}}
{{--                }else {--}}
{{--                    $end_date = $end_date->format('Y-m-d 00:00:00');--}}
{{--                }--}}
{{--            ?>--}}
{{--        dates.push({--}}
{{--            title: '{{get_title($date)}}',--}}
{{--            start: '{{$carbon->parse($date->from)->format('Y-m-d H:i:s')}}',--}}
{{--            end: '{{$end_date}}',--}}
{{--            // color: '#2980b9',--}}
{{--            color: '{{get_color($date)}}',--}}
{{--            @if($date->type == 'waiting')--}}
{{--            url: '{{route('availability.waiting', $date->id)}}'--}}
{{--            @endif--}}
{{--        })--}}
{{--        @endforeach--}}

{{--        document.addEventListener('DOMContentLoaded', function() {--}}
{{--            var calendarEl = document.getElementById('calendar');--}}

{{--            var calendar = new FullCalendar.Calendar(calendarEl, {--}}
{{--                initialDate: '{{$curnt_month}}',--}}
{{--                editable: false,--}}
{{--                draggable: false,--}}
{{--                locale: 'ar',--}}
{{--                selectable: true,--}}
{{--                eventLimit: false,--}}
{{--                businessHours: false,--}}
{{--                eventOrder: '-duration',--}}
{{--                dayMaxEvents: false, // allow "more" link when too many events--}}
{{--                handleWindowResize: false,--}}
{{--                aspectRatio: 1.35,--}}
{{--                minTime:'00:00:00',--}}
{{--                maxTime:'24:00:00',--}}
{{--                select: function(arg) {--}}
{{--                    // var title = prompt('Event Title:');--}}

{{--                    $('#close-modal').modal('show')--}}
{{--                    $('#start-date').html(arg.startStr)--}}
{{--                    $('.start-date-input').val(arg.startStr)--}}
{{--                    $('#end-date').html(arg.endStr)--}}
{{--                    $('.end-date-input').val(arg.endStr)--}}
{{--                    $('#add-availability').attr('href', '{{route('availability.create', $unit_id)}}'+'?from='+arg.startStr+'&to='+arg.endStr)--}}
{{--                },--}}
{{--                // events: [--}}
{{--                //     {--}}
{{--                //         title: 'بانتظار دفع العربون',--}}
{{--                //         start: '2022-03-10',--}}
{{--                //         end: '2022-03-15',--}}
{{--                //         color: '#f1c40f'--}}
{{--                //     },--}}
{{--                //     {--}}
{{--                //         title: 'محجوز',--}}
{{--                //         start: '2022-03-17',--}}
{{--                //         end: '2022-03-25',--}}
{{--                //         color: '#e74c3c'--}}
{{--                //     },--}}
{{--                //     {--}}
{{--                //         title: 'محجوز',--}}
{{--                //         start: '2022-03-26',--}}
{{--                //         end: '2022-03-29',--}}
{{--                //         color: '#e74c3c'--}}
{{--                //     }--}}
{{--                // ]--}}

{{--                events: dates--}}
{{--            });--}}

{{--            calendar.render();--}}
{{--        });--}}
{{--    </script>--}}
@endsection

@section('content')
    <div class="modal fade" id="edit-availability" tabindex="-1" role="dialog" aria-labelledby="EditAvailabilityLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditAvailabilityLabel">تعديل السعر</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="edit_prices_form" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                        <h3>تعديل سعر التوافر لتاريخ <span id="edit_date"></span></h3>

                        <div class="form-group">
                            <label for="min_stay">الحد الأدنى للحجز</label>

                            <select class="nice-select w-100" id="edit_min_stay" name="min_stay">
                                @for($i=1;$i<10;$i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="price">سعر الليلة</label>
                            <input type="number" name="price" id="edit_price" class="form-control" value="{{old('price')}}" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" id="edit-availability" class="btn btn-primary w-100"><i class="fa fa-edit"></i> تعديل السعر و التوافر </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container main-container">

        <div class="card">

            <div class="card-header">
                <h5 class="text-center w-100"><span class="unit-class">الوحدة: {{$unit->unit->unit_number ?? ''}}</span> - <span class="beach-class"> الشاطئ: {{$unit->unit->beach->beach ?? ''}}</span> - <span> القطاع: {{$unit->unit->sector->sector_name}}</span></h5>
            </div>

            <div class="card-body">
                @include('admin.layouts.messages')

                <div class="calendar-wrap">
                    <div id="header">
                        <div id="monthDisplay"></div>
                        <div>
                            <a href="{{route('availability.show', ['availability' => $unit_id, 'date' => urlencode($previous_month)])}}" class="btn btn-danger">السابق</a>
                            <a href="{{route('availability.show', ['availability' => $unit_id, 'date' => urlencode($next_month)])}}" class="btn btn-danger">التالي</a>
                        </div>
                    </div>

                    <div id="calendar-buttons">
                        <button data-toggle="modal" data-target="#edit-availability" id="edit-price" class="btn btn-primary form-inline-style" style="width: 100%">تعديل السعر</button>

                        <form class="form-inline-style" action="{{route('availability.store', $unit_id)}}" method="POST">
                            @csrf

                            <input type="hidden" class="dates-field" id="available-dates" name="dates" />
{{--                            <input type="hidden" class="to-field" id="to-available" name="to" />--}}

                            <a href="#" type="button" data-toggle="modal" data-target="#make-available-modal" class="btn btn-primary">اتاحة و تحديد السعر</a>

                            <div class="modal fade" id="make-available-modal" tabindex="-1" role="dialog" aria-labelledby="MakeAvailableModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="MakeAvailableModalLabel">اتاحة فترة</h5>

                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">

                                            <div class="form-group">
                                                <label for="min_stay">الحد الأدنى للحجز</label>

                                                <select class="nice-select w-100" id="min_stay" name="min_stay">
                                                    @for($i=1;$i<10;$i++)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="price">سعر الليلة</label>
                                                <input type="number" name="price" id="price" class="form-control" value="{{old('price')}}" />
                                            </div>
                                        </div>

                                        <div class="modal-footer d-block">
                                            <button type="submit" class="btn btn-success" style="width: 100%">اتاحة و تحديد السعر</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form class="form-inline-style" method="POST" action="{{route('availability.close', $unit_id)}}">
                            @csrf

                            <input type="hidden" class="dates-field" id="close-dates" name="dates" />

                            <button class="btn btn-primary" style="width: 100%">اغلاق الفترة</button>
                        </form>

{{--                        <form class="form-inline-style" method="POST" action="{{route('availability.open', $unit_id)}}">--}}
{{--                            @csrf--}}

{{--                            <input type="hidden" class="from-field" id="from-close" name="from" />--}}
{{--                            <input type="hidden" class="to-field" id="to-close" name="to" />--}}

{{--                            <button class="btn btn-primary" style="width: 100%">افتح الفترة المغلقة</button>--}}
{{--                        </form>--}}
                    </div>

                    <div id="weekdays">
                        <div>الأحد</div>
                        <div>الاثنين</div>
                        <div>الثلاثاء</div>
                        <div>الأربعاء</div>
                        <div>الخميس</div>
                        <div>الجمعة</div>
                        <div>السبت</div>
                    </div>

                    <div id='calendar'></div>

                    <ul id="list-of-color">
                        <li>معاني الألوان</li>
                        <li><span class="circle available"></span> متاح للحجز </li>
                        <li><span class="circle waiting"></span> بانتظار دفع العربون </li>
                        <li><span class="circle closed"></span> مغلق </li>
{{--                        <li><span class="circle pending"></span> طور التأكيد ( اثمار ) </li>--}}
                        <li><span class="circle approved"></span> محجوز </li>
                        <li><span class="circle contract"></span> يوجد عقد </li>
                    </ul>
                </div>
            </div>
        </div>

        {{--        <div class="alert alert"--}}
        {{--        <div id="mdp-demo"></div>--}}
    </div>
@endsection
