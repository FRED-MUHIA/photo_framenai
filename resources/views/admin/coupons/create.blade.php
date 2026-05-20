@extends('layouts.admin')

@section('heading', 'Create Coupon')

@section('content')
<form method="POST" action="{{ route('admin.coupons.store') }}" class="max-w-3xl space-y-4 rounded-xl bg-white p-6 shadow-sm">
    @csrf
    @include('admin.coupons.partials.form', ['coupon' => $coupon])
    <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Save Coupon</button>
</form>
@endsection
