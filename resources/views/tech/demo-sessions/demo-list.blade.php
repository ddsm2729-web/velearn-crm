<div class="slots-container p-3 bg-white shadow-sm" style="border-radius: 12px;">
    <!-- Header -->
    <div class="d-flex align-items-center mb-3">
        <span style="font-size: 0.85rem; font-weight: 700; color: #374151;">
            @if(isset($icon)) {!! $icon !!} @else <i class="fas fa-clipboard-list" style="color: #f59e0b;"></i> @endif
            {{ $title }}
        </span>
        <span class="badge rounded-pill bg-light text-primary border border-primary ms-2" style="font-size: 0.7rem;">
            {{ count($demos) }}
        </span>
    </div>

    <!-- Search & Filters -->
    <div class="d-flex align-items-center mb-3 flex-wrap gap-2">
        <div class="input-group" style="width: 220px;">
            <span class="input-group-text bg-light border-end-0" style="padding: 5px 10px;"><i class="fas fa-search text-muted" style="font-size: 0.75rem;"></i></span>
            <input type="text" class="form-control bg-light border-start-0 demo-search-input" data-target="{{ $idPrefix }}" placeholder="Search name or course..." style="font-size: 0.78rem; padding: 5px 8px;">
        </div>
        <input type="date" class="form-control demo-date-filter" data-target="{{ $idPrefix }}" style="width: 140px; font-size: 0.78rem; padding: 5px 8px;">
        <button class="btn btn-outline-secondary demo-filter-btn" data-target="{{ $idPrefix }}" data-filter="today" style="font-size: 0.75rem; padding: 4px 12px;">Today</button>
        <button class="btn btn-outline-secondary demo-filter-btn active" data-target="{{ $idPrefix }}" data-filter="all" style="font-size: 0.75rem; padding: 4px 12px;">All</button>
    </div>

    <!-- List -->
    <div class="demo-list-wrapper" id="{{ $idPrefix }}-list">
        @forelse($demos as $demo)
            <div class="card mb-2 shadow-sm demo-card-item" style="border-radius: 8px; border: 1px solid #eaeaea;"
                 data-name="{{ strtolower($demo->name) }}"
                 data-course="{{ strtolower($demo->course) }}"
                 data-date="{{ \Carbon\Carbon::parse($demo->date)->format('Y-m-d') }}">
                <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    
                    <div class="d-flex align-items-center">
                        @php
                            $initial = strtoupper(substr($demo->name, 0, 1));
                            $colors = ['#5b58ff', '#e83e8c', '#20c997', '#fd7e14', '#6f42c1', '#17a2b8'];
                            $bgColor = $colors[crc32($demo->name) % count($colors)];
                        @endphp
                        <div class="rounded-circle text-white d-flex justify-content-center align-items-center me-2 flex-shrink-0" style="width: 36px; height: 36px; min-width: 36px; background-color: {{ $bgColor }}; font-size: 14px; font-weight: 700;">
                            {{ $initial }}
                        </div>
                        <div>
                            <div class="mb-0" style="font-weight: 700; font-size: 0.85rem; color: #111827;">{{ $demo->name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                {{ $demo->course }} &middot; {{ \Carbon\Carbon::parse($demo->date)->format('d M Y') }} &middot; {{ $demo->time }}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="text-muted" style="font-size: 0.78rem; font-weight: 500; white-space: nowrap;">
                            <i class="fas fa-mobile-alt me-1"></i> {{ $demo->phone ?? 'N/A' }}
                        </div>
                        
                        <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} px-2 py-1" style="border-radius: 20px; font-weight: 600; font-size: 0.72rem;">
                            {{ $statusText }}
                        </span>

                        @if($showActions ?? false)
                            @if($demo->zoom_meet_link)
                                <a href="{{ $demo->zoom_meet_link }}" target="_blank" class="btn btn-sm" style="background-color: #2D8CFF; color: white; border-radius: 6px; font-size: 0.72rem; padding: 4px 10px; text-decoration: none;">
                                    <i class="fas fa-video me-1"></i> Join
                                </a>
                            @endif

                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown" style="border-radius: 6px; font-size: 0.75rem; padding: 4px 8px;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size: 0.8rem; border: none; border-radius: 8px;">
                                    <li>
                                        <button type="button" class="dropdown-item py-2 status-confirm-btn"
                                            data-action="{{ route('tech.demo-sessions.update-status', ['type' => $demo->type, 'id' => $demo->id]) }}"
                                            data-status="Demo Attended"
                                            data-name="{{ $demo->name }}"
                                            data-color="success"
                                            data-icon="fas fa-check-circle">
                                            <i class="fas fa-check text-success me-2"></i> Demo Attended
                                        </button>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button type="button" class="dropdown-item py-2 status-confirm-btn"
                                            data-action="{{ route('tech.demo-sessions.update-status', ['type' => $demo->type, 'id' => $demo->id]) }}"
                                            data-status="Demo Missed"
                                            data-name="{{ $demo->name }}"
                                            data-color="warning"
                                            data-icon="fas fa-times-circle">
                                            <i class="fas fa-times text-warning me-2"></i> Demo Missed
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item py-2 text-danger status-confirm-btn"
                                            data-action="{{ route('tech.demo-sessions.update-status', ['type' => $demo->type, 'id' => $demo->id]) }}"
                                            data-status="Demo lost"
                                            data-name="{{ $demo->name }}"
                                            data-color="danger"
                                            data-icon="fas fa-ban">
                                            <i class="fas fa-ban me-2"></i> Demo lost
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        @empty
            <div class="text-center py-4">
                <p class="text-muted mb-0" style="font-weight: 500; font-size: 0.82rem;">No demos found.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Status Confirmation Modal (rendered once per include) --}}
