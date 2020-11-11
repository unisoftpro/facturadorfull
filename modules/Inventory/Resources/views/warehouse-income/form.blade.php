@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-warehouse-income-form :id="{{ json_encode($id) }}" ></tenant-inventory-warehouse-income-form>

@endsection
