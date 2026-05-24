@php
    $initialGroups = json_decode($optionGroupsJson ?: '[]', true) ?: [];
    $initialQuantities = json_decode($quantityPricesJson ?: '[]', true) ?: [];
    $settings = old('settings', $template->settings ?: []);
@endphp

<div
    x-data="productFormBuilder(@js($initialGroups), @js($initialQuantities))"
    class="space-y-5"
>
    <div class="grid gap-4 sm:grid-cols-2">
        <input type="text" name="name" value="{{ old('name', $template->name) }}" placeholder="Form name e.g. Rollup Banner Calculator" class="rounded-xl border-slate-300" required>
        <input type="text" name="slug" value="{{ old('slug', $template->slug) }}" placeholder="Slug (optional)" class="rounded-xl border-slate-300">
        <textarea name="description" placeholder="Internal description" class="rounded-xl border-slate-300 sm:col-span-2">{{ old('description', $template->description) }}</textarea>
    </div>

    <div class="flex flex-wrap gap-2 border-b border-slate-200 pb-3">
        <button type="button" @click="tab = 'options'" :class="tab === 'options' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700'" class="rounded-lg px-3 py-2 text-xs font-bold">Options</button>
        <button type="button" @click="tab = 'pricing'" :class="tab === 'pricing' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700'" class="rounded-lg px-3 py-2 text-xs font-bold">Quantity Prices</button>
        <button type="button" @click="tab = 'upload'" :class="tab === 'upload' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700'" class="rounded-lg px-3 py-2 text-xs font-bold">Upload</button>
    </div>

    <input type="hidden" name="option_groups_json" :value="JSON.stringify(groups)">
    <input type="hidden" name="quantity_prices_json" :value="JSON.stringify(quantities)">

    <section x-show="tab === 'options'" class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-extrabold">Form Fields / Option Tabs</h2>
            <button type="button" @click="addGroup" class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-bold text-white">Add Field</button>
        </div>

        <template x-for="(group, groupIndex) in groups" :key="groupIndex">
            <div class="rounded-xl border border-slate-200 p-4">
                <div class="grid gap-3 sm:grid-cols-4">
                    <input type="text" x-model="group.name" placeholder="Field name e.g. Print Option" class="rounded-lg border-slate-300 text-sm">
                    <input type="text" x-model="group.code" placeholder="Code e.g. print_option" class="rounded-lg border-slate-300 text-sm">
                    <select x-model="group.input_type" class="rounded-lg border-slate-300 text-sm">
                        <option value="buttons">Button tabs</option>
                        <option value="image_tabs">Image tabs</option>
                        <option value="select">Dropdown</option>
                    </select>
                    <label class="inline-flex items-center gap-2 text-sm font-semibold">
                        <input type="checkbox" x-model="group.is_required">
                        Required
                    </label>
                </div>

                <div class="mt-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-500">Options, prices and image tabs</p>
                        <button type="button" @click="addValue(group)" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-bold">Add Option</button>
                    </div>
                    <template x-for="(value, valueIndex) in group.values" :key="valueIndex">
                        <div class="grid gap-2 rounded-lg bg-slate-50 p-3 sm:grid-cols-7">
                            <input type="text" x-model="value.label" placeholder="Label" class="rounded border-slate-300 text-xs sm:col-span-2">
                            <input type="text" x-model="value.value" placeholder="Value" class="rounded border-slate-300 text-xs">
                            <input type="number" step="0.01" x-model.number="value.price_modifier" placeholder="Extra price" class="rounded border-slate-300 text-xs">
                            <input type="number" x-model.number="value.delivery_modifier" placeholder="Extra days" class="rounded border-slate-300 text-xs">
                            <input type="text" x-model="value.image_url" placeholder="Image URL" class="rounded border-slate-300 text-xs">
                            <div class="flex items-center justify-end gap-2">
                                <label class="text-xs font-semibold"><input type="checkbox" x-model="value.is_default"> Default</label>
                                <button type="button" @click="group.values.splice(valueIndex, 1)" class="rounded border border-rose-200 px-2 py-1 text-xs font-bold text-rose-700">Remove</button>
                            </div>
                        </div>
                    </template>
                </div>

                <button type="button" @click="groups.splice(groupIndex, 1)" class="mt-3 rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-bold text-rose-700">Remove Field</button>
            </div>
        </template>
    </section>

    <section x-show="tab === 'pricing'" class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-extrabold">Quantity Price Tiers</h2>
            <button type="button" @click="addQuantity" class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-bold text-white">Add Tier</button>
        </div>

        <div class="space-y-2">
            <template x-for="(row, rowIndex) in quantities" :key="rowIndex">
                <div class="grid gap-2 rounded-lg bg-slate-50 p-3 sm:grid-cols-6">
                    <input type="number" x-model.number="row.quantity" placeholder="Quantity" class="rounded border-slate-300 text-sm">
                    <input type="number" step="0.01" x-model.number="row.price" placeholder="Total price" class="rounded border-slate-300 text-sm">
                    <input type="number" step="0.01" x-model.number="row.discount_percentage" placeholder="Discount %" class="rounded border-slate-300 text-sm">
                    <input type="number" x-model.number="row.delivery_days" placeholder="Delivery days" class="rounded border-slate-300 text-sm">
                    <label class="inline-flex items-center gap-2 text-sm font-semibold"><input type="checkbox" x-model="row.is_active"> Active</label>
                    <button type="button" @click="quantities.splice(rowIndex, 1)" class="rounded border border-rose-200 px-3 py-1.5 text-xs font-bold text-rose-700">Remove</button>
                </div>
            </template>
        </div>
    </section>

    <section x-show="tab === 'upload'" class="grid gap-4 sm:grid-cols-2">
        <label class="inline-flex items-center gap-2 text-sm font-semibold sm:col-span-2">
            <input type="hidden" name="settings[upload_enabled]" value="0">
            <input type="checkbox" name="settings[upload_enabled]" value="1" @checked((bool) data_get($settings, 'upload_enabled', true))>
            Show artwork upload block on product page
        </label>
        <input type="text" name="settings[upload_label]" value="{{ old('settings.upload_label', data_get($settings, 'upload_label', 'Upload Design (Optional)')) }}" placeholder="Upload block label" class="rounded-xl border-slate-300">
        <input type="text" name="settings[upload_help]" value="{{ old('settings.upload_help', data_get($settings, 'upload_help', 'PDF, AI, PSD, JPG, PNG (max 50MB)')) }}" placeholder="Upload help text" class="rounded-xl border-slate-300">
    </section>

    <div class="flex gap-4">
        <label class="inline-flex items-center gap-2 text-sm">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $template->is_active ?? true))>
            Active
        </label>
    </div>
</div>

@push('scripts')
<script>
    function productFormBuilder(initialGroups, initialQuantities) {
        return {
            tab: 'options',
            groups: initialGroups.length ? initialGroups : [],
            quantities: initialQuantities.length ? initialQuantities : [],
            addGroup() {
                this.groups.push({
                    name: '',
                    code: '',
                    input_type: 'buttons',
                    is_required: true,
                    values: [],
                });
            },
            addValue(group) {
                if (!Array.isArray(group.values)) {
                    group.values = [];
                }
                group.values.push({
                    label: '',
                    value: '',
                    price_modifier: 0,
                    delivery_modifier: 0,
                    is_default: false,
                    image_url: '',
                });
            },
            addQuantity() {
                this.quantities.push({
                    quantity: 100,
                    price: 0,
                    discount_percentage: 0,
                    delivery_days: 3,
                    is_active: true,
                });
            },
        };
    }
</script>
@endpush
