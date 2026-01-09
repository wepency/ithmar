@if(session()->has('new-login'))
    <div class="alert alert-success alert-dismissible new2 p-4" role="alert">
        <i class="alert-icon icon_pushpin_alt" aria-hidden="true"></i>

        <div class="alert-body">
            <h6 class="alert-header">أهلا بك {{auth()->user()->name}}</h6>
            <p class="mb-0">{{session()->get('new-login')}}</p>
        </div>

        <button type="button" class="close-button" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
