@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center space-x-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 border border-gray-300 text-gray-500 bg-white rounded-md">
                Prev
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="px-3 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-100 rounded-md">
                Prev
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-3 py-2 border border-gray-300 text-gray-700 bg-white rounded-md">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-2 border border-blue-300 text-blue-700 bg-blue-100 rounded-md">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           class="px-3 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-100 rounded-md">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="px-3 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-100 rounded-md">
                Next
            </a>
        @else
            <span class="px-3 py-2 border border-gray-300 text-gray-500 bg-white rounded-md">
                Next
            </span>
        @endif
    </nav>
@endif
