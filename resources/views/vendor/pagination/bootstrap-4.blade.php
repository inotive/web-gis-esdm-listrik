@if ($paginator->hasPages())
    <nav class="pagination-wrapper">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link" aria-label="Sebelumnya">
                        <i class="ri-arrow-left-s-line"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya">
                        <i class="ri-arrow-left-s-line"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya">
                        <i class="ri-arrow-right-s-line"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link" aria-label="Berikutnya">
                        <i class="ri-arrow-right-s-line"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    <style>
        .pagination-wrapper {
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 2px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination .page-item {
            list-style: none;
        }

        .pagination .page-link {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 14px;
            color: #4B5675;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            background: transparent;
        }

        .pagination .page-link:hover {
            background: #F5F5F5;
        }

        .pagination .page-item.active .page-link {
            background: #F1F1F4;
            color: #252F4A;
            font-weight: 500;
        }

        .pagination .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination .page-link i {
            font-size: 16px;
        }
    </style>
@else
<div class="summary">Menampilkan
    <strong>{{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }}</strong> dari
    <strong>{{ $paginator->total() }}</strong> data</div>
@endif
