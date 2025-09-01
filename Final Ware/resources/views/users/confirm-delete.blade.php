@extends('layouts.dashboard')

@section('title', 'Confirm User Deletion')

@section('content_header')
    <h1 class="font-weight-bold text-dark">Confirm User Deletion</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h3 class="card-title mb-0">Delete User: {{ $user->name }}</h3>
        </div>

        <div class="card-body">
            <p class="lead">Are you sure you want to delete this user?</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ $user->role->name ?? 'N/A' }}</p>

            <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                @csrf
                @method('DELETE')
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-danger mr-2">
                        Confirm Delete
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
<style>
    .card-header {
        background: linear-gradient(90deg, #dc3545 0%, #a71d2a 100%);
    }
</style>
@stop