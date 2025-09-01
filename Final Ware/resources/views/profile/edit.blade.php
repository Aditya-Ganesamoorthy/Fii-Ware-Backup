@extends('layouts.profile')

@section('content')
<div class="profile-container">
    <div class="profile-section">
        @include('profile.partials.update-profile-information-form')
    </div>
    <div class="profile-section">
        @include('profile.partials.update-password-form')
    </div>
    <div class="profile-section">
        @include('profile.partials.delete-user-form')
    </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endsection
