@extends('layouts.app')

@section('content')
<div class="calendar-header">
    <div style="display: flex; align-items: center; gap: 16px;">
        @php
            $prevDate = $baseDate->copy();
            $nextDate = $baseDate->copy();
            if ($viewType == 'day') {
                $prevDate->subDay();
                $nextDate->addDay();
            } elseif ($viewType == 'week') {
                $prevDate->subWeek();
                $nextDate->addWeek();
            } elseif ($viewType == 'year') {
                $prevDate->subYear();
                $nextDate->addYear();
            } else {
                $prevDate->subMonth();
                $nextDate->addMonth();
            }
            $titleFormat = 'F, Y';
            if ($viewType == 'day') {
                $titleFormat = 'l, F d, Y';
            } elseif ($viewType == 'year') {
                $titleFormat = 'Y';
            } elseif ($viewType == 'week') {
                $titleFormat = 'M d - ' . $nextDate->copy()->subDay()->format('M d, Y');
            }
        @endphp
        <div class="view-toggles" style="padding: 2px;">
            <a href="{{ route(request()->route()->getName(), array_merge(request()->query(), ['date' => $prevDate->format('Y-m-d')])) }}" class="view-btn" style="text-decoration:none;">&lt;</a>
            <a href="{{ route(request()->route()->getName(), array_merge(request()->query(), ['date' => $nextDate->format('Y-m-d')])) }}" class="view-btn" style="text-decoration:none;">&gt;</a>
        </div>
        <div class="calendar-title" style="margin: 0;">{{ $viewType == 'week' ? $baseDate->copy()->startOfWeek()->format('M d') . ' - ' . $baseDate->copy()->endOfWeek()->format('M d, Y') : $baseDate->format($titleFormat) }}</div>
    </div>

    <!-- Center Badge Pill -->
    <div style="display: flex; align-items: center; justify-content: center;">
        @if(request()->routeIs('events.regional_calendar'))
            <span style="background: linear-gradient(135deg, rgba(231, 0, 127, 0.08) 0%, rgba(255, 212, 0, 0.12) 100%); color: #E7007F; border: 1px solid rgba(231, 0, 127, 0.25); font-size: 0.85rem; font-weight: 600; padding: 6px 18px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(231, 0, 127, 0.1);">
                🌐 Regional Calendar
            </span>
        @else
            <span style="background: #ffffff; color: var(--text-primary); border: 1px solid var(--border-color); font-size: 0.85rem; font-weight: 600; padding: 6px 18px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
                🏢 Branch Calendar
            </span>
        @endif
    </div>
    
    <div class="calendar-controls">
        <div class="view-toggles">
            <a href="{{ route(request()->route()->getName(), array_merge(request()->query(), ['view' => 'day'])) }}" class="view-btn {{ $viewType == 'day' ? 'active' : '' }}" style="text-decoration: none;">Day</a>
            <a href="{{ route(request()->route()->getName(), array_merge(request()->query(), ['view' => 'week'])) }}" class="view-btn {{ $viewType == 'week' ? 'active' : '' }}" style="text-decoration: none;">Week</a>
            <a href="{{ route(request()->route()->getName(), array_merge(request()->query(), ['view' => 'month'])) }}" class="view-btn {{ $viewType == 'month' ? 'active' : '' }}" style="text-decoration: none;">Month</a>
            <a href="{{ route(request()->route()->getName(), array_merge(request()->query(), ['view' => 'year'])) }}" class="view-btn {{ $viewType == 'year' ? 'active' : '' }}" style="text-decoration: none;">Year</a>
        </div>
        
        <!-- Custom Nested Dropdown Filter -->
        @if(!request()->routeIs('events.regional_calendar'))
        <div class="nested-dropdown" id="filterDropdownContainer">
            <button class="dropdown-toggle" onclick="toggleDropdown()" type="button">
                <span>Filter: {{ request('brand') ?? (request('branch') ?? 'All Events') }}</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            
            <div class="dropdown-menu" id="filterDropdownMenu">
                <a href="{{ route(request()->route()->getName(), ['date' => request('date')]) }}" style="text-decoration: none; color: inherit;">
                    <div class="dropdown-item">All Events</div>
                </a>
                <hr style="border-top: 1px solid var(--border-color); margin: 4px 0; border-bottom: none;">
                
                @foreach($hierarchy as $branchName => $brands)
                    <div class="dropdown-item">
                        <span>{{ $branchName }}</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                        
                        <div class="dropdown-submenu">
                            <a href="{{ route(request()->route()->getName(), ['date' => request('date'), 'branch' => $branchName]) }}" style="text-decoration: none; color: inherit;">
                                <div class="dropdown-item" style="font-weight: 600;">All {{ $branchName }}</div>
                            </a>
                            
                            @foreach($brands as $brandName => $marcomsList)
                                <div class="dropdown-item">
                                    <span>{{ $brandName }}</span>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                                    
                                    <div class="dropdown-submenu">
                                        <a href="{{ route(request()->route()->getName(), ['date' => request('date'), 'branch' => $branchName, 'brand' => $brandName]) }}" style="text-decoration: none; color: inherit;">
                                            <div class="dropdown-item" style="font-weight: 600;">All {{ $brandName }}</div>
                                        </a>
                                        @foreach($marcomsList as $marcomItem)
                                            <a href="{{ route(request()->route()->getName(), ['date' => request('date'), 'branch' => $branchName, 'brand' => $brandName, 'marcom_id' => $marcomItem['id']]) }}" style="text-decoration: none; color: inherit;">
                                                <div class="dropdown-item">{{ $marcomItem['name'] }}</div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@if($viewType == 'year')
    <div class="year-wrapper">
        <div class="year-grid">
            @for($i = 1; $i <= 12; $i++)
                @php
                    $monthDate = \Carbon\Carbon::createFromDate($baseDate->year, $i, 1);
                    $monthEventsCount = $events->filter(function($event) use ($monthDate) {
                        return $event->start_date->format('Y-m') === $monthDate->format('Y-m');
                    })->count();
                @endphp
                <a href="{{ route(request()->route()->getName(), array_merge(request()->query(), ['view' => 'month', 'date' => $monthDate->format('Y-m-d')])) }}" class="month-card">
                    <div class="month-name">{{ $monthDate->format('F') }}</div>
                    <div class="month-count">{{ $monthEventsCount }} Event{{ $monthEventsCount != 1 ? 's' : '' }}</div>
                </a>
            @endfor
        </div>
    </div>
