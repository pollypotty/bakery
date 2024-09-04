@extends("app")

@section("content")

    <login-page
        :errors="{{ session()->has('errors') ? json_encode(session()->get('errors')->all()) : null }}">
    </login-page>

@endsection
