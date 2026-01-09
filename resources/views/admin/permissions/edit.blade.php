@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="box box-body box-primary">
            <div class="">
                <h3 class="box-title">{{$page_title}}</h3>
            </div>

            @include('admin.layouts.messages')

            <form action="{{admin_url('permissions/'.$role->id)}}" method="post">
                @csrf
                @method('PUT')

                <div class="form-group col-12">
                    <label for="role_name"> اسم الصلاحية </label>

                    <input type="text" value="{{old('role') ?? $role->main_name}}" id="role_name" class="form-control" name="role" style="width: 100%;" required />
                </div>

                <div class="row" style="margin: 0;padding: 0">
                    <div class="form-group col-12">
                        @foreach($permissions as $key => $permission)
                            <h3>{{$key}}</h3>
                            <br />
                            @foreach($permission as $key => $term)
                                @if(isset($term['name']) && isset($term['value']))
                                    <div class="form-inline">
{{--                                        {{dd($permissionsDB)}}--}}
{{--                                        {{dd(in_array(45, $permissionsDB))}}--}}
                                        <input type="checkbox" id="{{$term['value']}}" name="permission[]" value="{{$term['value']}}" {{old('permission') ? (in_array($term['value'], old('permission')) ? 'checked' : '') : (in_array($term['value'], $permissionsDB) ? 'checked' : '')}} />
                                        <label for="{{$term['value']}}">{{$term['name']}}</label>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                    <a class="btn btn-danger" href="{{admin_url('permissions')}}">إلغاء</a>
                </div>

            </form>
        </div>
    </div>
@endsection
