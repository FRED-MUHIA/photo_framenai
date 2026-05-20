@extends('layouts.admin')

@section('heading', 'Edit Coupon')

@section('content')
<form method="POST" action="{{ route('admin.coupons.update', $coupon) }}" class="max-w-3xl space-y-4 rounded-xl bg-white p-6 shadow-sm">
    @csrf
    @method('PUT')
    @include('admin.coupons.partials.form', ['coupon' => $coupon])
    <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Update Coupon</button>
</form>
@endsection
