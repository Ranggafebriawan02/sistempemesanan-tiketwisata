@if ($paginator->hasPages())
    <div class="pager">
        <div>
            @if ($paginator->onFirstPage())
                <span class="pager-link disabled">&laquo; Sebelumnya</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pager-link" rel="prev">&laquo; Sebelumnya</a>
            @endif
        </div>

        <span class="pager-info">
            Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
            &middot; {{ $paginator->total() }} data
        </span>

        <div>
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pager-link" rel="next">Berikutnya &raquo;</a>
            @else
                <span class="pager-link disabled">Berikutnya &raquo;</span>
            @endif
        </div>
    </div>
@endif
