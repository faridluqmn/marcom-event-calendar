@extends('layouts.app')

@section('content')
<div class="calendar-header">
    <div class="calendar-title">Regional Events</div>
</div>

<div class="card" style="padding: 24px;">
    <p style="color: var(--text-secondary); margin-bottom: 24px;">This list contains all major events that have been marked as Regional.</p>
    
    <form id="bulk-remove-form" action="{{ route('events.regional.remove') }}" method="POST">
        @csrf
        <div style="display: flex; justify-content: flex-end; margin-bottom: 16px;">
            <button type="submit" class="btn-primary" style="background-color: #ef4444; border-color: #ef4444; font-size: 0.85rem; padding: 8px 16px;">
                Remove Selected
            </button>
        </div>
        <table class="data-table display" id="regionalEventsTable" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">
                        <input type="checkbox" id="selectAll" style="cursor: pointer;">
                    </th>
                    <th>Event Date</th>
                    <th>Event Name</th>
                    <th>Branch</th>
                    <th>Brand</th>
                    <th>Marcom</th>
                    <th>Estimation</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($regionalEvents as $event)
                    <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" name="event_ids[]" value="{{ $event->id }}" class="event-checkbox" style="cursor: pointer;">
                        </td>
                        <td>{{ $event->start_date->format('d M Y') }}</td>
                        <td style="font-weight: 600;">{{ $event->name }}</td>
                        <td>{{ $event->branch->name }}</td>
                        <td>{{ $event->brand->name }}</td>
                        <td>{{ $event->marcom->name }}</td>
                        <td>{{ number_format($event->estimation, 0, ',', '.') }} pcs</td>
                        <td>
                            @if($event->result === null)
                                <span class="status-badge status-pending">Pending</span>
                                @if(Auth::user()->role === 'admin')
                                    <button type="button" onclick="promptResult({{ $event->id }}, '{{ addslashes($event->name) }}')" style="background: var(--event-green-bg); color: var(--event-green-text); border: 1px solid var(--event-green-text); border-radius: 4px; padding: 2px 8px; font-size: 0.75rem; cursor: pointer; font-weight: 600; margin-left: 8px;">Input Result</button>
                                @endif
                            @elseif($event->result >= $event->estimation)
                                <span class="status-badge status-achieved">Achieved</span>
                            @else
                                <span class="status-badge status-not-achieved">Not Achieved</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @if($regionalEvents->isEmpty())
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-secondary);">No regional events have been marked yet.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </form>
</div>

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
        $('#regionalEventsTable').DataTable({
            "order": [], // Disable initial sorting
            "pageLength": 10,
            "columnDefs": [
                { "orderable": false, "targets": 0 } // Disable sorting on checkbox column
            ],
            "language": {
                "search": "Filter events:"
            }
        });
    });

    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.event-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
        });
    });

    document.getElementById('bulk-remove-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Check if any checkbox is selected
        const selected = document.querySelectorAll('.event-checkbox:checked');
        if (selected.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No events selected',
                text: 'Please select at least one event to remove.',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to remove " + selected.length + " event(s) from the Regional tab.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, remove them!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

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
            confirmButtonColor: '#10b981',
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
</script>
</div>
@endsection
