<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::query()->latest()->paginate(20);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create', ['coupon' => new Coupon()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateCoupon($request);

        Coupon::query()->create($this->mapPayload($validated));

        return redirect()->route('admin.coupons.index')->with('status', 'Coupon created successfully.');
    }

    public function show(Coupon $coupon)
    {
        return redirect()->route('admin.coupons.edit', $coupon);
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $this->validateCoupon($request, $coupon->id);

        $coupon->update($this->mapPayload($validated));

        return redirect()->route('admin.coupons.index')->with('status', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return back()->with('status', 'Coupon removed.');
    }

    private function validateCoupon(Request $request, ?int $couponId = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:40', 'unique:coupons,code,' . $couponId],
            'type' => ['required', 'in:fixed,percentage'],
            'value' => ['required', 'numeric', 'min:0'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function mapPayload(array $validated): array
    {
        return [
            'code' => Str::upper($validated['code']),
            'type' => $validated['type'],
            'value' => $validated['value'],
            'min_order_amount' => $validated['min_order_amount'] ?? 0,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'starts_at' => $validated['starts_at'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
            'is_active' => ! empty($validated['is_active']),
        ];
    }
}
