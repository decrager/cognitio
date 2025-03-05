@if(session()->has('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <div class="d-flex align-items-center"></div>
        <h6 class="text-bold"><i class="icon fa fa-check"></i> Success!</h6>
        <p class="m-0 p-0 text-sm">{{ session('success') }}</p>
    </div>
@endif

@if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
