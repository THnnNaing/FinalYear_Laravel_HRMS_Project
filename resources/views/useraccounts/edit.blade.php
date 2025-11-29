@extends('layouts.app')
@section('title', 'Edit User Account')

@section('content')
<div class="container py-4">
    <h2><i class="bi bi-pencil"></i> Edit User Account</h2>

    <div class="card mt-4">
        <div class="card-body">
            <form action="{{ route('admin.useraccounts.update', $user) }}" method="POST">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Name</label>
                        <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <!-- <div class="col-md-6">
                        <label>New Password (leave blank to keep)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div> -->
                    <div class="col-md-6">
                        <label>Role</label>
                        <select name="user_type" class="form-select" required>
                            <option value="employee" {{ $user->user_type === 'employee' ? 'selected' : '' }}>Employee</option>
                            <option value="hr" {{ $user->user_type === 'hr' ? 'selected' : '' }}>HR</option>
                            <option value="admin" {{ $user->user_type === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Account</button>
                    <a href="{{ route('admin.useraccounts.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection