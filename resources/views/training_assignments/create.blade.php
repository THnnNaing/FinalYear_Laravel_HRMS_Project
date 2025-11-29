@extends('layouts.app')

@section('content')
<style>
    /* 🎨 Aesthetic Custom Styles (Reusable from Payroll Form) */
    :root {
        --primary-color: #4f46e5; /* A deep, clean violet/indigo */
        --light-bg: #f5f7fa; /* Very light background for contrast */
        --card-bg: #ffffff;
        --border-color: #e5e7eb;
        --success-color: #10b981;
    }

    /* General Layout Spacing */
    .container-custom {
        padding-top: 20px;
        padding-bottom: 20px;
        background-color: var(--light-bg);
    }

    /* Input Fields */
    .form-control-modern,
    .form-select-modern {
        border-radius: 0.5rem;
        border: 1px solid var(--border-color);
        padding: 0.6rem 0.8rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background-color: #fff;
        font-size: 0.95rem; /* Slightly larger for better readability */
        width: 100%; /* Ensures full responsiveness */
    }

    .form-control-modern:focus,
    .form-select-modern:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.15);
        background-color: #fff;
    }
    
    .form-control-modern.is-invalid,
    .form-select-modern.is-invalid {
        border-color: #dc3545;
        padding-right: 2.25rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem 1rem;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.4rem;
        font-size: 0.9rem;
    }

    /* Inner Section Panels */
    .form-section-panel {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 1rem; /* Slightly more rounded */
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .section-title {
        color: var(--primary-color);
        font-weight: 700;
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 1rem;
        margin-bottom: 2rem;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
    }

    /* Custom Back Button Hover Effect */
    .btn-outline-secondary-custom {
        --bs-btn-color: #6c757d;
        --bs-btn-border-color: #6c757d;
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: var(--primary-color);
        --bs-btn-hover-border-color: var(--primary-color);
        transition: all 0.2s ease-in-out;
    }

    .btn-submit-custom {
        background-color: var(--success-color);
        border-color: var(--success-color);
        transition: background-color 0.2s ease-in-out;
    }
    .btn-submit-custom:hover {
        background-color: #059669; /* Darker green on hover */
        border-color: #059669;
    }

</style>

<div class="container container-custom">
    <div class="row justify-content-center">
        {{-- Increased width for the form container --}}
        <div class="col-xl-9 col-lg-10 col-md-12">

            {{-- Back Button --}}
            <div class="d-flex justify-content-start align-items-center mb-5">
                {{-- Assuming a route named 'hr.training_assignments.index' for the assignments list --}}
                <a href="#" class="btn btn-outline-secondary-custom rounded-pill shadow-sm py-2 px-4">
                    <i class="bi bi-arrow-left me-2"></i> Back to Assignments
                </a>
            </div>

            {{-- Form Container --}}
            <div class="form-section-panel">
                <h4 class="section-title">
                    <i class="bi bi-person-workspace me-3"></i> Assign Employee to Training
                </h4>

                <form action="{{ route('hr.training_assignments.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        
                        {{-- Training Program Select --}}
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="training_program_id" class="form-label">Training Program <span class="text-danger">*</span></label>
                                <select name="training_program_id" 
                                    class="form-select-modern @error('training_program_id') is-invalid @enderror" 
                                    id="training_program_id" 
                                    required>
                                    <option value="">Select Program</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->id }}" {{ old('training_program_id') == $program->id ? 'selected' : '' }}>
                                            {{ $program->name }} ({{ $program->instructor->first_name }} {{ $program->instructor->last_name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('training_program_id') 
                                    <div class="invalid-feedback d-block">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        {{-- Employee Select --}}
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                                <select name="employee_id" 
                                    class="form-select-modern @error('employee_id') is-invalid @enderror" 
                                    id="employee_id" 
                                    required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id') 
                                    <div class="invalid-feedback d-block">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>
                        
                        {{-- Status Select --}}
                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" 
                                    class="form-select-modern @error('status') is-invalid @enderror" 
                                    id="status" 
                                    required>
                                    <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="started" {{ old('status') == 'started' ? 'selected' : '' }}>Started</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                @error('status') 
                                    <div class="invalid-feedback d-block">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        {{-- Start Date Input --}}
                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                    name="start_date" 
                                    class="form-control-modern @error('start_date') is-invalid @enderror" 
                                    id="start_date"
                                    value="{{ old('start_date') }}" 
                                    required>
                                @error('start_date') 
                                    <div class="invalid-feedback d-block">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        {{-- End Date Input --}}
                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                    name="end_date" 
                                    class="form-control-modern @error('end_date') is-invalid @enderror" 
                                    id="end_date"
                                    value="{{ old('end_date') }}" 
                                    required>
                                @error('end_date') 
                                    <div class="invalid-feedback d-block">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    {{-- Submit Button --}}
                    <div class="d-flex justify-content-end pt-4 mt-3">
                        <button type="submit" class="btn btn-submit-custom btn-lg px-5 rounded-pill shadow">
                            <i class="bi bi-check-circle-fill me-2"></i> Assign Employee
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
