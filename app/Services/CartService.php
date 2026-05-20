<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const CART_KEY = 'print_cart';
    private const COUPON_KEY = 'print_coupon';

    public function all(): array
    {
        return array_values(Session::get(self::CART_KEY, []));
    }

    public function add(array $item): void
    {
        $cart = $this->all();
        $cart[] = $item;

        Session::put(self::CART_KEY, $cart);
    }

    public function update(int $index, array $attributes): void
    {
        $cart = $this->all();

        if (! isset($cart[$index])) {
            return;
        }

        $cart[$index] = array_merge($cart[$index], $attributes);

        Session::put(self::CART_KEY, $cart);
    }

    public function remove(int $index): void
    {
        $cart = $this->all();

        if (! isset($cart[$index])) {
            return;
        }

        unset($cart[$index]);

        Session::put(self::CART_KEY, array_values($cart));
    }

    public function clear(): void
    {
        Session::forget(self::CART_KEY);
        Session::forget(self::COUPON_KEY);
    }

    public function applyCoupon(?string $code): ?Coupon
    {
        if (! $code) {
            Session::forget(self::COUPON_KEY);

            return null;
        }

        $coupon = Coupon::query()->whereRaw('upper(code) = ?', [strtoupper($code)])->first();

        if (! $coupon) {
            Session::forget(self::COUPON_KEY);

            return null;
        }

        Session::put(self::COUPON_KEY, strtoupper($coupon->code));

        return $coupon;
    }

    public function currentCoupon(): ?Coupon
    {
        $code = Session::get(self::COUPON_KEY);

        if (! $code) {
            return null;
        }

        return Coupon::query()->where('code', $code)->first();
    }

    public function totals(string $shippingOption = 'standard'): array
    {
        $items = $this->all();
        $subtotal = round(collect($items)->sum('line_total'), 2);
        $shippingAmount = (float) data_get(config('shop.shipping_options'), $shippingOption . '.price', 0);

        $coupon = $this->currentCoupon();
        $discountAmount = 0;

        if ($coupon && $coupon->isCurrentlyValid($subtotal)) {
            $discountAmount = $coupon->calculateDiscount($subtotal);
        }

        $total = max(0, round($subtotal + $shippingAmount - $discountAmount, 2));

        return [
            'subtotal' => $subtotal,
            'shipping_amount' => $shippingAmount,
            'discount_amount' => $discountAmount,
            'total' => $total,
            'coupon' => $coupon,
            'currency' => config('shop.currency', 'KES'),
            'currency_symbol' => config('shop.currency_symbol', 'KSh'),
        ];
    }
}
