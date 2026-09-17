@extends('layouts.app')

@section('content')
<div class="calendar-header" style="margin-bottom: 20px;">
    <div>
        <div class="calendar-title">Branch Events List</div>
        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 4px;">
            Comprehensive list of all branch events. Manage actual results, regional status, and event records.
        </p>
    </div>
</div>

<div class="card" style="padding: 24px;">
    <table class="data-table display" id="branchEventsTable" style="width:100%">
        <thead>
            <tr>
                <th style="width: 30px; text-align: center;">No.</th>
                <th>Event Date</th>
                <th>Event Name</th>
                <th>Branch</th>
                <th>Brand</th>
                <th>Marcom</th>
                <th>Estimation</th>
                <th>Actual Result</th>
                <th>Status</th>
                <th style="text-align: center; width: 60px;">Regional</th>
                <th style="text-align: center; width: 140px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $event->start_date->format('d M Y') }}</td>
                    <td>
                        <div style="font-weight: 600; color: var(--text-primary);">{{ $event->name }}</div>
                        <small style="color: var(--text-secondary);">📍 {{ $event->location }}</small>
                    </td>
                    <td>{{ $event->branch->name }}</td>
                    <td>
                        @if($event->brand->name === '3ID')
                            <span style="background: rgba(231, 0, 127, 0.08); color: #E7007F; border: 1px solid rgba(231, 0, 127, 0.22); padding: 2px 8px; border-radius: 6px; font-weight: 600; font-size: 0.8rem; display: inline-block;">3ID</span>
                        @elseif($event->brand->name === 'IM3')
                            <span style="background: rgba(255, 212, 0, 0.18); color: #854d0e; border: 1px solid rgba(255, 212, 0, 0.45); padding: 2px 8px; border-radius: 6px; font-weight: 600; font-size: 0.8rem; display: inline-block;">IM3</span>
                        @else
                            {{ $event->brand->name }}
                        @endif
                    </td>
                    <td>{{ $event->marcom->name }}</td>
                    <td style="font-weight: 500;">{{ number_format($event->estimation, 0, ',', '.') }} pcs</td>
                    <td>
                        @if($event->result !== null)
                            <span style="font-weight: 600; color: #E7007F;">{{ number_format($event->result, 0, ',', '.') }} pcs</span>
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
                    <td style="text-align: center;">
                        <form action="{{ route('events.regional', $event->id) }}" method="POST" style="margin: 0; padding: 0;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="background: none; border: none; padding: 4px; cursor: pointer; color: {{ $event->is_regional ? '#FFD400' : '#cbd5e1' }}; font-size: 1.25rem; line-height: 1;" title="{{ $event->is_regional ? 'Regional Event (Click to unmark)' : 'Mark as Regional' }}">
                                ★
                            </button>
                        </form>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: inline-flex; gap: 6px; align-items: center;">
                            @if($event->result === null)
                                <button type="button" onclick="promptResult({{ $event->id }}, '{{ addslashes($event->name) }}', '')" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;" title="Input Actual Result">
                                    <span>➕</span> Result
                                </button>
                            @else
                                <button type="button" onclick="promptResult({{ $event->id }}, '{{ addslashes($event->name) }}', {{ $event->result }})" style="background: #fdf2f8; color: #E7007F; border: 1px solid #fbcfe8; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;" title="Edit Actual Result">
                                    <span>✏️</span> Edit
                                </button>
                            @endif

                            <button type="button" onclick="confirmDeleteEvent({{ $event->id }}, '{{ addslashes($event->name) }}')" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;" title="Delete Event">
                                <span>🗑️</span>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
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
        var t = $('#branchEventsTable').DataTable({
            "order": [], // Disable initial sorting
            "pageLength": 10,
            "columnDefs": [
                { "searchable": false, "orderable": false, "targets": [0, 9, 10] }
            ],
            "language": {
                "search": "Filter events:",
                "emptyTable": "No branch events found."
            }
        });

        // Dynamic 'No.' column on sort/search
        t.on('order.dt search.dt', function () {
            let i = 1;
            t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                this.data(i++);
            });
        }).draw();
    });

    function promptResult(eventId, eventName, currentResult) {
        const isEditing = currentResult !== '' && currentResult !== null && currentResult !== undefined;
        Swal.fire({
            title: (isEditing ? 'Edit Result: ' : 'Input Result: ') + eventName,
            input: 'number',
            inputValue: isEditing ? currentResult : '',
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
                if (resultValue === '' || resultValue === null) {
                    Swal.showValidationMessage('Actual Result is required');
                    return false;
                }
                
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

    function confirmDeleteEvent(eventId, eventName) {
        Swal.fire({
            title: 'Delete Event?',
            text: `Are you sure you want to delete "${eventName}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/events/${eventId}`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection
