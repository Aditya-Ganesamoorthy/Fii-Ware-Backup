@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('content_header')
    <h1 class="font-weight-bold text-dark">Edit User</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h3 class="card-title mb-0">Edit User Details</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="font-weight-bold">Name</label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name', $user->name) }}"
                        class="form-control" required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="font-weight-bold">Email</label>
                    <input type="email" name="email" id="email"
                        value="{{ old('email', $user->email) }}"
                        class="form-control" required>
                </div>

                <!-- Role -->
                <div class="form-group">
                    <label for="role_id" class="font-weight-bold">Role</label>
                    <select name="role_id" id="role_id" class="form-control" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        Update User
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary ml-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@stop
