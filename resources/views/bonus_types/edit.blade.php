@extends('layouts.app')

@section('content')
<style>
    /* 🎨 Aesthetic Custom Styles (Copied for consistency) */
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
        font-size: 1.5rem;
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
        {{-- Matching the container size from the create view --}}
        <div class="col-md-8 col-lg-7">

            {{-- Back Button --}}
            <div class="d-flex justify-content-start align-items-center mb-5">
                {{-- Assuming a route named 'bonus_types.index' for the bonus type list --}}
                <a href="{{ route('bonus_types.index') }}" class="btn btn-outline-secondary-custom rounded-pill shadow-sm py-2 px-4">
                    <i class="bi bi-arrow-left me-2"></i> Back to Bonus Types
                </a>
            </div>

            {{-- Form Container --}}
            <div class="form-section-panel">
                {{-- Title reflects the action and current record --}}
                <h4 class="section-title">Edit Bonus Type: {{ $bonusType->name }} ✏️</h4>

                <form action="{{ route('bonus_types.update', $bonusType) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        
                        {{-- Bonus Type Name Input --}}
                        <div class="col-12">
                            <label for="name" class="form-label">Bonus Type Name <span class="text-danger">*</span></label>
                            {{-- Applied custom class and populated value with existing data/old input --}}
                            <input type="text" name="name" class="form-control-modern @error('name') is-invalid @enderror" id="name" placeholder="e.g., Annual Performance Bonus, Holiday Bonus" value="{{ old('name', $bonusType->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Description Textarea: Set to rows="2" for a cleaner, more compact look --}}
                        <div class="col-12">
                            <label for="description" class="form-label">Description (Optional)</label>
                            {{-- Applied custom class and populated value with existing data/old input --}}
                            <textarea name="description" class="form-control-modern @error('description') is-invalid @enderror" id="description" rows="2">{{ old('description', $bonusType->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="d-flex justify-content-end pt-4 mt-3">
                        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow">
                            <i class="bi bi-arrow-repeat me-2"></i> Update Bonus Type
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection