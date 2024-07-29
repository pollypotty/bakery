@extends("app")

@section("content")

    <cart-page :stripe-key="{{ json_encode($stripeKey) }}"></cart-page>

@endsection
