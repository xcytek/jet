@extends('layout')

@section('content')

    <p>{{ $file_name }}</p>
    <div class="row">
        <div class="col-lg-12">
            {{--<iframe src ="{{ $file_name }}" width="1000px" height="600px"></iframe>--}}

            {{--<object data="{{$file_name}}" type="application/pdf">--}}
                {{--<embed src="{{$file_name}}" type="application/pdf" />--}}
            {{--</object>--}}

            {{ asset('/laraview/#..' . $file_name) }}

        </div>
    </div>

    <div class="links">
        <a href="/dashboard">Regresar</a>
    </div>
@endsection