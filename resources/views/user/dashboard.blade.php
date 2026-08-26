@extends('layouts.mobile')

@section('content')
<div style="margin-bottom: 24px;">
    <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">Submit New Event</h2>
    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 4px;">Fill out the form below to add a new event to the calendar.</p>
</div>

<div class="card" style="padding: 20px;">
    @if(session('success'))
        <div class="alert-success" style="font-size: 0.9rem;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-danger" style="font-size: 0.9rem;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('events.store') }}">
        @csrf
        
        <div class="form-group" style="margin-bottom: 16px;">
            <label class="form-label">Event Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Design Area">
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="{{ old('location') }}" required placeholder="e.g. Alun-Alun Sidoarjo">
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label class="form-label">Event Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
        </div>

        <div class="row form-group">
            <div class="col-6">
                <label class="form-label">Branch</label>
                <select name="branch_id" id="branch_id" class="form-control" required>
                    <option value="" disabled selected>-- Select Branch --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6">
                <label class="form-label">Brand</label>
                <select name="brand_id" id="brand_id" class="form-control" required>
                    <option value="" disabled selected>-- Select Brand --</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Marcom In Charge</label>
            <select name="marcom_id" id="marcom_id" class="form-control" required>
                <option value="" disabled selected>-- Select Marcom --</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label class="form-label">Target Estimation</label>
            <input type="number" step="0.01" name="estimation" class="form-control" value="{{ old('estimation') }}" required placeholder="0">
        </div>
        

        <div class="form-group" style="margin-bottom: 24px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="is_regional" value="1" {{ old('is_regional') ? 'checked' : '' }} style="width: 18px; height: 18px;">
                <span style="font-weight: 500;">Mark as Regional Event</span>
            </label>
        </div>
            
        <div style="margin-top: 30px;">
            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 14px; font-size: 1rem;">Submit Event Request</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const branchSelect = document.getElementById('branch_id');
        const brandSelect = document.getElementById('brand_id');
        const marcomSelect = document.getElementById('marcom_id');
        
        // Save the old marcom_id value to auto-select if validation fails
        const oldMarcomId = '{{ old("marcom_id") }}';

        function fetchMarcoms(selectedMarcomId = null) {
            const branchId = branchSelect.value;
            const brandId = brandSelect.value;
            
            if (!branchId || !brandId) {
                marcomSelect.innerHTML = '<option value="" disabled selected>-- Select Marcom --</option>';
                return;
            }
            
            // Clear current options and show loading state
            marcomSelect.innerHTML = '<option value="" disabled selected>Loading...</option>';
            
            fetch(`/api/marcoms?branch_id=${branchId}&brand_id=${brandId}`)
                .then(response => response.json())
                .then(data => {
                    marcomSelect.innerHTML = '<option value="" disabled selected>-- Select Marcom --</option>';
                    
                    data.forEach(marcom => {
                        const option = document.createElement('option');
                        option.value = marcom.id;
                        option.textContent = marcom.name;
                        
                        if (selectedMarcomId && selectedMarcomId == marcom.id) {
                            option.selected = true;
                        }
                        
                        marcomSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching marcoms:', error);
                    marcomSelect.innerHTML = '<option value="" disabled selected>Error loading data</option>';
                });
        }

        // On change event for both branch and brand
        branchSelect.addEventListener('change', () => fetchMarcoms());
        brandSelect.addEventListener('change', () => fetchMarcoms());

        // Initialize on page load (useful if old('brand_id') and old('branch_id') exist)
        if (branchSelect.value && brandSelect.value) {
            fetchMarcoms(oldMarcomId);
        }
    });
</script>
@endsection
