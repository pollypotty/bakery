@extends("app")

@section("content")

    <profile-page :authenticated="{{ Auth::check() }}"></profile-page>

@endsection
