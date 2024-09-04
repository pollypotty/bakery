@extends("app")

@section("content")

    <registration-page
        :errors="{{ session()->has('errors') ? json_encode(session()->get('errors')->all()) : null }}">
    </registration-page>

@endsection
