<nav class="officer-pagination" role="navigation" aria-label="Navigasi petugas">
    <p class="officer-pagination-summary">
        Menampilkan <strong>{{ $paginator->firstItem() ?? 0 }}</strong>–<strong>{{ $paginator->lastItem() ?? 0 }}</strong>
        dari <strong>{{ $paginator->total() }}</strong> akun
    </p>

    @if ($paginator->hasPages())
        <div class="officer-pagination-controls">
            @if ($paginator->onFirstPage())
                <span class="officer-pagination-arrow is-disabled" aria-disabled="true" aria-label="Halaman sebelumnya">
                    <img class="is-previous" src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="">
                </span>
            @else
                <a class="officer-pagination-arrow" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya">
                    <img class="is-previous" src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="">
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="officer-pagination-ellipsis" aria-disabled="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="officer-pagination-page is-current" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="officer-pagination-page" href="{{ $url }}" aria-label="Ke halaman {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="officer-pagination-arrow" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman selanjutnya">
                    <img class="is-next" src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="">
                </a>
            @else
                <span class="officer-pagination-arrow is-disabled" aria-disabled="true" aria-label="Halaman selanjutnya">
                    <img class="is-next" src="{{ asset('images/officer/arrow-dropdown.svg') }}" alt="">
                </span>
            @endif
        </div>
    @endif
</nav>
