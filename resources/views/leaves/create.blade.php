@extends('layouts.app')

@section('content')

{{-- Add Select2 CSS and Custom Styles --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    :root {
        --primary-color: #4f46e5;
        --light-bg: #f5f7fa;
        --card-bg: #ffffff;
        --border-color: #e5e7eb;
    }

    .container-custom {
        padding-top: 20px;
        padding-bottom: 20px;
        background-color: var(--light-bg);
    }

    .form-control-modern,
    .form-select-modern {
        border-radius: 0.5rem;
        border: 1px solid var(--border-color);
        padding: 0.6rem 0.8rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background-color: #fff;
        font-size: 0.9rem;
        min-height: 40px;
    }

    .form-control-modern:focus,
    .form-select-modern:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.15);
        background-color: #fff;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.4rem;
        font-size: 0.9rem;
    }

    .form-section-panel {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 0.75rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
    }

    .section-title {
        color: #1f2937;
        font-weight: 700;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
    }

    .select2-container--default .select2-selection--single {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        height: 40px;
        padding: 0.375rem 0.75rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
        padding-left: 0;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }
</style>

<div class="container container-custom">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
            <div class="form-section-panel">
                <h4 class="section-title">Request Leave 🏖️</h4>

                <form action="{{ route(auth()->user()->user_type === 'hr' ? 'hr.leaves.store' : 'employee.leaves.store') }}" method="POST">
                    @csrf

                    {{-- Employee Search Box (Only show for HR users) --}}
                    @if(auth()->user()->user_type === 'hr')
                    <div class="mb-4">
                            <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select-modern employee-select-ajax @error('employee_id') is-invalid @enderror" id="employee_id" required>
                                @if(old('employee_id'))
                                    <option value="{{ old('employee_id') }}" selected>Employee ID {{ old('employee_id') }} (Name to be loaded by Select2)</option>
                                @else
                                    <option value="" disabled selected>Search for an Employee</option>
                                @endif
                            </select>
                            @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    @endif

                    {{-- Leave Type Field --}}
                    <div class="mb-4">
                        <label for="leave_type_id" class="form-label">Leave Type <span class="text-danger">*</span></label>
                        <select name="leave_type_id" class="form-select-modern @error('leave_type_id') is-invalid @enderror" id="leave_type_id" required>
                            <option value="" disabled selected>Select a leave type</option>
                            @foreach($leaveTypes as $leaveType)
                            <option value="{{ $leaveType->id }}" {{ old('leave_type_id') == $leaveType->id ? 'selected' : '' }}>
                                {{ $leaveType->name }} ({{ $leaveType->is_paid ? 'Paid' : 'Unpaid' }})
                            </option>
                            @endforeach
                        </select>
                        @error('leave_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-4">
                        {{-- Start Date Field --}}
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control-modern @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                            @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- End Date Field --}}
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control-modern @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                            @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Reason Field --}}
                    <div class="mb-4 mt-4">
                        <label for="reason" class="form-label">Reason <span class="text-muted">(Optional)</span></label>
                        <textarea name="reason" class="form-control-modern @error('reason') is-invalid @enderror" rows="3" placeholder="Briefly explain the reason for your leave...">{{ old('reason') }}</textarea>
                        @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end pt-3">
                        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow">
                            <i class="bi bi-calendar-plus me-2"></i> Submit Leave Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Add Dependencies and Initialization Scripts HERE --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
            // 1. Employee Search Box (AJAX)
    $('.employee-select-ajax').select2({
        placeholder: '',
        dropdownParent: $('.employee-select-ajax').parent(), 
        ajax: {
            // Ensure this route is correctly defined in web.php
            url: "{{ route('hr.leaves.search_employees') }}", 
            dataType: 'json',
            delay: 250, 
            data: function (params) {
                return {
                    q: params.term // search term
                };
            },
            processResults: function (data) {
                return {
                    results: data.results // Assuming your controller returns {'results': [...]}
                };
            },
            cache: true
        }
    });
    });
</script>
@endsection