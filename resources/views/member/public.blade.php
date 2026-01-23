@extends('layouts.member')

@section('title', 'Member Profile - Shree Hindutakht')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4 mobile-container page-transition">
    <div class="w-full max-w-md">
        <div class="card text-center animate-fade-in">
            <img src="{{ asset('logo3.png') }}" alt="Shree Hindutakht" class="h-16 mx-auto mb-4 object-contain animate-fade-in-down">
            <p class="text-gray-600 mb-6 animate-fade-in">Member ID: {{ $member_id }}</p>
            
            <div class="bg-gray-50 rounded-xl p-6 mb-6 animate-fade-in">
                <p class="text-gray-600">This member is part of our community.</p>
            </div>
            
            <a href="/home" class="btn-primary inline-block touch-target mobile-button mobile-tap-highlight nav-transition">
                Join Our Community
            </a>
        </div>
    </div>
</div>
@endsection