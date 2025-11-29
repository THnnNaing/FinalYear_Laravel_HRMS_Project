@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-primary mb-4">Edit Training Assignment</h1>
    <div class="card bg-white p-3">
        <div class="card-body">
            <form action="{{ route('hr.training_assignments.update', $trainingAssignment) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="pending" {{ $trainingAssignment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="started" {{ $trainingAssignment->status == 'started' ? 'selected' : '' }}>Started</option>
                        <option value="completed" {{ $trainingAssignment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $trainingAssignment->start_date }}" required>
                    @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $trainingAssignment->end_date }}" required>
                    @error('end_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection