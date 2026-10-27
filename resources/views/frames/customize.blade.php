@extends('layouts.storefront')

@section('meta_title', 'Customize Your Picture Frame | ' . config('app.name'))
@section('meta_description', 'Choose an uploaded frame design, add your photo, and preview a custom picture frame before ordering.')

@push('head')
<style>
    .frame-customizer{background:#171717;color:#f5f2ed;padding:clamp(18px,3vw,38px) 0 52px;min-height:72vh}
    .frame-customizer *{box-sizing:border-box}.fc-wrap{width:min(1500px,calc(100% - 36px));margin:auto}
    .fc-head{display:flex;justify-content:space-between;align-items:end;gap:20px;margin-bottom:22px}.fc-head h1{font:500 clamp(30px,4vw,46px)/1.05 'Cormorant Garamond',Georgia,serif!important;color:#fff!important;letter-spacing:-.02em}.fc-head p{margin-top:8px;color:#a9a39a;font-size:13px}.fc-layout{display:grid;grid-template-columns:230px minmax(0,1fr);gap:22px;align-items:start}
    .fc-gallery,.fc-stage,.fc-controlbar,.fc-summary{border:1px solid #353535;background:#202020}.fc-gallery{padding:14px;max-height:780px;overflow:auto}.fc-gallery h2,.fc-panel-title{font:600 12px/1.3 Inter,sans-serif!important;color:#f6f3ee!important;letter-spacing:.1em;text-transform:uppercase}.fc-gallery-list{display:grid;gap:10px;margin-top:14px}.fc-frame-choice{width:100%;padding:8px;border:1px solid #3c3c3c;background:#292929;color:#f5f2ed;text-align:left;cursor:pointer}.fc-frame-choice.is-selected{border:2px solid #e31b2b;padding:7px}.fc-frame-choice img,.fc-frame-empty{width:100%;aspect-ratio:1.2;object-fit:cover;background:#343434;display:grid;place-items:center}.fc-frame-choice strong{display:block;margin-top:8px;font-size:12px}.fc-frame-choice small{display:block;color:#a9a39a;font-size:11px;margin-top:3px}.fc-main{min-width:0}.fc-controlbar{display:flex;align-items:center;flex-wrap:wrap;gap:15px;padding:12px;margin-bottom:14px}.fc-controlgroup{display:flex;align-items:center;gap:7px}.fc-controlgroup label{display:block;color:#aaa39a;font-size:10px;text-transform:uppercase;letter-spacing:.06em}.fc-controlgroup input[type=range]{width:115px;accent-color:#e31b2b}.fc-controlgroup input[type=number],.fc-controlgroup select{width:82px;height:34px;padding:5px 7px;border:1px solid #4a4a4a;background:#292929;color:#fff}.fc-controlgroup select{width:130px}.fc-tool{min-width:34px;height:34px;padding:0 9px;border:1px solid #484848;background:#2b2b2b;color:#fff;cursor:pointer}.fc-tool:hover{border-color:#e31b2b;color:#fff}.fc-primary{height:40px;padding:0 18px;border:0;background:#d71928;color:#fff;font-weight:700;cursor:pointer}.fc-primary:hover{background:#b81220}.fc-stage{position:relative;display:grid;min-height:480px;place-items:center;padding:clamp(16px,4vw,42px);overflow:hidden;background:radial-gradient(ellipse at center,#333 0%,#1e1e1e 72%)}
    .fc-canvas-frame{width:min(100%,900px);max-height:650px;display:grid;place-items:center}.fc-canvas-frame canvas{display:block;width:100%;max-height:650px;object-fit:contain;touch-action:none;cursor:grab}.fc-canvas-frame canvas:active{cursor:grabbing}.fc-drop-hint{position:absolute;inset:auto 20px 18px;text-align:center;color:#c9c3b9;font-size:11px;pointer-events:none}.fc-upload-prompt{position:absolute;z-index:2;inset:0;display:grid;place-items:center;padding:24px;background:rgb(15 15 15 / .4)}.fc-upload-card{width:min(410px,100%);padding:24px;border:1px dashed #8b867e;background:#242424;text-align:center}.fc-upload-card strong{display:block;margin-bottom:7px}.fc-upload-card p{font-size:12px;color:#bdb6ac}.fc-upload-card button{margin-top:14px;padding:10px 16px;background:#e31b2b;color:white;border:0;font-weight:700;cursor:pointer}.fc-stage.has-photo .fc-upload-prompt{display:none}
    .fc-below{display:grid;grid-template-columns:minmax(0,1fr) 300px;gap:14px;margin-top:14px}.fc-panel{padding:16px;border:1px solid #353535;background:#202020}.fc-panel-title{margin-bottom:12px}.fc-photo-controls{display:flex;flex-wrap:wrap;gap:10px 16px;align-items:center}.fc-photo-controls input[type=range]{accent-color:#e31b2b;width:140px}.fc-photo-controls label{font-size:11px;color:#c9c3b9}.fc-photo-controls output{display:inline-block;min-width:60px;color:#ded9d2;font-size:11px}.fc-photo-controls select,.fc-size-row input,.fc-size-row select,.fc-quantity{height:36px;border:1px solid #494949;background:#292929;color:#fff;padding:6px 9px}.fc-size-row{display:grid;grid-template-columns:1fr 95px 95px 80px;gap:8px}.fc-size-row select{width:100%}.fc-size-row input{min-width:0;width:100%}.fc-mats{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px}.fc-mat{padding:7px 10px;border:1px solid #4a4a4a;background:#292929;color:#eee;font-size:11px;cursor:pointer}.fc-mat.is-selected{border-color:#e31b2b}.fc-accessories{display:grid;gap:8px}.fc-accessories label{display:flex;justify-content:space-between;gap:10px;color:#ded9d2;font-size:12px}.fc-summary{padding:16px}.fc-summary h2{font:500 22px/1.1 'Cormorant Garamond',Georgia,serif!important;color:#fff!important}.fc-summary p{margin-top:6px;color:#aaa39a;font-size:12px}.fc-price{margin:16px 0 12px;font-size:23px;font-weight:700;color:#fff}.fc-breakdown{display:grid;gap:7px;padding:12px 0;border-top:1px solid #3b3b3b;border-bottom:1px solid #3b3b3b;color:#c7c1b8;font-size:11px}.fc-breakdown div{display:flex;justify-content:space-between;gap:8px}.fc-summary .fc-primary{width:100%;margin-top:14px}.fc-summary small{display:block;color:#999188;font-size:10px;line-height:1.5;margin-top:9px}.fc-error{margin:0 auto 14px;width:min(1500px,calc(100% - 36px));padding:12px 16px;background:#4a171b;color:#ffe2e4}
    @media(max-width:1000px){.fc-layout{grid-template-columns:190px minmax(0,1fr)}.fc-controlbar{gap:10px}.fc-controlgroup input[type=range]{width:90px}.fc-below{grid-template-columns:1fr}.fc-summary{display:grid;grid-template-columns:1fr 1fr;align-items:center;gap:4px 20px}.fc-summary h2,.fc-summary>p,.fc-price,.fc-breakdown,.fc-summary .fc-primary,.fc-summary small{grid-column:1/-1}.fc-summary .fc-primary{margin-top:5px}}
    @media(max-width:650px){.fc-wrap{width:calc(100% - 24px)}.fc-head{display:block}.fc-layout{display:flex;flex-direction:column}.fc-gallery{width:100%;max-height:none;overflow:visible}.fc-gallery-list{display:flex;overflow-x:auto;padding-bottom:6px}.fc-frame-choice{flex:0 0 128px}.fc-frame-choice img,.fc-frame-empty{aspect-ratio:1.15}.fc-main{width:100%}.fc-controlbar{display:grid;grid-template-columns:1fr 1fr}.fc-controlgroup{justify-content:space-between}.fc-controlgroup input[type=range]{width:95px}.fc-stage{min-height:340px;padding:15px}.fc-below{display:flex;flex-direction:column}.fc-panel{padding:13px}.fc-size-row{grid-template-columns:1fr 1fr}.fc-summary{display:block}.fc-summary .fc-primary{margin-top:14px}.fc-error{width:calc(100% - 24px)}}
    .fc-empty{display:grid;grid-template-columns:minmax(200px,340px) minmax(0,1fr);min-height:300px;border:1px solid #3b3b3b;background:#202020}
    .fc-empty-image{min-height:300px;background:#292929;overflow:hidden}.fc-empty-image img{display:block;width:100%;height:100%;min-height:300px;object-fit:cover}
    .fc-empty-copy{align-self:center;padding:clamp(24px,5vw,56px)}.fc-empty-copy .fc-panel-title{color:#e31b2b!important;margin-bottom:12px}
    .fc-empty-copy h2{font:600 clamp(24px,3vw,34px)/1.1 Inter,Arial,sans-serif!important;color:#fff!important;margin:0 0 12px}
    .fc-empty-copy p{max-width:620px;color:#c9c3b9;font-size:14px;line-height:1.7}.fc-empty-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:22px}
    .fc-empty-link{display:inline-flex;align-items:center;justify-content:center;min-height:42px;padding:0 18px;border:1px solid #777;background:transparent;color:#fff;text-decoration:none;font-weight:700}
    .fc-empty-link.primary{border-color:#d71928;background:#d71928}.fc-empty-link:hover{border-color:#e31b2b;background:#b81220;color:#fff}
    @media(max-width:650px){.fc-empty{grid-template-columns:1fr}.fc-empty-image,.fc-empty-image img{height:190px;min-height:190px}.fc-empty-copy{padding:24px}.fc-empty-actions{display:grid}.fc-empty-link{width:100%}}
    .fc-frame-stock{display:block;color:#aaa39a;font-size:11px;margin-top:5px}.fc-frame-stock.unavailable,.fc-stock-status.unavailable{color:#ff9aa2}.fc-stock-status{margin-top:8px;color:#c8c1b7;font-size:12px}.fc-primary:disabled{background:#555;color:#ddd;cursor:not-allowed}
    /* Compact the workspace so the preview and its key controls need less scrolling. */
    .frame-customizer{padding:14px 0 28px;min-height:0}.fc-wrap{width:min(1600px,calc(100% - 28px))}
    .fc-head{align-items:center;margin-bottom:12px;gap:12px}.fc-head h1{font-size:clamp(27px,3vw,36px)!important}.fc-head p{margin-top:4px}
    .fc-layout{grid-template-columns:190px minmax(0,1fr);gap:14px}.fc-gallery{padding:10px;max-height:calc(100vh - 190px)}
    .frame-customizer .fc-gallery h2{font:600 12px/1.3 Inter,Arial,sans-serif!important;white-space:nowrap;letter-spacing:.08em}
    .fc-gallery-list{gap:8px;margin-top:10px}.fc-frame-choice{padding:6px}.fc-frame-choice.is-selected{padding:5px}.fc-frame-choice img,.fc-frame-empty{aspect-ratio:1.45}.fc-frame-choice strong{margin-top:6px}
    .fc-controlbar{position:sticky;top:0;z-index:3;gap:10px;padding:8px;margin-bottom:10px}.fc-controlgroup{gap:5px}.fc-controlgroup label{font-size:9px}.fc-controlgroup input[type=range]{width:88px}
    .fc-stage{height:min(52vh,470px);min-height:300px;padding:12px}.fc-canvas-frame{width:min(100%,760px);max-height:min(50vh,450px)}.fc-canvas-frame canvas{max-height:min(50vh,450px)}
    .fc-below{grid-template-columns:minmax(0,1fr) 260px;gap:10px;margin-top:10px}.fc-edit-controls{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}.fc-edit-controls .fc-panel{padding:10px}.fc-edit-controls .fc-panel-title{margin-bottom:8px}.fc-photo-controls{gap:7px 10px}.fc-photo-controls input[type=range]{width:100px}.fc-summary{padding:12px}.fc-summary h2{font-size:20px}
    @media(max-width:1000px){.fc-layout{grid-template-columns:160px minmax(0,1fr);gap:10px}.fc-below{grid-template-columns:1fr}.fc-summary{display:block}.fc-summary .fc-primary{margin-top:10px}}
    @media(max-width:650px){.fc-wrap{width:calc(100% - 20px)}.fc-head{margin-bottom:10px}.fc-layout{display:flex;flex-direction:column}.fc-gallery{max-height:none;width:100%;padding:9px}.fc-gallery-list{display:flex;overflow-x:auto;padding-bottom:5px}.fc-frame-choice{flex:0 0 120px}.fc-controlbar{position:static;display:grid;grid-template-columns:1fr 1fr}.fc-stage{height:min(48vh,360px);min-height:260px;padding:8px}.fc-canvas-frame,.fc-canvas-frame canvas{max-height:min(45vh,340px)}.fc-edit-controls{grid-template-columns:1fr}.fc-below{display:flex;flex-direction:column}.fc-summary{display:block}}
    .frame-customizer .fc-workspace{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:14px;align-items:start}
    .frame-customizer .fc-stage{height:min(64vh,560px)!important;min-height:380px!important;padding:16px!important}
    .frame-customizer .fc-canvas-frame{width:min(100%,940px)!important;max-height:min(62vh,540px)!important}
    .frame-customizer .fc-canvas-frame canvas{width:100%!important;height:auto!important;max-height:min(62vh,540px)!important}
    .frame-customizer .fc-side-editor{min-width:0;max-height:calc(100vh - 245px);overflow-y:auto;overscroll-behavior:contain;position:sticky;top:10px}
    .frame-customizer .fc-side-content{display:grid;gap:8px}
    .frame-customizer .fc-edit-controls{display:grid;grid-template-columns:minmax(0,1fr);gap:8px}
    .frame-customizer .fc-edit-controls .fc-panel{min-width:0;padding:12px}
    .frame-customizer .fc-edit-controls .fc-panel-title{font:600 13px/1.3 Inter,Arial,sans-serif!important;letter-spacing:.06em;margin-bottom:9px}
    .frame-customizer .fc-tab-panel h2.fc-panel-title{font-family:Inter,Arial,sans-serif!important;font-size:12px!important;font-weight:700!important;line-height:1.3!important;letter-spacing:.06em!important;text-transform:uppercase!important;margin:0 0 8px!important}
    .frame-customizer .fc-gallery-heading{display:block;font:700 12px/1.3 Inter,Arial,sans-serif!important;color:#f6f3ee!important;letter-spacing:.06em!important;text-transform:uppercase!important;white-space:nowrap!important;margin:0!important;padding:0!important}
    .frame-customizer .fc-tabs{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:4px;margin-bottom:8px}
    .frame-customizer .fc-tab{min-height:36px;padding:6px 4px;border:1px solid #494949;background:#282828;color:#eee;font:600 11px/1.2 Inter,Arial,sans-serif;cursor:pointer}
    .frame-customizer .fc-tab.is-active{border-color:#d71928;background:#d71928;color:#fff}
    .frame-customizer .fc-tab:focus-visible{outline:2px solid #fff;outline-offset:2px}
    .frame-customizer .fc-tab-panel[hidden]{display:none!important}
    .frame-customizer .fc-order-shortcut{margin-left:auto;min-height:34px;padding:0 14px;border:0;background:#d71928;color:#fff;font:700 12px/1 Inter,Arial,sans-serif;cursor:pointer}
    .frame-customizer .fc-order-shortcut:hover,.frame-customizer .fc-order-action:hover{background:#b81220}
    .frame-customizer .fc-order-actions{display:grid;gap:8px;margin-top:12px}
    .frame-customizer .fc-saved-count{margin-top:9px;color:#c9c3b9;font-size:11px;line-height:1.5}
    .frame-customizer .fc-saved-count a{color:#ff6b76;text-decoration:underline}
    .frame-customizer .fc-order-action{min-height:40px;padding:9px 12px;border:0;background:#d71928;color:#fff;font:700 12px/1.25 Inter,Arial,sans-serif;cursor:pointer}
    .frame-customizer .fc-order-action:disabled{background:#555;color:#ddd;cursor:not-allowed}
    .frame-customizer .fc-order-action.secondary{border:1px solid #666;background:transparent;color:#f5f2ed}
    .frame-customizer .fc-order-action.secondary:hover{border-color:#d71928;background:#b81220;color:#fff}
    .frame-customizer .fc-photo-controls{gap:8px 10px}.frame-customizer .fc-photo-controls input[type=range]{width:110px}
    .frame-customizer .fc-size-row{grid-template-columns:minmax(0,1fr) 76px 76px 58px;gap:6px}
    .frame-customizer .fc-summary{padding:12px}.frame-customizer .fc-summary h2{font:600 18px/1.2 Inter,Arial,sans-serif!important}
    @media(max-width:1100px){.frame-customizer .fc-workspace{grid-template-columns:minmax(0,1fr) 300px;gap:10px}.frame-customizer .fc-side-editor{max-height:calc(100vh - 220px)}.frame-customizer .fc-size-row{grid-template-columns:minmax(0,1fr) 60px 60px 50px}}
    @media(max-width:800px){.frame-customizer .fc-workspace{grid-template-columns:1fr}.frame-customizer .fc-side-editor{position:static;max-height:none;overflow:visible}.frame-customizer .fc-stage{height:min(58vh,500px)!important;min-height:320px!important}.frame-customizer .fc-canvas-frame,.frame-customizer .fc-canvas-frame canvas{max-height:min(56vh,480px)!important}}
    @media(max-width:650px){.frame-customizer .fc-stage{height:min(48vh,370px)!important;min-height:260px!important;padding:8px!important}.frame-customizer .fc-canvas-frame,.frame-customizer .fc-canvas-frame canvas{max-height:min(45vh,350px)!important}.frame-customizer .fc-side-content{gap:8px}.frame-customizer .fc-size-row{grid-template-columns:minmax(0,1fr) 1fr 1fr 58px}}
    .frame-customizer #fc-size-select,.frame-customizer #fc-unit{background-color:#fff!important;color:#171717!important;border-color:#c9c9c9!important;color-scheme:light}
    .frame-customizer #fc-size-select option,.frame-customizer #fc-unit option{background:#fff;color:#171717}
    @media(max-width:650px){.frame-customizer .fc-size-row{grid-template-columns:minmax(0,1fr) minmax(0,1fr) 58px}.frame-customizer #fc-size-select{grid-column:1/-1}}
</style>
@endpush

@section('content')
@if($errors->any())<div class="fc-error">{{ $errors->first() }}</div>@endif
<div class="frame-customizer" id="frame-customizer">
<div class="fc-wrap">
    <header class="fc-head"><div><h1>Customize Your Picture Frame</h1><p>Choose a frame, add your photo, and make it yours.</p></div>@if($frames->isNotEmpty())<span id="fc-selected-name" class="text-sm text-slate-300">{{ $frames->first()->name }}</span>@endif</header>
    @if($frames->isEmpty())
        <section class="fc-empty" aria-labelledby="fc-empty-title">
            <div class="fc-empty-image"><img src="{{ asset('images/frames/color-black.webp') }}" alt="Close-up of a black picture frame"></div>
            <div class="fc-empty-copy">
                <div class="fc-panel-title">Frame catalogue</div>
                <h2 id="fc-empty-title">Frame designs are being added</h2>
                <p>You can customize and order as a guest—no account is required. We are preparing the first frame designs for the gallery. Once they are available, you will be able to upload a photo, adjust its crop, choose a size and mat, review the live price, and add your design to the cart.</p>
                <div class="fc-empty-actions">
                    <a class="fc-empty-link primary" href="{{ route('products.index') }}">Browse available products</a>
                    <a class="fc-empty-link" href="{{ route('pages.contact') }}">Ask about custom framing</a>
                </div>
            </div>
        </section>
    @else
    <div class="fc-layout">
        <aside class="fc-gallery"><div class="fc-gallery-heading" role="heading" aria-level="2">Choose a frame</div><div class="fc-gallery-list" id="fc-frame-gallery">
            @foreach($catalogue as $index => $frame)
                <button type="button" class="fc-frame-choice {{ $index === 0 ? 'is-selected' : '' }}" data-frame-index="{{ $index }}" aria-pressed="{{ $index === 0 ? 'true' : 'false' }}">
                    @if($frame['thumbnail'])<img loading="lazy" src="{{ $frame['thumbnail'] }}" alt="{{ $frame['name'] }}">@else<div class="fc-frame-empty">No preview</div>@endif
                    <strong>{{ $frame['name'] }}</strong><small>{{ $frame['material'] ?: 'Picture frame' }} · {{ config('shop.currency_symbol') }} {{ number_format($frame['base_price'], 0) }}+</small>
                    @if($frame['inventory'] !== null && $frame['inventory'] < 1)<span class="fc-frame-stock unavailable">Out of stock · preview available</span>@endif
                </button>
            @endforeach
        </div></aside>
        <div class="fc-main">
            <div class="fc-controlbar" aria-label="Frame controls">
                <div class="fc-controlgroup"><div><label for="fc-width-range">Frame width</label><input id="fc-width-range" type="range" min="2" max="12" step="0.1" value="4"></div><input id="fc-width-number" type="number" min="2" max="12" step="0.1" value="4" aria-label="Frame profile width in centimetres"></div>
                <div class="fc-controlgroup"><div><label for="fc-frame-angle">Frame rotation</label><input id="fc-frame-angle" type="range" min="-180" max="180" step="1" value="0"></div><output id="fc-frame-angle-output">0°</output><button class="fc-tool" id="fc-frame-reset" type="button" title="Reset frame rotation">Reset</button></div>
                <div class="fc-controlgroup"><label for="fc-orientation">Production orientation</label><select id="fc-orientation"><option value="portrait">Portrait</option><option value="landscape">Landscape</option></select></div>
                <button type="button" class="fc-tool" id="fc-download">Download preview</button>
                <button type="button" class="fc-order-shortcut" id="fc-order-shortcut">Order</button>
            </div>
            <div class="fc-workspace">
            <div class="fc-stage" id="fc-stage">
                <div class="fc-canvas-frame"><canvas id="fc-canvas" width="900" height="1200" aria-label="Live framed photograph preview"></canvas></div>
                <div class="fc-upload-prompt"><div class="fc-upload-card"><strong>Add your photograph</strong><p>Choose an image or drop it here. JPG, PNG, or WebP up to 20 MB.</p><button type="button" id="fc-choose-photo">Choose photo</button></div></div>
                <div class="fc-drop-hint" id="fc-drag-hint">Drag the photo to adjust its crop and position.</div>
            </div>
            <form id="fc-order-form" class="fc-side-editor" method="POST" enctype="multipart/form-data" action="{{ route('frames.customize.add') }}">
                @csrf
                <input type="hidden" name="frame_id" id="fc-frame-id" value="{{ $initialCustomization['frame_id'] ?? $frames->first()->id }}">
                <input type="hidden" name="existing_line_key" value="{{ $lineKey ?? '' }}">
                <input type="hidden" name="save_action" id="fc-save-action" value="cart">
                <input type="hidden" name="orientation" id="fc-orientation-hidden" value="portrait">
                <input type="hidden" name="frame_width_cm" id="fc-frame-width-hidden" value="4">
                <input type="hidden" name="frame_rotation" id="fc-frame-rotation-hidden" value="0">
                <input type="hidden" name="image_rotation" id="fc-image-rotation-hidden" value="0">
                <input type="hidden" name="photo_tone" id="fc-photo-tone-hidden" value="0">
                <input type="hidden" name="zoom" id="fc-zoom-hidden" value="1">
                <input type="hidden" name="pan_x" id="fc-pan-x-hidden" value="0">
                <input type="hidden" name="pan_y" id="fc-pan-y-hidden" value="0">
                <input type="hidden" name="mat_color" id="fc-mat-hidden" value="">
                <input type="hidden" name="size_name" id="fc-size-name-hidden" value="Custom size">
                <input type="hidden" name="width_cm" id="fc-width-hidden" value="30">
                <input type="hidden" name="height_cm" id="fc-height-hidden" value="40">
                <input type="file" name="preview" id="fc-preview-file" accept="image/png" hidden>
                <div class="fc-side-content">
                    <nav class="fc-tabs" role="tablist" aria-label="Customization steps">
                        <button class="fc-tab is-active" id="fc-tab-photo-button" type="button" role="tab" aria-controls="fc-tab-photo" aria-selected="true" data-fc-tab="photo">Photo</button>
                        <button class="fc-tab" id="fc-tab-size-button" type="button" role="tab" aria-controls="fc-tab-size" aria-selected="false" data-fc-tab="size">Size & extras</button>
                        <button class="fc-tab" id="fc-tab-order-button" type="button" role="tab" aria-controls="fc-tab-order" aria-selected="false" data-fc-tab="order">Price & order</button>
                    </nav>
                    <div class="fc-tab-panel" id="fc-tab-photo" role="tabpanel" aria-labelledby="fc-tab-photo-button">
                        <section class="fc-panel"><h2 class="fc-panel-title">Photo controls</h2>
                            <div class="fc-photo-controls">
                                <label>Image rotation <button class="fc-tool" type="button" data-image-rotate="-90" aria-label="Rotate photo counterclockwise">↶</button> <button class="fc-tool" type="button" data-image-rotate="90" aria-label="Rotate photo clockwise">↷</button></label>
                                <label>Zoom <input id="fc-zoom" type="range" min="0.5" max="4" step="0.05" value="1"></label>
                                <label for="fc-tone">Color tone <input id="fc-tone" type="range" min="-100" max="100" step="1" value="0"><output id="fc-tone-output" for="fc-tone">Neutral</output></label>
                                <label><input type="radio" name="fit-mode" value="fit"> Fit</label><label><input type="radio" name="fit-mode" value="fill" checked> Fill</label>
                                <button class="fc-tool" type="button" id="fc-photo-reset">Reset photo</button>
                                <button class="fc-tool" type="button" id="fc-photo-remove" disabled>Remove photo</button>
                                <button class="fc-tool" type="button" id="fc-customizer-reset">Reset all</button>
                                <label class="flex items-center gap-2">Photo <input type="file" name="photo" id="fc-photo" accept="image/jpeg,image/png,image/webp" @required(empty($initialCustomization))></label>
                                <span id="fc-photo-error" class="text-xs text-rose-300" role="alert"></span>
                            </div>
                        </section>
                    </div>
                    <div class="fc-tab-panel" id="fc-tab-size" role="tabpanel" aria-labelledby="fc-tab-size-button" hidden>
                        <section class="fc-panel"><h2 class="fc-panel-title">Print size and mount</h2>
                            <div class="fc-size-row"><select id="fc-size-select" aria-label="Supported print size"><option value="custom">Custom dimensions</option></select><input id="fc-size-width" type="number" min="1" max="300" step="0.1" value="30" aria-label="Print width"><input id="fc-size-height" type="number" min="1" max="300" step="0.1" value="40" aria-label="Print height"><select id="fc-unit" name="unit" aria-label="Dimension unit"><option value="cm" @selected(old('unit', data_get($initialCustomization, 'unit', 'cm')) === 'cm')>cm</option><option value="in" @selected(old('unit', data_get($initialCustomization, 'unit', 'cm')) === 'in')>in</option></select></div>
                            <div class="fc-mats" id="fc-mat-options"></div>
                        </section>
                        <section class="fc-panel"><h2 class="fc-panel-title">Available extras</h2><div id="fc-accessories" class="fc-accessories"><span class="text-xs text-neutral-400">Choose a frame to see available extras.</span></div></section>
                    </div>
                    <div class="fc-tab-panel" id="fc-tab-order" role="tabpanel" aria-labelledby="fc-tab-order-button" hidden>
                        <aside class="fc-summary"><h2 id="fc-summary-name">{{ $frames->first()->name }}</h2><p id="fc-summary-material">{{ $frames->first()->material ?: 'Picture frame' }}</p><div class="fc-price" id="fc-price">{{ config('shop.currency_symbol') }} {{ number_format($frames->first()->base_price, 2) }}</div>
                            <div class="fc-breakdown" id="fc-breakdown"></div>
                            <label class="text-xs text-neutral-300">Quantity <input class="fc-quantity" id="fc-quantity" name="quantity" type="number" min="1" max="99" value="1"></label>
                            <p id="fc-stock-status" class="fc-stock-status" role="status"></p>
                            <div class="fc-order-actions">
                                <button class="fc-order-action" type="submit" name="save_action" value="cart" data-save-action="cart" id="fc-add">Save design &amp; view order</button>
                                <button class="fc-order-action secondary" type="submit" name="save_action" value="another" data-save-action="another">Save design &amp; add another</button>
                            </div>
                            <p class="fc-saved-count">{{ collect(session('print_cart', []))->filter(fn ($item) => !empty($item['frame_customization']))->count() }} saved frame design(s) in your order. <a href="{{ route('cart.index') }}">View order</a></p>
                            <small>Preview is a mockup for approval, not a print-resolution production file. Your original photograph is retained privately with the order.</small>
                        </aside>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
    @endif
</div>
</div>
<script type="application/json" id="fc-catalogue">@json($catalogue)</script>
<script type="application/json" id="fc-initial-customization">@json($initialCustomization ?? null)</script>
<script type="application/json" id="fc-initial-photo">@json($lineKey ? route('frames.customize.photo', $lineKey) : null)</script>
@endsection

@push('scripts')
<script>
(() => {
    const catalogNode = document.getElementById('fc-catalogue');
    if (!catalogNode) return;
    const frames = JSON.parse(catalogNode.textContent || '[]');
    if (!frames.length) return;
    const initial = JSON.parse(document.getElementById('fc-initial-customization')?.textContent || 'null');
    const initialPhotoUrl = JSON.parse(document.getElementById('fc-initial-photo')?.textContent || 'null');
    const $ = (id) => document.getElementById(id);
    const tabs=[...document.querySelectorAll('[data-fc-tab]')];
    function activateTab(key){tabs.forEach(tab=>{const active=tab.dataset.fcTab===key;tab.classList.toggle('is-active',active);tab.setAttribute('aria-selected',active?'true':'false');tab.tabIndex=active?0:-1;const panel=$(`fc-tab-${tab.dataset.fcTab}`);panel.hidden=!active})}
    $('fc-order-shortcut')?.addEventListener('click',()=>{activateTab('order');$('fc-tab-order-button').focus();document.querySelector('.fc-side-editor')?.scrollIntoView({behavior:'smooth',block:'nearest'})});
    tabs.forEach((tab,index)=>{tab.addEventListener('click',()=>activateTab(tab.dataset.fcTab));tab.addEventListener('keydown',event=>{if(!['ArrowLeft','ArrowRight','Home','End'].includes(event.key))return;event.preventDefault();const next=event.key==='Home'?0:event.key==='End'?tabs.length-1:(index+(event.key==='ArrowRight'?1:-1)+tabs.length)%tabs.length;tabs[next].focus();activateTab(tabs[next].dataset.fcTab)})});activateTab('photo');
    let dimensionUnit=$('fc-unit').value;
    const canvas = $('fc-canvas'), ctx = canvas.getContext('2d');
    const firstOrientation=(frames[0].orientations||['portrait','landscape']).includes('portrait')?'portrait':'landscape';
    const initialFrame=initial?frames.find(f=>Number(f.id)===Number(initial.frame_id)):null;
    const state = { frame: initialFrame||frames[0], photo: null, photoUrl: null, orientation: initial?.orientation || (initialFrame?(initialFrame.orientations||[])[0]:firstOrientation), width: Number(initial?.frame_width_cm ?? (initialFrame||frames[0]).min_width_cm), frameRotation: Number(initial?.frame_rotation||0), imageRotation: Number(initial?.image_rotation||0), photoTone: Number(initial?.photo_tone||0), zoom: Number(initial?.zoom||1), panX: Number(initial?.pan_x||0), panY: Number(initial?.pan_y||0), fit: 'fill', mat: initial?.mat_color||'', size: null, selectedAccessories: new Set(), dragging: false, lastX: 0, lastY: 0 };
    let overlayImage = null, textureImage = null, overlayOpening = null;
    const money = (n) => `${@json(config('shop.currency_symbol'))} ${Number(n).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}`;
    const loadImage = (src) => new Promise((resolve) => { if (!src) return resolve(null); const image = new Image(); image.onload=()=>resolve(image); image.onerror=()=>resolve(null); image.src=src; });
    function clampFrameControls(){
        $('fc-width-range').min=state.frame.min_width_cm; $('fc-width-range').max=state.frame.max_width_cm;
        $('fc-width-number').min=state.frame.min_width_cm; $('fc-width-number').max=state.frame.max_width_cm;
        state.width=Math.max(state.frame.min_width_cm,Math.min(state.frame.max_width_cm,state.width));
        $('fc-width-range').value=state.width; $('fc-width-number').value=state.width;
        const supportedOrientations=state.frame.orientations||['portrait','landscape'];
        const orientation=$('fc-orientation'); [...orientation.options].forEach(o=>{o.disabled=!supportedOrientations.includes(o.value)});
        if (!supportedOrientations.includes(state.orientation)) state.orientation=supportedOrientations[0];
        orientation.value=state.orientation;$('fc-orientation-hidden').value=state.orientation;
        const sizes=state.frame.sizes||[]; const select=$('fc-size-select'); select.innerHTML='';
        if(sizes.length){ select.add(new Option('Choose print size','')); sizes.forEach((s,i)=>select.add(new Option(`${s.name} (${s.width_cm} × ${s.height_cm} cm)`,String(i)))); }
        else select.add(new Option('Custom dimensions','custom'));
        state.size=sizes.length?(sizes[0]||null):null; if(sizes.length){select.value='0';const swapped=(state.orientation==='landscape'&&Number(state.size.width_cm)<Number(state.size.height_cm))||(state.orientation==='portrait'&&Number(state.size.width_cm)>Number(state.size.height_cm));setDimensionFields(swapped?state.size.height_cm:state.size.width_cm,swapped?state.size.width_cm:state.size.height_cm);$('fc-size-width').disabled=true;$('fc-size-height').disabled=true}else{$('fc-size-width').disabled=false;$('fc-size-height').disabled=false;setDimensionFields(state.orientation==='landscape'?40:30,state.orientation==='landscape'?30:40)}updateSizeLabels();
        const mats=$('fc-mat-options');mats.replaceChildren();const noMat=document.createElement('button');noMat.type='button';noMat.className='fc-mat';noMat.dataset.mat='';noMat.textContent='No mat';mats.append(noMat);(state.frame.mats||[]).forEach(mat=>{const option=document.createElement('button');option.type='button';option.className='fc-mat';option.dataset.mat=mat.color;option.textContent=`${mat.name} · ${money(mat.price||0)}`;mats.append(option)});if(!(state.frame.mats||[]).some(mat=>mat.color===state.mat))state.mat='';
        const accessories=$('fc-accessories'); accessories.innerHTML=''; state.selectedAccessories.clear();
        if(!(state.frame.accessories||[]).length){accessories.innerHTML='<span class="text-xs text-neutral-400">No extras available for this frame.</span>'}
        (state.frame.accessories||[]).forEach((item)=>{const key=String(item.id||item.name);const label=document.createElement('label');const choice=document.createElement('span');const checkbox=document.createElement('input');checkbox.type='checkbox';checkbox.name='accessories[]';checkbox.value=key;choice.append(checkbox,document.createTextNode(` ${item.name||key}`));const price=document.createElement('span');price.textContent=money(item.price||0);label.append(choice,price);checkbox.addEventListener('change',()=>{checkbox.checked?state.selectedAccessories.add(key):state.selectedAccessories.delete(key);updatePrice()});accessories.append(label)});
        $('fc-frame-id').value=state.frame.id; $('fc-selected-name').textContent=state.frame.name; $('fc-summary-name').textContent=state.frame.name; $('fc-summary-material').textContent=state.frame.material||'Picture frame';
        overlayImage=null; overlayOpening=null; textureImage=null;
        const overlaySrc=state.orientation==='landscape'?(state.frame.landscape_overlay||state.frame.overlay):state.frame.overlay;
        Promise.all([loadImage(overlaySrc),loadImage(state.frame.texture)]).then(([o,t])=>{overlayImage=o;overlayOpening=o?findOverlayOpening(o):null;textureImage=t;draw()});
        const outOfStock=state.frame.inventory!==null&&Number(state.frame.inventory)<1;document.querySelectorAll('[data-save-action]').forEach(button=>button.disabled=outOfStock);const addButton=$('fc-add');addButton.textContent=outOfStock?'Out of stock':'Save design & view order';const stockStatus=$('fc-stock-status');stockStatus.textContent=outOfStock?'You can preview and customize this frame, but it is unavailable to order right now.':(state.frame.inventory!==null?`${state.frame.inventory} available`:'Available to order');stockStatus.classList.toggle('unavailable',outOfStock);
        updatePrice(); draw();
    }
    const unitFactor=()=> $('fc-unit').value==='in'?2.54:1;
    function updateDimensionBounds(){const factor=unitFactor();['fc-size-width','fc-size-height'].forEach(id=>{$(id).min=(1/factor).toFixed(2);$(id).max=(300/factor).toFixed(2)})}
    function setDimensionFields(widthCm,heightCm){const factor=unitFactor();$('fc-size-width').value=(widthCm/factor).toFixed(1);$('fc-size-height').value=(heightCm/factor).toFixed(1)}
    function updateSizeLabels(){const factor=unitFactor(),unit=$('fc-unit').value;[...$('fc-size-select').options].forEach((option,index)=>{if(option.value==='custom'||option.value==='')return;const size=state.frame.sizes?.[Number(option.value)];if(size)option.textContent=`${size.name} (${(Number(size.width_cm)/factor).toFixed(1)} × ${(Number(size.height_cm)/factor).toFixed(1)} ${unit})`})}
    function dimensions(){const preset=state.size;if(!preset)return{w:Number($('fc-size-width').value||30)*unitFactor(),h:Number($('fc-size-height').value||40)*unitFactor(),label:`Custom size (${ $('fc-unit').value })`,adjust:0};let w=Number(preset.width_cm),h=Number(preset.height_cm);if((state.orientation==='landscape'&&w<h)||(state.orientation==='portrait'&&w>h))[w,h]=[h,w];return{w,h,label:preset.name,adjust:Number(preset.price||0)}}
    function findOverlayOpening(image){try{const probe=document.createElement('canvas');probe.width=image.naturalWidth;probe.height=image.naturalHeight;const p=probe.getContext('2d',{willReadFrequently:true});p.drawImage(image,0,0);const data=p.getImageData(0,0,probe.width,probe.height).data;const row=Math.floor(probe.height/2),column=Math.floor(probe.width/2);let runs=[];let start=-1;for(let x=0;x<probe.width;x++){const transparent=data[(row*probe.width+x)*4+3]<24;if(transparent&&start<0)start=x;if((!transparent||x===probe.width-1)&&start>=0){const end=transparent?x:x-1;runs.push([start,end]);start=-1}}const openingX=runs.sort((a,b)=>(b[1]-b[0])-(a[1]-a[0]))[0];runs=[];start=-1;for(let y=0;y<probe.height;y++){const transparent=data[(y*probe.width+column)*4+3]<24;if(transparent&&start<0)start=y;if((!transparent||y===probe.height-1)&&start>=0){const end=transparent?y:y-1;runs.push([start,end]);start=-1}}const openingY=runs.sort((a,b)=>(b[1]-b[0])-(a[1]-a[0]))[0];if(!openingX||!openingY)return null;const [x0,x1]=openingX,[y0,y1]=openingY;if(x1-x0<image.naturalWidth*.2||y1-y0<image.naturalHeight*.2)return null;return{x0,y0,x1,y1,width:probe.width,height:probe.height}}catch(_){return null}}
    function updatePrice(){const d=dimensions();const widthAdj=Math.max(0,state.width-state.frame.min_width_cm)*state.frame.width_price_per_cm;const sizeAdj=d.adjust;const matAdjustment=Number((state.frame.mats||[]).find(mat=>mat.color===state.mat)?.price||0);const extras=(state.frame.accessories||[]).filter(x=>state.selectedAccessories.has(String(x.id||x.name))).reduce((s,x)=>s+Number(x.price||0),0);const unit=Number(state.frame.base_price)+widthAdj+sizeAdj+matAdjustment+extras;const qty=Math.max(1,Number($('fc-quantity').value||1));$('fc-price').textContent=money(unit*qty);$('fc-breakdown').innerHTML=`<div><span>Frame</span><b>${money(state.frame.base_price)}</b></div><div><span>Width adjustment</span><b>${money(widthAdj)}</b></div><div><span>Print size</span><b>${money(sizeAdj)}</b></div><div><span>Mat</span><b>${money(matAdjustment)}</b></div><div><span>Extras</span><b>${money(extras)}</b></div><div><span>Unit · ${qty} pcs</span><b>${money(unit)} · ${money(unit*qty)}</b></div>`}
    function render(){
        const portrait=state.orientation==='portrait';canvas.width=portrait?900:1200;canvas.height=portrait?1200:900;
        const W=canvas.width,H=canvas.height;ctx.clearRect(0,0,W,H);ctx.fillStyle='#ddd8ce';ctx.fillRect(0,0,W,H);
        const d=dimensions();const profile=Math.max(.035,Math.min(.19,state.width/Math.max(1,Math.min(d.w,d.h)+2*state.width)));
        const insetX=W*profile,insetY=H*profile;
        ctx.save();ctx.translate(W/2,H/2);const rad=state.frameRotation*Math.PI/180;const rotatedW=Math.abs(W*Math.cos(rad))+Math.abs(H*Math.sin(rad));const rotatedH=Math.abs(W*Math.sin(rad))+Math.abs(H*Math.cos(rad));const scale=Math.min(W/rotatedW,H/rotatedH);ctx.scale(scale,scale);ctx.rotate(rad);ctx.translate(-W/2,-H/2);
        ctx.fillStyle='#e6e6e6';ctx.fillRect(0,0,W,H);
        if(state.mat){ctx.fillStyle=state.mat;ctx.fillRect(insetX*.72,insetY*.72,W-2*insetX*.72,H-2*insetY*.72)}
        const ix=insetX,iy=insetY,iw=W-2*insetX,ih=H-2*insetY;
        if(state.photo){ctx.save();ctx.beginPath();ctx.rect(ix,iy,iw,ih);ctx.clip();ctx.translate(ix+iw/2+state.panX*iw/100,iy+ih/2+state.panY*ih/100);ctx.rotate(state.imageRotation*Math.PI/180);const base=state.fit==='fill'?Math.max(iw/state.photo.width,ih/state.photo.height):Math.min(iw/state.photo.width,ih/state.photo.height);const z=base*state.zoom;ctx.drawImage(state.photo,-state.photo.width*z/2,-state.photo.height*z/2,state.photo.width*z,state.photo.height*z);if(state.photoTone!==0){ctx.globalCompositeOperation='soft-light';ctx.fillStyle=state.photoTone>0?`rgba(255,166,82,${Math.abs(state.photoTone)/420})`:`rgba(88,155,255,${Math.abs(state.photoTone)/420})`;ctx.fillRect(-state.photo.width*z/2,-state.photo.height*z/2,state.photo.width*z,state.photo.height*z)}ctx.restore()}
        else {ctx.fillStyle='#d1ccc3';ctx.fillRect(ix,iy,iw,ih);ctx.fillStyle='#6b655d';ctx.textAlign='center';ctx.font='24px Inter, sans-serif';ctx.fillText('Your photo preview',W/2,H/2)}
        if(state.frame.asset_type==='overlay'&&overlayImage){if(overlayOpening){const sx=(W-2*insetX)/(overlayOpening.x1-overlayOpening.x0),sy=(H-2*insetY)/(overlayOpening.y1-overlayOpening.y0);ctx.drawImage(overlayImage,ix-overlayOpening.x0*sx,iy-overlayOpening.y0*sy,overlayOpening.width*sx,overlayOpening.height*sy)}else{ctx.drawImage(overlayImage,0,0,W,H)}}
        if(state.frame.asset_type==='texture'&&textureImage){const pattern=ctx.createPattern(textureImage,'repeat');ctx.fillStyle=pattern;ctx.fillRect(0,0,W,iy);ctx.fillRect(0,H-iy,W,iy);ctx.fillRect(0,iy,ix,ih);ctx.fillRect(W-ix,iy,ix,ih)}
        ctx.strokeStyle='rgba(0,0,0,.55)';ctx.lineWidth=8;ctx.strokeRect(5,5,W-10,H-10);ctx.restore();
    }
    function draw(){render();const d=dimensions();$('fc-size-name-hidden').value=d.label;$('fc-width-hidden').value=d.w;$('fc-height-hidden').value=d.h;$('fc-frame-width-hidden').value=state.width;$('fc-frame-rotation-hidden').value=state.frameRotation;$('fc-image-rotation-hidden').value=state.imageRotation;$('fc-photo-tone-hidden').value=state.photoTone;$('fc-zoom-hidden').value=state.zoom;$('fc-pan-x-hidden').value=state.panX;$('fc-pan-y-hidden').value=state.panY;$('fc-mat-hidden').value=state.mat;updatePrice()}
    document.querySelectorAll('.fc-frame-choice').forEach(button=>button.addEventListener('click',()=>{document.querySelectorAll('.fc-frame-choice').forEach(b=>{b.classList.remove('is-selected');b.setAttribute('aria-pressed','false')});button.classList.add('is-selected');button.setAttribute('aria-pressed','true');state.frame=frames[Number(button.dataset.frameIndex)];state.width=state.frame.min_width_cm;state.frameRotation=0;clampFrameControls()}));
    $('fc-width-range').addEventListener('input',e=>{state.width=Number(e.target.value);$('fc-width-number').value=state.width;draw()});$('fc-width-number').addEventListener('input',e=>{state.width=Math.max(state.frame.min_width_cm,Math.min(state.frame.max_width_cm,Number(e.target.value||state.frame.min_width_cm)));$('fc-width-range').value=state.width;draw()});
    $('fc-frame-angle').addEventListener('input',e=>{state.frameRotation=Number(e.target.value);$('fc-frame-angle-output').value=`${state.frameRotation}°`;draw()});$('fc-frame-reset').addEventListener('click',()=>{$('fc-frame-angle').value=0;state.frameRotation=0;$('fc-frame-angle-output').value='0°';draw()});$('fc-orientation').addEventListener('change',e=>{state.orientation=e.target.value;$('fc-orientation-hidden').value=state.orientation;if(state.size){const swap=(state.orientation==='landscape'&&Number(state.size.width_cm)<Number(state.size.height_cm))||(state.orientation==='portrait'&&Number(state.size.width_cm)>Number(state.size.height_cm));setDimensionFields(swap?state.size.height_cm:state.size.width_cm,swap?state.size.width_cm:state.size.height_cm)}else{const width=Number($('fc-size-width').value),height=Number($('fc-size-height').value);if((state.orientation==='landscape'&&width<height)||(state.orientation==='portrait'&&width>height)){$('fc-size-width').value=height;$('fc-size-height').value=width}}const src=state.orientation==='landscape'?(state.frame.landscape_overlay||state.frame.overlay):state.frame.overlay;loadImage(src).then(i=>{overlayImage=i;overlayOpening=i?findOverlayOpening(i):null;draw()})});
    $('fc-size-select').addEventListener('change',e=>{const i=Number(e.target.value);state.size=(e.target.value!==''&&e.target.value!=='custom')?state.frame.sizes[i]:null;if(state.size){let w=Number(state.size.width_cm),h=Number(state.size.height_cm);if((state.orientation==='landscape'&&w<h)||(state.orientation==='portrait'&&w>h))[w,h]=[h,w];setDimensionFields(w,h);$('fc-size-width').disabled=true;$('fc-size-height').disabled=true}else{$('fc-size-width').disabled=false;$('fc-size-height').disabled=false}draw()});$('fc-unit').addEventListener('change',e=>{const oldFactor=dimensionUnit==='in'?2.54:1;const widthCm=state.size?dimensions().w:Number($('fc-size-width').value||30)*oldFactor;const heightCm=state.size?dimensions().h:Number($('fc-size-height').value||40)*oldFactor;dimensionUnit=e.target.value;updateDimensionBounds();setDimensionFields(widthCm,heightCm);updateSizeLabels();draw()});['fc-size-width','fc-size-height','fc-quantity'].forEach(id=>$(id).addEventListener('input',draw));
    const toneControl=$('fc-tone'),toneOutput=$('fc-tone-output');function updateToneControl(){toneControl.value=state.photoTone;toneOutput.value=state.photoTone===0?'Neutral':state.photoTone<0?`Cool ${Math.abs(state.photoTone)}%`:`Warm ${state.photoTone}%`}updateToneControl();toneControl.addEventListener('input',e=>{state.photoTone=Number(e.target.value);updateToneControl();draw()});
    $('fc-zoom').addEventListener('input',e=>{state.zoom=Number(e.target.value);draw()});document.querySelectorAll('[data-image-rotate]').forEach(b=>b.addEventListener('click',()=>{state.imageRotation=(state.imageRotation+Number(b.dataset.imageRotate)+360)%360;draw()}));document.querySelectorAll('[name=fit-mode]').forEach(r=>r.addEventListener('change',()=>{state.fit=document.querySelector('[name=fit-mode]:checked').value;draw()}));$('fc-photo-reset').addEventListener('click',()=>{state.imageRotation=0;state.zoom=1;state.panX=0;state.panY=0;state.photoTone=0;$('fc-zoom').value=1;updateToneControl();draw()});$('fc-customizer-reset').addEventListener('click',()=>{$('fc-frame-angle').value=0;$('fc-frame-angle-output').value='0°';$('fc-zoom').value=1;state.width=state.frame.min_width_cm;state.orientation=(state.frame.orientations||['portrait'])[0];state.frameRotation=0;state.imageRotation=0;state.photoTone=0;state.zoom=1;state.panX=0;state.panY=0;state.fit='fill';state.mat='';updateToneControl();document.querySelector('[name=fit-mode][value=fill]').checked=true;clampFrameControls()});
    $('fc-mat-options').addEventListener('click',e=>{const button=e.target.closest('.fc-mat');if(!button)return;state.mat=button.dataset.mat;document.querySelectorAll('.fc-mat').forEach(option=>option.classList.toggle('is-selected',option===button));draw()});
    const input=$('fc-photo'),stage=$('fc-stage'),error=$('fc-photo-error'),removePhotoButton=$('fc-photo-remove');function syncRemoveButton(){removePhotoButton.disabled=!state.photo}function removePhoto(){if(state.photoUrl){URL.revokeObjectURL(state.photoUrl);state.photoUrl=null}state.photo=null;state.imageRotation=0;state.zoom=1;state.panX=0;state.panY=0;state.photoTone=0;input.value='';$('fc-zoom').value=1;updateToneControl();stage.classList.remove('has-photo');error.textContent='Photo removed. Choose another photo to continue.';syncRemoveButton();draw()}removePhotoButton.addEventListener('click',removePhoto);syncRemoveButton();function useFile(file){error.textContent='';if(!file)return;if(!['image/jpeg','image/png','image/webp'].includes(file.type)||file.size>20*1024*1024){error.textContent='Choose a JPG, PNG, or WebP image under 20 MB.';input.value='';return}const url=URL.createObjectURL(file);const img=new Image();img.onload=()=>{if(img.width*img.height>60000000){error.textContent='Image must be under 60 megapixels.';URL.revokeObjectURL(url);return}if(state.photoUrl)URL.revokeObjectURL(state.photoUrl);state.photo=img;state.photoUrl=url;stage.classList.add('has-photo');syncRemoveButton();draw()};img.onerror=()=>{error.textContent='This image could not be opened.';URL.revokeObjectURL(url)};img.src=url}input.addEventListener('change',()=>useFile(input.files[0]));$('fc-choose-photo').addEventListener('click',()=>input.click());stage.addEventListener('dragover',e=>{e.preventDefault();stage.classList.add('is-dragging')});stage.addEventListener('dragleave',()=>stage.classList.remove('is-dragging'));stage.addEventListener('drop',e=>{e.preventDefault();stage.classList.remove('is-dragging');const file=e.dataTransfer.files[0];useFile(file);try{const transfer=new DataTransfer();transfer.items.add(file);input.files=transfer.files}catch(_){error.textContent='Your browser could not attach the dropped file. Please use Choose photo instead.'}});
    canvas.addEventListener('pointerdown',e=>{if(!state.photo)return;state.dragging=true;state.lastX=e.clientX;state.lastY=e.clientY;canvas.setPointerCapture(e.pointerId)});canvas.addEventListener('pointermove',e=>{if(!state.dragging)return;const r=canvas.getBoundingClientRect();state.panX=Math.max(-100,Math.min(100,state.panX+(e.clientX-state.lastX)/r.width*100));state.panY=Math.max(-100,Math.min(100,state.panY+(e.clientY-state.lastY)/r.height*100));state.lastX=e.clientX;state.lastY=e.clientY;draw()});['pointerup','pointercancel'].forEach(e=>canvas.addEventListener(e,()=>state.dragging=false));
    $('fc-download').addEventListener('click',()=>canvas.toBlob(blob=>{if(!blob)return;const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download=`${state.frame.name.toLowerCase().replace(/[^a-z0-9]+/g,'-')}-preview.png`;a.click();URL.revokeObjectURL(a.href)},'image/png'));
    $('fc-order-form').addEventListener('submit',e=>{if(!state.photo){e.preventDefault();error.textContent='Add a photograph before adding this frame to your cart.';return}draw();e.preventDefault();$('fc-save-action').value=e.submitter?.dataset.saveAction||'cart';canvas.toBlob(blob=>{if(blob){const dt=new DataTransfer();dt.items.add(new File([blob],'frame-preview.png',{type:'image/png'}));$('fc-preview-file').files=dt.files}e.target.submit()},'image/png')});
    if(initialFrame){document.querySelectorAll('.fc-frame-choice').forEach(button=>{const selected=Number(frames[Number(button.dataset.frameIndex)].id)===Number(initialFrame.id);button.classList.toggle('is-selected',selected);button.setAttribute('aria-pressed',selected?'true':'false')})}
    clampFrameControls();
    if(initialFrame&&initial){const preset=(initialFrame.sizes||[]).findIndex(size=>size.name===initial.size_name);if(preset>=0){$('fc-size-select').value=String(preset);state.size=initialFrame.sizes[preset];$('fc-size-width').disabled=true;$('fc-size-height').disabled=true}else{state.size=null;$('fc-size-select').value='custom';$('fc-size-width').disabled=false;$('fc-size-height').disabled=false}if(initial.width_cm)setDimensionFields(initial.width_cm,initial.height_cm);$('fc-frame-width-hidden').value=state.width;$('fc-width-range').value=state.width;$('fc-width-number').value=state.width;$('fc-frame-angle').value=state.frameRotation;$('fc-frame-angle-output').value=`${state.frameRotation}°`;$('fc-image-rotation-hidden').value=state.imageRotation;$('fc-zoom').value=state.zoom;$('fc-quantity').value=initial.price_breakdown?.quantity||1;state.selectedAccessories=new Set((initial.accessories||[]).map(item=>String(item.id||item.name)));document.querySelectorAll('#fc-accessories input[type=checkbox]').forEach(input=>input.checked=state.selectedAccessories.has(input.value));document.querySelectorAll('.fc-mat').forEach(button=>button.classList.toggle('is-selected',button.dataset.mat===state.mat));if(initialPhotoUrl){$('fc-photo').required=false;loadImage(initialPhotoUrl).then(image=>{if(image){state.photo=image;stage.classList.add('has-photo');syncRemoveButton();draw()}})}}
    draw();
})();
</script>
@endpush