@once
<div class="modal fade" id="statusConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content" style="border-radius: 14px; border: none; padding: 0.5rem;">
            <div class="modal-body text-center py-4 px-4">
                <div id="statusConfirmIcon" class="mb-3" style="font-size: 2.5rem;"></div>
                <h6 class="mb-1" style="font-weight: 700; font-size: 1rem;">Confirm Status Update</h6>
                <p class="text-muted mb-1" style="font-size: 0.82rem;">You are about to mark</p>
                <p class="mb-1" style="font-size: 0.9rem; font-weight: 700;" id="statusConfirmName"></p>
                <p class="text-muted mb-3" style="font-size: 0.82rem;">as <strong id="statusConfirmLabel"></strong></p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light px-4" style="border-radius: 8px; font-size: 0.82rem; font-weight: 600;" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn px-4" id="statusConfirmOkBtn" style="border-radius: 8px; font-size: 0.82rem; font-weight: 600;">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="statusConfirmForm" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="status" id="statusConfirmFormStatus">
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorMap = {
        success: { btn: 'btn-success', text: 'text-success', icon: '#198754' },
        warning: { btn: 'btn-warning', text: 'text-warning', icon: '#f59e0b' },
        danger:  { btn: 'btn-danger',  text: 'text-danger',  icon: '#dc3545' },
    };

    document.querySelectorAll('.status-confirm-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const action   = this.dataset.action;
            const status   = this.dataset.status;
            const name     = this.dataset.name;
            const color    = this.dataset.color;
            const icon     = this.dataset.icon;
            const c        = colorMap[color] || colorMap.success;

            document.getElementById('statusConfirmName').textContent  = name;
            document.getElementById('statusConfirmLabel').textContent = status;
            document.getElementById('statusConfirmIcon').innerHTML    =
                `<i class="${icon} ${c.text}"></i>`;

            const okBtn = document.getElementById('statusConfirmOkBtn');
            okBtn.className = `btn px-4 ${c.btn}`;
            okBtn.style.borderRadius = '8px';
            okBtn.style.fontSize    = '0.82rem';
            okBtn.style.fontWeight  = '600';
            okBtn.textContent = 'Yes, ' + status;

            okBtn.onclick = function() {
                const form = document.getElementById('statusConfirmForm');
                form.action = action;
                document.getElementById('statusConfirmFormStatus').value = status;
                form.submit();
            };

            const modal = new bootstrap.Modal(document.getElementById('statusConfirmModal'));
            modal.show();
        });
    });
});
</script>
@endonce
