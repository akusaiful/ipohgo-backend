@extends ('layouts.app')
@section ('content')

<div class="panel">
    <div class="panel-title"><strong>Please don't leave me :(</strong></div>
    <div class="panel-body">


<h1>We regret to see you leave.</h1>
<p>Please enter your email account. We will delete your account immediately.</p>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ url('delete') }}" class="filter-form filter-form-left d-flex flex-column flex-sm-row" method="POST">
    @csrf
    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ @old('email') }}">
    <button type="submit" class="btn-info btn">Submit</button>
</form>

    </div>
</div>


@endsection



