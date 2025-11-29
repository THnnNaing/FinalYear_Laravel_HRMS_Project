{{-- resources/views/training_programs/index.blade.php --}}

@extends('layouts.app')
@section('title', 'Training Programs')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <!-- <h2 class="h4 mb-0 fw-bold">Training Programs Management</h2> -->
        <a href="{{ route('training_programs.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Create Program
        </a>
    </div>

    @if(session('success'))
        <!-- <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div> -->
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Program Name</th>
                            <th>Instructor</th>
                            <th>Schedule</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programs as $program)
                        <tr>
                            <!-- Program Name -->
                            <td class="ps-4">
                                <strong>{{ $program->name }}</strong>
                            </td>

                            <!-- Instructor -->
                            <td>
                                @if($program->instructor)
                                    {{ $program->instructor->first_name }} {{ $program->instructor->last_name }}
                                    <small class="text-muted d-block">
                                        ID: {{ $program->instructor->employee_id }}
                                    </small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <!-- Schedule: Days + Time -->
                            <td>
                                <div class="small">
                                    @php
                                        $days = is_string($program->available_days)
                                            ? (json_decode($program->available_days, true) ?: [])
                                            : ($program->available_days ?? []);
                                    @endphp

                                    @if(!empty($days))
                                        @foreach($days as $day)
                                            <span class="badge bg-primary-subtle text-primary me-1">
                                                {{ $day }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No days set</span>
                                    @endif
                                </div>
                                <div class="fw-bold text-primary mt-1">
                                    {{ $program->available_time ?? '—' }}
                                </div>
                            </td>

                            <!-- Capacity -->
                            <td>
                                <span class="badge bg-info text-dark fs-6">
                                    {{ $program->available_total_employees }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td>
                                <span class="badge rounded-pill
                                    @if($program->status === 'available') bg-success
                                    @elseif($program->status === 'active') bg-primary
                                    @elseif($program->status === 'ended') bg-secondary
                                    @else bg-warning text-dark
                                    @endif">
                                    {{ ucfirst($program->status) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="text-end pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('training_programs.show', $program) }}"
                                       class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('training_programs.edit', $program) }}"
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('training_programs.destroy', $program) }}" method="POST"
                                          class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Delete {{ addslashes($program->name) }}?')"
                                                title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                No training programs found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-3 border-top bg-light">
                {{ $programs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection