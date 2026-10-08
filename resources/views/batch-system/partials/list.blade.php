<div class="row g-4">
    @forelse($batches as $batch)
        @php
            $today = \Carbon\Carbon::now()->format('Y-m-d');
            $todaysSchedule = $batch->schedules()->whereDate('date', $today)->first();
            $meetingLink = $todaysSchedule ? $todaysSchedule->meeting_link : ($batch->meeting_link ?? '');
            
            $progress = 0;
            if ($batch->start_date && \Carbon\Carbon::parse($batch->start_date)->isPast()) {
                $progress = 40; // Mock progress
            }
            
            $icon = '<i class="fas fa-book text-primary"></i>';
            $borderColor = '';
            if (stripos($batch->course->title ?? '', 'Full Stack') !== false) {
                $icon = '<i class="fas fa-bolt text-warning"></i>';
                $borderColor = 'border-top-blue';
            }
        @endphp
        
        <div class="col-12 col-md-6 col-xl-4 batch-item course-{{ $batch->course_id }}">
            <div class="mockup-batch-card {{ $borderColor }}">
                <div class="mockup-batch-id">{{ $batch->batch_code }}</div>
                
                <div class="mockup-course-title">
                    {!! $icon !!} {{ $batch->course->title ?? 'Unknown Course' }}
                </div>
                
                <div class="mockup-batch-type">
                    {{ $batch->batch_type ?? 'Weekday' }}
                </div>
                
                <div class="mockup-progress-wrapper mt-auto">
                    <div class="mockup-progress-track">
                        <div class="mockup-progress-fill" style="width: {{ $progress }}%;"></div>
                    </div>
                    <div class="mockup-progress-labels">
                        <span>{{ $batch->start_date ? date('M Y', strtotime($batch->start_date)) : 'N/A' }}</span>
                        <span class="mockup-progress-center">{{ $progress }}% done</span>
                        <span>{{ $batch->end_date ? date('M Y', strtotime($batch->end_date)) : 'N/A' }}</span>
                    </div>
                </div>
                
                <div class="mockup-stats-row">
                    <div class="mockup-stat-item">
                        <div class="mockup-stat-val">{{ $batch->joined_student_count ?? 0 }}</div>
                        <div class="mockup-stat-label">Students</div>
                    </div>
                    <div class="mockup-stat-item">
                        <div class="mockup-stat-val">{{ $progress }}%</div>
                        <div class="mockup-stat-label">Progress</div>
                    </div>
                    <div class="mockup-stat-item">
                        <div class="mockup-stat-val">{{ stripos($batch->batch_type, 'weekend') !== false ? '2d' : '5d' }}</div>
                        <div class="mockup-stat-label">Days/Wk</div>
                    </div>
                </div>
                
                <button class="mockup-btn-connect" onclick="openConnectModal('{{ $batch->batch_code }}', '{{ addslashes($batch->course->title ?? 'Unknown Course') }}', '{{ $batch->batch_type }}', '{{ $meetingLink }}')">
                    <i class="fas fa-video"></i> Connect
                </button>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="d-flex align-items-center gap-2 p-3" style="border-radius: 12px; background: #eff6ff; color: #2563eb; font-weight: 600;">
                <i class="fas fa-info-circle fs-5"></i>
                <span>No batches found.</span>
            </div>
        </div>
    @endforelse
</div>
