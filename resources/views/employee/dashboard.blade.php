@extends('layouts.app')

@section('content')
<div class="container">
    <!-- <h1 class="text-primary mb-4">Employee Dashboard</h1> -->
    <div class="card bg-white p-3">
        <div class="card-body">
            <h5 class="card-title text-primary">Your Profile</h5>
            <p class="card-text">View or update your personal information.</p>
            @if(auth()->user()->employee)
                <a href="{{ route('employees.show', auth()->user()->employee) }}" class="btn btn-primary">View Profile</a>
            @else
                <p class="text-muted">No employee profile linked.</p>
            @endif
        </div>
    </div>
</div>
@endsection