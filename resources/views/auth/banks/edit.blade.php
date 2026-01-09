@extends('layouts.front-page')

@section('content')
    <div class="container main-container">
        <div class="card">
            <div class="preloader-wrapper">
                <img src="{{asset('images/preloader.svg')}}" alt="loading ..." />
            </div>

            <div class="card-body">
                @include('admin.layouts.messages')

                <h3 class="form-title"><span class="form-ribbon">تعديل الحسابات البنكية</span></h3>

                <form id="contract-form" style="background-color: #fff;padding: 10px;margin-bottom: 25px" method="post" action="{{route('user.banks.update')}}">
                    @csrf
                    @method('PUT')

                    <?php $i = 0 ?>

                    <div class="rows-wrapper">
                        @if($user->banks()->count() == 0)
                            <?php
                            $key = 0;
                            $bank_name = null;
                            $holder_name = null;
                            $bank_account = null;
                            $iban = null;
                            $down_payment = null;
                            ?>

                            @include('auth.banks.fields')
                        @endif

                        @foreach($user->banks as $key => $bank)
                            <?php
                            $holder_name = $bank->holder_name;
                            $bank_name = $bank->bank_name;
                            $bank_account = $bank->bank_account;
                            $iban = $bank->iban;
                            $down_payment = $bank->down_payment;
                            ?>

                            @include('auth.banks.fields')

                            <?php $i++ ?>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label class="form-label" for="down_payment">نسبة العربون</label>

                                <select id="down_payment" class="nice-select mt-2 w-100" name="down_payment">
                                    <option value="25" {{$user->down_payment == '25' ? 'selected' : ''}}>25%</option>
                                    <option value="50" {{$user->down_payment == '50' ? 'selected' : ''}}>50%</option>
                                    <option value="100" {{$user->down_payment == '100' ? 'selected' : ''}}>100%</option>
                                </select>

                                {{--            <input class="form-control grey @error('iban') is-invalid @enderror" value="{{@old('down_payment')[$key] ?? $down_payment}}" id="down_payment_{{$key}}" type="text" name="down_payment[]" />--}}

                                @error('down_payment')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="gb gb-bordered hover-slide next gb9"><i class="arrow_right"></i> <span class="text">حفظ البيانات</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function (){
            $('.preloader-wrapper').remove()
        })

        $('body').on('click', '.add-row', function (){
            const index = Math.floor(Math.random() * 1000);

            let output = '<div class="row">';

            output += '<div class="col-md-3">';
            output += '<div class="form-group">';
            output += '<label class="form-label" for="holder_name_'+index+'">اسم المستفيد</label>';

            output += '<input class="form-control grey" id="holder_name_'+index+'" type="text" name="holder_name[]" required />';

            output += '</div>'
            output += '</div>';

            output += '<div class="col-md-3">';
            output += '<div class="form-group">';
            output += '<label class="form-label" for="bank_name_'+index+'">اسم البنك</label>';
            output += '<input class="form-control grey" id="bank_name_'+index+'" type="text" name="bank_name[]" required />'
            output += '</div>';
            output += '</div>';

            output += '<div class="col-md-3">';
            output += '<div class="form-group">';
            output += '<label class="form-label" for="bank_account_'+index+'">رقم الحساب</label>';
            output += '<input class="form-control grey" id="bank_account_'+index+'" type="text" name="bank_account[]" required />';
            output += '</div>';
            output += '</div>';

            output += '<div class="col-md-3">';
            output += '<div class="form-group">';
            output += '<label class="form-label" for="iban_'+index+'">IBAN</label>';
            output += '<div class="password-wrapper mt-2">';
            output += '<input style="direction: ltr" id="iban_'+index+'" type="text" class="input" name="iban[]" required>';
            output += '<div class="icon-wrapper">SA</div>';
            output += '</div>';
            output += '</div>';
            output += '</div>';

            output += '</div>';

            output += '<div class="col-md-3 d-flex align-items-end">';
            output += '<div class="form-group">';
            output += '<button type="button" class="add-row btn btn-success"><i class="fa fa-plus"></i></button>'

            output += '<button type="button" class="delete-row btn btn-danger"><i class="fa fa-trash"></i></button>';

            output += '</div>';
            output += '</div>';
            output += '</div>';

            $('.rows-wrapper').append(output);
            $('.nice-select').niceSelect('update');
        })

        $('body').on('click', '.delete-row', function (){
            $(this).parents('.row').remove()
        })
    </script>
@endsection
