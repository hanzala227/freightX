@if ($paginator->hasPages())
    <nav class="tp-pagination">
        {{-- Previous Button --}}
        @if ($paginator->onFirstPage())
            <span class="tp-page-btn disabled"><i class="fa fa-chevron-left"></i></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="tp-page-btn" rel="prev"><i class="fa fa-chevron-left"></i></a>
        @endif

        {{-- Page Numbers --}}
        @php
            $start = max($paginator->currentPage() - 2, 1);
            $end = min($paginator->currentPage() + 2, $paginator->lastPage());
        @endphp

        @if ($start > 1)
            <a href="{{ $paginator->url(1) }}" class="tp-page-btn">1</a>
            @if ($start > 2)
                <span class="tp-page-btn disabled">...</span>
            @endif
        @endif

        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $paginator->currentPage())
                <span class="tp-page-btn active">{{ $page }}</span>
            @else
                <a href="{{ $paginator->url($page) }}" class="tp-page-btn">{{ $page }}</a>
            @endif
        @endfor

        @if ($end < $paginator->lastPage())
            @if ($end < $paginator->lastPage() - 1)
                <span class="tp-page-btn disabled">...</span>
            @endif
            <a href="{{ $paginator->url($paginator->lastPage()) }}" class="tp-page-btn">{{ $paginator->lastPage() }}</a>
        @endif

        {{-- Next Button --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="tp-page-btn" rel="next"><i class="fa fa-chevron-right"></i></a>
        @else
            <span class="tp-page-btn disabled"><i class="fa fa-chevron-right"></i></span>
        @endif
    </nav>
@endif
