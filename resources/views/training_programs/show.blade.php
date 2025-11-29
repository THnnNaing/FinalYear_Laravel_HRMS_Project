@extends('layouts.app')
@section('title', $trainingProgram->name)

@section('content')
<div class="container py-5">
    <a href="{{ route('training_programs.index') }}" class="btn btn-secondary mb-4">
         Back to Programs
    </a>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">{{ $trainingProgram->name }}</h3>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <strong>Instructor:</strong><br>
                    {{ $trainingProgram->instructor?->first_name }}
                    {{ $trainingProgram->instructor?->last_name }}
                    <small class="text-muted d-block">(ID: {{ $trainingProgram->instructor?->employee_id }})</small>
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong><br>
                    <span class="badge {{ $trainingProgram->status === 'available' ? 'bg-success' : ($trainingProgram->status === 'active' ? 'bg-primary' : 'bg-secondary') }}">
                        {{ ucfirst($trainingProgram->status) }}
                    </span>
                </div>
                <div class="col-md-6">
                    <strong>Available Days:</strong><br>
                    @foreach($trainingProgram->available_days ?? [] as $day)
                        <span class="badge bg-primary-subtle text-primary me-1">
                            {{ $day }}
                        </span>
                    @endforeach
                </div>
                <div class="col-md-6">
                    <strong>Time:</strong><br>
                    <span class="h5 text-primary">{{ $trainingProgram->available_time }}</span>
                </div>
                <div class="col-md-6">
                    <strong>Max Participants:</strong><br>
                    <h4 class="text-info mb-0">{{ $trainingProgram->available_total_employees }}</h4>
                </div>
                @if($trainingProgram->details)
                <div class="col-12">
                    <strong>Details:</strong><br>
                    <p class="mt-2">{{ nl2br(e($trainingProgram->details)) }}</p>
                </div>
                @endif
            </div>

            <hr class="my-4">

            <div class="text-end">
                <a href="{{ route('training_programs.edit', $trainingProgram) }}" class="btn btn-warning">
                    Edit Program
                </a>
            </div>
        </div>
    </div>
</div>
@endsection