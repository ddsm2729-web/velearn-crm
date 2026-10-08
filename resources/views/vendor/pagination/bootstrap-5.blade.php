@if ($paginator->hasPages())
    <nav aria-label="Page navigation" class="custom-pagination-nav">
        {{-- Mobile: simple prev/next --}}
        <div class="d-flex justify-content-between align-items-center d-sm-none">
            @if ($paginator->onFirstPage())
                <span class="custom-page-btn custom-page-btn-disabled">&#8592; Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="custom-page-btn">&#8592; Prev</a>
            @endif
            <span class="small text-muted">Page {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="custom-page-btn">Next &#8594;</a>
            @else
                <span class="custom-page-btn custom-page-btn-disabled">Next &#8594;</span>
            @endif
        </div>

        {{-- Desktop: full pagination --}}
        <div class="d-none d-sm-flex align-items-center justify-content-between gap-3">
            {{-- Info text --}}
            <span class="small text-muted">
                Showing <strong>{{ $paginator->firstItem() }}</strong>&ndash;<strong>{{ $paginator->lastItem() }}</strong>
                of <strong>{{ $paginator->total() }}</strong> results
            </span>

            {{-- Page buttons --}}
            <ul class="custom-pagination mb-0">
                {{-- Prev --}}
                @if ($paginator->onFirstPage())
                    <li class="custom-page-item disabled">
                        <span class="custom-page-link">&#8592;</span>
                    </li>
                @else
                    <li class="custom-page-item">
                        <a class="custom-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">&#8592;</a>
                    </li>
                @endif

                {{-- Page numbers --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="custom-page-item disabled"><span class="custom-page-link custom-page-dots">&#8230;</span></li>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="custom-page-item active" aria-current="page">
                                    <span class="custom-page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="custom-page-item">
                                    <a class="custom-page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <li class="custom-page-item">
                        <a class="custom-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">&#8594;</a>
                    </li>
                @else
                    <li class="custom-page-item disabled">
                        <span class="custom-page-link">&#8594;</span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
@endif
