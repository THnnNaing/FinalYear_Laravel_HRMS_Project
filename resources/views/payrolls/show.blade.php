@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center py-3">
            <h4 class="mb-0"><i class="bi bi-file-earmark-person-fill me-2"></i>Payroll Details</h4>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-person-circle text-primary me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">Employee:</small>
                            <p class="fw-bold mb-0">{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-calendar-check-fill text-primary me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">Month:</small>
                            <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($payroll->month)->format('F Y') }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-cash-stack text-primary me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">Basic Salary:</small>
                            <p class="fw-bold mb-0">${{ number_format($payroll->basic_salary, 2) }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-clock-fill text-primary me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">Worked Days:</small>
                            <p class="fw-bold mb-0">{{ $payroll->worked_days ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-journal-x-fill text-primary me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">Unpaid Leave Days:</small>
                            <p class="fw-bold mb-0">{{ $payroll->unpaid_leave_days }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-plus-circle-fill text-success me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">Total Bonus:</small>
                            <p class="fw-bold mb-0">${{ number_format($payroll->total_bonus, 2) }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-dash-circle-fill text-danger me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">Total Deduction:</small>
                            <p class="fw-bold mb-0">${{ number_format($payroll->total_deduction, 2) }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-wallet-fill text-success me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">Net Salary:</small>
                            <p class="fw-bold mb-0">${{ number_format($payroll->net_salary, 2) }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-patch-check-fill text-info me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">Status:</small>
                            <p class="fw-bold mb-0">{{ ucfirst($payroll->status) }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-person-badge-fill text-secondary me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">Approved By:</small>
                            <p class="fw-bold mb-0">{{ $payroll->approver ? $payroll->approver->name : 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-calendar-event-fill text-secondary me-2 fs-5"></i>
                        <div>
                            <small class="text-muted">Approved At:</small>
                            <p class="fw-bold mb-0">{{ $payroll->approved_at ? \Carbon\Carbon::parse($payroll->approved_at)->format('Y-m-d H:i') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($payroll->bonuses->count())
            <hr class="my-4">
            <h5 class="text-primary mb-3"><i class="bi bi-gift-fill me-2"></i>Bonuses</h5>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>Type</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payroll->bonuses as $bonus)
                        <tr>
                            <td>{{ $bonus->bonusType->name }}</td>
                            <td class="text-end">${{ number_format($bonus->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            @if($payroll->deductions->count())
            <hr class="my-4">
            <h5 class="text-primary mb-3"><i class="bi bi-cash-coin me-2"></i>Deductions</h5>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>Type</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payroll->deductions as $deduction)
                        <tr>
                            <td>{{ $deduction->deductionType->name }}</td>
                            <td class="text-end">${{ number_format($deduction->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            @if(auth()->user()->user_type === 'hr' && $payroll->status === 'pending')
            <div class="mt-4 text-center">
                <form action="{{ route('hr.payrolls.approve', $payroll) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg shadow-sm">
                        <i class="bi bi-check-circle-fill me-2"></i>Approve Payroll
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Add Bootstrap Icons CSS link if not already included in layouts/app.blade.php --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection