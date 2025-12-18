@if ($paginator->hasPages())
    <nav class="pagination-simple">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-btn disabled">
                <i class="ri-arrow-left-s-line"></i> Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-btn" rel="prev">
                <i class="ri-arrow-left-s-line"></i> Sebelumnya
            </a>
        @endif

        <span class="page-info">
            Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
        </span>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn" rel="next">
                Selanjutnya <i class="ri-arrow-right-s-line"></i>
            </a>
        @else
            <span class="page-btn disabled">
                Selanjutnya <i class="ri-arrow-right-s-line"></i>
            </span>
        @endif
    </nav>

    <style>
        .pagination-simple {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .pagination-simple .page-btn {
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
        }

        .pagination-simple .page-btn:hover:not(.disabled) {
            color: #059669;
            border-color: #059669;
            background: #ECFDF5;
        }

        .pagination-simple .page-btn.disabled {
            color: #9CA3AF;
            background: #F9FAFB;
            cursor: not-allowed;
        }

        .pagination-simple .page-info {
            font-size: 13px;
            color: #6B7280;
        }

        .pagination-simple .page-btn i {
            font-size: 16px;
        }
    </style>
@endif