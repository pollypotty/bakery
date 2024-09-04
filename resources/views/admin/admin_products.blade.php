@extends("app")

@section("content")

    <admin-products-page :products='{{ $products }}'></admin-products-page>

@endsection
