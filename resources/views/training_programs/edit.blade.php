{{-- resources/views/training_programs/edit.blade.php --}}

@extends('layouts.app')
@section('title', 'Edit Training Program - ' . $trainingProgram->name)

@section('content')
{{-- Select2 CSS + Custom Styles (same as create) --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    :root {
        --primary-color: #4f46e5;
        --light-bg: #f5f7fa;
        --card-bg: #ffffff;
        --border-color: #e5e7eb;
    }
    .container-custom { padding: 20px 0; background: var(--light-bg); }
    .form-control-modern, .form-select-modern {
        border-radius: .5rem; border: 1px solid var(--border-color); padding: .6rem .8rem;
        font-size: .9rem; min-height: 40px; transition: all .3s ease;
    }
    .form-control-modern:focus, .form-select-modern:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(79,70,229,.15);
    }
    .form-section-panel {
        background: var(--card-bg); border: 1px solid var(--border-color);
        border-radius: .75rem; padding: 2rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,.05);
    }
    .section-title {
        font-size: 1.5rem; font-weight: 700; border-bottom: 1px solid var(--border-color);
        padding-bottom: 1rem; margin-bottom: 1.5rem;
    }
    .btn-outline-secondary-custom {
        transition: all .2s ease;
    }
    .btn-outline-secondary-custom:hover {
        background: var(--primary-color) !important; color: white !important; border-color: var(--primary-color) !important;
    }

    /* SELECT2 PERFECT MATCH WITH YOUR FORM STYLE */
    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--multiple {
        min-height: 40px !important;
        border: 1px solid var(--border-color) !important;
        border-radius: .5rem !important;
        padding: 0.2rem 0.8rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
        padding-left: 0.5rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        padding: 0.2rem 0.5rem;
    }
    .select2-container--default.select2-container--focus .select2-selection {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 2px rgba(79,70,229,.15) !important;
    }
</style>

<div class="container container-custom">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-9">

            <!-- Back Button -->
            <div class="mb-5">
                <a href="{{ route('training_programs.index') }}" class="btn btn-outline-secondary-custom rounded-pill shadow-sm py-2 px-4">
                    Back to Programs
                </a>
            </div>

            <!-- Edit Form -->
            <div class="form-section-panel">
                <h4 class="section-title">Edit Training Program: {{ $trainingProgram->name }}</h4>

                <form action="{{ route('training_programs.update', $trainingProgram) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row g-4">

                        <!-- Program Name -->
                        <div class="col-12">
                            <label class="form-label">Program Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control-modern @error('name') is-invalid @enderror"
                                   value="{{ old('name', $trainingProgram->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Details -->
                        <div class="col-12">
                            <label class="form-label">Details (Optional)</label>
                            <textarea name="details" class="form-control-modern" rows="3">{{ old('details', $trainingProgram->details) }}</textarea>
                        </div>

                        <!-- Instructor (AJAX Search) -->
                        <div class="col-md-6">
                            <label class="form-label">Instructor <span class="text-danger">*</span></label>
                            <select name="instructor_employee_id" class="form-select-modern instructor-select-ajax @error('instructor_employee_id') is-invalid @enderror" required>
                                <!-- Current instructor will be injected by JS -->
                            </select>
                            @error('instructor_employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Time -->
                        <div class="col-md-6">
                            <label class="form-label">Available Time <span class="text-danger">*</span></label>
                            <input type="text" name="available_time" class="form-control-modern"
                                   value="{{ old('available_time', $trainingProgram->available_time) }}" placeholder="e.g. 5:00pm - 7:00pm" required>
                        </div>

                        <!-- Available Days (Multi-Select) -->
                        <div class="col-md-6">
                            <label class="form-label">Available Days <span class="text-danger">*</span></label>
                            <select name="available_days[]" class="form-control-modern available-days-select" multiple required>
                                @php
                                    $savedDays = old('available_days');
                                    if (!$savedDays) {
                                        $savedDays = is_string($trainingProgram->available_days)
                                            ? json_decode($trainingProgram->available_days, true)
                                            : ($trainingProgram->available_days ?? []);
                                    }
                                @endphp
                                @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                    <option value="{{ $day }}"
                                        {{ is_array($savedDays) && in_array($day, $savedDays) ? 'selected' : '' }}>
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl (or Cmd) to select multiple</small>
                        </div>

                        <!-- Max Participants -->
                        <div class="col-md-6">
                            <label class="form-label">Max Participants <span class="text-danger">*</span></label>
                            <input type="number" name="available_total_employees" class="form-control-modern"
                                   value="{{ old('available_total_employees', $trainingProgram->available_total_employees) }}" min="1" required>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select-modern" required>
                                @php $status = old('status', $trainingProgram->status) @endphp
                                <option value="available" {{ $status == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="ended" {{ $status == 'ended' ? 'selected' : '' }}>Ended</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="text-end mt-5">
                        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow">
                            Update Program
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Instructor Select2 (AJAX)
    $('.instructor-select-ajax').select2({
        placeholder: 'Search instructor by name or ID',
        allowClear: true,
        width: '100%',
        ajax: {
            url: "{{ route('training_programs.search_employees') }}",
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data.results }),
            cache: true
        }
    });

    // Pre-select current instructor
    @if($trainingProgram->instructor)
        var currentInstructor = new Option(
            "{{ $trainingProgram->instructor->first_name }} {{ $trainingProgram->instructor->last_name }} ({{ $trainingProgram->instructor->employee_id }})",
            "{{ $trainingProgram->instructor->id }}",
            true, true
        );
        $('.instructor-select-ajax').append(currentInstructor).trigger('change');
    @endif

    // Days Select2
    $('.available-days-select').select2({
        placeholder: 'Select days',
        allowClear: true,
        width: '100%'
    });
});
</script>
@endsection