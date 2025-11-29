@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-primary mb-4">Training Assignment Details</h1>
    <div class="card bg-white p-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Program:</strong> {{ $trainingAssignment->trainingProgram->name }}</p>
                    <p><strong>Employee:</strong> {{ $trainingAssignment->employee->first_name }} {{ $trainingAssignment->employee->last_name }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($trainingAssignment->status) }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Start Date:</strong> {{ $trainingAssignment->start_date->format('Y-m-d') }}</p>
                    <p><strong>End Date:</strong> {{ $trainingAssignment->end_date->format('Y-m-d') }}</p>
                    <p><strong>Instructor:</strong> {{ $trainingAssignment->trainingProgram->instructor->first_name }} {{ $trainingAssignment->trainingProgram->instructor->last_name }}</p>
                </div>
            </div>
            <h5 class="text-primary mt-4">Program Details</h5>
            <p><strong>Details:</strong> {{ $trainingAssignment->trainingProgram->details ?? 'N/A' }}</p>
            <p><strong>Available Days:</strong> {{ implode(', ', $trainingAssignment->trainingProgram->available_days) }}</p>
            <p><strong>Available Time:</strong> {{ $trainingAssignment->trainingProgram->available_time }}</p>
            <p><strong>Total Employees:</strong> {{ $trainingAssignment->trainingProgram->available_total_employees }}</p>
            @if($coTrainees->count())
                <h5 class="text-primary mt-4">Co-Trainees</h5>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coTrainees as $trainee)
                            <tr>
                                <td>{{ $trainee->employee->first_name }} {{ $trainee->employee->last_name }}</td>
                                <td>{{ ucfirst($trainee->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection