@extends("app")

@section("content")

    <admin-login-page
        :errors="{{ session()->has('errors') ? json_encode(session()->get('errors')->all()) : null }}"
    ></admin-login-page>

@endsection
