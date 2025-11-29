@extends('layouts.app')

@section('title', 'Leave Types')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <!-- <h2 class="h4 mb-0">Leave Types Management</h2> -->
        
        @if(auth()->check() && (auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'hr'))
            <a href="{{ route('leave_types.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> <span>Add Leave Type</span>
            </a>
        @endif
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle text-nowrap">
                    <thead>
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Paid/Unpaid</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveTypes as $leaveType)
                            <tr>
                                <td class="ps-4" data-label="Name">{{ $leaveType->name }}</td>
                                <td data-label="Paid/Unpaid">
                                    <span class="badge {{ $leaveType->is_paid ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $leaveType->is_paid ? 'Paid' : 'Unpaid' }}
                                    </span>
                                </td>
                                <td data-label="Actions" class="text-end pe-4">
                                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                                        
                                        <a href="{{ route('leave_types.edit', $leaveType) }}"
                                           class="btn btn-sm btn-outline-warning d-flex align-items-center gap-1">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>

                                        @if(auth()->check() && auth()->user()->user_type === 'admin')
                                            <form action="{{ route('leave_types.destroy', $leaveType) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1"
                                                        onclick="return confirm('Are you sure you want to delete the leave type: {{ $leaveType->name }}?')">
                                                    <i class="bi bi-trash3"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if(isset($leaveTypes) && $leaveTypes instanceof \Illuminate\Contracts\Pagination\Paginator)
                <div class="pagination-container p-3">
                    {{ $leaveTypes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection