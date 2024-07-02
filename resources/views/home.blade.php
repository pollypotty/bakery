@extends("app")

@section("content")

    <home-page :authenticated='{{ Auth::check() }}'></home-page>

@endsection
