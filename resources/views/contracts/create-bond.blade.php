<div class="modal fade" id="create-bond" tabindex="-1" role="dialog" aria-labelledby="bond" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{url('addBond')}}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">أضف سند جديد</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="icon_close"></i></span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="contract-code-field">أختر العقد</label>

                        <div class="form-control-container">
                            <select id="contract-code-field" name="code" required>
                                <option>اختر العقد</option>

                                @foreach($contracts as $contract)
                                    <option value="{{$contract->code}}">{{$contract->code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bond_name">اسم المستأجر</label>

                        <div class="form-control-container">
                            <input type="text" class="form-control grey @error('bond_name') has-error @enderror" value="{{old('bond_name')}}" id="bond_name" name="bond_name" disabled />
                            <i></i>
                        </div>

                        <div class="text-danger @error('bond_name') active @enderror">
                            @error('bond_name')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bond_value">المبلغ (ريال سعودي)</label>

                        <div class="form-control-container">
                            <input type="text" class="form-control grey @error('bond_value') has-error @enderror" value="{{old('bond_value')}}" id="bond_value" name="bond_value" required />
                            <i></i>
                        </div>

                        <div class="text-danger @error('tenant_nationality') active @enderror">
                            @error('tenant_nationality')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bond_for">السند لأجل</label>

                        <div class="form-control-container">
                            <input type="text" class="form-control grey @error('bond_for') has-error @enderror" value="{{old('bond_for')}}" id="bond_for" name="bond_for" disabled />
                            <i></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bond_note">ملاحظة</label>

                        <div class="form-control-container">
                            <textarea class="form-control grey @error('bond_note') has-error @enderror" id="bond_note" name="bond_note" style="resize: none">{{old('bond_note')}}</textarea>
                            <i></i>
                        </div>

                        <div class="text-danger @error('bond_note') active @enderror">
                            @error('bond_note')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="gb gb-bordered hover-slide next gb9"><i class="icon_check"></i> <span class="text"> حفظ السند </span> <span class="loader"></span></button>
                    <button type="button" class="btn normal-button btn-danger" data-dismiss="modal"><i class="icon_close"></i> <span class="text"> إلغاء </span> <span class="loader"></span></button>
                </div>
            </form>
        </div>
    </div>
</div>
