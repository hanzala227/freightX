@if ($paginator->hasPages())
    <ul class="ce-pages">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="ce-page-item disabled" aria-disabled="true">
                <span class="ce-page-link"><i class="fa fa-angle-left"></i></span>
            </li>
        @else
            <li class="ce-page-item">
                <a class="ce-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">
                    <i class="fa fa-angle-left"></i>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="ce-page-item disabled" aria-disabled="true">
                    <span class="ce-page-link dots">{{ $element }}</span>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="ce-page-item active" aria-current="page">
                            <span class="ce-page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="ce-page-item">
                            <a class="ce-page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="ce-page-item">
                <a class="ce-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>
        @else
            <li class="ce-page-item disabled" aria-disabled="true">
                <span class="ce-page-link"><i class="fa fa-angle-right"></i></span>
            </li>
        @endif
    </ul>
@endif
