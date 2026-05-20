<div class="grid gap-4 sm:grid-cols-2">
    <input type="text" name="code" value="{{ old('code', $coupon->code) }}" placeholder="Code" class="rounded-xl border-slate-300" required>
    <select name="type" class="rounded-xl border-slate-300" required>
        <option value="fixed" @selected(old('type', $coupon->type) === 'fixed')>Fixed</option>
        <option value="percentage" @selected(old('type', $coupon->type) === 'percentage')>Percentage</option>
    </select>
    <input type="number" step="0.01" name="value" value="{{ old('value', $coupon->value) }}" placeholder="Value" class="rounded-xl border-slate-300" required>
    <input type="number" step="0.01" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" placeholder="Min Order Amount" class="rounded-xl border-slate-300">
    <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" placeholder="Usage Limit" class="rounded-xl border-slate-300">
    <input type="datetime-local" name="starts_at" value="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d\TH:i')) }}" class="rounded-xl border-slate-300">
    <input type="datetime-local" name="expires_at" value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d\TH:i')) }}" class="rounded-xl border-slate-300">
</div>
<label class="inline-flex items-center gap-2 text-sm"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active ?? true))> Active</label>
