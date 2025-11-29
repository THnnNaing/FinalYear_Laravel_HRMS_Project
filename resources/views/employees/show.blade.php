@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-primary mb-4">Employee Details</h1>
    <div class="card bg-white p-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Name:</strong> {{ $employee->first_name }} {{ $employee->last_name }}</p>
                    <p><strong>Email:</strong> {{ $employee->email }}</p>
                    <p><strong>Phone Number:</strong> {{ $employee->phonenumber }}</p>
                    <p><strong>NRC:</strong> {{ $employee->nrc }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Date of Birth:</strong> {{ $employee->dob->format('Y-m-d') }}</p>
                    <p><strong>Address:</strong> {{ $employee->address }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($employee->status) }}</p>
                    <p><strong>Department:</strong> {{ $employee->department->name }}</p>
                    <p><strong>Designation:</strong> {{ $employee->designation->title }}</p>
                </div>
            </div>
            <div class="mt-3">
                @if(auth()->user()->user_type !== 'employee' || auth()->user()->employee_id === $employee->id)
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">Edit</a>
                @endif
                @if(auth()->user()->user_type === 'admin')
                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection