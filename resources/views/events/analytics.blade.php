@extends('layouts.app')

@section('content')
<div class="calendar-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div class="calendar-title">Dashboard Analytics ({{ date('F Y', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)) }})</div>
    <form method="GET" action="{{ route('events.analytics') }}" style="display: flex; gap: 10px; align-items: center;">
        <select name="month" class="form-control" onchange="this.form.submit()" style="width: 140px; background-color: white;">
            @for($m=1; $m<=12; ++$m)
                <option value="{{ sprintf('%02d', $m) }}" {{ $selectedMonth == sprintf('%02d', $m) ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                </option>
            @endfor
        </select>
        <select name="year" class="form-control" onchange="this.form.submit()" style="width: 100px; background-color: white;">
            @for($y = date('Y') - 2; $y <= date('Y') + 2; $y++)
                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endfor
        </select>
    </form>
</div>

<!-- 1. Top Summary Cards -->
<div class="analytics-grid">
    <div class="stat-card">
        <div class="stat-title">Total Events</div>
        <div class="stat-value">{{ number_format($totalEvents) }}</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-title">Total Estimation</div>
        <div class="stat-value" style="color: #b45309;">{{ number_format($totalEstimation, 0, ',', '.') }} pcs</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-title">Total Actual Result</div>
        <div class="stat-value" style="color: #E7007F;">{{ number_format($totalResult, 0, ',', '.') }} pcs</div>
    </div>
    
    <div class="stat-card highlight">
        <div class="stat-title">Achievement</div>
        <div class="stat-value">{{ number_format($achievementPercentage, 1) }}%</div>
    </div>
</div>

<!-- 2. Chart Area -->
<div class="section-title">Estimation vs Result per Branch</div>
<div class="chart-container">
    <canvas id="branchChart"></canvas>
</div>

<!-- 3. Recent Events Table -->
<div class="section-title">Recent Events</div>
<table class="data-table display" id="recentEventsTable" style="width:100%">
    <thead>
        <tr>
            <th>No.</th>
            <th>Event Name</th>
            <th>Period</th>
            <th>Branch</th>
            <th>Brand</th>
            <th>Marcom</th>
            <th>Estimation</th>
            <th>Result</th>
            <th>Achiev. (%)</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($recentEvents as $event)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $event->name }}</td>
                <td>{{ \Carbon\Carbon::parse($event->start_date)->format('F Y') }}</td>
                <td>{{ $event->branch->name }}</td>
                <td>{{ $event->brand->name }}</td>
                <td>{{ $event->marcom->name }}</td>
                <td>{{ number_format($event->estimation, 0, ',', '.') }} pcs</td>
                <td>
                    @if($event->result !== null)
                        {{ number_format($event->result, 0, ',', '.') }} pcs
                    @else
                        <span style="color: var(--text-secondary); font-style: italic;">-</span>
                    @endif
                </td>
                <td>
                    @if($event->result !== null && $event->estimation > 0)
                        {{ number_format(($event->result / $event->estimation) * 100, 1, ',', '.') }}%
                    @else
                        <span style="color: var(--text-secondary); font-style: italic;">-</span>
                    @endif
                </td>
                <td>
                    @if($event->result === null)
                        <span class="status-badge status-pending">Pending</span>
                    @elseif($event->result >= $event->estimation)
                        <span class="status-badge status-achieved">Achieved</span>
                    @else
                        <span class="status-badge status-not-achieved">Not Achieved</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    /* Ensure pointer cursor on headers and nice spacing */
    table.dataTable thead th {
        cursor: pointer;
        padding: 12px 10px;
    }
    table.dataTable tbody td {
        padding: 12px 10px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        var t = $('#recentEventsTable').DataTable({
            "order": [], // Disable initial sorting
            "pageLength": 10,
            "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
            }],
            "language": {
                "search": "Filter events:",
                "emptyTable": "No recent events found."
            }
        });

        // Make the 'No.' column truly dynamic and static when sorting
        t.on('order.dt search.dt', function () {
            let i = 1;
            t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                this.data(i++);
            });
        }).draw();
    });
</script>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('branchChart').getContext('2d');
        
        const rawData = @json($chartData);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: rawData.labels,
                datasets: [
                    {
                        label: 'Estimation (pcs)',
                        data: rawData.estimation,
                        backgroundColor: '#FFD400', // IM3 Yellow
                        borderRadius: 6
                    },
                    {
                        label: 'Actual Result (pcs)',
                        data: rawData.result,
                        backgroundColor: '#E7007F', // 3ID Magenta
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID').format(context.parsed.y) + ' pcs';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: 1000,
                        ticks: {
                            stepSize: 200,
                            callback: function(value, index, values) {
                                if (value >= 1000000) {
                                    return (value / 1000000) + 'M pcs';
                                }
                                return new Intl.NumberFormat('id-ID').format(value) + ' pcs';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
