
@if(isset($errors) && count($errors->all()) > 0)
    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            <button class="close" data-close="alert"></button>
            <span> {{ $error }}</span>
        </div>
    @endforeach
@endif

@if(is_array($errors) && count($errors)>0)



    @foreach($errors as $error)

        <div class="alert alert-danger">
            <button class="close" data-close="alert"></button>
            <span> {{ $errors }}</span>
        </div>
    @endforeach
@endif

@if (session('success') && !count($errors))
    <div class="alert alert-success">
        <button class="close" data-close="alert"></button>
        {{ session('success') }}
    </div>
@endif

@if(session()->has('error'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ session('error') }}
    </div>
@endif
{{ session('error_custom')}}
@if(session()->has('error_custom'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        {{ session('error_custom') }}
    </div>
    @php(session()->forget('error_custom'))
@endif