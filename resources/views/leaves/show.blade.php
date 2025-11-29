@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2>Leave Request Details</h2>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Employee:</strong>
                    <p>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Leave Type:</strong>
                    <p>{{ $leave->leaveType->name }} ({{ $leave->leaveType->is_paid ? 'Paid' : 'Unpaid' }})</p>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Start Date:</strong>
                    <p>{{ $leave->start_date->format('Y-m-d') }}</p>
                </div>
                <div class="col-md-6">
                    <strong>End Date:</strong>
                    <p>{{ $leave->end_date->format('Y-m-d') }}</p>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Status:</strong>
                    <p>
                        <span class="badge 
                            @if($leave->status === 'approved') bg-success 
                            @elseif($leave->status === 'declined') bg-danger 
                            @else bg-warning text-dark @endif">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </p>
                </div>
                @if($leave->status !== 'pending')
                <div class="col-md-6">
                    <strong>Processed By:</strong>
                    <p>{{ $leave->approvedBy->name ?? 'System' }}</p>
                </div>
                @endif
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <strong>Reason:</strong>
                    <p>{{ $leave->reason ?? 'No reason provided' }}</p>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ 
                    auth()->user()->user_type === 'hr' 
                        ? route('hr.leaves.index') 
                        : route('employee.leaves.index') 
                }}" class="btn btn-secondary">Back to List</a>
                
                @if(auth()->user()->user_type === 'hr' && $leave->status === 'pending')
                    <form action="{{ route('hr.leaves.approve', $leave) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                    <form action="{{ route('hr.leaves.decline', $leave) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Decline</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection