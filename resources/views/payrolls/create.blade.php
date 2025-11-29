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
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background-color: #fff;
        font-size: 0.9rem;
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
        --bs-btn-hover-border-color: #4f46e5;
        --bs-btn-focus-shadow-rgb: 108, 117, 125;
        --bs-btn-active-color: #fff;
        --bs-btn-active-bg: #4f46e5;
        --bs-btn-active-border-color: #4f46e5;
        transition: all 0.2s ease-in-out;
    }

    .btn-outline-secondary-custom:hover {
        color: var(--bs-btn-hover-color) !important;
        background-color: var(--bs-btn-hover-bg) !important;
        border-color: var(--bs-btn-hover-border-color) !important;
    }
    
    /* Style for the nested Add/Remove buttons for Bonues/Deductions */
    .btn-bonus-add {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .btn-bonus-remove {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    /* Responsive Adjustments: Use 100% width on small screens for dynamic fields */
    @media (max-width: 767.98px) {
        .row.g-2 .col-md-5,
        .row.g-2 .col-md-4,
        .row.g-2 .col-md-3 {
            width: 100% !important;
        }
        .row.g-2 .col-md-3 {
            /* Fix spacing for the remove button on small screens */
            margin-top: 0.5rem;
            justify-content: flex-start !important;
        }
    }
</style>

<div class="container container-custom">
    <div class="row justify-content-center">
        {{-- Increased width for the form container to make inputs longer --}}
        <div class="col-xl-10 col-lg-10">

            {{-- Back Button --}}
            <div class="d-flex justify-content-start align-items-center mb-5">
                {{-- Assuming a route named 'hr.payrolls.index' for the payroll list --}}
                <a href="#" class="btn btn-outline-secondary-custom rounded-pill shadow-sm py-2 px-4">
                    <i class="bi bi-arrow-left me-2"></i> Back to Payrolls
                </a>
            </div>

            {{-- Form Container --}}
            <div class="form-section-panel">
                <h4 class="section-title">Create Payroll 💸</h4>

                <form action="{{ route('hr.payrolls.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        
                        {{-- Employee Select --}}
                        <div class="col-md-6 col-12">
                            <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select-modern @error('employee_id') is-invalid @enderror" id="employee_id" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->first_name }} {{ $employee->last_name }}</option>
                                @endforeach
                            </select>
                            @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Month Input --}}
                        <div class="col-md-6 col-12">
                            <label for="month" class="form-label">Payroll Month <span class="text-danger">*</span></label>
                            <input type="month" name="month" class="form-control-modern @error('month') is-invalid @enderror" id="month" value="{{ old('month') }}" required>
                            @error('month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Worked Days Input --}}
                        <div class="col-12">
                            <label for="worked_days" class="form-label">Worked Days (Non-Permanent Only)</label>
                            <input type="number" name="worked_days" class="form-control-modern @error('worked_days') is-invalid @enderror" id="worked_days" placeholder="e.g., 20" value="{{ old('worked_days') }}" min="0" max="31">
                            @error('worked_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="form-text text-muted">Leave blank for permanent employees.</small>
                        </div>
                    </div>
                    
                    {{-- Horizontal Divider --}}
                    <hr class="my-4">

                    {{-- Bonuses Section --}}
                    <h5 class="section-title" style="font-size: 1.25rem; border-bottom: none; margin-bottom: 1rem; padding-bottom: 0;">Bonuses (Additions)</h5>
                    <div class="mb-4">
                        <div id="bonuses">
                            {{-- Initial Bonus Entry --}}
                            <div class="bonus-entry mb-3">
                                <div class="row g-2 align-items-end">
                                    {{-- Adjusted column sizes for better spacing on large screens, still totalling 12 --}}
                                    <div class="col-lg-6 col-md-5 col-12">
                                        <label for="bonus_type_0" class="form-label">Bonus Type</label>
                                        <select name="bonuses[0][bonus_type_id]" class="form-select-modern" id="bonus_type_0">
                                            <option value="">Select Bonus Type</option>
                                            @foreach($bonusTypes as $bonusType)
                                                <option value="{{ $bonusType->id }}">{{ $bonusType->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('bonuses.0.bonus_type_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-12">
                                        <label for="bonus_amount_0" class="form-label">Amount</label>
                                        <input type="number" name="bonuses[0][amount]" class="form-control-modern" id="bonus_amount_0" placeholder="0.00" step="0.01" min="0">
                                        @error('bonuses.0.amount') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-12 d-flex justify-content-end">
                                        <button type="button" class="btn btn-danger btn-bonus-remove" style="display:none;">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-bonus-add rounded-pill px-4 mt-2" id="add-bonus">
                            <i class="bi bi-plus-circle me-1"></i> Add Bonus
                        </button>
                    </div>

                    {{-- Horizontal Divider --}}
                    <hr class="my-4">

                    {{-- Deductions Section --}}
                    <h5 class="section-title" style="font-size: 1.25rem; border-bottom: none; margin-bottom: 1rem; padding-bottom: 0;">Deductions</h5>
                    <div class="mb-4">
                        <div id="deductions">
                            {{-- Initial Deduction Entry --}}
                            <div class="deduction-entry mb-3">
                                <div class="row g-2 align-items-end">
                                    {{-- Adjusted column sizes for better spacing on large screens, still totalling 12 --}}
                                    <div class="col-lg-6 col-md-5 col-12">
                                        <label for="deduction_type_0" class="form-label">Deduction Type</label>
                                        <select name="deductions[0][deduction_type_id]" class="form-select-modern" id="deduction_type_0">
                                            <option value="">Select Deduction Type</option>
                                            @foreach($deductionTypes as $deductionType)
                                                <option value="{{ $deductionType->id }}">{{ $deductionType->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('deductions.0.deduction_type_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-12">
                                        <label for="deduction_amount_0" class="form-label">Amount</label>
                                        <input type="number" name="deductions[0][amount]" class="form-control-modern" id="deduction_amount_0" placeholder="0.00" step="0.01" min="0">
                                        @error('deductions.0.amount') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-12 d-flex justify-content-end">
                                        <button type="button" class="btn btn-danger btn-bonus-remove" style="display:none;">Remove</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-bonus-add rounded-pill px-4 mt-2" id="add-deduction">
                            <i class="bi bi-dash-circle me-1"></i> Add Deduction
                        </button>
                    </div>
                    
                    {{-- Submit Button --}}
                    <div class="d-flex justify-content-end pt-4 mt-3">
                        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow">
                            <i class="bi bi-calculator me-2"></i> Create Payroll
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    let bonusIndex = 1;
    const bonusTypesOptions = `@foreach($bonusTypes as $bonusType)<option value="{{ $bonusType->id }}">{{ $bonusType->name }}</option>@endforeach`;
    const deductionTypesOptions = `@foreach($deductionTypes as $deductionType)<option value="{{ $deductionType->id }}">{{ $deductionType->name }}</option>@endforeach`;

    // Selectors for initial elements
    const initialBonusRemove = document.querySelector('.bonus-entry:first-child .btn-bonus-remove');
    const initialDeductionRemove = document.querySelector('.deduction-entry:first-child .btn-bonus-remove');
    
    // Apply modern classes and hide remove button for the initial, often pre-filled row
    if (initialBonusRemove) initialBonusRemove.style.display = 'none';
    if (initialDeductionRemove) initialDeductionRemove.style.display = 'none';

    document.querySelectorAll('.bonus-entry:first-child select, .bonus-entry:first-child input, .deduction-entry:first-child select, .deduction-entry:first-child input').forEach(el => {
        if (el.tagName === 'SELECT') {
            el.classList.add('form-select-modern');
        } else if (el.tagName === 'INPUT') {
            el.classList.add('form-control-modern');
        }
    });

    document.getElementById('add-bonus').addEventListener('click', function() {
        const container = document.getElementById('bonuses');
        const entry = document.createElement('div');
        entry.className = 'bonus-entry mb-3';
        entry.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-lg-6 col-md-5 col-12">
                    <label for="bonus_type_${bonusIndex}" class="form-label">Bonus Type</label>
                    <select name="bonuses[${bonusIndex}][bonus_type_id]" class="form-select-modern" id="bonus_type_${bonusIndex}">
                        <option value="">Select Bonus Type</option>
                        ${bonusTypesOptions}
                    </select>
                </div>
                <div class="col-lg-4 col-md-4 col-12">
                    <label for="bonus_amount_${bonusIndex}" class="form-label">Amount</label>
                    <input type="number" name="bonuses[${bonusIndex}][amount]" class="form-control-modern" id="bonus_amount_${bonusIndex}" placeholder="0.00" step="0.01" min="0">
                </div>
                <div class="col-lg-2 col-md-3 col-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-danger btn-bonus-remove">Remove</button>
                </div>
            </div>
        `;
        container.appendChild(entry);
        bonusIndex++;
    });

    let deductionIndex = 1;
    document.getElementById('add-deduction').addEventListener('click', function() {
        const container = document.getElementById('deductions');
        const entry = document.createElement('div');
        entry.className = 'deduction-entry mb-3';
        entry.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-lg-6 col-md-5 col-12">
                    <label for="deduction_type_${deductionIndex}" class="form-label">Deduction Type</label>
                    <select name="deductions[${deductionIndex}][deduction_type_id]" class="form-select-modern" id="deduction_type_${deductionIndex}">
                        <option value="">Select Deduction Type</option>
                        ${deductionTypesOptions}
                    </select>
                </div>
                <div class="col-lg-4 col-md-4 col-12">
                    <label for="deduction_amount_${deductionIndex}" class="form-label">Amount</label>
                    <input type="number" name="deductions[${deductionIndex}][amount]" class="form-control-modern" id="deduction_amount_${deductionIndex}" placeholder="0.00" step="0.01" min="0">
                </div>
                <div class="col-lg-2 col-md-3 col-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-danger btn-bonus-remove">Remove</button>
                </div>
            </div>
        `;
        container.appendChild(entry);
        deductionIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-bonus-remove')) {
            const parentEntry = e.target.closest('.bonus-entry') || e.target.closest('.deduction-entry');
            if(parentEntry) {
                 parentEntry.remove();
            }
        }
    });
</script>
@endsection