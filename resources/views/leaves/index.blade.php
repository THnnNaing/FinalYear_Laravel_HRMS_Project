@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-md-0">Leave Requests</h1>
        @if(auth()->user()->user_type === 'hr')
            <a href="{{ route('hr.leaves.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> <span>Request Leave</span>
            </a>
        @elseif(auth()->user()->user_type === 'employee')
            <a href="{{ route('employee.leaves.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> <span>Request Leave</span>
            </a>
        @endif
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle text-nowrap">
                    <thead>
                        <tr>
                            <th class="ps-4">Employee</th>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Duration (Days)</th>
                            <th>Status</th>
                            {{-- TH for Actions --}}
                            <th class="text-end pe-4">Actions</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <td class="ps-4" data-label="Employee">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                                <td data-label="Leave Type">
                                    {{ $leave->leaveType->name }} 
                                    <span class="badge bg-light text-muted ms-1">{{ $leave->leaveType->is_paid ? 'Paid' : 'Unpaid' }}</span>
                                </td>
                                <td data-label="Start Date">{{ $leave->start_date->format('Y-m-d') }}</td>
                                <td data-label="End Date">{{ $leave->end_date->format('Y-m-d') }}</td>
                                <td data-label="Duration">
                                    {{ $leave->start_date->diffInDays($leave->end_date) + 1 }}
                                </td>
                                <td data-label="Status">
                                    <span class="badge 
                                        @if($leave->status === 'approved') bg-success 
                                        @elseif($leave->status === 'declined') bg-danger 
                                        @else bg-warning text-dark @endif">
                                        {{ ucfirst($leave->status) }}
                                    </span>
                                </td>
                                <td data-label="Actions" class="text-end pe-4">
                                    {{-- 👇 Action buttons horizontally aligned using d-flex and gap-2 --}}
                                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                                        
                                        {{-- View Button (For all roles) --}}
                                        <a href="{{ 
                                            auth()->user()->user_type === 'hr' 
                                                ? route('hr.leaves.show', $leave) 
                                                : route('employee.leaves.show', $leave) 
                                        }}" class="btn btn-sm btn-outline-info d-flex align-items-center gap-1">
                                            <i class="bi bi-eye"></i> View
                                        </a>

                                        {{-- HR Approval/Decline Actions (Only for HR on Pending Requests) --}}
                                        @if(auth()->user()->user_type === 'hr' && $leave->status === 'pending')
                                            
                                            {{-- Approve Form --}}
                                            <form action="{{ route('hr.leaves.approve', $leave) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success d-flex align-items-center gap-1"
                                                    onclick="return confirm('Are you sure you want to approve this leave?')">
                                                    <i class="bi bi-check-lg"></i> Approve
                                                </button>
                                            </form>

                                            {{-- Decline Form --}}
                                            <form action="{{ route('hr.leaves.decline', $leave) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center gap-1"
                                                    onclick="return confirm('Are you sure you want to decline this leave?')">
                                                    <i class="bi bi-x-lg"></i> Decline
                                                </button>
                                            </form>
                                            
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No leave requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Assuming $leaves is paginated --}}
            {{-- <div class="pagination-container">
                {{ $leaves->links() }}
            </div> --}}
        </div>
    </div>
</div>
@endsection