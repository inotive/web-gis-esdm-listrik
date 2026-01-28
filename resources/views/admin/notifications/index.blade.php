@extends('admin.layouts.app')

@section('title', 'Daftar Notifikasi')

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">Notifikasi</div>
            <div class="page-title">Riwayat Notifikasi</div>
        </div>
        <div class="page-actions">
            <button id="markAllReadPage" class="btn btn-primary">
                <i class="ri-check-double-line"></i> Tandai Semua Dibaca
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($notifications->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="ri-notification-off-line" style="font-size: 48px; color: #9ca3af;"></i>
                    </div>
                    <h4 style="color: #4b5563;">Tidak ada notifikasi</h4>
                    <p style="color: #6b7280;">Anda belum memiliki notifikasi apapun saat ini.</p>
                </div>
            @else
                <div class="notification-full-list">
                    @foreach ($notifications as $notif)
                        <div class="notif-item-row {{ $notif->is_read ? '' : 'unread' }}"
                            onclick="handleNotifClick(this, '{{ $notif->id }}', '{{ $notif->action_url }}')">
                            <div class="notif-icon-wrapper">
                                @php
                                    $iconClass = match ($notif->type) {
                                        'info' => 'ri-information-line',
                                        'success' => 'ri-checkbox-circle-line',
                                        'warning' => 'ri-alert-line',
                                        'error' => 'ri-error-warning-line',
                                        default => 'ri-notification-3-line',
                                    };
                                    $bgClass = match ($notif->type) {
                                        'info' => '#e0f2fe',
                                        'success' => '#dcfce7',
                                        'warning' => '#fef9c3',
                                        'error' => '#fee2e2',
                                        default => '#f3f4f6',
                                    };
                                    $colorClass = match ($notif->type) {
                                        'info' => '#0284c7',
                                        'success' => '#16a34a',
                                        'warning' => '#ca8a04',
                                        'error' => '#dc2626',
                                        default => '#4b5563',
                                    };
                                @endphp
                                <div class="notif-icon"
                                    style="background-color: {{ $bgClass }}; color: {{ $colorClass }};">
                                    <i class="{{ $iconClass }}"></i>
                                </div>
                            </div>
                            <div class="notif-content-wrapper">
                                <div class="d-flex justify-between align-start">
                                    <h5 class="notif-title">{{ $notif->title }}</h5>
                                    <span class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="notif-message">{{ $notif->message }}</p>
                            </div>
                            @if (!$notif->is_read)
                                <div class="notif-indicator"></div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('styles')
        <style>
            .notification-full-list {
                display: flex;
                flex-direction: column;
            }

            .notif-item-row {
                display: flex;
                gap: 16px;
                padding: 16px;
                border-bottom: 1px solid #f3f4f6;
                cursor: pointer;
                transition: background-color 0.2s;
                position: relative;
            }

            .notif-item-row:last-child {
                border-bottom: none;
            }

            .notif-item-row:hover {
                background-color: #f9fafb;
            }

            .notif-item-row.unread {
                background-color: #eff6ff;
            }

            .notif-icon {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
            }

            .notif-content-wrapper {
                flex: 1;
            }

            .d-flex {
                display: flex;
            }

            .justify-between {
                justify-content: space-between;
            }

            .align-start {
                align-items: flex-start;
            }

            .notif-title {
                font-size: 15px;
                font-weight: 600;
                color: #1f2937;
                margin-bottom: 4px;
                margin-top: 0;
            }

            .notif-message {
                font-size: 14px;
                color: #4b5563;
                margin: 0;
                line-height: 1.5;
            }

            .notif-time {
                font-size: 12px;
                color: #9ca3af;
                white-space: nowrap;
                margin-left: 10px;
            }

            .notif-indicator {
                position: absolute;
                top: 50%;
                right: 16px;
                transform: translateY(-50%);
                width: 10px;
                height: 10px;
                background-color: #3b82f6;
                border-radius: 50%;
            }

            .btn-primary {
                background-color: #3b82f6;
                color: white;
                border: none;
                padding: 8px 16px;
                border-radius: 6px;
                font-weight: 600;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }

            .btn-primary:hover {
                background-color: #2563eb;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function handleNotifClick(element, id, url) {
                // Mark visual as read immediately
                element.classList.remove('unread');
                const indicator = element.querySelector('.notif-indicator');
                if (indicator) indicator.remove();

                // Send request to mark as read
                fetch(`{{ url('admin/notifications') }}/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    if (url && url !== 'null') {
                        window.location.href = url;
                    }
                });
            }

            document.getElementById('markAllReadPage')?.addEventListener('click', function() {
                if (confirm('Tandai semua notifikasi sebagai sudah dibaca?')) {
                    fetch("{{ route('admin.notifications.mark-all-read') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.reload();
                            }
                        });
                }
            });
        </script>
    @endpush
@endsection
