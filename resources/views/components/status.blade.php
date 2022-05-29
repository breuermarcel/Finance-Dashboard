@if (session('success'))
    <div class="my-3 alert alert-success alert-dismissible fade show" role="alert">
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="my-3 alert alert-danger alert-dismissible fade show" role="alert">
            <span>{{ $error }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endforeach
@endif

