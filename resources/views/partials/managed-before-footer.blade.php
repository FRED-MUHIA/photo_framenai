@if(isset($cmsPage) && ($cmsPage?->body || ! empty($cmsPage?->sections)))
    @php
        $managedSections = is_array($cmsPage->sections) ? $cmsPage->sections : [];
    @endphp

    <section class="bg-white px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl space-y-5">
            @if($cmsPage->body)
                <article class="rounded-lg border border-[#d9d9d9] bg-white p-5 text-[15px] leading-7 text-[#334155]">
                    {!! nl2br(e($cmsPage->body)) !!}
                </article>
            @endif

            @foreach($managedSections as $section)
                @php
                    $title = data_get($section, 'title');
                    $subtitle = data_get($section, 'subtitle');
                    $content = data_get($section, 'content');
                    $items = collect(data_get($section, 'items', []))->filter(fn ($item) => is_array($item))->values();
                @endphp

                @if($title || $subtitle || $content || $items->isNotEmpty())
                    <article class="rounded-lg border border-[#d9d9d9] bg-white p-5 text-[#111]">
                        @if($title)
                            <h2 class="text-2xl font-extrabold">{{ $title }}</h2>
                        @endif
                        @if($subtitle)
                            <p class="mt-1 text-sm font-semibold text-[#64748b]">{{ $subtitle }}</p>
                        @endif
                        @if($content)
                            <p class="mt-3 whitespace-pre-line text-[15px] leading-7 text-[#334155]">{{ $content }}</p>
                        @endif
                        @if($items->isNotEmpty())
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                @foreach($items as $item)
                                    <div class="rounded border border-[#e5e7eb] bg-[#f8fafc] p-4">
                                        @if(data_get($item, 'title'))
                                            <h3 class="font-bold">{{ data_get($item, 'title') }}</h3>
                                        @endif
                                        @if(data_get($item, 'content'))
                                            <p class="mt-2 text-sm leading-6 text-[#475569]">{{ data_get($item, 'content') }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @endif
            @endforeach
        </div>
    </section>
@endif
