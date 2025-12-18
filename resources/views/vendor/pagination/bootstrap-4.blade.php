@if ($paginator->hasPages())
    <nav class="pagination-wrapper">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="ri-arrow-left-s-line"></i> Sebelumnya
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="ri-arrow-left-s-line"></i> Sebelumnya
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
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        Selanjutnya <i class="ri-arrow-right-s-line"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">
                        Selanjutnya <i class="ri-arrow-right-s-line"></i>
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
            gap: 4px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination .page-item {
            display: inline-flex;
        }

        .pagination .page-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 500;
            color: #4B5675;
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .pagination .page-link:hover {
            color: #059669;
            border-color: #059669;
            background: #ECFDF5;
        }

        .pagination .page-item.active .page-link {
            color: white;
            background: #059669;
            border-color: #059669;
        }

        .pagination .page-item.disabled .page-link {
            color: #9CA3AF;
            background: #F9FAFB;
            border-color: #E5E7EB;
            cursor: not-allowed;
        }

        .pagination .page-link i {
            font-size: 16px;
        }
    </style>
@endif