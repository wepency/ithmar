@extends('layouts.front-page')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/contract.css')}}" />
    <link rel="stylesheet" href="{{asset('css/gallery.css')}}" />

    <style>
        .alert{
            display: none
        }
        .gb{
            min-height: 47px;
        }
    </style>
@endsection

@section('content')
    <div class="container home-container">
        <div class="card">
            <div class="card-body">

                <div class="progress-outer mt-4 mb-4">
                    <div class="progress-bar--ribbon">
                        <span class="step-header">رفع باقي المبلغ</span>
                    </div>
                </div>

                <div class="alert alert-success new2 p-4" id="success-message" role="alert">
                    <i class="alert-icon icon_check_alt" aria-hidden="true"></i>

                    <div class="alert-body">تم ارسال صورة التحويل بنجاح ، بانتظار مراجعتها.</div>
                </div>


                <div class="alert alert-danger new2 p-4" id="error-message" role="alert">
                    <i class="alert-icon icon_close_alt2" aria-hidden="true"></i>

                    <div class="alert-body">برجاء التأكد من الصوره المرسله.</div>
                </div>

                <form method="post" id="form-upload" action="{{route('upload.post.invoice', $reservation_id)}}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="front-image" class="form-label">رفع صورة الحوالة بباقي المبلغ*</label>

                        <div class="images-wrapper">
                            <div class="main-image-upload">
                                <label class="" for="front-image">
                                    <i class="fa fa-plus"></i>
                                    <img src="" alt="" />
                                </label>

                                <input class="single-image-field" id="front-image" type="file" accept="image/*" name="file" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" id="form-submit" class="gb gb-bordered hover-slide next gb9"><i class="arrow_right"></i> <span class="text"> تحديث الحجز </span> <span class="loader"></span></button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/gallery.js')}}"></script>

    <script>
        $('#form-upload').on('submit', function (e){
            e.preventDefault();

            const FormSubmit = $('#form-submit');
            const $this = $(this);

            let formData = new FormData(this);

            FormSubmit.find('.text').hide();
            FormSubmit.find('.arrow_right').hide();
            FormSubmit.find('.loader').show()

            formData.append('reservation', '{{deep_decode($reservation_id)}}')

            $.ajax({
                type: 'POST',
                url: '{{env('RESERVATION_APP_URL').'/api/upload-via-api'}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: (data) => {
                    $this.remove()
                    $('#success-message').show()
                    $('#error-message').hide();
                },
                error: function(data){
                    $('#error-message').show();
                    $('#success-message').hide()

                    FormSubmit.find('.text').show();
                    FormSubmit.find('.arrow_right').show();
                    FormSubmit.find('.loader').hide()
                }
            });
        })
    </script>
@endsection
