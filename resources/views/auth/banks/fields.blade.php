<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="holder_name_{{$key}}">اسم المستفيد</label>

            <input class="form-control grey @error('holder_name') is-invalid @enderror" value="{{@old('holder_name')[$key] ?? $holder_name}}" id="holder_name_{{$key}}" type="text" name="holder_name[]" required />

            @error('holder_name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="bank_name_{{$key}}">اسم البنك</label>

            <input class="form-control grey @error('bank_name') is-invalid @enderror" value="{{@old('bank_name')[$key] ?? $bank_name}}" id="bank_name_{{$key}}" type="text" name="bank_name[]" required />

            @error('bank_name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="bank_account_{{$key}}">رقم الحساب</label>

            <input class="form-control grey @error('bank_account') is-invalid @enderror" value="{{@old('bank_account')[$key] ?? $bank_account}}" id="bank_account_{{$key}}" type="text" name="bank_account[]" required />

            @error('bank_account')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="iban_{{$key}}">IBAN</label>

            <div class="password-wrapper mt-2">
                <input style="direction: ltr" id="iban_{{$key}}" type="text" class="input" value="{{@old('iban')[$key] ?? $iban}}" name="iban[]" required />

                <div class="icon-wrapper">SA</div>
            </div>

{{--            <input class="form-control grey @error('iban') is-invalid @enderror" id="iban_{{$key}}" type="text" name="iban[]" />--}}

            @error('iban')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="form-group">
            <button type="button" class="add-row btn btn-success"><i class="fa fa-plus"></i></button>

            @if($i > 0)
            <button type="button" class="delete-row btn btn-danger"><i class="fa fa-trash"></i></button>
            @endif
        </div>
    </div>
</div>
