@extends('layouts.app')

@section('title', 'Deduction Types')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <!-- <h2 class="h4 mb-0">Deduction Types Management</h2> -->
        
        @if(auth()->check() && (auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'hr'))
            <a href="{{ route('deduction_types.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> <span>Add Deduction Type</span>
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
                            <th>Description</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deductionTypes as $deductionType)
                            <tr>
                                <td class="ps-4" data-label="Name">{{ $deductionType->name }}</td>
                                <td data-label="Description">{{ $deductionType->description ?? 'N/A' }}</td>
                                <td data-label="Actions" class="text-end pe-4">
                                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                                        
                                        <a href="{{ route('deduction_types.edit', $deductionType) }}"
                                           class="btn btn-sm btn-outline-warning d-flex align-items-center gap-1">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>

                                        @if(auth()->check() && auth()->user()->user_type === 'admin')
                                            <form action="{{ route('deduction_types.destroy', $deductionType) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1"
                                                        onclick="return confirm('Are you sure you want to delete the deduction type: {{ $deductionType->name }}?')">
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
            
            @if(isset($deductionTypes) && $deductionTypes instanceof \Illuminate\Contracts\Pagination\Paginator)
                <div class="pagination-container p-3">
                    {{ $deductionTypes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection