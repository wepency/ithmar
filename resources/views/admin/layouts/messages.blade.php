@if($errors->any())
    <div class="alert alert-danger alert-dismissible new2 p-4" role="alert">
        <i class="alert-icon icon_close_alt2" aria-hidden="true"></i>

        <div class="alert-body">
            @foreach($errors->all() as $error)
                <p>{{$error}}</p>
            @endforeach
        </div>

        <button type="button" class="close-button" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session()->has('message'))
    <div class="alert alert-success alert-dismissible new2 p-4" role="alert">
        <i class="alert-icon icon_check_alt" aria-hidden="true"></i>

        <div class="alert-body">{{session()->get('message')}}</div>

        <button type="button" class="close-button" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session()->has('success'))
    <div class="alert alert-success alert-dismissible new2 p-4" role="alert">
        <i class="alert-icon icon_check_alt" aria-hidden="true"></i>

        <div class="alert-body">{{session()->get('success')}}</div>

        <button type="button" class="close-button" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible new2 p-4" role="alert">
        <i class="alert-icon icon_close_alt2" aria-hidden="true"></i>

        <div class="alert-body">
            <p>{{session()->get('error')}}</p>
        </div>

        <button type="button" class="close-button" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
