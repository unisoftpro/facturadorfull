@extends('tenant.layouts.app')

@section('content')

    <tenant-transport-work-orders-form :id="{{ json_encode($id) }}" :user="{{ json_encode(auth()->user()) }}"></tenant-transport-work-orders-form>

@endsection