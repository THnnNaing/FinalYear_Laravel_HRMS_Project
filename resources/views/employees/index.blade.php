<!-- resources/views/employees/index.blade.php -->
@extends('layouts.app')

@section('title', 'Employees')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        @if(auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'hr')
            <a href="{{ route(auth()->user()->user_type === 'hr' ? 'hr.employees.create' : 'employees.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> <span>Add Employee</span>
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
                            <th>Email</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td class="ps-4" data-label="Name">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                <td data-label="Email">{{ $employee->email }}</td>
                                <td data-label="Department">{{ $employee->department->name }}</td>
                                <td data-label="Designation">{{ $employee->designation->title }}</td>
                                <td data-label="Status">
                                    <span class="badge badge-{{ $employee->status === 'permanent' ? 'success' : ($employee->status === 'contracted' ? 'primary' : ($employee->status === 'training' ? 'warning' : 'info')) }}">
                                        {{ str_replace('_', ' ', $employee->status) }}
                                    </span>
                                </td>
                                <td data-label="Actions" class="text-end pe-4">
                                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                                        <a href="{{ route(auth()->user()->user_type === 'hr' ? 'hr.employees.show' : 'employees.show', $employee) }}" 
                                           class="btn btn-sm btn-outline-info d-flex align-items-center gap-1">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        @if(auth()->user()->user_type !== 'employee' || auth()->user()->employee_id === $employee->id)
                                            <a href="{{ route(auth()->user()->user_type === 'hr' ? 'hr.employees.edit' : 'employees.edit', $employee) }}" 
                                               class="btn btn-sm btn-outline-warning d-flex align-items-center gap-1">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                        @endif
                                        @if((auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'hr') && $employee->user_id)
                                            <form action="{{ route(auth()->user()->user_type === 'hr' ? 'hr.employees.reset-password' : 'employees.reset-password', $employee) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1"
                                                        onclick="return confirm('Are you sure you want to reset this employee\'s password to the default?')">
                                                    <i class="bi bi-key"></i> Reset Password
                                                </button>
                                            </form>
                                        @endif
                                        @if(auth()->user()->user_type === 'admin')
                                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1"
                                                        onclick="return confirm('Are you sure you want to delete this employee?')">
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
            <div class="pagination-container">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>
@endsection