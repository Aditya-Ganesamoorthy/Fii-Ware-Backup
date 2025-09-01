@php
    $randomPassword = \Illuminate\Support\Str::random(10);
@endphp

@extends('layouts.dashboard')

@section('title', 'Create New User')

@section('content_header')
    <h1 class="font-weight-bold text-dark">Create New User</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">User Information</h3>
        </div>
        <div class="card-tools">
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
                    ⬅ Back
                </a>
            </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <!-- Name Field -->
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="form-group mt-3">
                    <label for="email">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           required>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Role Field -->
                <!-- Role Field -->
<div class="form-group mt-3">
    <label for="role">Role</label>
    <select name="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
        <option value="">-- Select Role --</option>
        @foreach($roles as $role)
            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                {{ $role->name }}
            </option>
        @endforeach
    </select>
    @error('role_id')
        <span class="invalid-feedback d-block">{{ $message }}</span>
    @enderror
</div>

                <!-- Password Field -->
                <div class="form-group mt-3">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               value="{{ old('password', $randomPassword) }}"
                               required minlength="8">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', 'toggle-icon-password')">
                            <span id="toggle-icon-password">👁️</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="generatePassword()">
                            🔄
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group mt-3">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-group">
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="form-control"
                               value="{{ old('password_confirmation', $randomPassword) }}"
                               required minlength="8">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation', 'toggle-icon-password-confirm')">
                            <span id="toggle-icon-password-confirm">👁️</span>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-success w-100">Create User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function togglePassword(inputId, iconId) {
            const field = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (field.type === 'password') {
                field.type = 'text';
                icon.textContent = '🙈';
            } else {
                field.type = 'password';
                icon.textContent = '👁️';
            }
        }

        function generatePassword() {
            const length = 10;
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
            let password = "";
            for (let i = 0; i < length; i++) {
                password += charset.charAt(Math.floor(Math.random() * charset.length));
            }
            document.getElementById('password').value = password;
            document.getElementById('password_confirmation').value = password;
        }
    </script>
@stop