@elseif($viewType == 'day')
    <div class="calendar-wrapper day-view">
        <div class="day-list">
            @if($events->isEmpty())
                <div style="padding: 40px; text-align: center; color: var(--text-secondary);">
                    No events scheduled for this day.
                </div>
            @else
                @foreach($events as $event)
                    @php
                        $branchColors = [
                            'Sidoarjo' => ['bg' => '#dbeafe', 'text' => '#1e3a8a'], // Blue
                            'Madura' => ['bg' => '#d1fae5', 'text' => '#064e3b'], // Emerald
                            'Tuban Lamongan' => ['bg' => '#fee2e2', 'text' => '#7f1d1d'], // Red
                            'Jember' => ['bg' => '#ffedd5', 'text' => '#7c2d12'], // Orange
                            'Probolinggo' => ['bg' => '#f3e8ff', 'text' => '#581c87'], // Purple
                            'Surabaya' => ['bg' => '#fae8ff', 'text' => '#701a75'], // Fuchsia
                            'Malang' => ['bg' => '#22212aff', 'text' => '#E1F5FE'], // Biru Langit
                            'Madiun' => ['bg' => '#cffafe', 'text' => '#164e63'], // Cyan
                            'Jombang' => ['bg' => '#fef9c3', 'text' => '#713f12'], // Yellow
                            'Kediri' => ['bg' => '#fce7f3', 'text' => '#831843'], // Pink
                            'Gresik Mojokerto' => ['bg' => '#e2e8f0', 'text' => '#0f172a'], // Slate
                            'Tulungagung' => ['bg' => '#dcfce7', 'text' => '#14532d'], // Green
                        ];
                        $branchColor = $branchColors[$event->branch->name] ?? ['bg' => '#f1f5f9', 'text' => '#334155'];
                        $brandBorderColor = $event->brand->name === '3ID' ? '#a855f7' : ($event->brand->name === 'IM3' ? '#eab308' : '#cbd5e1');
                    @endphp
                    <div class="day-event-card" style="background-color: {{ $branchColor['bg'] }}; border-left-color: {{ $brandBorderColor }}; color: {{ $branchColor['text'] }};">
                        <div class="day-event-time" style="color: {{ $branchColor['text'] }}; opacity: 0.9;">
                            {{ $event->start_date->format('H:i') }}
                            @if($event->end_date)
                                - {{ $event->end_date->format('H:i') }}
                            @endif
                        </div>
                        <div class="day-event-details" style="display: flex; justify-content: space-between; align-items: flex-start; width: 100%;">
                            <div style="flex-grow: 1; cursor: pointer;" onclick="openEventModal({{ $event->id }}, '{{ addslashes($event->name) }}', '{{ $event->start_date->format('d M Y') }}', '{{ addslashes($event->location ?? '-') }}', '{{ $event->branch->name }}', '{{ $event->brand->name }}', '{{ $event->marcom->name }}', {{ $event->estimation }}, {{ $event->result ?? 'null' }}, {{ $event->is_regional ? 'true' : 'false' }}, {{ Auth::user()->role === 'admin' ? 'true' : 'false' }})">
                                <h4>
                                    {{ $event->name }}
                                    @if($event->is_regional)
                                        <span class="status-badge status-achieved" style="font-size: 0.65rem; margin-left: 8px;">Regional</span>
                                    @endif
                                </h4>
                                <p><strong>Marcom:</strong> {{ $event->marcom->name }}</p>
                                <p><strong>Branch:</strong> {{ $event->branch->name }} | <strong>Brand:</strong> {{ $event->brand->name }}</p>
                                <p>
                                    <strong>Est:</strong> {{ number_format($event->estimation, 0, ',', '.') }} pcs
                                    @if($event->result !== null)
                                        | <strong style="color: var(--event-green-text);">Result:</strong> {{ number_format($event->result, 0, ',', '.') }} pcs
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@else
    {{-- Month and Week View use similar grid structure --}}
    <div class="calendar-wrapper {{ $viewType == 'week' ? 'week-view' : '' }}">
        <!-- Days of the week header -->
        <div class="calendar-days-header">
            @php
                $dayNames = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'];
            @endphp
            @foreach($dayNames as $index => $dayName)
                <div class="day-header">
                    <span class="day-name">{{ $dayName }}</span>
                    @if($viewType == 'week')
                        <div class="week-date-number">{{ $days[$index]->format('d') }}</div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Calendar Grid Body -->
        <div class="calendar-grid {{ $viewType == 'week' ? 'calendar-grid-week' : '' }}">
            @foreach($days as $day)
                @php
                    $isCurrentMonth = $day->format('m') === $baseDate->format('m');
                    $isSunday = $day->isSunday();
                @endphp
                <div class="calendar-day-cell {{ (!$isCurrentMonth && $viewType == 'month') ? 'out-of-month' : '' }}">
                    @if($viewType == 'month')
                        <div class="day-number {{ $isSunday ? 'text-red' : '' }}">{{ $day->format('d') }}</div>
                    @endif
                    
                    <div class="events-container">
                        @foreach($events as $event)
                            @if($event->start_date->isSameDay($day))
                                @php
                                    $branchColors = [
                                        'Sidoarjo' => ['bg' => '#dbeafe', 'text' => '#1e3a8a'],
                                        'Madura' => ['bg' => '#d1fae5', 'text' => '#064e3b'],
                                        'Tuban Lamongan' => ['bg' => '#fee2e2', 'text' => '#7f1d1d'],
                                        'Jember' => ['bg' => '#ffedd5', 'text' => '#7c2d12'],
                                        'Probolinggo' => ['bg' => '#f3e8ff', 'text' => '#581c87'],
                                        'Surabaya' => ['bg' => '#fae8ff', 'text' => '#701a75'],
                                        'Malang' => ['bg' => '#e0e7ff', 'text' => '#312e81'],
                                        'Madiun' => ['bg' => '#cffafe', 'text' => '#164e63'],
                                        'Jombang' => ['bg' => '#fef9c3', 'text' => '#713f12'],
                                        'Kediri' => ['bg' => '#fce7f3', 'text' => '#831843'],
                                        'Gresik Mojokerto' => ['bg' => '#e2e8f0', 'text' => '#0f172a'],
                                        'Tulungagung' => ['bg' => '#dcfce7', 'text' => '#14532d'],
                                    ];
                                    $branchColor = $branchColors[$event->branch->name] ?? ['bg' => '#f1f5f9', 'text' => '#334155'];
                                    $brandBorderColor = $event->brand->name === '3ID' ? '#E7007F' : ($event->brand->name === 'IM3' ? '#FFD400' : '#cbd5e1');
                                @endphp
                                <div class="event-block" title="{{ $event->name }} ({{ $event->start_date->format('H:i') }})" style="background-color: {{ $branchColor['bg'] }}; border-left-color: {{ $brandBorderColor }}; color: {{ $branchColor['text'] }}; cursor: pointer;" onclick="openEventModal({{ $event->id }}, '{{ addslashes($event->name) }}', '{{ $event->start_date->format('d M Y') }}', '{{ addslashes($event->location ?? '-') }}', '{{ $event->branch->name }}', '{{ $event->brand->name }}', '{{ $event->marcom->name }}', {{ $event->estimation }}, {{ $event->result ?? 'null' }}, {{ $event->is_regional ? 'true' : 'false' }}, {{ Auth::user()->role === 'admin' ? 'true' : 'false' }})">
                                    <div class="event-title" style="display: flex; align-items: center; justify-content: space-between;">
                                        <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $event->name }}
                                        </div>
                                        <form action="{{ route('events.regional', $event->id) }}" method="POST" style="margin: 0; padding: 0;" title="Toggle Regional" onclick="event.stopPropagation()">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer; color: {{ $event->is_regional ? '#FFD400' : 'rgba(0,0,0,0.15)' }}; font-size: 0.95rem; line-height: 1;">
                                                ★
                                            </button>
                                        </form>
                                    </div>
                                    @if($viewType == 'week')
                                        <div class="event-meta" style="font-size: 0.7rem; margin-top: 4px; opacity: 0.8;">
                                            {{ $event->marcom->name }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<script>
    function openEventModal(eventId, name, date, location, branch, brand, marcom, estimation, result, isRegional, isAdmin) {
        // Format numbers
        const formatRp = (num) => new Intl.NumberFormat('id-ID').format(num) + ' pcs';
        const formattedEst = formatRp(estimation);
        const formattedRes = result !== null ? formatRp(result) : '<span style="color: #9ca3af; font-style: italic;">Not inputted yet</span>';
        
        let htmlContent = `
            <div style="text-align: left; padding: 10px;">
                <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 600; color: var(--text-secondary);">Date:</span> 
                    <span>${date}</span>
                </div>
                <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 600; color: var(--text-secondary);">Location:</span> 
                    <span>${location}</span>
                </div>
                <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 600; color: var(--text-secondary);">Branch & Brand:</span> 
                    <span>${branch} | ${brand}</span>
                </div>
                <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 600; color: var(--text-secondary);">Marcom:</span> 
                    <span>${marcom}</span>
                </div>
                <hr style="margin: 16px 0; border: none; border-top: 1px solid var(--border-color);">
                <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 600; color: var(--text-secondary);">Estimation:</span> 
                    <span style="font-size: 1.1rem; font-weight: 700; color: #b45309; background: rgba(255, 212, 0, 0.15); padding: 2px 8px; border-radius: 6px; border: 1px solid rgba(255, 212, 0, 0.35);">${formattedEst}</span>
                </div>
                <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 600; color: var(--text-secondary);">Actual Result:</span> 
                    <span style="font-size: 1.1rem; font-weight: 700; color: #E7007F; background: rgba(231, 0, 127, 0.08); padding: 2px 8px; border-radius: 6px; border: 1px solid rgba(231, 0, 127, 0.22);">${formattedRes}</span>
                </div>
        `;

        if (isAdmin && result === null) {
            const escapedName = name.replace(/'/g, "\\'");
            htmlContent += `
                <button onclick="promptResult(${eventId}, '${escapedName}')" class="btn-primary" style="width: 100%; justify-content: center; padding: 12px; font-size: 1rem; border-radius: 10px;">
                    Input Result Now
                </button>
            `;
        }
        
        htmlContent += `</div>`;

        Swal.fire({
            title: name,
            html: htmlContent,
            showCloseButton: true,
            showConfirmButton: false,
            width: '450px',
            customClass: {
                title: 'text-left font-bold text-xl'
            }
        });
    }

    function promptResult(eventId, eventName) {
        Swal.fire({
            title: 'Input Result for ' + eventName,
            input: 'number',
            inputLabel: 'Actual Result (pcs)',
            inputPlaceholder: 'e.g. 1500',
            inputAttributes: {
                min: 0,
                step: 'any'
            },
            showCancelButton: true,
            confirmButtonText: 'Save Result',
            confirmButtonColor: '#E7007F',
            showLoaderOnConfirm: true,
            preConfirm: (resultValue) => {
                if (!resultValue) {
                    Swal.showValidationMessage('Result is required');
                    return false;
                }
                
                // Create a form dynamically and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/events/${eventId}/result`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PATCH';
                form.appendChild(methodInput);
                
                const resultInput = document.createElement('input');
                resultInput.type = 'hidden';
                resultInput.name = 'result';
                resultInput.value = resultValue;
                form.appendChild(resultInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function toggleDropdown() {
        const menu = document.getElementById('filterDropdownMenu');
        menu.classList.toggle('show');
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-toggle') && !event.target.closest('.dropdown-toggle')) {
            const dropdowns = document.getElementsByClassName("dropdown-menu");
            for (let i = 0; i < dropdowns.length; i++) {
                const openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>
@endsection
