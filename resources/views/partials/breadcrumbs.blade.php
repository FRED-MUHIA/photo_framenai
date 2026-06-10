@props(['items' => []])

@if(count($items) > 1)
    <nav class="bg-white" aria-label="Breadcrumb">
        <ol class="mx-auto flex max-w-7xl items-center gap-2 overflow-x-auto px-4 py-4 text-sm text-[#777] sm:px-6 lg:px-8">
            @foreach($items as $item)
                <li class="flex shrink-0 items-center gap-2">
                    @if(! $loop->first)
                        <span class="text-xl leading-none text-[#9b9b9b]" aria-hidden="true">&rsaquo;</span>
                    @endif

                    @if(! $loop->last && ! empty($item['url']))
                        <a href="{{ $item['url'] }}" class="whitespace-nowrap transition hover:text-[#ff5a1f]">
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="whitespace-nowrap font-medium text-[#ff5a1f]" aria-current="page">
                            {{ $item['label'] }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
