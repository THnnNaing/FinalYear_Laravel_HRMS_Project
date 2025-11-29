@extends('layouts.app')

@section('content')
<style>
    /* 🎨 Aesthetic Custom Styles (Copied from Create View for consistency) */
    :root {
        --primary-color: #4f46e5;
        /* A deep, clean violet/indigo */
        --light-bg: #f5f7fa;
        /* Very light background for contrast */
        --card-bg: #ffffff;
        --border-color: #e5e7eb;
    }

    /* General Layout Spacing */
    .container-custom {
        padding-top: 20px;
        padding-bottom: 20px;
        background-color: var(--light-bg);
    }

    /* Input Fields: Reduced width and size for a cleaner look */
    .form-control-modern,
    .form-select-modern {
        border-radius: 0.5rem;
        border: 1px solid var(--border-color);
        padding: 0.6rem 0.8rem;
        /* Slightly reduced vertical padding */
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background-color: #fff;
        font-size: 0.9rem;
        /* Slightly smaller font */
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

    /* Inner Section Panels */
    .form-section-panel {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 0.75rem;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .section-title {
        color: #1f2937;
        font-weight: 700;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }

    /* Custom Back Button Hover Effect */
    .btn-outline-secondary-custom {
        --bs-btn-color: #6c757d;
        --bs-btn-border-color: #6c757d;
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: #4f46e5;
        /* Primary color background on hover */
        --bs-btn-hover-border-color: #4f46e5;
        /* Primary color border on hover */
        --bs-btn-focus-shadow-rgb: 108, 117, 125;
        --bs-btn-active-color: #fff;
        --bs-btn-active-bg: #4f46e5;
        --bs-btn-active-border-color: #4f46e5;
        transition: all 0.2s ease-in-out;
    }

    /* Explicit hover rule to ensure older browsers or complex CSS structures apply the effect */
    .btn-outline-secondary-custom:hover {
        color: var(--bs-btn-hover-color) !important;
        background-color: var(--bs-btn-hover-bg) !important;
        border-color: var(--bs-btn-hover-border-color) !important;
    }
</style>

<div class="container container-custom">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">

            {{-- Header/Title --}}
            <h2 class="text-3xl font-weight-bold text-gray-800 mb-5 text-center">
                <i class="bi bi-pencil-square me-2 text-primary"></i> Edit Employee Record
            </h2>

            {{-- Back Button --}}
            <div class="d-flex justify-content-start align-items-center mb-5">
                {{-- Changed the route to the index page --}}
                <a href="{{ route('hr.employees.index') }}" class="btn btn-outline-secondary-custom rounded-pill shadow-sm py-2 px-4">
                    <i class="bi bi-arrow-left me-2"></i> Back to Employee List
                </a>
            </div>

            <form action="{{ route('employees.update', $employee) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- LEFT COLUMN: Personal Information --}}
                    <div class="col-lg-6">
                        <div class="form-section-panel">
                            <h4 class="section-title">Personal Information</h4>

                            <div class="row g-4">

                                {{-- First Name & Last Name (Side by Side) --}}
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control-modern @error('first_name') is-invalid @enderror" id="first_name" value="{{ old('first_name', $employee->first_name) }}" required>
                                    @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control-modern @error('last_name') is-invalid @enderror" id="last_name" value="{{ old('last_name', $employee->last_name) }}" required>
                                    @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Date of Birth & Phone Number (Side by Side) --}}
                                <div class="col-md-6">
                                    <label for="dob" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    {{-- Use the format method for date input value --}}
                                    <input type="date" name="dob" class="form-control-modern @error('dob') is-invalid @enderror" id="dob" value="{{ old('dob', $employee->dob->format('Y-m-d')) }}" required>
                                    @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phonenumber" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" name="phonenumber" class="form-control-modern @error('phonenumber') is-invalid @enderror" id="phonenumber" value="{{ old('phonenumber', $employee->phonenumber) }}" required>
                                    @error('phonenumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- NRC --}}
                                <div class="col-12">
                                    <label for="nrc" class="form-label">NRC (ID Number) <span class="text-danger">*</span></label>
                                    <input type="text" name="nrc" class="form-control-modern @error('nrc') is-invalid @enderror" id="nrc" placeholder="e.g., 12/ABC123456" value="{{ old('nrc', $employee->nrc) }}" required>
                                    @error('nrc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-12">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control-modern @error('email') is-invalid @enderror" id="email" value="{{ old('email', $employee->email) }}" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN: Employment Details & Address --}}
                    <div class="col-lg-6">

                        {{-- Address Panel --}}
                        <div class="form-section-panel">
                            <h4 class="section-title">Contact & Location</h4>
                            <div class="row g-4">
                                <div class="col-12">
                                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control-modern @error('address') is-invalid @enderror" id="address" rows="2" required>{{ old('address', $employee->address) }}</textarea>
                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Employment Details Panel --}}
                        <div class="form-section-panel">
                            <h4 class="section-title">Employment Details</h4>
                            <div class="row g-4">

                                {{-- Basic Salary (Retains col-md-6 width) --}}
                                <div class="col-md-6">
                                    <label for="basic_salary" class="form-label">Basic Salary <span class="text-danger">*</span></label>
                                    <input type="number" name="basic_salary" class="form-control-modern @error('basic_salary') is-invalid @enderror" id="basic_salary" value="{{ old('basic_salary', $employee->basic_salary) }}" step="0.01" required>
                                    @error('basic_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Status (Changed to col-md-6 so it stays beside Basic Salary, this section will look the same as before if you only change the next 2 options) --}}
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select form-control-modern @error('status') is-invalid @enderror" id="status" required>
                                        <option value="permanent" {{ old('status', $employee->status) == 'permanent' ? 'selected' : '' }}>Permanent</option>
                                        <option value="contracted" {{ old('status', $employee->status) == 'contracted' ? 'selected' : '' }}>Contracted</option>
                                        <option value="training" {{ old('status', $employee->status) == 'training' ? 'selected' : '' }}>Training</option>
                                        <option value="intern" {{ old('status', $employee->status) == 'intern' ? 'selected' : '' }}>Intern</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                {{-- Department (Changed to full width - col-12) --}}
                                <div class="col-12">
                                    <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                                    <select name="department_id" class="form-select form-control-modern @error('department_id') is-invalid @enderror" id="department_id" required>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Designation (Changed to full width - col-12) --}}
                                <div class="col-12">
                                    <label for="designation_id" class="form-label">Designation <span class="text-danger">*</span></label>
                                    <select name="designation_id" class="form-select form-control-modern @error('designation_id') is-invalid @enderror" id="designation_id" required>
                                        @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}" {{ old('designation_id', $employee->designation_id) == $designation->id ? 'selected' : '' }}>{{ $designation->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('designation_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Button (Changed text from "Update" to be more descriptive) --}}
                <div class="d-flex justify-content-end pt-4 mt-3">
                    <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow">
                        <i class="bi bi-floppy-fill me-2"></i> Update Employee Details
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection