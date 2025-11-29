@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<style>
    /* 🎨 Aesthetic Custom Styles */
    :root {
        --primary-color: #4f46e5; /* Indigo */
        --light-bg: #f5f7fa;     /* Very light background */
        --card-bg: #ffffff;
        --border-color: #e5e7eb;
        --text-color: #1f2937;
        --muted-color: #6b7280;
    }

    /* General Layout Spacing & Background */
    .container-custom {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: var(--light-bg);
        min-height: 90vh;
    }

    /* Main Profile Card Container */
    .profile-panel {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        padding: 0;
    }

    /* Header Section (Name and Avatar) */
    .header-section {
        padding: 2.5rem 2rem 2rem 2rem; /* Slightly more top padding */
        border-bottom: 1px solid var(--border-color);
    }

    /* Detail Section (Dates, Actions) */
    .detail-section {
        padding: 2rem;
    }

    .section-title {
        color: var(--text-color);
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0;
    }

    /* Enhanced Profile Avatar */
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        /* Premium Gradient */
        background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
        margin: 0 auto 15px;
        border: 5px solid var(--card-bg);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.5); /* Primary glow */
    }

    /* Info Labels */
    .info-label {
        font-weight: 600;
        color: var(--muted-color);
        margin-bottom: 0.25rem;
        font-size: 0.85rem;
        display: block;
    }

    .info-value {
        color: var(--text-color);
        font-size: 1rem;
        font-weight: 500;
        margin-top: 0;
    }

    /* Badge Styling */
    .badge-user-type {
        background-color: var(--primary-color) !important;
        padding: 0.5em 0.75em;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 0.5rem;
    }

    /* Button Styles (Consistent) */
    .btn-primary-custom {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
        padding: 0.75rem 1.75rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.2s ease-in-out;
    }
    .btn-primary-custom:hover {
        background-color: #6366f1;
        border-color: #6366f1;
    }
    
    .btn-outline-secondary-custom {
        --bs-btn-color: #6c757d;
        --bs-btn-border-color: #d1d5db;
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: #4f46e5;
        --bs-btn-hover-border-color: #4f46e5;
        transition: all 0.2s ease-in-out;
        padding: 0.75rem 1.75rem;
        border-radius: 0.5rem;
        font-weight: 600;
    }
    .btn-outline-secondary-custom:hover {
        color: var(--bs-btn-hover-color) !important;
        background-color: var(--bs-btn-hover-bg) !important;
        border-color: var(--bs-btn-hover-border-color) !important;
    }
</style>

<div class="container container-custom">
    <div class="row justify-content-center">
        {{-- Use a slightly narrower column for focus on the profile --}}
        <div class="col-md-8 col-lg-7">
            
            <div class="profile-panel">
                
                {{-- Profile Header Section --}}
                <div class="header-section">
                    <h3 class="section-title mb-4">Your Profile Overview</h3>
                    <div class="row align-items-center">
                        {{-- Avatar --}}
                        <div class="col-md-4 text-center mb-4 mb-md-0">
                            <div class="profile-avatar">
                                {{-- Display the first letter of the user's name --}}
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        
                        {{-- Primary Information --}}
                        <div class="col-md-8">
                            <h4 class="text-color fw-bold mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-3">{{ $user->email }}</p>
                            
                            {{-- User Type Badge --}}
                            <span class="badge badge-user-type">
                                {{ ucfirst($user->user_type) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Account Details Section --}}
                <div class="detail-section">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="info-label">Account Created</label>
                            <p class="info-value">{{ $user->created_at->format('F d, Y') }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label class="info-label">Last Updated</label>
                            <p class="info-value">{{ $user->updated_at->format('F d, Y') }}</p>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between pt-3">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary-custom shadow-sm">
                            <i class="bi bi-pencil-square me-2"></i> Edit Profile
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary-custom shadow-sm">
                            <i class="bi bi-speedometer2 me-2"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
