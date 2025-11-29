@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-primary mb-4">Training Assignments</h1>
    @if(auth()->user()->user_type === 'hr')
        <a href="{{ route('hr.training_assignments.create') }}" class="btn btn-primary mb-3">Assign Employee</a>
    @endif
    <div class="card bg-white">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Program</th>
                        <th>Employee</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $assignment)
                        <tr>
                            <td>{{ $assignment->trainingProgram->name }}</td>
                            <td>{{ $assignment->employee->first_name }} {{ $assignment->employee->last_name }}</td>
                            <td>{{ ucfirst($assignment->status) }}</td>
                            <td>{{ $assignment->start_date->format('Y-m-d') }}</td>
                            <td>{{ $assignment->end_date->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route(auth()->user()->user_type . '.training_assignments.show', $assignment) }}" class="btn btn-info btn-sm">View</a>
                                @if(auth()->user()->user_type === 'hr')
                                    <a href="{{ route('hr.training_assignments.edit', $assignment) }}" class="btn btn-warning btn-sm">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection